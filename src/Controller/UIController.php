<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class UIController extends AbstractController
{

    #[Route("/")]
    public function home()
    {
        $sentences_examples_str = file_get_contents(__DIR__ . '/../../resources/examples.txt');
        $sentences_examples = explode("\n", $sentences_examples_str);
        $sentences_examples = array_filter($sentences_examples, fn($line) => $line !== '');

        return $this->render('home.html.twig', [
            'sentences_examples' => $sentences_examples,
        ]);

    }

    #[Route("/api.html")]
    public function api()
    {
        return $this->render('api.html.twig');
    }


}
