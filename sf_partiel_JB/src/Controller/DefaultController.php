<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Skill;
use App\Form\NoteFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $notation = new Note();

        $form = $this->createForm(NoteFormType::class, $notation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($notation);
            $em->flush();
        }

        $notes = $em->getRepository(Note::class)->findAll();
        $skills = $em->getRepository(Skill::class)->findAll();

        return $this->render('default/index.html.twig', [
            'notes' => $notes,
            'skills' => $skills,
            'add_note' => $form->createView()
        ]);
    }

    /**
     * @Route("/note/{id}", name="note")
     */
    public function note(Note $notation, Request $request){
        $form = $this->createForm(NoteFormType::class, $notation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($notation);
            $em->flush();
        }

        return $this->render('default/note.html.twig',
            array(
                'notation' => $notation,
                'edit_form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/note/delete/{id}", name="noteDelete")
     */
    public function noteDelete(Note $notation = null){
        if ($notation != null) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($notation);
            $em->flush();
        }

        return $this->redirectToRoute('home');
    }
}
