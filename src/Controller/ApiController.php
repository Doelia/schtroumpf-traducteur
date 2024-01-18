<?php

namespace App\Controller;

use App\Service\SchtroumpfizerApostropheService;
use App\Service\SchtroumpfizerService;
use App\Service\SchtroumpfizerVerbsService;
use App\Service\SpacyApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{

    public function __construct(
        private SchtroumpfizerService $mainService,
        private SpacyApiService $spacyApiService,
        private SchtroumpfizerVerbsService $schtroumpfizerVerbsService,
        private SchtroumpfizerApostropheService $schtroumpfizerApostropheService,
    ) {}

    #[Route('/api/schtroumpfize')]
    public function schtroumpfize(Request $request)
    {
        $sentence = $request->query->get('sentence');
        $debug = !!$request->query->get('debug');
        if ($this->getParameter('kernel.debug')) {
            $debug = true;
        }

        if (!$sentence) {
            return new JsonResponse([
                'error' => 'Missing GET ?sentence parameter',
            ], 400);
        }

        $nChars = strlen($sentence);
        if ($nChars > 1000) {
            return new JsonResponse([
                'error' => "Sentence is too long ($nChars > 1000 chars)",
            ], 400);
        }

        $sentence = trim($sentence);
        $sentence = str_replace('’', "'", $sentence);

        $response = $this->spacyApiService->lemmatize($sentence);
        $tokens = $response['tokens'];

        // Ajoute un attribut 'schtroumpfed' à chaque token qui a besoin d'être schtroumpfé
        $tokens = $this->schtroumpfizerVerbsService->schtroumfizeTokens($tokens);
        $tokens = $this->schtroumpfizerApostropheService->schtroumfizeTokens($tokens);

        // Construit une nouvelle phrase en remplaçant les tokens schtroumpfés
        $final_sentence = $this->mainService->replaceBySchtroumpfedTokens($sentence, $tokens, $debug);

        $json = [
            'final_sentence' => $final_sentence,
        ];

        if ($debug) {
            $json['debug'] = [
                'tokens' => $tokens,
            ];
        }

        return new JsonResponse($json);
    }

}
