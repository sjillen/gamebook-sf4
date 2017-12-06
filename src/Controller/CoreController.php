<?php

namespace App\Controller;

use App\Entity\Story;
use App\Entity\Chapter;
use App\Entity\Choice;
use App\Entity\Skill;
use App\Entity\Item;
use App\Form\StoryType;
use App\Form\ChapterType;
use App\Form\SkillType;
use App\Form\ItemType;
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

        $items = $em->getRepository(Item::class)->findBy(['story' => $story]);

        if (!$story) {
            throw $this->createNotFoundException("No story found with the id" . $id);
        }

        return $this->render("story.html.twig", [
            "story" => $story,
            "items" => $items
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
        return $this->render("story-form.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request
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
        return $this->render("skill-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

        /**
     * @param Request
     * 
     * @return Response
     * @Route("/story/{id}/item-form", name="itemForm")
     */
    public function itemFormAction(Request $request, $id) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        $item = new Item();

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item->setStory($story);
            $em->persist($item);
            $em->flush();

            $this->addFlash("success", "The item has been saved successfully");
            return $this->redirectToRoute("story", [
                            "id" => $story->getId()
                        ]);
        }
        return $this->render("item-form.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
