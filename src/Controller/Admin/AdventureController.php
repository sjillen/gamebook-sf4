<?php

namespace App\Controller\Admin;

use App\Adventure\Alteration;
use App\Adventure\ChoiceDisplay;
use App\Adventure\ChoiceInteraction;
use App\Adventure\ItemPicker;
use App\Adventure\Saver;
use App\Entity\BackpackItem;
use App\Entity\Choice;
use App\Entity\ConsumableItem;
use App\Entity\Ruleset;
use App\Entity\Skill;
use App\Entity\SpecialItem;
use App\Entity\User;
use App\Entity\Weapon;
use App\Repository\ChapterRepository;
use App\Repository\HeroRepository;
use App\Repository\StoryRepository;
use const Grpc\CHANNEL_CONNECTING;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Hero;
use App\Form\HeroType;
use App\Entity\Story;
use App\Entity\Chapter;
use App\HeroBuilder\HeroBuilder;
use App\HeroBuilder\StarterInventory;
use App\HeroBuilder\HeroSkills;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * Class AdventureController
 * @package App\Controller\Admin
 * @Route("/adventure")
 */
class AdventureController extends AbstractController
{

    /**
     * @param StoryRepository $storyRepository
     * @return Response
     * @Route("/story-list", name="adventure_stories")
     *
     */
    public function gamebookIndex(StoryRepository $storyRepository, HeroRepository $heroes) : Response
    {
        $stories = $storyRepository->findBy(["isPublished" => true]);
        $user = $this->getUser();
        $deceasedHeroes = $heroes->findBy(["user" => $user, "status" => Hero::IS_DEAD]);
        $tavernHeroes = $heroes->findBy(["user" => $user, "status" => Hero::AT_TAVERN]);

        return $this->render('adventure/index.html.twig', [
            "stories" => $stories,
            "deceasedHeroes" => $deceasedHeroes,
            "tavernHeroes" => $tavernHeroes
        ]);
    }

    /**
     * @param Story $story
     * @return RedirectResponse
     * @Route("/play-story/{slug}", name="story_play")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function startStory(Story $story, Saver $saver) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(["username" => $this->getUser()->getUsername()]);

        $hero = $saver->loadHero($user, $story);
        $intro = $em->getRepository(Chapter::class)->findOneBy(["story" => $story, "type" => "intro"]);

        if (isset($hero)) {
            return $this->redirectToRoute("adventure", [
                "slug" => $story->getSlug(),
                "idHero" => $hero->getId(),
                "id" => $hero->getChapter()->getId()
            ]);
        } else {
            return $this->redirectToRoute("story_intro", [
                "slug" => $story->getSlug(),
                "idIntro" => $intro->getId()
            ]);
        }
    }

    /**
     * @param Story $story
     * @param Chapter $chapter
     * @return Response
     * @Route("/{slug}/intro-{idIntro}", name="story_intro")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function introductionAdventure(Story $story, $idIntro, ChapterRepository $chapters) : Response
    {
        $intro = $chapters->find($idIntro);
        $ruleset = $story->getRuleset();
        return $this->render("adventure/intro.html.twig", [
            "story" => $story,
            "intro" => $intro,
            "ruleset" => $ruleset
        ]);

    }

    /**
     * @param Request
     *
     * @return Response
     * @Route("/{slug}/create-hero", name="hero_create")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function createHero(Request $request, Story $story, HeroBuilder $heroBuilder, StarterInventory $starterInventory, HeroSkills $heroSkills) : Response
    {
        $hero = new Hero();
        $hero->setStory($story);
        $em = $this->getDoctrine()->getManager();
        $skills = $em->getRepository(Skill::class)->findSkillsByStory($story);

        $user = $em->getRepository(User::class)->findOneBy(["username" => $this->getUser()->getUsername()]);
        $hero->setUser($user);
        $form = $this->createForm(HeroType::class, $hero, ["skills" => $skills]);
        $form->handleRequest($request);
        $ruleset = $em->getRepository(Ruleset::class)->findOneBy(["story" => $story]);
        $hero->setMaxLife(0);

        if($form->isSubmitted() && $form->isValid()) {
            //Apply the set of rules from the corresponding story to complete the creation of the hero
            $hero = $heroBuilder->buildHero($hero, $ruleset);
            $starterInventory->setStarterInventory($story, $hero);

            //Check if the number of skills chosen complies with the rules
            if (HeroSkills::maxSkillAllowed($ruleset, $hero)) {
                $this->addFlash("warning", "You need to choose " . $ruleset->getMaxSkill() ." skills !");
                return $this->redirectToRoute("hero_create", ["slug" => $story->getSlug()]);
            }
            //Choose a weapon for Weaponskill
            $heroSkills->weaponSkillSelection($story, $hero);
            //Set Hero's max life
            $hero->setMaxLife($hero->getLife());

            $em->persist($hero);
            $em->flush();
            $starter = $em->getRepository(Chapter::class)->findOneBy(["story" => $story,"type" => "starter"]);
            $this->addFlash("success", "The creation of your hero is finished");
            return $this->redirectToRoute("adventure", [
                "slug" => $story->getSlug(),
                "idHero" => $hero->getId(),
                "id" => $starter->getId()
            ]);
        }
        return $this->render("form/hero-form.html.twig", [
            "form" => $form->createView(),
            "ruleset" => $ruleset
        ]);
    }



    /**
     * @return RedirectResponse
     * @Route("/{slug}/hero-delete/{id}", name="hero_delete")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("hero", options={"mapping": {"id": "id"}})
     */
    public function deleteHero(Story $story,Hero $hero)
    {
        $this->addFlash("danger", $hero->getName()." has been forever forgotten...");
        $status = $hero->getStatus();
        $status === Hero::IS_DEAD ? $anchor = "adventure-graveyard" : $anchor = "adventure-tavern";
        $em = $this->getDoctrine()->getManager();
        $em->remove($hero);
        $em->flush();

        $url = $this->generateUrl("adventure_stories");
        return $this->redirect(sprintf('%s#%s', $url, $anchor));
    }

    /**
     * @param $idStory, $idChapter
     *
     * @return Response
     * @Route("/{slug}/{idHero}/{id}", name="adventure")
     *
     */
    public function onAdventure($slug, $id, $idHero, SessionInterface $session, Saver $saver) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->findOneBy(["slug"=>$slug]);

        $hero = $em->getRepository(Hero::class)->find($idHero);
        $ruleset = $em->getRepository(Ruleset::class)->findOneBy(["story" => $story]);
        //load the whole content of the chapter
        $chapter = $em->getRepository(Chapter::class)->findWholeChapter($id);
        $choices = $chapter->getChoices();
        //check if chapter is different from previous chapter
        $chapterSession = $session->get('chapter');
        //save the hero progression
        $saver->saveHero($hero, $chapter);
        if(isset($chapterSession)) {
            if ($chapterSession->getId() === $chapter->getId()) {
                $chapter = $chapterSession; //same chapter
            } else {
                $session->set('chapter', $chapter); // if different: set new chapter in session
                $hero->iterate(); //update chapterIterator
                $em->persist($hero);
                $em->flush();
            }
        } else {
            $session->set('chapter', $chapter);
        }
        $hasFight = count($chapter->getNpcs()) === 0 ? false: true;
        $isDeath = $chapter->getType() === "death"? true : false;
        $isEnd = $chapter->getType() === "end"? true : false;
        $unlockChoices = ChoiceDisplay::unlockChoices($hero, $choices);
        $backpackStock = ItemPicker::getCurrentStock($hero);

        return $this->render("adventure/adventure.html.twig", [
            "story" => $story,
            "hero" => $hero,
            "ruleset" => $ruleset,
            "backpackStock" => $backpackStock,
            "chapter" => $chapter,
            "hasFight" => $hasFight,
            "isDeath" => $isDeath,
            "isEnd" => $isEnd,
            "choices" => $unlockChoices,

        ]);
    }

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @return JsonResponse
     * @Route("/{slug}/encounter/{idHero}/{idChapter}", name="encounter_json")
     */
    public function encounter(Request $request, $idHero, $idChapter, SessionInterface $session) : JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $heroLife = $request->query->get("heroLife");
        $life = (int) $heroLife;

        $hero = $em->getRepository(Hero::class)->find($idHero);
        $hero->setLife($life);
        $em->persist($hero);
        $em->flush();
        $chapter = $em->getRepository(Chapter::class)->find($idChapter);
        $chapter->getNpcs()->clear();
        $session->set("chapter", $chapter);
        $status = array('status' => "success","HeroSaved" => true);

        return new JsonResponse($status);


    }

    /**
     * @param Story $story
     * @param Hero $hero
     * @Route("/{slug}/death{idHero}", name="adventure_death")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     */
    public function deathChapter(Story $story, Hero $hero)
    {
        $em = $this->getDoctrine()->getManager();
        $hero->setChapter(null);
        $hero->setStatus(Hero::IS_DEAD);
        $backpackItems = $em->getRepository(BackpackItem::class)->findBy(["hero" => $hero]);
        foreach ($backpackItems as $item) {
            $em->remove($item);
        }
        $em->persist($hero);
        $em->flush();

        return $this->render('adventure/death.html.twig', [
            "story" => $story,
            "hero" => $hero
        ]);

    }

    /**
     * @param Story $story
     * @param Hero $hero
     * @return Response
     * @Route("/{slug}/end{idHero}", name="adventure_end")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     */
    public function endChapter(Story $story, Hero $hero)
    {
        $em = $this->getDoctrine()->getManager();
        $hero->setChapter(null);
        $hero->setStatus(Hero::AT_TAVERN);
        $backpackItems = $em->getRepository(BackpackItem::class)->findBy(["hero" => $hero]);
        foreach ($backpackItems as $item) {
            $em->remove($item);
        }
        $em->persist($hero);
        $em->flush();

        return $this->render('adventure/end.html.twig', [
            "story" => $story,
            "hero" => $hero
        ]);
    }

    /**
     * @param Story $story
     * @param Choice $choice
     * @param Hero $hero
     * @param ChoiceInteraction $interaction
     * @return RedirectResponse
     * @Route("/{slug}/hero-{idHero}/trade/{idChoice}", name="tradeGoldOrItem")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("choice", options={"mapping": {"idChoice": "id"}})
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     */
    public function tradeGoldOrItem(Story $story, Choice $choice, Hero $hero, ChoiceInteraction $interaction) : RedirectResponse
    {
        //Check whether requirement is Gold or Item, and remove the corresponding amount or object from inventory
        $message = $interaction->trade($hero, $choice);

        $this->addFlash("info", $message);
        return $this->redirectToRoute("adventure", [
            "slug" => $story->getSlug(),
            "idHero" => $hero->getId(),
            "id" => $choice->getTargetChapter()->getId()
        ]);
    }

    /**
     * @param Story $story
     * @param Chapter $chapter
     * @param Hero $hero
     * @param SpecialItem $specialItem
     * @return Response
     * @Route("/{slug}/hero-{idHero}/{idChapter}/pickup-si/{idItem}", name="pickupSpecialItem")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     * @ParamConverter("chapter", options={"mapping": {"idChapter": "id"}})
     * @ParamConverter("specialItem", options={"mapping": {"idItem": "id"}})
     */
    public function pickUpSpecialItem(Story $story, Hero $hero, SpecialItem $specialItem, ItemPicker $itemPicker, SessionInterface $session) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $chapter = $session->get('chapter');

        //add item to hero if possible
        $itemPicked = $itemPicker->pickUpSpecialItem($hero, $specialItem);
        if ($itemPicked) {
            foreach ($chapter->getSpecialItems() as $item) {
                if ($item->getId() === $specialItem->getId()) {
                    $chapter->removeSpecialItem($item);
                }
            }
            $this->addFlash("info", "You picked up " . $specialItem->getName());
            if($specialItem->getBonusGiven() !== 0) {
                $this->addFlash("success", "Bonus: +". $specialItem->getBonusGiven(). " ".$specialItem->getAttributeTargeted());
            }
        } else {
            $this->addFlash("warning", "Cannot Equip: slot ". $specialItem->getSlot(). " is taken");
        }

        $session->set('chapter', $chapter);
        $em->persist($hero);
        $em->flush();

        return $this->redirectToRoute("adventure", [
            "slug" => $story->getSlug(),
            "idHero" => $hero->getId(),
            "id" => $chapter->getId()

        ]);
    }

    /**
     * @param Chapter $chapter
     * @param Hero $hero
     * @param SpecialItem $specialItem
     * @return RedirectResponse
     * @Route("/{slug}/hero-{idHero}/{idChapter}/remove-si/{idItem}", name="specialItem_remove")
     * @ParamConverter("chapter", options={"mapping": {"idChapter": "id"}})
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     * @ParamConverter("specialItem", options={"mapping": {"idItem": "id"}})
     */
    public function removeSpecialItem(Chapter $chapter, Hero $hero, $idItem) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $specialItem = $em->getRepository(SpecialItem::class)->find($idItem);
        $hero->removeSpecialItem($specialItem);
        Alteration::removeSpecialItem($hero, $specialItem);
        $em->persist($hero);
        $em->flush();
        $this->addFlash("info", $specialItem->getName(). " removed!");
        $this->addFlash("warning", "-".$specialItem->getBonusGiven(). " ".$specialItem->getAttributeTargeted());

        return $this->redirectToRoute("adventure", [
            "slug" => $chapter->getStory()->getSlug(),
            "idHero" => $hero->getId(),
            "id" => $chapter->getId(),
        ]);

    }

    /**
     * @param Story $story
     * @param Hero $hero
     * @param ItemPicker $itemPicker
     * @param SessionInterface $session
     * @return RedirectResponse
     * @Route("/{slug}/{idHero}/{idChapter}/pickGold", name="pickupGold")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     */
    public function pickUpGold(Story $story, Hero $hero, ItemPicker $itemPicker, SessionInterface $session) : RedirectResponse
    {
        $chapter = $session->get("chapter");

        $message = $itemPicker->pickupGold($hero, $chapter->getGold());

        $chapter->setGold(null);
        $session->set("chapter", $chapter);

        $this->addFlash("info", $message);
        return $this->redirectToRoute("adventure", [
            "slug" => $story->getSlug(),
            "idHero" => $hero->getId(),
            "id" => $chapter->getId()
        ]);

    }

    /**
     * @param Story $story
     * @param Chapter $chapter
     * @param Hero $hero
     * @param ConsumableItem $consumableItem
     * @return RedirectResponse
     * @Route("/{slug}/hero-{idHero}/{idChapter}pickup-ci/{idItem}", name="pickupConsumableItem")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     * @ParamConverter("chapter", options={"mapping": {"idChapter": "id"}})
     *
     */
    public function pickUpConsumableItem(Story $story, Hero $hero, $idChapter, $idItem, ItemPicker $itemPicker, SessionInterface $session) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $consumableItem = $em->getRepository(ConsumableItem::class)->find($idItem);
        if($consumableItem === null) {
            throw new NotFoundHttpException("The consumable item with id " . $idItem . " does not exist!");
        }
        $itemPicked = $itemPicker->pickUpConsumableItem($hero, $consumableItem);

        $chapter = $session->get('chapter');
        if ($itemPicked) {
            foreach ($chapter->getConsumableItems() as $item) {
                if ($item->getId() === $consumableItem->getId()) {
                    $chapter->removeConsumableItem($item);
                }
            }
            $this->addFlash("info", "You picked " . $consumableItem->getName());
        } else {
            $this->addFlash("warning", "Your bagpack is full!");
        }

        $session->set("chapter", $chapter);

        return $this->redirectToRoute("adventure", [
            "slug" => $story->getSlug(),
            "idHero" => $hero->getId(),
            "id" => $chapter->getId()
        ]);
    }

    /**
     * @param Hero $hero
     * @param Chapter $chapter
     * @param BackpackItem $item
     * @return RedirectResponse
     * @Route("/hero/{idHero}/{idChapter}/bpi-remove-{idItem}", name="backpackItem_remove")
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     * @ParamConverter("chapter", options={"mapping": {"idChapter": "id"}})
     * @ParamConverter("backpackItem", options={"mapping": {"idItem": "id"}})
     */
    public function removeBackpackItem(Hero $hero, Chapter $chapter, $idItem) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $backpackItem = $em->getRepository(BackpackItem::class)->find($idItem);
        $backpackItem->removeStock(1);
        
        if ($backpackItem->getStock() === 0) {
            $em->remove($backpackItem);
        } else {
            $em->persist($backpackItem);
        }
        $em->flush();

        $this->addFlash("info", "You dropped ". $backpackItem->getItem()->getName());

        return $this->redirectToRoute("adventure", [
            "slug" => $hero->getStory()->getSlug(),
            "idHero" => $hero->getId(),
            "id" => $chapter->getId()
        ]);
    }

    /**
     * @param $slug
     * @param Hero $hero
     * @param BackpackItem $backpackItem
     * @param $idChapter
     * @param Alteration $useConsumable
     * @return RedirectResponse
     * @Route("/{slug}/{idHero}/useConsumable-{idItem}/{idChapter}", name="use_consumable")
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     * @ParamConverter("backpackItem", options={"mapping": {"idItem": "id"}})
     */
    public function useBackpackItem($slug, Hero $hero, BackpackItem $backpackItem, $idChapter, Alteration $useConsumable) : RedirectResponse
    {
        $message = $useConsumable->useConsumable($hero, $backpackItem);
        !$message
        ? $this->addFlash("warning", "You cannot use this item!")
        : $this->addFlash("success", $message);

        return $this->redirectToRoute("adventure", [
            "slug" => $slug,
            "idHero" => $hero->getId(),
            "id" => $idChapter
        ]);
    }

    /**
     * @param $slug
     * @param $idHero
     * @param $idChapter
     * @param $idWeapon
     * @param ItemPicker $itemPicker
     * @return RedirectResponse
     * @Route("/{slug}/{idHero}/{idChapter}/pick-up/{idWeapon}", name="pickupWeapon")
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     */
    public function pickUpWeapon($slug, Hero $hero, $idChapter, $idWeapon, SessionInterface $session) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository(Story::class)->findOneBy(["slug" => $slug]);

        $weapon = $em->getRepository(Weapon::class)->find($idWeapon);

        $chapter = $session->get('chapter');
        //Check whether weapon has been picked or not
        $messages = ItemPicker::pickUpWeapon($hero, $weapon);

        if(!isset($messages)) {
            $this->addFlash("warning", "You cannot equip this weapon!");
        } else {
            foreach ($chapter->getWeapons() as $weaponChapter) {
                if ($weaponChapter->getId() === $weapon->getId()) {
                    $chapter->removeWeapon($weaponChapter);
                }
            }
            //send every relevant message
            foreach ($messages as $message) {
                $this->addFlash("success",  $message);
            }

        }

        $session->set('chapter', $chapter);
        $em->persist($hero);
        $em->flush();

        return $this->redirectToRoute("adventure", [
            "slug" => $story->getSlug(),
            "idHero" => $hero->getId(),
            "id" => $chapter->getId()
        ]);

    }

    /**
     * @param Chapter $chapter
     * @param Hero $hero
     * @param Weapon $weapon
     * @return RedirectResponse
     * @Route("/{idChapter}/{idHero}/weapon-remove/{idWeapon}", name="weapon_remove")
     */
    public function removeWeapon($idChapter, $idHero, $idWeapon) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $hero = $em->getRepository(Hero::class)->find($idHero);
        $weapon = $em->getRepository(Weapon::class)->find($idWeapon);
        //remove weapon
        $hero->removeWeapon($weapon);
        $this->addFlash("info", "You dropped " . $weapon->getName());
        //check if weapon removed was a weaponskill
        $weaponSkillBonus = Alteration::weaponSkillBonus($hero, $weapon);
        if($weaponSkillBonus) {
            //cancel the bonus given
            $hero->setAbility($hero->getAbility() - 4);
            $this->addFlash("warning", "Your Weaponskill is not active any more (-2 ability)");
        }
        //Check if hero is still carrying at least one weapon
        if ($hero->getWeapons()->isEmpty()) {
            $hero->setAbility($hero->getAbility() - 4);
            $this->addFlash("danger", "No weapon carried - Penalty: -4 ability");
        }
        $em->persist($hero);
        $em->flush();

        return $this->redirectToRoute("adventure", [
            "slug" => $hero->getStory()->getSlug(),
            "idHero" => $hero->getId(),
            "id" => $idChapter
        ]);
    }
}
