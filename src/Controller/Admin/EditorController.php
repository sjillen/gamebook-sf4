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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;


class EditorController extends Controller
{
    /**
     * @param $id
     *
     * @Route("story/{slug}", name="story")
     */
    public function storyAction(Story $story) : Response
    {
        $starter = $this->getDoctrine()->getManager()->getRepository(Chapter::class)->findOneBy(["story" => $story,"type" => "standard"]);

        return $this->render("story/story.html.twig", ["story" => $story, "starter" => $starter]);
    }

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
     * @Route("/story-edit/{slug}", name="storyEdit")
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
     * @param $id
     *
     * @return RedirectResponse
     * @Route("/story/{slug}/remove", name="storyRemove")
     */
    public function removeStoryAction(Story $story) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($story);
        $em->flush();

        $this->addFlash("danger", "The story and all its components have been deleted");

        return $this->redirectToRoute("index");
    }

    /**
     * @param $id, $slug
     *
     * @return Response
     * @Route("/story/{slug}/skill/{id}", name="skill")
     * @ParamConverter("story", options={"mapping": {"slug" : "slug"}})
     * @ParamConverter("skill", options={"mapping": {"id": "id"}})
     */
    public function skillAction(Story $story, Skill $skill) : Response
    {
        return $this->render("story/skill.html.twig", [
            "story" => $story,
            "skill" => $skill
        ]);
    }

    /**
     * @param Request, $id
     *
     * @return Response
     * @Route("/story/{slug}/skill-form", name="skillForm")
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
                "slug" => $story->getSlug()
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
     * @Route("/story/{slug}/skill-edit/{id}", name="skillEdit")
     * @ParamConverter("skill", options={"mapping": {"id": "id"}})
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
                "slug" => $story->getSlug()
            ]);
        }
        return $this->render("form/skill-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @return RedirectResponse
     * @Route("/story/{slug}/skill-remove/{id}", name="skillRemove")
     * @ParamConverter("skill", options={"mapping": {"id": "id"}})
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function removeSkill(Story $story, Skill $skill) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($skill);
        $em->flush();

        $this->addFlash("success", "The skill has been removed successfully");

        return $this->redirectToRoute("story", ["slug" => $story->getSlug()]);
    }

    /**
     * @param $idStory, $idWeapon
     *
     * @return Response
     * @Route("/story/{slug}/weapon/{id}", name="weapon")
     * @ParamConverter("story", options={"mapping": {"slug" : "slug"}})
     * @ParamConverter("weapon", options={"mapping": {"id": "id"}})
     */
    public function weaponAction(Story $story, Weapon $weapon) : Response
    {
        return $this->render("story/weapon.html.twig", [
            "story" => $story,
            "weapon" => $weapon
        ]);
    }

    /**
     * @param Request
     *
     * @return Response
     * @Route("/story/{slug}/weapon-form", name="weaponForm")
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
                "slug" => $story->getSlug()
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
     * @Route("/story/{slug}/weapon-edit/{id}", name="weaponEdit")
     * @ParamConverter("weapon", options={"mapping": {"id": "id"}})
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
                "slug" => $story->getSlug()
            ]);
        }
        return $this->render("form/weapon-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @return RedirectResponse
     * @Route("/story/{slug}/weapon-remove/{id}", name="weaponRemove")
     * @ParamConverter("weapon", options={"mapping": {"id": "id"}})
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function removeWeapon(Story $story, Weapon $weapon) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($weapon);
        $em->flush();

        $this->addFlash("success", "The weapon has been removed successfully");

        return $this->redirectToRoute("story", ["slug" => $story->getSlug()]);
    }

    /**
     * @param $idStory, $idConsumableItem
     *
     * @return Response
     * @Route("/story/{slug}/npc/{id}", name="consumableItem")
     * @ParamConverter("story", options={"mapping": {"slug" : "slug"}})
     * @ParamConverter("consumableItem", options={"mapping": {"id": "id"}})
     */
    public function consumableItemAction(Story $story, ConsumableItem $consumableItem) : Response
    {
        return $this->render("story/consumableItem.html.twig", [
            "story" => $story,
            "consumableItem" => $consumableItem
        ]);
    }

    /**
     * @param Request, $id
     *
     * @return Response
     * @Route("/story/{slug}/consumable-form", name="consumableForm")
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
                "slug" => $story->getSlug()
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
     * @Route("/story/{slug}/consumable-edit/{id}", name="consumableEdit")
     * @ParamConverter("consumableItem", options={"mapping": {"id": "id"}})
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
                "slug" => $story->getSlug()
            ]);
        }
        return $this->render("form/consumableItem-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @return RedirectResponse
     * @Route("/story/{slug}/consumableItem-remove/{id}", name="consumableItemRemove")
     * @ParamConverter("consumableItem", options={"mapping": {"id": "id"}})
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function removeConsumableItem(Story $story, ConsumableItem $consumableItem) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($consumableItem);
        $em->flush();

        $this->addFlash("success", "The consumable item has been removed successfully");

        return $this->redirectToRoute("story", ["slug" => $story->getSlug()]);
    }

    /**
     * @param $idStory, $idSpecialItem
     *
     * @return Response
     * @Route("/story/{slug}/npc/{id}", name="specialItem")
     * @ParamConverter("story", options={"mapping": {"slug" : "slug"}})
     * @ParamConverter("specialItem", options={"mapping": {"id": "id"}})
     */
    public function specialItemAction(Story $story, SpecialItem $specialItem) : Response
    {
        return $this->render("story/specialItem.html.twig", [
            "story" => $story,
            "specialItem" => $specialItem
        ]);
    }

    /**
     * @param Request, $slug
     *
     * @return Response
     * @Route("/story/{slug}/specialItem-form", name="specialItemForm")
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
                "slug" => $story->getSlug()
            ]);
        }
        return $this->render("form/specialItem-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id, $slug
     *
     * @return Response
     * @Route("/story/{slug}/specialItem-edit/{id}", name="specialItemEdit")
     * @ParamConverter("specialItem", options={"mapping": {"id": "id"}})
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
                "slug" => $story->getSlug()
            ]);
        }
        return $this->render("form/specialItem-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @return RedirectResponse
     * @Route("/story/{slug}/specialItem-remove/{id}", name="specialItemRemove")
     * @ParamConverter("specialItem", options={"mapping": {"id": "id"}})
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function removeSpecialItem(Story $story, SpecialItem $specialItem) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($specialItem);
        $em->flush();

        $this->addFlash("success", "The special item has been removed successfully");

        return $this->redirectToRoute("story", ["slug" => $story->getSlug()]);
    }

    /**
     * @param $id, $slug
     *
     * @return Response
     * @Route("/story/{slug}/npc/{id}", name="npc")
     * @ParamConverter("story", options={"mapping": {"slug" : "slug"}})
     * @ParamConverter("npc", options={"mapping": {"id": "id"}})
     */
    public function npcAction(Story $story, Npc $npc) : Response
    {
        return $this->render("story/npc.html.twig", [
            "story" => $story,
            "npc" => $npc
        ]);
    }

    /**
     * @param Request, $slug
     *
     * @return Response
     * @Route("/story/{slug}/npc-form", name="npcForm")
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
                "slug" => $story->getSlug()
            ]);
        }
        return $this->render("form/npc-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id, $slug
     *
     * @return Response
     * @Route("/story/{slug}/npc-edit/{id}", name="npcEdit")
     * @ParamConverter("npc", options={"mapping": {"id": "id"}})
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
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
                "slug" => $story->getSlug()
            ]);
        }
        return $this->render("form/npc-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @return RedirectResponse
     * @Route("/story/{slug}/npc-remove/{id}", name="npcRemove")
     * @ParamConverter("npc", options={"mapping": {"id": "id"}})
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function removeNpc(Story $story, Npc $npc) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($npc);
        $em->flush();

        $this->addFlash("success", "The character has been removed successfully");

        return $this->redirectToRoute("story", ["slug" => $story->getSlug()]);
    }

    /**
     * @param Request, $slug
     *
     * @return Response
     * @Route("/story/{slug}/chapter-form", name="chapterForm")
     */
    public function chapterFormAction(Request $request, Story $story)
    {
        $chapter = new Chapter();
        $chapter->setStory($story);

        $form = $this->createForm(ChapterType::class, $chapter, [
            'story' => $story,
        ]);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $starters = $em->getRepository(Chapter::class)->findStarterByStory($story);

        if ($form->isSubmitted() && $form->isValid()) {
            if($starters && $starters[0]->getTitle() !== $chapter->getTitle()) {
                if($chapter->getType() == "starter") {
                    $this->addFlash("warning", "A Starter chapter already exists : " . $starters[0]->getTitle());
                    return $this->redirectToRoute("chapterEdit", ["slug" => $story->getSlug(), "id" => $chapter->getId()]);
                }
            }
            foreach($chapter->getChoices() as $choice) {
                $choice->setChapter($chapter);
            }

            $em->persist($chapter);
            $em->flush();

            $this->addFlash("success", "The chapter has been saved successfully");
            return $this->redirectToRoute('story', [
                "slug" => $story->getSlug()
            ]);
        }
        return $this->render("form/chapter-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id, $slug
     *
     * @return Response
     * @Route("/story/{slug}/chapter-form/{id}", name="chapterEdit")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("chapter", options={"mapping": {"id": "id"}})
     */
    public function editChapterAction(Request $request, Story $story, Chapter $chapter)
    {
        $form = $this->createForm(ChapterType::class, $chapter, [
            'story' => $story
        ]);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $starters = $em->getRepository(Chapter::class)->findStarterByStory($story);

        if ($form->isSubmitted() && $form->isValid()) {
            if($starters[0] && $starters[0]->getTitle() !== $chapter->getTitle()) {
                if($chapter->getType() == "starter") {
                    $this->addFlash("warning", "A Starter chapter already exists : " . $starters[0]->getTitle());
                    return $this->redirectToRoute("chapterEdit", ["slug" => $story->getSlug(), "id" => $chapter->getId()]);
                }
            }
            foreach($chapter->getChoices() as $choice) {
                $choice->setChapter($chapter);
            }

            $em->persist($chapter);
            $em->flush();

            $this->addFlash("success", "The chapter has been modified successfully");
            return $this->redirectToRoute('story', [
                "slug" => $story->getSlug()
            ]);
        }
        return $this->render("form/chapter-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Story $story
     * @param Chapter $chapter
     * @return RedirectResponse
     * @Route("/story/{slug}/chapter-remove/{id}", name="chapterRemove")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("chapter", options={"mapping": {"id": "id"}})
     */
    public function removeChapter(Story $story, Chapter $chapter): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($chapter);
        $em->flush();
        $this->addFlash("success", "The chapter has been removed successfully");

        return $this->redirectToRoute("story", ["slug" => $story->getSlug()]);
    }
}
