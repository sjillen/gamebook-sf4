<?php

namespace App\Controller\Admin;

use App\Adventure\ChoiceDisplay;
use App\Adventure\ChoiceInteraction;
use App\Adventure\ItemPicker;
use App\Entity\BackpackItem;
use App\Entity\Choice;
use App\Entity\ConsumableItem;
use App\Entity\Ruleset;
use App\Entity\Skill;
use App\Entity\SpecialItem;
use App\Entity\Weapon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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



class AdventureController extends AbstractController
{
    /**
     * @param Request
     *
     * @return Response
     * @Route("/story/{slug}/create-hero", name="newHero")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function newHero(Request $request, Story $story, HeroBuilder $heroBuilder, StarterInventory $starterInventory, HeroSkills $heroSkills) : Response
    {
        $hero = new Hero();
        $hero->setStory($story);
        $em = $this->getDoctrine()->getManager();
        $skills = $em->getRepository(Skill::class)->findSkillsByStory($story);
        $form = $this->createForm(HeroType::class, $hero, ["skills" => $skills]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //Apply the set of rules from the corresponding story to complete the creation of the hero
            $ruleset = $em->getRepository(Ruleset::class)->findOneBy(["story" => $story]);
            $hero = $heroBuilder->buildHero($hero, $ruleset);
            $starterInventory->setStarterInventory($story, $hero);

            //Check if the number of skills chosen complies with the rules
            if (HeroSkills::maxSkillAllowed($ruleset, $hero)) {
                $this->addFlash("warning", "You need to choose 6 skills !");
                return $this->redirectToRoute("newHero", ["slug" => $story->getSlug()]);
            }

            //Choose a weapon for Weaponskill
            $heroSkills->weaponSkillSelection($story, $hero);
            $em->persist($hero);
            $em->flush();

            $this->addFlash("success", "The creation of your hero is finished");
            return $this->redirectToRoute("heroResume", [
                "slug" => $story->getSlug(),
                "id" => $hero->getId()
            ]);
        }
        return $this->render("form/hero-form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request
     * @return Response
     *
     * @Route("/story/{slug}/hero-resume/{id}", name="heroResume")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("hero", options={"mapping": {"id": "id"}})
     */
    public function heroResume(Request $request, Story $story, Hero $hero) : Response
    {
        $starter = $this->getDoctrine()->getManager()->getRepository(Chapter::class)->findOneBy(["type" => "starter"]);
        return $this->render("story/heroResume.html.twig", ["slug" => $story->getSlug(), "hero" => $hero, "starter" => $starter]);
    }

    /**
     * @return RedirectResponse
     * @Route("/story/{slug}/hero-remove/{id}", name="heroRemove")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("hero", options={"mapping": {"id": "id"}})
     */
    public function removeHero(Story $story,Hero $hero)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($hero);
        $em->flush();

        return $this->redirectToRoute("story", ["slug" => $story->getSlug()]);
    }

    /**
     * @param $idStory, $idChapter
     *
     * @return Response
     * @Route("/story/{slug}/{idHero}/chapter/{id}", name="adventure")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     * @ParamConverter("chapter", options={"mapping": {"id": "id"}})
     */
    public function adventureAction(Story $story, Chapter $chapter, Hero $hero) : Response
    {
        $unlockChoices = ChoiceDisplay::unlockChoices($hero, $chapter->getChoices());
        return $this->render("adventure/adventure.html.twig", [
            "story" => $story,
            "hero" => $hero,
            "chapter" => $chapter,
            "specialItems" => $chapter->getSpecialItems(),
            "consumableItems" => $chapter->getConsumableItems(),
            "choices" => $unlockChoices,

        ]);
    }

    /**
     * @param Story $story
     * @param Choice $choice
     * @param Hero $hero
     * @param ChoiceInteraction $interaction
     * @return RedirectResponse
     * @Route("/story/{slug}/hero-{idHero}/trade{idChoice}", name="tradeGoldOrItem")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     * @ParamConverter("choice", options={"mapping": {"idChoice": "id"}})
     *
     */
    public function tradeGoldOrItem(Story $story, Choice $choice, Hero $hero, ChoiceInteraction $interaction) : RedirectResponse
    {
        //Check whether requirement is Gold or Item, and remove the corresponding amount or object from inventory
        $interaction->trade($hero, $choice);
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
     * @Route("/story/{slug}/hero-{idHero}/{idChapter}/pickup-si/{idItem}", name="pickupSpecialItem")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     * @ParamConverter("chapter", options={"mapping": {"idChapter": "id"}})
     *
     */
    public function pickUpSpecialItem(Story $story, Hero $hero, Chapter $chapter, $idItem, ItemPicker $itemPicker) : Response
    {
        $em = $this->getDoctrine()->getManager();
        //find the item to be picked
        $specialItem = $em->getRepository(SpecialItem::class)->find($idItem);
        if($specialItem === null) {
            throw new NotFoundHttpException("The special item with id " . $idItem . " does not exist!");
        }
        //add item to hero
        $itemPicker->pickUpSpecialItem($hero, $specialItem);
        //remove item from chapter
        $specialItems = $chapter->getSpecialItems();
        $specialItems->removeElement($specialItem);
        //get the corresponding choices of the chapter
        $unlockChoices = ChoiceDisplay::unlockChoices($hero, $chapter->getChoices());
        return $this->render("adventure.html.twig", [
            "story" => $story,
            "hero" => $hero,
            "chapter" => $chapter,
            "specialItems" => $specialItems,
            "consumableItems" => $chapter->getConsumableItems(),
            "choices" => $unlockChoices

        ]);
    }

    /**
     * @param Story $story
     * @param Chapter $chapter
     * @param Hero $hero
     * @param ConsumableItem $consumableItem
     * @return RedirectResponse
     * @Route("/story/{slug}/hero-{idHero}/{idChapter}pickup-ci/{idItem}", name="pickupConsumableItem")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     * @ParamConverter("chapter", options={"mapping": {"idChapter": "id"}})
     *
     */
    public function pickUpConsumableItem(Story $story, Hero $hero, Chapter $chapter, $idItem, ItemPicker $itemPicker) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $consumableItem = $em->getRepository(ConsumableItem::class)->find($idItem);
        $itemPicked = $itemPicker->pickUpConsumableItem($hero, $consumableItem);

        $itemPicked ? $this->addFlash("success", "you picked " . $consumableItem->getName()) : $this->addFlash("warning", "Your bagpack is full!");
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
     * @Route("hero/{idHero}/{idChapter}/bpi-remove{idItem}", name="removeFromBackpack")
     * @ParamConverter("hero", options={"mapping": {"idHero": "id"}})
     * @ParamConverter("chapter", options={"mapping": {"idChapter": "id"}})
     * @ParamConverter("backpackItem", options={"mapping": {"idItem": "id"}})
     */
    public function removeBackpackItem(Hero $hero, Chapter $chapter, BackpackItem $backpackItem) : RedirectResponse
    {
        $backpackItem->removeStock(1);
        $em = $this->getDoctrine()->getManager();
        if ($backpackItem->getStock() === 0) {
            $em->remove($backpackItem);
        } else {
            $em->persist($backpackItem);
        }
        $em->flush();

        return $this->redirectToRoute("adventure", [
            "slug" => $hero->getStory()->getSlug(),
            "idHero" => $hero->getId(),
            "id" => $chapter->getId()
        ]);
    }

    /**
     * @param Story $story
     * @param Hero $hero
     * @param Chapter $chapter
     * @param $idWeapon
     * @param ItemPicker $itemPicker
     * @return RedirectResponse
     * @Route("/story/{slug}/{idHero}/{idChapter}/{idWeapon}", name="pickupWeapon")
     * @ParamConverter("Story", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("Chapter", options={"mapping": {"idChapter": "id"}})
     */
    public function pickUpWeapon(Story $story, $idHero, Chapter $chapter, $idWeapon, ItemPicker $itemPicker) : RedirectResponse
    {
        $hero = $this->getDoctrine()->getManager()->getRepository(Hero::class)->find($idHero);
        $message = $itemPicker->pickUpWeapon($hero, $idWeapon);
        if(isset($message)) {
            $this->addFlash("warning", $message);
        } else {
            $this->addFlash("success", "the weapon has been successfully equipped!");
        }

        return $this->redirectToRoute("adventure", [
            "slug" => $story->getSlug(),
            "idHero" => $hero->getId(),
            "id" => $chapter->getId()
        ]);

    }
}
