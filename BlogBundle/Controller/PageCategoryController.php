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
use Nines\BlogBundle\Entity\PageCategory;
use Nines\BlogBundle\Form\PageCategoryType;

/**
 * PageCategory controller.
 *
 * @Route("/page_category")
 */
class PageCategoryController extends Controller {

    /**
     * Lists all PageCategory entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="page_category_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(PageCategory::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $pageCategories = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'pageCategories' => $pageCategories,
        );
    }

    /**
     * Typeahead API endpoint for PageCategory entities.
     *
     * @param Request $request
     *
     * @Route("/typeahead", name="page_category_typeahead")
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
        $repo = $em->getRepository(PageCategory::class);
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
     * Creates a new PageCategory entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @Security("has_role('ROLE_BLOG_ADMIN')")
     * @Route("/new", name="page_category_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request) {
        $pageCategory = new PageCategory();
        $form = $this->createForm(PageCategoryType::class, $pageCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pageCategory);
            $em->flush();

            $this->addFlash('success', 'The new pageCategory was created.');
            return $this->redirectToRoute('page_category_show', array('id' => $pageCategory->getId()));
        }

        return array(
            'pageCategory' => $pageCategory,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new PageCategory entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @Security("has_role('ROLE_BLOG_ADMIN')")
     * @Route("/new_popup", name="page_category_new_popup")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a PageCategory entity.
     *
     * @param PageCategory $pageCategory
     *
     * @return array
     *
     * @Route("/{id}", name="page_category_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(PageCategory $pageCategory) {

        return array(
            'pageCategory' => $pageCategory,
        );
    }

    /**
     * Displays a form to edit an existing PageCategory entity.
     *
     *
     * @param Request $request
     * @param PageCategory $pageCategory
     *
     * @return array|RedirectResponse
     *
     * @Security("has_role('ROLE_BLOG_ADMIN')")
     * @Route("/{id}/edit", name="page_category_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, PageCategory $pageCategory) {
        $editForm = $this->createForm(PageCategoryType::class, $pageCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The pageCategory has been updated.');
            return $this->redirectToRoute('page_category_show', array('id' => $pageCategory->getId()));
        }

        return array(
            'pageCategory' => $pageCategory,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a PageCategory entity.
     *
     *
     * @param Request $request
     * @param PageCategory $pageCategory
     *
     * @return array|RedirectResponse
     *
     * @Security("has_role('ROLE_BLOG_ADMIN')")
     * @Route("/{id}/delete", name="page_category_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, PageCategory $pageCategory) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($pageCategory);
        $em->flush();
        $this->addFlash('success', 'The pageCategory was deleted.');

        return $this->redirectToRoute('page_category_index');
    }

}
