<?php

namespace App\Controller;

use App\Entity\ConsumableItem;
use App\Entity\Story;
use App\Entity\Chapter;
use App\Entity\Choice;
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
     * @Route("/story-edit/{id}", name="storyEdit")
     */
    public function editStoryAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        if(!$story) {
            throw $this->createNotFoundException("No story found with the following id: " . $id);
        }

        $form = $this->createForm(StoryType::class, $story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/story/{id}/skill-edit/{skillId}", name="skillEdit")
     */
    public function editSkillAction(Request $request, $id, $skillId) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        if(!$story) {
            throw $this->createNotFoundException("No story found with the following id: " . $id);
        }
        $skill = $em->getRepository(Skill::class)->find($skillId);
        if(!$skill) {
            throw $this->createNotFoundException("No skill found with the following id: " . $skillId);
        }
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/story/{id}/weapon-edit/{weaponId}", name="weaponEdit")
     */
    public function editWeaponAction(Request $request, $id, $weaponId) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        if(!$story) {
            throw $this->createNotFoundException("No Story found with the following id: " .$id);
        }
        $weapon = $em->getRepository(Weapon::class)->find($weaponId);
        if(!$weapon) {
            throw $this->createNotFoundException("No Weapon found with the following id: " .$weaponId);
        }
        $form = $this->createForm(WeaponType::class, $weapon, ["story" => $story]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
    public function consumableFormAction(Request $request, $id) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        if(!$story) {
            throw $this->createNotFoundException("No Story found with the following id: " .$id);
        }
        $consumable = new ConsumableItem;
        $consumable->setStory($story);

        $form = $this->createForm(ConsumableItemType::class, $consumable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @param Request, $id
     *
     * @return Response
     * @Route("/story/{id}/consumable-edit/{consumableId}", name="consumableEdit")
     */
    public function editConsumableAction(Request $request, $id, $consumableId) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        if(!$story) {
            throw $this->createNotFoundException("No Story found with the following id: " .$id);
        }
        $consumable = $em->getRepository(ConsumableItem::class)->find($consumableId);
        if(!$consumable) {
            throw $this->createNotFoundException("No Consumable Item found with the following id: " . $consumableId);
        }
        $form = $this->createForm(ConsumableItemType::class, $consumable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
    public function specialItemFormAction(Request $request, $id) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        if(!$story) {
            throw $this->createNotFoundException("No Story found with the following id: " .$id);
        }
        $specialItem = new SpecialItem;
        $specialItem->setStory($story);

        $form = $this->createForm(SpecialItemType::class, $specialItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @param Request, $id
     *
     * @return Response
     * @Route("/story/{id}/specialItem-edit/{specialItemId", name="specialItemEdit")
     */
    public function editSpecialItemAction(Request $request, $id, $specialItemId) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        if(!$story) {
            throw $this->createNotFoundException("No Story found with the following id: " .$id);
        }
        $specialItem = $em->getRepository(SpecialItem::class)->find($specialItemId);
        if(!$specialItem) {
            throw $this->createNotFoundException("No Special Item found with the following id: " .$specialItemId);
        }
        $form = $this->createForm(SpecialItemType::class, $specialItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/story/{id}/npc-edit/{npcId}", name="npcEdit")
     */
    public function editNpcAction (Request $request, $id, $npcId) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        if(!$story) {
            throw $this->createNotFoundException("No Story found with the following id: " . $id);
        }
        $npc = $em->getRepository(Npc::class)->find($npcId);
        if(!$npc) {
            throw $this->createNotFoundException("No NPC found with the following id: " . $npcId);
        }
        $form = $this->createForm(NpcType::class, $npc, ["story" => $story]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
    public function chapterFormAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        $specialItems = $em->getRepository(SpecialItem::class)->findBy(["story" => $story]);
        $chapter = new Chapter();
        $chapter->setStory($story);

        $form = $this->createForm(ChapterType::class, $chapter, [
            'story' => $story,
            'specialItems' => $specialItems
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($chapter->getChoices() as $choice) {
                $choice->setChapter($chapter);
            }
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

    /**
     * @param Request, $id
     *
     * @return Response
     * @Route("/story/{id}/chapter-form/{idChapter}", name="chapterEdit")
     */
    public function editChapterAction(Request $request, $id, $idChapter)
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($id);
        $specialItems = $em->getRepository(SpecialItem::class)->findBy(["story" => $story]);
        $chapter = $em->getRepository(Chapter::class)->find($idChapter);

        $form = $this->createForm(ChapterType::class, $chapter, [
            'story' => $story,
            'specialItems' => $specialItems
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($chapter->getChoices() as $choice) {
                $choice->setChapter($chapter);
            }
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
