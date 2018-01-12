<?php

namespace App\Controller;

use App\Repository\StoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GamebookController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(StoryRepository $stories) : Response
    {
        $allStories = $stories->findAll();

        return $this->render("default/homepage.html.twig", [
            'stories' => $allStories
        ]);
    }

    /**
     * @return Response
     * @Route("/about", name="about")
     */
    public function about() : Response
    {
        return $this->render("default/about.html.twig");
    }
}
