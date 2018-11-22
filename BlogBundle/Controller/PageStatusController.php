<?php

namespace Nines\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Nines\BlogBundle\Entity\PageStatus;
use Nines\BlogBundle\Form\PageStatusType;

/**
 * PageStatus controller.
 *
 * @Security("has_role('ROLE_USER')")
 * @Route("/page_status")
 */
class PageStatusController extends Controller {

    /**
     * Lists all PageStatus entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="page_status_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(PageStatus::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $pageStatuses = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'pageStatuses' => $pageStatuses,
        );
    }

    /**
     * Typeahead API endpoint for PageStatus entities.
     *
     * @param Request $request
     *
     * @Route("/typeahead", name="page_status_typeahead")
     * @Security("has_role('ROLE_BLOG_ADMIN')")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeahead(Request $request) {
        $q = $request->query->get('q');
        if (!$q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(PageStatus::class);
        $data = [];
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }
        return new JsonResponse($data);
    }

    /**
     * Creates a new PageStatus entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @Security("has_role('ROLE_BLOG_ADMIN')")
     * @Route("/new", name="page_status_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request) {
        $pageStatus = new PageStatus();
        $form = $this->createForm(PageStatusType::class, $pageStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pageStatus);
            $em->flush();

            $this->addFlash('success', 'The new pageStatus was created.');
            return $this->redirectToRoute('page_status_show', array('id' => $pageStatus->getId()));
        }

        return array(
            'pageStatus' => $pageStatus,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new PageStatus entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @Security("has_role('ROLE_BLOG_ADMIN')")
     * @Route("/new_popup", name="page_status_new_popup")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a PageStatus entity.
     *
     * @param PageStatus $pageStatus
     *
     * @return array
     *
     * @Route("/{id}", name="page_status_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(PageStatus $pageStatus) {

        return array(
            'pageStatus' => $pageStatus,
        );
    }

    /**
     * Displays a form to edit an existing PageStatus entity.
     *
     *
     * @param Request $request
     * @param PageStatus $pageStatus
     *
     * @return array|RedirectResponse
     *
     * @Security("has_role('ROLE_BLOG_ADMIN')")
     * @Route("/{id}/edit", name="page_status_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, PageStatus $pageStatus) {
        $editForm = $this->createForm(PageStatusType::class, $pageStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The pageStatus has been updated.');
            return $this->redirectToRoute('page_status_show', array('id' => $pageStatus->getId()));
        }

        return array(
            'pageStatus' => $pageStatus,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a PageStatus entity.
     *
     *
     * @param Request $request
     * @param PageStatus $pageStatus
     *
     * @return array|RedirectResponse
     *
     * @Security("has_role('ROLE_BLOG_ADMIN')")
     * @Route("/{id}/delete", name="page_status_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, PageStatus $pageStatus) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($pageStatus);
        $em->flush();
        $this->addFlash('success', 'The pageStatus was deleted.');

        return $this->redirectToRoute('page_status_index');
    }

}
