<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class VisionController extends Controller
{

    public $annotator = null;
    public function __construct()
    {
        $this->annotator = new ImageAnnotatorClient();
    }

    public function index()
    {
        $path = storage_path('app/public/wakeupcat.jpg');
        $image = file_get_contents($path);
        $labels = $this->labelDetection($image);
        $props = $this->imageProperties($image);
        $text = $this->textDetection($image);


        dd('stop');
    }

    public function labelDetection($image)
    {
        $response = $this->annotator->labelDetection($image);
        $labels = $response->getLabelAnnotations();

        $arr = [];
        if ($labels) {
            foreach ($labels as $label) {
                $arr[] = $label->getDescription();
            }
        }

        dump($arr);
        $this->annotator->close();
        return $arr;
    }

    public function imageProperties($image)
    {
        $response = $this->annotator->imagePropertiesDetection($image);
        $props = $response->getImagePropertiesAnnotation();

        $arr = [];
        foreach ($props->getDominantColors()->getColors() as $colorInfo) {
            $key = $colorInfo->getPixelFraction();
            $color = $colorInfo->getColor();
            $r = intval($color->getRed());
            $g = intval($color->getGreen());
            $b = intval($color->getBlue());
            $arr["$key"] = "R$r - G$g - B$b";

            krsort($arr);
        }
        dump($arr);
        $this->annotator->close();
    }

    public function textDetection($image)
    {
        $response = $this->annotator->documentTextDetection($image);
        $annotation = $response->getFullTextAnnotation();

        $texts = [];
        if ($annotation) {
            foreach ($annotation->getPages() as $page) {
                foreach ($page->getBlocks() as $blockKey => $block) {
                    $text = '';
                    foreach ($block->getParagraphs() as $paragraph) {
                        foreach ($paragraph->getWords() as $word) {
                            foreach ($word->getSymbols() as $symbol) {
                                $text .= $symbol->getText();
                            }
                            $text .= ' ';
                        }
                    }
                    $texts[$blockKey]['text'] = $text;
                    $texts[$blockKey]['confidence'] = $block->getConfidence();

                    $vertices = $block->getBoundingBox()->getVertices();
                    foreach ($vertices as $vertexKey => $vertex) {
                        $texts[$blockKey][$vertexKey]['x'] = $vertex->getX();
                        $texts[$blockKey][$vertexKey]['y'] = $vertex->getY();
                    }
                }
            }
        }
        dump($texts);

        return;
    }
}
