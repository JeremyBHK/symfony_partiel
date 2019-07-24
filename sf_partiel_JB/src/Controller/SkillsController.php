<?php
namespace App\Controller;

use App\Entity\Skill;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Form\SkillsFormType;

use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/skill")
 */
class SkillsController extends AbstractController
{
    /**
     * @Route("/", name="skills")
     */
    public function index(Request $request, TranslatorInterface $trans)
    {
        $em = $this->getDoctrine()->getManager();

        $skill = new Skill();

        $form = $this->createForm(SkillsFormType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($skill);
            $em->flush();
            $this->addFlash('success', $trans->trans('skill.created'));   
        }

        $skill = $em->getRepository(Skill::class)->findAll();

        return $this->render('skills/index.html.twig', [
            'skills'     => $skill,
            'add_skill'  => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="skill")
     */
    public function skill(Request $request, Skill $skill){
        $form = $this->createForm(SkillsFormType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = getDoctrine()->getManager();
            $em->persist($skill);
            $em->flush();

            $this->addFlash('success', 'Note mise à jour');
        }

        return $this->render('skills/skill.html.twig', [
            '$skill'      => $skill,
            'edit_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="skillDelete")
     */
    public function skillDelete(SKill $skill){
        $em = $this->getDoctrine()->getManager();
        $em->remove($skill);
        $em->flush();

        return $this->redirectToRoute('skill');
    }
}
