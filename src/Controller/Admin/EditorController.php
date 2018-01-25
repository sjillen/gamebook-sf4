<?php

namespace App\Controller\Admin;

use App\Entity\Choice;
use App\Entity\ConsumableItem;
use App\Entity\Hero;
use App\Entity\Ruleset;
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
use App\Form\RulesetType;
use App\Repository\StoryRepository;
use App\StoryBuilder\Publisher;
use App\StoryBuilder\UniqueChapterType;
use App\Utils\Slugger;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditorController
 * @package App\Controller\Admin
 * @Route("/editor")
 */
class EditorController extends Controller
{

    /**
     * @param StoryRepository $storyRepository
     * @return Response
     * @Route("/index", name="editor_index")
     */
    public function editorIndex(StoryRepository $storyRepository) : Response
    {
        $user = $this->getUser();
        $stories = $storyRepository->findBy(["user" => $user]);
        return $this->render("editor/editor_index.html.twig",[
            "stories" => $stories
        ]);
    }

    /**
     * @param $slug
     *
     * @Route("story/{slug}", name="editor_story")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function storyDetail(Story $story) : Response
    {
        $chapters = $story->getChapters();
        $skills = $this->getDoctrine()->getManager()->getRepository(Skill::class)->findSkillsByStory($story);

        return $this->render("editor/editor_story.html.twig", [
            "story" => $story,
            "chapters" => $chapters,
            "skills" => $skills
        ]);
    }

    /**
     * @param Story $story
     * @return RedirectResponse
     * @Route("/test-story/{slug}", name="story_test")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function testStory(Story $story, Publisher $publisher) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();

        $errorMessages = $publisher->publishStory($story);
        if (count($errorMessages) > 0) {
            $this->addFlash("danger", "The story cannot be tested yet!");
            foreach ($errorMessages as $message) {
                $this->addFlash("warning", $message);
            }

            return $this->redirectToRoute("editor_index");

        } else {
            $intro = $em->getRepository(Chapter::class)->findOneBy(["story" => $story, "type" => "intro"]);
            return $this->redirectToRoute("story_intro", [
                "slug" => $story->getSlug(),
                "idIntro" => $intro->getId()
            ]);
        }
    }

    /**
     * @param Story $story
     * @return RedirectResponse
     * @Route("/publish-{slug}", name="story_publish")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function publishStory(Story $story, Publisher $publisher) : RedirectResponse
    {
        if ($story->getIsPublished()) {
            $story->setIsPublished(false);
            $this->addFlash("warning", $story->getTitle()." can't be played anymore!");
        } else {
            $errorMessages = $publisher->publishStory($story);
            if (count($errorMessages) > 0) {
                $this->addFlash("danger", "The story cannot be published!");
                foreach ($errorMessages as $message) {
                    $this->addFlash("warning", $message);
                }

            } else {
                $story->setIsPublished(true);
                $this->addFlash("success", "Players can now enjoy " . $story->getTitle(). " !");
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($story);
        $em->flush();
        return $this->redirectToRoute("editor_index");

    }

    /**
     * @param REQUEST $request
     *
     * @return Response
     * @Route("/story-form", name="story_add")
     */
    public function addStory(Request $request) : Response
    {
        $story = new Story();
        $user = $this->getUser();
        $story->setUser($user);
        $story->setIsPublished(false);
        $form = $this->createForm(StoryType::class, $story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $story->setSlug(Slugger::slugify($story->getTitle()));
            $em->persist($story);
            $em->flush();

            $this->addFlash("success", "The story has been saved successfully");
            return $this->redirectToRoute("editor_index");
        }
        return $this->render("form/story-form.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request, $slug
     *
     * @return Response
     * @Route("/{slug}/edit", name="story_edit")
     */
    public function editStory(Request $request, Story $story)
    {
        $form = $this->createForm(StoryType::class, $story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($story);
            $em->flush();

            $this->addFlash("success", "The story has been modified successfully");
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-story'));
        }
        return $this->render("form/story-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param $slug
     *
     * @return RedirectResponse
     * @Route("/{slug}/delete", name="story_delete")
     */
    public function deleteStory(Story $story) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($story);
        $em->flush();

        $this->addFlash("danger", "The story and all its components have been deleted");

        return $this->redirectToRoute("editor_index");
    }


    /**
     * @param Request $request
     * @param Story $story
     * @return Response
     * @Route("/story/{slug}/new-ruleset", name="ruleset_add")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function addRuleset(Request $request, Story $story) : Response
    {
        if($story->getRuleset()) {
            $this->addFlash("danger", "A Set of Rules already exists for this story");
            return $this->redirectToRoute("story", ["slug" => $story->getSlug()]);
        }
        $ruleset = new Ruleset();
        $ruleset->setStory($story);
        $form = $this->createForm(RulesetType::class, $ruleset);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ruleset);
            $em->flush();

            $this->addFlash("success", "Your set of rules for this story has been successfully created");
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-story'));        }

        return $this->render("form/ruleset-form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param Story $story
     * @param Ruleset $ruleset
     * @return Response
     * @Route("/story/{slug}/ruleset-edit/{id}", name="ruleset_edit")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("ruleset", options={"mapping": {"id": "id"}})
     */
    public function editRuleset(Request $request, Story $story, Ruleset $ruleset) : Response
    {
        $form = $this->createForm(RulesetType::class, $ruleset);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ruleset);
            $em->flush();

            $this->addFlash("success", "Your set of rules for this story has been successfully modified");
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-story'));
        }

        return $this->render("form/ruleset-form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @param Story $story
     * @param Ruleset $ruleset
     * @return RedirectResponse
     * @Route("/story/{slug}/ruleset-remove/{id}", name="ruleset_delete")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("ruleset", options={"mapping": {"id": "id"}})
     */
    public function deleteRuleset(Story $story, Ruleset $ruleset) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($ruleset);
        $em->flush();
        $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
        return $this->redirect(sprintf( '%s#%s', $url, 'editor-story'));    }


    /**
     * @param Request, $id
     *
     * @return Response
     * @Route("/story/{slug}/skill-add", name="skill_add")
     */
    public function addSkill(Request $request, Story $story) : Response
    {
        $skill = new Skill();

        $form = $this->createForm(SkillType::class, $skill, ["story" => $story]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skill->setStory($story);
            $em = $this->getDoctrine()->getManager();
            $em->persist($skill);
            $em->flush();

            $this->addFlash("success", "The skill has been saved successfully");
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));
        }
        return $this->render("form/skill-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id
     *
     * @return Response
     * @Route("/story/{slug}/skill-edit/{id}", name="skill_edit")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("skill", options={"mapping": {"id": "id"}})
     */
    public function editSkill(Request $request, Story $story, Skill $skill) : Response
    {

        $form = $this->createForm(SkillType::class, $skill, ["story" => $story]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($skill);
            $em->flush();

            $this->addFlash("success", "The skill has been modified successfully");
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));
        }
        return $this->render("form/skill-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @return RedirectResponse
     * @Route("/story/{slug}/skill-remove/{id}", name="skill_delete")
     * @ParamConverter("skill", options={"mapping": {"id": "id"}})
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function deleteSkill(Story $story, Skill $skill) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($skill);
        $em->flush();

        $this->addFlash("success", "The skill has been removed successfully");

        $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
        return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));    }

    /**
     * @param Request
     *
     * @return Response
     * @Route("/{slug}/weapon-add", name="weapon_add")
     */
    public function addWeapon(Request $request, Story $story) : Response
    {
        $weapon = new Weapon();
        $weapon->setBonusGiven(0);
        $weapon->setAttributeTargeted("none");
        $weapon->setStory($story);

        $form = $this->createForm(WeaponType::class, $weapon, ["story" => $story]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($weapon);
            $em->flush();

            $this->addFlash("success", "The weapon has been saved successfully");
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));
        }
        return $this->render("form/weapon-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id, $idWeapon
     *
     * @return Response
     * @Route("/{slug}/weapon-edit/{id}", name="weapon_edit")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("weapon", options={"mapping": {"id": "id"}})
     */
    public function editWeapon(Request $request,Story $story, Weapon $weapon) : Response
    {
        $form = $this->createForm(WeaponType::class, $weapon, ["story" => $story]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($weapon);
            $em->flush();

            $this->addFlash("success", "The weapon has been modified successfully");
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));
        }
        return $this->render("form/weapon-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @return RedirectResponse
     * @Route("/story/{slug}/weapon-delete/{id}", name="weapon_delete")
     * @ParamConverter("weapon", options={"mapping": {"id": "id"}})
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function deleteWeapon(Story $story, Weapon $weapon) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($weapon);
        $em->flush();

        $this->addFlash("success", "The weapon has been removed successfully");

        $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
        return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));
    }

    /**
     * @param Request, $id
     *
     * @return Response
     * @Route("/{slug}/consumableItem-add", name="consumableItem_add")
     */
    public function addConsumableItem(Request $request, Story $story) : Response
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
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));
        }
        return $this->render("form/consumableItem-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id, $idConsumable
     *
     * @return Response
     * @Route("/{slug}/consumableItem-edit/{id}", name="consumableItem_edit")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("consumableItem", options={"mapping": {"id": "id"}})
     */
    public function editConsumableItem(Request $request, Story $story, ConsumableItem $consumable) : Response
    {
        $form = $this->createForm(ConsumableItemType::class, $consumable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($consumable);
            $em->flush();

            $this->addFlash("success", "The consumable has been modified successfully");
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));
        }
        return $this->render("form/consumableItem-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @return RedirectResponse
     * @Route("/{slug}/consumableItem-delete/{id}", name="consumableItem_delete")
     * @ParamConverter("consumableItem", options={"mapping": {"id": "id"}})
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function deleteConsumableItem(Story $story, ConsumableItem $consumableItem) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($consumableItem);
        $em->flush();

        $this->addFlash("success", "The consumable item has been removed successfully");

        $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
        return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));
    }

    /**
     * @param Request, $slug
     *
     * @return Response
     * @Route("/{slug}/specialItem-form", name="specialItem_add")
     */
    public function addSpecialItem(Request $request, Story $story) : Response
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
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));
        }
        return $this->render("form/specialItem-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id, $slug
     *
     * @return Response
     * @Route("/{slug}/specialItem-edit/{id}", name="specialItem_edit")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("specialItem", options={"mapping": {"id": "id"}})
     */
    public function editSpecialItem(Request $request, Story $story, SpecialItem $specialItem) : Response
    {
        $form = $this->createForm(SpecialItemType::class, $specialItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($specialItem);
            $em->flush();

            $this->addFlash("success", "The Special Item has been modified successfully");
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));
        }
        return $this->render("form/specialItem-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @return RedirectResponse
     * @Route("/{slug}/specialItem-delete/{id}", name="specialItem_delete")
     * @ParamConverter("specialItem", options={"mapping": {"id": "id"}})
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function deleteSpecialItem(Story $story, SpecialItem $specialItem) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($specialItem);
        $em->flush();

        $this->addFlash("success", "The special item has been removed successfully");

        $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
        return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));
    }

    /**
     * @param Request, $slug
     *
     * @return Response
     * @Route("/{slug}/npc-add", name="npc_add")
     */
    public function addNpc (Request $request, Story $story) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $npc = new Npc();
        $npc->setStory($story);
        $skills = $em->getRepository(Skill::class)->findSkillsByStory($story);
        $form = $this->createForm(NpcType::class, $npc, ["story" => $story, "skills" => $skills]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($npc);
            $em->flush();

            $this->addFlash("success", "The Character has been successully saved");
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));
        }
        return $this->render("form/npc-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id, $slug
     *
     * @return Response
     * @Route("/{slug}/npc-edit/{id}", name="npc_edit")
     * @ParamConverter("npc", options={"mapping": {"id": "id"}})
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function editNpc (Request $request, Story $story, Npc $npc) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $skills = $em->getRepository(Skill::class)->findSkillsByStory($story);

        $form = $this->createForm(NpcType::class, $npc, ["story" => $story, "skills" => $skills]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($npc);
            $em->flush();

            $this->addFlash("success", "The Character has been successully modified");
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));
        }
        return $this->render("form/npc-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @return RedirectResponse
     * @Route("/{slug}/npc-delete/{id}", name="npc_delete")
     * @ParamConverter("npc", options={"mapping": {"id": "id"}})
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function deleteNpc(Story $story, Npc $npc) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($npc);
        $em->flush();

        $this->addFlash("success", "The character has been removed successfully");

        $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
        return $this->redirect(sprintf( '%s#%s', $url, 'editor-components'));
    }

    /**
     * @param Request, $slug
     *
     * @return Response
     * @Route("/story/{slug}/chapter-add", name="chapter_add")
     */
    public function addChapter(Request $request, Story $story, UniqueChapterType $unique)
    {
        $em = $this->getDoctrine()->getManager();
        $chapter = new Chapter();
        $chapter->setStory($story);
        $skills = $em->getRepository(Skill::class)->findSkillsByStory($story);
        $form = $this->createForm(ChapterType::class, $chapter, [
            'story' => $story,
            'skills' => $skills
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Call UniqueChapterType service to check if chapter is a starter, and a starter already exists
            $starter = $unique->checkUniqueStarter($story, $chapter);
            if($starter) {
                $this->addFlash("warning", "A Starter chapter already exists : " . $starter->getTitle());
                return $this->redirectToRoute("chapter_add", ["slug" => $story->getSlug(), "id" => $chapter->getId()]);
            }
            $intro = $unique->checkUniqueIntro($story, $chapter);
            if($intro) {
                $this->addFlash("warning", "A Intro chapter already exists : " . $intro->getTitle());
                return $this->redirectToRoute("chapter_add", ["slug" => $story->getSlug(), "id" => $chapter->getId()]);
            }
            foreach($chapter->getChoices() as $choice) {
                $choice->setChapter($chapter);
            }
            $em->persist($chapter);
            $em->flush();

            $this->addFlash("success", "The chapter has been saved successfully");
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-chapters'));
        }
        return $this->render("form/chapter-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request, $id, $slug
     *
     * @return Response
     * @Route("/story/{slug}/chapter-edit/{id}", name="chapter_edit")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("chapter", options={"mapping": {"id": "id"}})
     */
    public function editChapter(Request $request, Story $story, $id, UniqueChapterType $unique)
    {
        $em = $this->getDoctrine()->getManager();
        $chapter = $em->getRepository(Chapter::class)->find($id);
        $choices = $em->getRepository(Choice::class)->findBy(["chapter" => $chapter]);

        $form = $this->createForm(ChapterType::class, $chapter, ['story' => $story]);
        $form->handleRequest($request);

        $originalChoices = new ArrayCollection();
        foreach ($choices as $choice) {
            $originalChoices->add($choice);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($originalChoices as $choice) {
                if (false === $chapter->getChoices()->contains($choice)) {
                    $chapter->removeChoice($choice);
                    $choice->setChapter(null);
                    $em->remove($choice);
                }
            }
            //Call UniqueChapterType service to check if chapter is a starter, and a starter already exists
            $starter = $unique->checkUniqueStarter($story, $chapter);
            if($starter) {
                $this->addFlash("warning", "A Starter chapter already exists : " . $starter->getTitle());
                return $this->redirectToRoute("chapter_edit", ["slug" => $story->getSlug(), "id" => $chapter->getId()]);
            }
            $intro = $unique->checkUniqueIntro($story, $chapter);
            if($intro) {
                $this->addFlash("warning", "A Intro chapter already exists : " . $intro->getTitle());
                return $this->redirectToRoute("chapter_edit", ["slug" => $story->getSlug(), "id" => $chapter->getId()]);
            }
            foreach($chapter->getChoices() as $choice) {
                $choice->setChapter($chapter);
            }
            $em->persist($chapter);
            $em->flush();

            $this->addFlash("success", "The chapter has been modified successfully");
            $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
            return $this->redirect(sprintf( '%s#%s', $url, 'editor-chapters'));
        }
        return $this->render("form/chapter-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Story $story
     * @param Chapter $chapter
     * @return RedirectResponse
     * @Route("/story/{slug}/chapter-delete/{id}", name="chapter_delete")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("chapter", options={"mapping": {"id": "id"}})
     */
    public function deleteChapter(Story $story, Chapter $chapter): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $targetingChoices = $em->getRepository(Choice::class)->findBy(["targetChapter" => $chapter]);
        foreach ($targetingChoices as $choice) {
            $em->remove($choice);
        }
        $em->remove($chapter);
        $em->flush();
        $this->addFlash("success", "The chapter has been removed successfully");
        $url = $this->generateUrl("editor_story", ["slug" => $story->getSlug()]);
        return $this->redirect(sprintf( '%s#%s', $url, 'editor-chapters'));
    }
}
