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
use App\Form\NpcType;
use App\Form\StoryType;
use App\Form\ChapterType;
use App\Form\SkillType;
use App\Form\WeaponType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @param Request, $idStory, $idChapter
     *
     * @return Response
     * @Route("/story/{idStory}/chapter/{idChapter}", name="chapter")
     */
    public function chapterAction(Request $request, $idStory, $idChapter) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($idStory);
        if (!$story) {
            throw $this->createNotFoundException("No story found with the id" . $idStory);
        }
        $chapter = $em->getRepository(Chapter::class)->find($idChapter);
        if (!$chapter) {
            throw $this->createNotFoundException("No chapter found with the id" . $idChapter);
        }

        return $this->render("chapter.html.twig", [
            "story" => $story,
            "chapter" => $chapter
        ]);
    }

    /**
     * @param Request, $idStory, $idNpc
     *
     * @return Response
     * @Route("/story/{idStory}/npc/{idNpc}", name="npc")
     */

    public function npcAction(Request $request, $idStory, $idNpc) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($idStory);
        if (!$story) {
            throw $this->createNotFoundException("No story found with the id" . $idStory);
        }
        $npc = $em->getRepository(Npc::class)->find($idNpc);
        if (!$npc) {
            throw $this->createNotFoundException("No npc found with the id" . $idNpc);
        }

        return $this->render("npc.html.twig", [
            "story" => $story,
            "npc" => $npc
        ]);
    }

    /**
     * @param Request, $idStory, $idSkill
     *
     * @return Response
     * @Route("/story/{idStory}/skill/{idSkill}", name="skill")
     */

    public function skillAction(Request $request, $idStory, $idSkill) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($idStory);
        if (!$story) {
            throw $this->createNotFoundException("No story found with the id" . $idStory);
        }
        $skill = $em->getRepository(Skill::class)->find($idSkill);
        if (!$skill) {
            throw $this->createNotFoundException("No skill found with the id" . $idSkill);
        }

        return $this->render("skill.html.twig", [
            "story" => $story,
            "skill" => $skill
        ]);
    }

    /**
     * @param Request, $idStory, $idWeapon
     *
     * @return Response
     * @Route("/story/{idStory}/weapon/{idWeapon}", name="weapon")
     */

    public function weaponAction(Request $request, $idStory, $idWeapon) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($idStory);
        if (!$story) {
            throw $this->createNotFoundException("No story found with the id" . $idStory);
        }
        $weapon = $em->getRepository(Weapon::class)->find($idWeapon);
        if (!$weapon) {
            throw $this->createNotFoundException("No weapon found with the id" . $idWeapon);
        }

        return $this->render("weapon.html.twig", [
            "story" => $story,
            "weapon" => $weapon
        ]);
    }

    /**
     * @param Request, $idStory, $idSpecialItem
     *
     * @return Response
     * @Route("/story/{idStory}/npc/{idSpecialItem}", name="specialItem")
     */

    public function specialItemAction(Request $request, $idStory, $idSpecialItem) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($idStory);
        if (!$story) {
            throw $this->createNotFoundException("No story found with the id" . $idStory);
        }
        $specialItem = $em->getRepository(SpecialItem::class)->find($idSpecialItem);
        if (!$specialItem) {
            throw $this->createNotFoundException("No Special Item found with the id" . $idSpecialItem);
        }

        return $this->render("specialItem.html.twig", [
            "story" => $story,
            "specialItem" => $specialItem
        ]);
    }

    /**
     * @param Request, $idStory, $idConsumableItem
     *
     * @return Response
     * @Route("/story/{idStory}/npc/{idConsumableItem}", name="consumableItem")
     */

    public function consumableItemAction(Request $request, $idStory, $idConsumableItem) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->find($idStory);
        if (!$story) {
            throw $this->createNotFoundException("No story found with the id" . $idStory);
        }
        $consumableItem = $em->getRepository(ConsumableItem::class)->find($idConsumableItem);
        if (!$consumableItem) {
            throw $this->createNotFoundException("No Consumable Item found with the id" . $idConsumableItem);
        }

        return $this->render("consumableItem.html.twig", [
            "story" => $story,
            "consumableItem" => $consumableItem
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
