<?php

namespace App\Controller\Admin;

use App\Entity\ConsumableItem;
use App\Entity\Story;
use App\Entity\Chapter;
use App\Entity\Skill;
use App\Entity\Weapon;
use App\Entity\Npc;
use App\Entity\SpecialItem;
use App\Form\ConsumableItemType;
use App\Form\NpcType;
use App\Form\SpecialItemType;
use App\Form\StoryType;
use App\Form\ChapterType;
use App\Form\SkillType;
use App\Form\WeaponType;
use App\Utils\Slugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FormController extends Controller
{
    /**
     * @param REQUEST $request
     *
     * @return Response
     * @Route("/story-form", name="storyForm")
     */
    public function storyFormAction(Request $request) : Response
    {
        $story = new Story();
        $form = $this->createForm(StoryType::class, $story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $story->setSlug(Slugger::slugify($story->getTitle()));
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
     * @Route("/story-edit/{id}", name="storyEdit")
     */
    public function editStoryAction(Request $request, Story $story)
    {
        $form = $this->createForm(StoryType::class, $story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($story);
            $em->flush();

            $this->addFlash("success", "The story has been modified successfully");
            return $this->redirectToRoute('index');
        }
        return $this->render("form/story-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id
     *
     * @return Response
     * @Route("/story/{id}/skill-form", name="skillForm")
     */
    public function skillFormAction(Request $request, Story $story) : Response
    {
        $skill = new Skill();

        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skill->setStory($story);
            $em = $this->getDoctrine()->getManager();
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
     * @Route("/story/{id}/skill-edit/{idSkill}", name="skillEdit")
     * @ParamConverter("skill", options={"mapping": {"idSkill": "id"}})
     */
    public function editSkillAction(Request $request, Story $story, Skill $skill) : Response
    {

        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($skill);
            $em->flush();

            $this->addFlash("success", "The skill has been modified successfully");
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
    public function weaponFormAction(Request $request, Story $story) : Response
    {
        $weapon = new Weapon();
        $weapon->setStory($story);

        $form = $this->createForm(WeaponType::class, $weapon, ["story" => $story]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
     * @param Request, $id, $idWeapon
     *
     * @return Response
     * @Route("/story/{id}/weapon-edit/{idWeapon}", name="weaponEdit")
     * @ParamConverter("weapon", options={"mapping": {"idWeapon": "id"}})
     */
    public function editWeaponAction(Request $request,Story $story, Weapon $weapon) : Response
    {
        $form = $this->createForm(WeaponType::class, $weapon, ["story" => $story]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($weapon);
            $em->flush();

            $this->addFlash("success", "The weapon has been modified successfully");
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
     * @Route("/story/{id}/consumable-form", name="consumableForm")
     */
    public function consumableFormAction(Request $request, Story $story) : Response
    {
        $consumable = new ConsumableItem;
        $consumable->setStory($story);

        $form = $this->createForm(ConsumableItemType::class, $consumable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($consumable);
            $em->flush();

            $this->addFlash("success", "The consumable has been saved successfully");
            return $this->redirectToRoute("story", [
                "id" => $story->getId()
            ]);
        }
        return $this->render("form/consumableItem-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id, $idConsumable
     *
     * @return Response
     * @Route("/story/{id}/consumable-edit/{idConsumable}", name="consumableEdit")
     * @ParamConverter("consumableItem", options={"mapping": {"idConsumable": "id"}})
     */
    public function editConsumableAction(Request $request, Story $story, ConsumableItem $consumable) : Response
    {
        $form = $this->createForm(ConsumableItemType::class, $consumable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($consumable);
            $em->flush();

            $this->addFlash("success", "The consumable has been modified successfully");
            return $this->redirectToRoute("story", [
                "id" => $story->getId()
            ]);
        }
        return $this->render("form/consumableItem-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id
     *
     * @return Response
     * @Route("/story/{id}/specialItem-form", name="specialItemForm")
     */
    public function specialItemFormAction(Request $request, Story $story) : Response
    {
        $specialItem = new SpecialItem;
        $specialItem->setStory($story);

        $form = $this->createForm(SpecialItemType::class, $specialItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($specialItem);
            $em->flush();

            $this->addFlash("success", "The Special Item has been saved successfully");
            return $this->redirectToRoute("story", [
                "id" => $story->getId()
            ]);
        }
        return $this->render("form/specialItem-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id, $idSpecialItem
     *
     * @return Response
     * @Route("/story/{id}/specialItem-edit/{idSpecialItem}", name="specialItemEdit")
     * @ParamConverter("specialItem", options={"mapping": {"idSpecialItem": "id"}})
     */
    public function editSpecialItemAction(Request $request, Story $story, SpecialItem $specialItem) : Response
    {
        $form = $this->createForm(SpecialItemType::class, $specialItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($specialItem);
            $em->flush();

            $this->addFlash("success", "The Special Item has been modified successfully");
            return $this->redirectToRoute("story", [
                "id" => $story->getId()
            ]);
        }
        return $this->render("form/specialItem-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id
     *
     * @return Response
     * @Route("/story/{id}/npc-form", name="npcForm")
     */
    public function npcFormAction (Request $request, Story $story) : Response
    {
        $npc = new Npc();
        $npc->setStory($story);

        $form = $this->createForm(NpcType::class, $npc, ["story" => $story]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
     * @param Request, $id, $idNpc
     *
     * @return Response
     * @Route("/story/{id}/npc-edit/{idNpc}", name="npcEdit")
     * @ParamConverter("npc", options={"mapping": {"idNpc": "id"}})
     */
    public function editNpcAction (Request $request, Story $story, Npc $npc) : Response
    {
        $form = $this->createForm(NpcType::class, $npc, ["story" => $story]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($npc);
            $em->flush();

            $this->addFlash("success", "The Character has been successully modified");
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
    public function chapterFormAction(Request $request, Story $story)
    {
        $chapter = new Chapter();
        $chapter->setStory($story);

        $form = $this->createForm(ChapterType::class, $chapter, [
            'story' => $story,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($chapter->getChoices() as $choice) {
                $choice->setChapter($chapter);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($chapter);
            $em->flush();

            $this->addFlash("success", "The chapter has been saved successfully");
            return $this->redirectToRoute('story', [
                "id" => $story->getId()
            ]);
        }
        return $this->render("form/chapter-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id
     *
     * @return Response
     * @Route("/story/{id}/chapter-form/{idChapter}", name="chapterEdit")
     * @ParamConverter("chapter", options={"mapping": {"idChapter": "id"}})
     */
    public function editChapterAction(Request $request, Story $story, Chapter $chapter)
    {
        $form = $this->createForm(ChapterType::class, $chapter, [
            'story' => $story
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($chapter->getChoices() as $choice) {
                $choice->setChapter($chapter);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($chapter);
            $em->flush();

            $this->addFlash("success", "The chapter has been modified successfully");
            return $this->redirectToRoute('story', [
                "id" => $story->getId()
            ]);
        }
        return $this->render("form/chapter-form.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
