<?php

namespace App\Controller;

use App\Entity\Story;
use App\Entity\Chapter;
use App\Entity\Choice;
use App\Entity\Skill;
use App\Entity\Weapon;
use App\Entity\Npc;
use App\Entity\SpecialItem;
use App\Form\NpcType;
use App\Form\StoryType;
use App\Form\ChapterType;
use App\Form\SkillType;
use App\Form\WeaponType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Routing\Annotation\Route;

class CoreController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction() : Response
    {
        $em = $this->getDoctrine()->getManager();
        $stories = $em->getRepository(Story::class)->findAll();

        return $this->render("index.html.twig", [
            'stories' => $stories
        ]);
    }

    /**
     * @param $id
     * 
     * @Route("story/{id}", name="story")
     */
    public function storyAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        $weapons = $em->getRepository(Weapon::class)->findBy(['story' => $story]);
        $npcs = $em->getRepository(Npc::class)->findBy(['story' => $story]);

        if (!$story) {
            throw $this->createNotFoundException("No story found with the id" . $id);
        }

        return $this->render("story.html.twig", [
            "story" => $story,
            "weapons" => $weapons,
            "npcs" => $npcs
        ]);
    }

    /**
     * @param REQUEST $request
     * 
     * @return Response
     * @Route("/story-form", name="storyForm")
     */
    public function storyFormAction(Request $request) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = new Story();
        $form = $this->createForm(StoryType::class, $story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($story);
            $em->flush();
            
            $this->addFlash("success", "The story has been saved successfully");
            return $this->redirectToRoute("index");
        }
        return $this->render("form/story-form.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request, $id
     * 
     * @return Response
     * @Route("/story/{id}/skill-form", name="skillForm")
     */
    public function skillFormAction(Request $request, $id) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        $skill = new Skill();

        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skill->setStory($story);
            $em->persist($skill);
            $em->flush();

            $this->addFlash("success", "The skill has been saved successfully");
            return $this->redirectToRoute("story", [
                            "id" => $story->getId()
                        ]);
        }
        return $this->render("form/skill-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

        /**
     * @param Request, $id
     * 
     * @return Response
     * @Route("/story/{id}/weapon-form", name="weaponForm")
     */
    public function weaponFormAction(Request $request, $id) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        $weapon = new Weapon();
        $weapon->setStory($story);

        $form = $this->createForm(WeaponType::class, $weapon, ["story" => $story]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($weapon);
            $em->flush();

            $this->addFlash("success", "The weapon has been saved successfully");
            return $this->redirectToRoute("story", [
                            "id" => $story->getId()
                        ]);
        }
        return $this->render("form/weapon-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id
     * 
     * @return Response
     * @Route("/story/{id}/npc-form", name="npcForm")
     */
    public function npcFormAction (Request $request, $id) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        $npc = new Npc();
        $npc->setStory($story);

        $form = $this->createForm(NpcType::class, $npc, ["story" => $story]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($npc);
            $em->flush();

            $this->addFlash("success", "The Character has been successully saved");
            return $this->redirectToRoute("story", [
                            "id" => $story->getId()
                        ]);
        }
        return $this->render("form/npc-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id
     * 
     * @return Response
     * @Route("/story/{id}/chapter-form", name="chapterForm")
     */
    public function chapterFormAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        $specialItems = $em->getRepository(SpecialItem::class)->findBy(["story" => $story]);
        $chapter = new Chapter();
        $chapter->setStory($story);

        $form = $this->createForm(ChapterType::class, $chapter, [
                                    "story" => $story, 
                                    "specialItems" => $specialItems
                                ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($chapter);
            $em->flush();

            $this->addFlash("success", "The chapter has been savec successfully");
            return $this->redirectToRoute('story', [
                            "id" => $story->getId()
                        ]);
        }
        return $this->render("form/chapter-form.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
