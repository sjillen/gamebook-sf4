<?php

namespace App\Controller\Admin;

use App\Entity\Ruleset;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
        $form = $this->createForm(HeroType::class, $hero, ["story" => $story]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $ruleSet = $em->getRepository(Ruleset::class)->findOneBy(["story" => $story]);
            $hero = $heroBuilder->buildHero($hero, $ruleSet);
            $starterInventory->setStarterInventory($story, $hero);
            $heroSkills->weaponSkillSelection($story, $hero);
            if (HeroSkills::maxSkillAllowed($story, $hero)) {
                $this->addFlash("warning", "You need to choose 6 skills !");
                return $this->redirectToRoute("newHero", ["slug" => $story->getSlug()]);
            }
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
        return $this->render("story/heroResume.html.twig", ["slug" => $story->getSlug(), "hero" => $hero]);
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
