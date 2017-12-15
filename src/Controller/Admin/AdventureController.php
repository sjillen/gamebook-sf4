<?php

namespace App\Controller\Admin;

use App\Entity\Weapon;
use App\HeroBuilder\StarterInventory;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Hero;
use App\Form\HeroType;
use App\Entity\Story;
use App\Entity\Chapter;


class AdventureController extends AbstractController
{
    /**
     * @param Request
     *
     * @return Response
     * @Route("/story/{slug}/create-hero", name="newHero")
     * @ParamConverter("story", options={"mapping": {"slug": "slug"}})
     */
    public function newHero(Request $request, Story $story) : Response
    {
        $hero = new Hero();
        $form = $this->createForm(HeroType::class, $hero, ["story" => $story]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $energy = mt_rand(10,19);
            $life = mt_rand(20, 29);
            $gold = mt_rand(0, 9);
            $hero->setGold($gold);
            $hero->setLife($life);
            $hero->setEnergy($energy);

            $hero->addWeapon($em->getRepository(Weapon::class)->findOneBy(["name" => "Axe"]));
            $hero = StarterInventory::setStarterItem($story, $hero, $em);
            dump($hero);
            die();
            $em->persist($hero);
            $em->flush();

            $this->addFlash("success", "The creation of your hero is finished");
            return $this->redirectToRoute("story", [
                "slug" => $story->getSlug()
            ]);
        }
        return $this->render("form/hero-form.html.twig", [
            "form" => $form->createView()
        ]);
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
}
