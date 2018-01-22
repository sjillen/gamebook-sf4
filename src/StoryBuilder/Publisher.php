<?php

namespace App\StoryBuilder;

use App\Entity\Story;
use App\Repository\ChapterRepository;

class Publisher
{
    const NO_INTRO = "The story requires a Intro Chapter!";
    const NO_STARTER = "The story requires a Starter Chapter!";
    const NO_END = "The story requires a End Chapter!";
    private $chapterRepo;

    public function __construct(ChapterRepository $chapterRepo)
    {
        $this->chapterRepo = $chapterRepo;
    }

    public function publishStory(Story $story) : array
    {
        $missingElts = [];
        $intro = $this->storyHasIntro($story);
        $starter = $this->storyHasStarter($story);
        $end = $this->storyHasEnd($story);
        isset($intro)? $missingElts [] = $intro: false;
        isset($starter)? $missingElts[] = $starter: false;
        isset($end)? $missingElts[] = $end: false;
        return $missingElts;
    }


    public function storyHasIntro(Story $story)
    {
      $intro = $this->chapterRepo->findOneBy(["story" => $story, "type" => "intro"]);
      if(!isset($intro)) {
          return self::NO_INTRO;
      }
    }

    public function storyHasStarter(Story $story)
    {
        $starter = $this->chapterRepo->findOneBy(["story" => $story, "type" => "starter"]);
        if(!isset($starter)) {
            return self::NO_STARTER;
        }
    }

    public function storyHasEnd(Story $story)
    {
        $end = $this->chapterRepo->findOneBy(["story" => $story, "type" => "end"]);
        if(!isset($end)) {
            return self::NO_END;
        }
    }
}