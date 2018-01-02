<?php

namespace App\Controller\Admin;

use App\Repository\StoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GamebookController extends Controller
{
    /**
     * @Route("/stories", name="stories")
     */
    public function stories(StoryRepository $stories) : Response
    {
        $allStories = $stories->findAll();

        return $this->render("admin/stories.html.twig", [
            'stories' => $allStories
        ]);
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function adminIndex() : Response
    {
        return $this->render("admin.html.twig");
    }
}
