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

    public function index(Request $request)
    {
        $filepath = '/storage/sample_1280x853.jpg';
        $file = file_get_contents(public_path($filepath));
        $labels = [];
        $props = [];
        $texts = [];
        $objects = [];
        if ($request->has('file')) {
            $file = $request->file('file');
            $filepath = '/storage/' . $file->getClientOriginalName();
            if (!file_exists(public_path($filepath))) {
                file_put_contents(public_path($filepath), file_get_contents($file));
            }
            $file = file_get_contents($file);
        }
        $labels = $this->labelDetection($file);
        $props = $this->imageProperties($file);
        $texts = $this->textDetection($file);
        // TODO: Show objects in blade
        $objects = $this->objectLocalization($file);

        // dump($objects);

        return view('home', compact('filepath', 'labels', 'props', 'texts'));
        // dd('stop');
    }

    public function labelDetection($image)
    {
        $response = $this->annotator->labelDetection($image);
        $labels = $response->getLabelAnnotations();

        $arr = [];
        if ($labels) {
            foreach ($labels as $label) {
                $key = $label->getScore();
                $arr["$key"] = $label->getDescription();
            }
        }

        $this->annotator->close();
        return $arr;
    }

    public function imageProperties($image)
    {
        $response = $this->annotator->imagePropertiesDetection($image);
        $props = $response->getImagePropertiesAnnotation();

        $arr = [];
        if ($props) {
            foreach ($props->getDominantColors()->getColors() as $colorInfo) {
                $key = round($colorInfo->getPixelFraction(), 3);
                $color = $colorInfo->getColor();
                $r = intval($color->getRed());
                $g = intval($color->getGreen());
                $b = intval($color->getBlue());
                $arr["$key"] = "R$r - G$g - B$b";

                krsort($arr);
            }
        }
        $this->annotator->close();
        return $arr;
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
                    if ($block->getConfidence() > 0.7) {
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
        }
        return $texts;
    }

    public function objectLocalization($file)
    {
        $path = 'https://cloud.google.com/vision/docs/images/bicycle_example.png';
        $response = $this->annotator->objectLocalization($file);
        $objects = $response->getLocalizedObjectAnnotations();

        return $objects;
        $this->annotator->close();
    }
}
