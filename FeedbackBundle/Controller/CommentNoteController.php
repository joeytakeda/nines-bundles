<?php

namespace Nines\FeedbackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Nines\FeedbackBundle\Entity\CommentNote;
use Nines\FeedbackBundle\Form\CommentNoteType;

/**
 * CommentNote controller.
 *
 * @Security("has_role('ROLE_USER')")
 * @Route("/admin/comment_note")
 */
class CommentNoteController extends Controller
{
    /**
     * Lists all CommentNote entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="admin_comment_note_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(CommentNote::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $commentNotes = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'commentNotes' => $commentNotes,
        );
    }

/**
     * Typeahead API endpoint for CommentNote entities.
     *
     * To make this work, add something like this to CommentNoteRepository:
        //    public function typeaheadQuery($q) {
        //        $qb = $this->createQueryBuilder('e');
        //        $qb->andWhere("e.name LIKE :q");
        //        $qb->orderBy('e.name');
        //        $qb->setParameter('q', "{$q}%");
        //        return $qb->getQuery()->execute();
        //    }
     *
     * @param Request $request
     *
     * @Route("/typeahead", name="admin_comment_note_typeahead")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeahead(Request $request)
    {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository(CommentNote::class);
        $data = [];
        foreach($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string)$result,
            ];
        }
        return new JsonResponse($data);
    }
    /**
     * Search for CommentNote entities.
     *
     * To make this work, add a method like this one to the
     * NinesFeedbackBundle:CommentNote repository. Replace the fieldName with
     * something appropriate, and adjust the generated search.html.twig
     * template.
     *
     * <code><pre>
     *    public function searchQuery($q) {
     *       $qb = $this->createQueryBuilder('e');
     *       $qb->addSelect("MATCH (e.title) AGAINST(:q BOOLEAN) as HIDDEN score");
     *       $qb->orderBy('score', 'DESC');
     *       $qb->setParameter('q', $q);
     *       return $qb->getQuery();
     *    }
     * </pre></code>
     *
     * @param Request $request
     *
     * @Route("/search", name="admin_comment_note_search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('NinesFeedbackBundle:CommentNote');
	$q = $request->query->get('q');
	if($q) {
	    $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $commentNotes = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
	} else {
            $commentNotes = array();
	}

        return array(
            'commentNotes' => $commentNotes,
            'q' => $q,
        );
    }

    /**
     * Creates a new CommentNote entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/new", name="admin_comment_note_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $commentNote = new CommentNote();
        $form = $this->createForm(CommentNoteType::class, $commentNote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentNote);
            $em->flush();

            $this->addFlash('success', 'The new commentNote was created.');
            return $this->redirectToRoute('admin_comment_note_show', array('id' => $commentNote->getId()));
        }

        return array(
            'commentNote' => $commentNote,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new CommentNote entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/new_popup", name="admin_comment_note_new_popup")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newPopupAction(Request $request)
    {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a CommentNote entity.
     *
     * @param CommentNote $commentNote
     *
     * @return array
     *
     * @Route("/{id}", name="admin_comment_note_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(CommentNote $commentNote)
    {

        return array(
            'commentNote' => $commentNote,
        );
    }

    /**
     * Displays a form to edit an existing CommentNote entity.
     *
     *
     * @param Request $request
     * @param CommentNote $commentNote
     *
     * @return array|RedirectResponse
     *
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/{id}/edit", name="admin_comment_note_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, CommentNote $commentNote)
    {
        $editForm = $this->createForm(CommentNoteType::class, $commentNote);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The commentNote has been updated.');
            return $this->redirectToRoute('admin_comment_note_show', array('id' => $commentNote->getId()));
        }

        return array(
            'commentNote' => $commentNote,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a CommentNote entity.
     *
     *
     * @param Request $request
     * @param CommentNote $commentNote
     *
     * @return array|RedirectResponse
     *
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/{id}/delete", name="admin_comment_note_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, CommentNote $commentNote)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($commentNote);
        $em->flush();
        $this->addFlash('success', 'The commentNote was deleted.');

        return $this->redirectToRoute('admin_comment_note_index');
    }
}
