<?php

namespace App\Controller;

use App\Repository\StoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        return $this->render("default/homepage.html.twig", [
            'stories' => $allStories
        ]);
    }
}
