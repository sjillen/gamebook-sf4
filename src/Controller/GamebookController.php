<?php

namespace App\Controller;

use App\Entity\ConsumableItem;
use App\Entity\Story;
use App\Entity\Chapter;
use App\Entity\Skill;
use App\Entity\Weapon;
use App\Entity\Npc;
use App\Entity\SpecialItem;
use App\Repository\StoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GamebookController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(StoryRepository $stories) : Response
    {
        $allStories = $stories->findAll();

        return $this->render("gamebook/index.html.twig", [
            'stories' => $allStories
        ]);
    }

    /**
     * @param $id
     * 
     * @Route("story/{slug}", name="story")
     */
    public function storyAction(Story $story) : Response
    {
        return $this->render("story/story.html.twig", ["story" => $story]);
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
     * @param $idStory, $idChapter
     *
     * @return Response
     * @Route("/story/{slug}/chapter/{id}", name="chapter")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("chapter", options={"mapping": {"id": "id"}})
     */
    public function chapterAction(Story $story, Chapter $chapter) : Response
    {
        return $this->render("story/chapter.html.twig", [
            "story" => $story,
            "chapter" => $chapter
        ]);
    }

    /**
     * @param $idStory, $idNpc
     *
     * @return Response
     * @Route("/story/{idStory}/npc/{idNpc}", name="npc")
     * @ParamConverter("story", options={"mapping": {"idStory" : "id"}})
     * @ParamConverter("npc", options={"mapping": {"idNpc": "id"}})
     */

    public function npcAction(Story $story, Npc $npc) : Response
    {
        return $this->render("story/npc.html.twig", [
            "story" => $story,
            "npc" => $npc
        ]);
    }

    /**
     * @param $idStory, $idSkill
     *
     * @return Response
     * @Route("/story/{idStory}/skill/{idSkill}", name="skill")
     * @ParamConverter("story", options={"mapping": {"idStory" : "id"}})
     * @ParamConverter("skill", options={"mapping": {"idSkill": "id"}})
     */

    public function skillAction(Story $story, Skill $skill) : Response
    {
        return $this->render("story/skill.html.twig", [
            "story" => $story,
            "skill" => $skill
        ]);
    }

    /**
     * @param $idStory, $idWeapon
     *
     * @return Response
     * @Route("/story/{idStory}/weapon/{idWeapon}", name="weapon")
     * @ParamConverter("story", options={"mapping": {"idStory" : "id"}})
     * @ParamConverter("weapon", options={"mapping": {"idWeapon": "id"}})
     */

    public function weaponAction(Story $story, Weapon $weapon) : Response
    {
        return $this->render("story/weapon.html.twig", [
            "story" => $story,
            "weapon" => $weapon
        ]);
    }

    /**
     * @param $idStory, $idSpecialItem
     *
     * @return Response
     * @Route("/story/{idStory}/npc/{idSpecialItem}", name="specialItem")
     * @ParamConverter("story", options={"mapping": {"idStory" : "id"}})
     * @ParamConverter("specialItem", options={"mapping": {"idSpecialItem": "id"}})
     */

    public function specialItemAction(Story $story, SpecialItem $specialItem) : Response
    {
        return $this->render("story/specialItem.html.twig", [
            "story" => $story,
            "specialItem" => $specialItem
        ]);
    }

    /**
     * @param $idStory, $idConsumableItem
     *
     * @return Response
     * @Route("/story/{idStory}/npc/{idConsumableItem}", name="consumableItem")
     * @ParamConverter("story", options={"mapping": {"idStory" : "id"}})
     * @ParamConverter("consumableItem", options={"mapping": {"idConsumableItem": "id"}})
     */

    public function consumableItemAction(Story $story, ConsumableItem $consumableItem) : Response
    {
        return $this->render("story/consumableItem.html.twig", [
            "story" => $story,
            "consumableItem" => $consumableItem
        ]);
    }
}
