<?php

namespace Acme\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Request;

use Acme\UserBundle\Form\ChangePasswordType;
use Acme\UserBundle\Form\Model\ChangePassword;
use Acme\UserBundle\Form\Model\ResetPassword;

use Acme\UserBundle\Form\ResetPwMailType;
use Acme\UserBundle\Entity\User;
use Acme\UserBundle\Form\ResetPasswordType;

use Acme\UserBundle\Form\Type\RegistrationType;
use Acme\UserBundle\Form\Model\Registration;

#use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

use Symfony\Component\HttpFoundation\Session\Session;

class SecurityController extends Controller
{
    /**
     * @var EncoderFactoryInterface 
     */
    protected $encoderFactory;
    
    /**
     * @Route("/login", name="login_route")
     */
    public function loginAction(Request $request)
    {
	$referrer = $request->headers->get('referer');
	#$template = 'security/loginFondo.html.twig';

	$authenticationUtils = $this->get('security.authentication_utils');

	// get the login error if there is one
	$error = $authenticationUtils->getLastAuthenticationError();

	// last username entered by the user
	$lastUsername = $authenticationUtils->getLastUsername();
	
	return $this->render(
	    'security/login.html.twig',
	    array(
		// last username entered by the user
		'last_username' => $lastUsername,
		'error'         => $error,
		'referrer'      => $referrer,
	    )
	);
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
    }

    /**
     * @Route("/logout", name="logout_route")
     */
    public function logoutAction()
    {
	$this->get('security.context')->setToken(null);
	$this->get('request')->getSession()->invalidate();
	return $this->redirect($this->generateUrl('NayarNayarBundle_homepage'));
    }
    
    
    /**
     * @Route("/cambiar_pwd", name="AcmeUserBundle_changepass")
     */
    public function changePasswdAction(Request $request)
    {
      $changePasswordModel = new ChangePassword();
      $form = $this->createForm(new ChangePasswordType(), $changePasswordModel);

      $form->handleRequest($request);
      $datos=$request->get('form');

      if ($form->isSubmitted() && $form->isValid()) {
          // perform some action,
          // such as encoding with MessageDigestPasswordEncoder and persist
          $registration = $form->getData();
          $password = $registration->getNewPassword();
          $usr= $this->get('security.context')->getToken()->getUser();
          #$encoder = $this->getEncoder($usr);
          
          $usr->setPassword(password_hash($password, PASSWORD_BCRYPT, array()));
          $em = $this->getDoctrine()->getManager();
          $em->persist($usr);
          $em->flush();
          #$usr->eraseCredentials();
          return $this->render('AcmeUserBundle:Default:changePasswdSuccess.html.twig', array());
      }

      return $this->render('AcmeUserBundle:Default:changePasswd.html.twig', array(
          'form' => $form->createView(),
      ));
    }
    
    #protected function getEncoder(UserInterface $user)
    #{
    #    return $this->encoderFactory->getEncoder($user);
    #}

    /**
     * @Route("/reset", name="AcmeUserBundle_resetpwinit")
     */
    public function resetPwInitAction(Request $request)
    {
	$request = $this->getRequest();
        #$em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new ResetPwMailType($request,Null,""), array());
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
	    {
	    $buscar = "";
            $datos = $form->getData();
	    $em = $this->get('doctrine')->getManager();
	    $res = $em
            ->createQueryBuilder()
	    ->select('u')
	    ->from('AcmeUserBundle:User', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $datos['email'])
            ->getQuery()->getResult();

        if (count($res) > 0) {
	    $codigo = rand(100000000, 999999999999);
	$session = $request->getSession();
	$session->set('codigo',$codigo);
	$session->set('usuario',$res[0]);
	$session->set('fecha_hoy',date("d.m.Y"));
	$mailer = $this->get('mailer');
	$message = $mailer->createMessage()
        ->setSubject('Recuperar contraseña de Nayar Consultora')
        ->setFrom('noreply@nayarconsultora.com.ar')
        ->setTo($datos['email'])
        ->setBody(
            $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                'AcmeUserBundle:Default:TextoMailResetPw.html.twig',
                array('usuario' => $res[0]->getNombre(), 'codigo'=>$codigo)
            ),
            'text/html'
        )
        /*
         * If you also want to include a plaintext version of the message
        ->addPart(
            $this->renderView(
                'Emails/registration.txt.twig',
                array('name' => $name)
            ),
            'text/plain'
        )
        */
    ;
    $mailer->send($message);
    
    return $this->redirect($this->generateUrl('AcmeUserBundle_resetpass'));

        } else {
            return $this->render('AcmeUserBundle:Default:changePasswdSuccess.html.twig',
		  array('mensaje'=>'El mail indicado no pertenece a un usuario.'));
        }
	    }
	    else
	    {
		return $this->render('AcmeUserBundle:Default:changePasswdSuccess.html.twig',
		  array('mensaje'=>'Formulario mal completado.'));
	    }
	}
    else
        {
	return $this->render('AcmeUserBundle:Default:changePasswd.html.twig', array(
          'form' => $form->createView(),
	    ));
	}
    }

    /**
     * @Route("/reset_pwd", name="AcmeUserBundle_resetpass")
     */
    public function resetPasswdAction(Request $request)
    { //, defaults={"token" = 1}
      $session = $request->getSession();
      $codigo = $session->get('codigo');
      $usuario = $session->get('usuario');
      $fecha_hoy = $session->get('fecha_hoy');

      $texto = 'Se ha enviado un mail a la cuenta '.$usuario->getEmail().' con el codigo a completar.';
      #var_dump($usuario);
      #exit();
      $resetPasswordModel = new ResetPassword();
      $form = $this->createForm(new ResetPasswordType(), $resetPasswordModel);

      $form->handleRequest($request);
      $datos=$request->get('form');

      if ($form->isSubmitted() && $form->isValid()) {
	  if ($fecha_hoy != date("d.m.Y"))
	    {
	    $texto = "El código ingresado ya no es valido.";
	    return $this->render('AcmeUserBundle:Default:changePasswd.html.twig', array(
		'form' => $form->createView(), 'texto' => $texto
		));
	    }
          // perform some action,
          // such as encoding with MessageDigestPasswordEncoder and persist
          $registration = $form->getData();
          $cod_ingresado = $registration->getCodigo();
	  if ($codigo != $cod_ingresado)
	    {
	    $texto = "El código ingresado es invalido.";
	    return $this->render('AcmeUserBundle:Default:changePasswd.html.twig', array(
		'form' => $form->createView(), 'texto' => $texto
		));
	    }
	  $password = $registration->getNewPassword();
          //$usr= $this->get('security.context')->getToken()->getUser();
          #$encoder = $this->getEncoder($usr);

          $usuario->setPassword(password_hash($password, PASSWORD_BCRYPT, array()));
          $em = $this->getDoctrine()->getManager();
          $em->merge($usuario);
          $em->flush();
          #$usr->eraseCredentials();
          return $this->render('AcmeUserBundle:Default:changePasswdSuccess.html.twig',
		  array('mensaje'=>"La contraseña se ha modificado correctamente."));
      }

      return $this->render('AcmeUserBundle:Default:changePasswd.html.twig', array(
          'form' => $form->createView(), 'texto' => $texto
      ));
    }

    /**
     * @Route("/registrarse", name="AcmeUserBundle_registrarse")
     */
    public function registerAction()
    {
        $registration = new Registration();
        $form = $this->createForm(new RegistrationType(), $registration, array(
            'action' => $this->generateUrl('AcmeUserBundle_procesoregistro'),
        ));

        return $this->render(
            'AcmeUserBundle:Default:registrarse.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/proceso_registro", name="AcmeUserBundle_procesoregistro")
     */
    public function createAction(Request $request)
{
    $em = $this->getDoctrine()->getManager();

    $form = $this->createForm(new RegistrationType(), new Registration());

    $form->handleRequest($request);

    if ($form->isValid()) {
        $registration = $form->getData();
	#var_dump($registration);
	#exit();
	if ($registration->getUser()->getUsertype() != 'Usuario')
	{
	    return $this->redirectToRoute('AcmeUserBundle_registrarse');
	}

        $em->persist($registration->getUser());
        $em->flush();

        return $this->render('AcmeUserBundle:Default:changePasswdSuccess.html.twig',
		  array('mensaje'=>"Se ha creado el usuario. Por favor haga click en iniciar sesion para ingresar."));
    }

    return $this->render(
        'AcmeUserBundle:Default:registrarse.html.twig',
        array('form' => $form->createView())
    );
}
}
