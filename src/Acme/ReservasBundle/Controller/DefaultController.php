<?php

namespace Acme\ReservasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Acme\ReservasBundle\Entity\Obra;
use Acme\ReservasBundle\Entity\FechaObra;
use Acme\ReservasBundle\Form\Type\ObraType;
use Acme\ReservasBundle\Form\Type\FechasObraType;
use Acme\ReservasBundle\Form\Type\FechaObraType;



use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;



class DefaultController extends Controller
{
    public function nuevaObraAction(Request $request)
    {
        # https://github.com/adesigns/calendar-bundle
	# https://github.com/mateuszmarkowski/jQuery-Seat-Charts
	# http://www.goocode.net/js/73-jquery-election-seat-reservations-online-theater-piece.html#sel=28:6,28:9
	# http://twig.sensiolabs.org/doc/tags/embed.html
	$obra = new Obra();

	#$tag1 = new Tag();
        #$tag1->name = 'tag1';
        #$task->getTags()->add($tag1);

        $form = $this->createForm(new ObraType(), $obra);
	
        if ($request->isMethod("POST")) {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->persist($obra);
		$path = $this->get('kernel')->getRootDir() . '/../public_html/imagenes/fotos';
		// preg_replace(“/[^a-z0-9\.]/”, “”, strtolower($str));
		//$nombre = $this->normalizeString($form['foto']->getData()->getClientOriginalName());
		$form['foto']->getData()->move($path, $obra->getFoto());

                 $em->flush();

                $respuesta = ' - '.$path.' Se ha creado una obra:';
                $respuesta = $respuesta . sprintf('nombre: %s', $obra->getDescripcion());
                if (0 !== count($obra->getFechasobra())) {
                    $respuesta = $respuesta . 'fechas:';
                    foreach ($obra->getFechasobra() as $fecha) {
                        $respuesta = $respuesta . sprintf('&nbsp;&nbsp;%s', $fecha->getFecha()->format('Y-m-d H:i:s'));
                    }
                }

                return $this->render('AcmeReservasBundle:Default:ObraAgregada.html.twig', array(
		    'mensaje' => $respuesta
		    ));
            }
        }
#var_dump($form->createview());
        return $this->render('AcmeReservasBundle:Default:nuevaObra.html.twig', array(
            'form' => $form->createView()
        ));

	//return $this->render('AcmeReservasBundle:Default:index.html.twig', array('name' => $name));
    }

    public function editarObraAction($id, Request $request)
    {
	$em = $this->getDoctrine()->getManager();
	$obra = $em->getRepository('AcmeReservasBundle:Obras')->find($id);

	if (!$obra) {
	    throw $this->createNotFoundException('No hay una obra con ese id '.$id);
	}

	$originalFechas = new ArrayCollection();

	// Create an ArrayCollection of the current Tag objects in the database
	foreach ($obra->getFechas() as $fecha) {
	    $originalFechas->add($fecha);
	}

	$editForm = $this->createForm(new ObrasType(), $obra);

	$editForm->handleRequest($request);

	if ($editForm->isValid()) {

	    // remove the relationship between the tag and the Task
	    foreach ($originalFechas as $fecha) {
		if (false === $obra->getObras()->contains($fecha)) {
		    // remove the Task from the Tag
		    $fecha->getObras()->removeElement($obra);

		    // if it was a many-to-one relationship, remove the relationship like this
		    $fecha->setObras(null);

		    $em->persist($fecha);

		    // if you wanted to delete the Tag entirely, you can also do that
		    $em->remove($fecha);
		}
	    }

	    $em->persist($obra);
	    $em->flush();

	    // redirect back to some edit page
	    return $this->redirectToRoute('task_edit', array('id' => $id));
	}

	// render some form template
	return $this->render('AcmeReservasBundle:Default:index.html.twig', array(
            'form' => $form->createView(), 'name' => $id
        ));
    }

    public function normalizeString ($str = '')
    {
	$str = strip_tags($str);
	$str = preg_replace('/[\r\n\t ]+/', ' ', $str);
	$str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
	$str = strtolower($str);
	$str = html_entity_decode( $str, ENT_QUOTES, "utf-8" );
	$str = htmlentities($str, ENT_QUOTES, "utf-8");
	$str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
	$str = str_replace(' ', '-', $str);
	$str = rawurlencode($str);
	$str = str_replace('%', '-', $str);
	return $str;
    }

    public function ReservaPaso2Action()
    {
	return $this->render('AcmeReservasBundle:Reservas:paso2.html.twig', array(
        #    'form' => $form->createView(), 'name' => $id
	    'mapa_asientos' => "[ '_aaaaaaaaaa__aaaaaaaaaaaaa__',
				'aaaaaaaaaaa__aaaaaaaaaaaaaa_',
				'aaaaaaaaaaa__aaaaaaaaaaaaaa_',
				'aaaaaaaaaaa__aaaaaaaaaaaaaa_',
				'aaaaaaaaaaa__aaaaaaaaaaaaaa_',
				'aaaaaaaaaaa__aaaaaaaaaaaaaa_',
				'aaaaaaaaaaa__aaaaaaaaaaaaaaa',
				'aaaaaaaaaaa__aaaaaaaaaaaaaaa',
				'aaaaaaaaaaa__aaaaaaaaaaaaaaa',
				'aaaaaaaaaaa__aaaaaaaaaaaaaaa',
				'aaaaaaaaaaa__aaaaaaaaaaaaaaa',
				'aaaaaaaaaaa__aaaaaaaaaaaaaaa',
				'aaaaaaaaaaa__aaaaaaaaaaaaaaa',
				'_aaaaaaaaaa_________________' ]",
	    'asientos_ocupados' => "['1_2', '4_4','4_5','6_6','6_7','8_5','8_6','8_7','8_8', '10_1', '10_2']"
	    ));
    }
}
