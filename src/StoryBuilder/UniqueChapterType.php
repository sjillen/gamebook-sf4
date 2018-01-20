<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 16/12/2017
 * Time: 21:41
 */

namespace App\StoryBuilder;
/* This service check that there is only one starter chapter per story*/
use App\Entity\Chapter;
use App\Entity\Story;
use App\Repository\ChapterRepository;

class UniqueChapterType
{
    private $chapters;

    public function __construct(ChapterRepository $chapters)
    {
        $this->chapters = $chapters;
    }

    public function checkUniqueStarter(Story $story, Chapter $chapter)
    {
        if($chapter->getType() == "starter") {
            $starter = $this->chapters->findOneBy(["story" => $story, "type" => "starter"]);
            if ($starter->getId() !== $chapter->getId()) {
                return $starter;
            }
        }
    }

    public function checkUniqueIntro(Story $story, Chapter $chapter)
    {
        if($chapter->getType() == "intro") {
            $intro = $this->chapters->findOneBy(["story" => $story, "type" => "intro"]);
            if ($intro->getId() !== $chapter->getId()) {
                return $intro;
            }
        }
    }
}