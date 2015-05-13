<?php

namespace Acme\ReservasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;

use Acme\ReservasBundle\Entity\Obra;
use Acme\ReservasBundle\Entity\FechaObra;
use Acme\ReservasBundle\Form\ObraType;
use Acme\ReservasBundle\Form\ObraFilterType;
use Acme\ReservasBundle\Form\Type\FechaObraType;

/**
 * Obra controller.
 *
 */
class ObraController extends Controller
{
    /**
     * Lists all Obra entities.
     *
     */
    public function indexAction()
    {
        list($filterForm, $queryBuilder) = $this->filter();

        list($entities, $pagerHtml) = $this->paginator($queryBuilder);

        return $this->render('AcmeReservasBundle:Obra:index.html.twig', array(
            'entities' => $entities,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
        ));
    }

    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new ObraFilterType());
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AcmeReservasBundle:Obra')->createQueryBuilder('e');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('ObraControllerFilter');
        }

        // Filter action
        if ($request->get('filter_action') == 'filter') {
            // Bind values from the request
            $filterForm->bind($request);

            if ($filterForm->isValid()) {
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                // Save filter to session
                $filterData = $filterForm->getData();
                $session->set('ObraControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('ObraControllerFilter')) {
                $filterData = $session->get('ObraControllerFilter');
                $filterForm = $this->createForm(new ObraFilterType(), $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return array($filterForm, $queryBuilder);
    }

    /**
    * Get results from paginator and get paginator view.
    *
    */
    protected function paginator($queryBuilder)
    {
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $this->getRequest()->get('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me)
        {
            return $me->generateUrl('admin_obras', array('page' => $page));
        };

        // Paginator - view
        $translator = $this->get('translator');
        $view = new TwitterBootstrapView();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => $translator->trans('views.index.pagprev', array(), 'JordiLlonchCrudGeneratorBundle'),
            'next_message' => $translator->trans('views.index.pagnext', array(), 'JordiLlonchCrudGeneratorBundle'),
        ));

        return array($entities, $pagerHtml);
    }

    /**
     * Creates a new Obra entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Obra();
        $form = $this->createForm(new ObraType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);

	    $path = $this->get('kernel')->getRootDir() . '/../public_html/imagenes/fotos';
		// preg_replace(“/[^a-z0-9\.]/”, “”, strtolower($str));
		//$nombre = $this->normalizeString($form['foto']->getData()->getClientOriginalName());
		$form['foto']->getData()->move($path, $entity->getFoto());

            $em->flush();
	    
            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');
	    $respuesta = ' - '.$path.' Se ha creado una obra:';
                $respuesta = $respuesta . sprintf('nombre: %s', $entity->getDescripcion());
                if (0 !== count($entity->getFechasObra())) {
                    $respuesta = $respuesta . 'fechas:';
                    foreach ($obra->getFechasObra() as $fecha) {
                        $respuesta = $respuesta . sprintf('&nbsp;&nbsp;%s', $fecha->getFecha()->format('Y-m-d H:i:s'));
                    }
                }
	    $this->get('session')->getFlashBag()->add('success', $respuesta);

            return $this->redirect($this->generateUrl('admin_obras_show', array('id' => $entity->getId())));
        }

        return $this->render('AcmeReservasBundle:Obra:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Obra entity.
     *
     */
    public function newAction()
    {
        $entity = new Obra();
        $form   = $this->createForm(new ObraType(), $entity);

        return $this->render('AcmeReservasBundle:Obra:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Obra entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeReservasBundle:Obra')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Obra entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AcmeReservasBundle:Obra:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Obra entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeReservasBundle:Obra')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Obra entity.');
        }

        $editForm = $this->createForm(new ObraType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AcmeReservasBundle:Obra:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Obra entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeReservasBundle:Obra')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Obra entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ObraType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.update.success');

            return $this->redirect($this->generateUrl('admin_obras_edit', array('id' => $id)));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.update.error');
        }

        return $this->render('AcmeReservasBundle:Obra:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Obra entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AcmeReservasBundle:Obra')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Obra entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('admin_obras'));
    }

    /**
     * Creates a form to delete a Obra entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
