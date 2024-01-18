<?php

namespace App\Command;

use App\Service\SchtroumpfizerApostropheService;
use App\Service\SchtroumpfizerService;
use App\Service\SchtroumpfizerVerbsService;
use App\Service\SpacyApiService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SchtroumpfizeCommand extends Command
{


    public function __construct(
        private SchtroumpfizerService $mainService,
        private SpacyApiService $spacyApiService,
        private SchtroumpfizerVerbsService $schtroumpfizerVerbsService,
        private SchtroumpfizerApostropheService $schtroumpfizerApostropheService,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('schtroumpfize-mokes')
            ->setDescription('Schtroumpferizer the resources/examples.txt file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $content_file = file_get_contents(__DIR__ . '/../../resources/examples.txt');
        $phrases = explode("\n", $content_file);

        foreach ($phrases as $sentence) {
             try {

                 $response = $this->spacyApiService->lemmatize($sentence);
                 $tokens = $response['tokens'];

                 // Ajoute un attribut 'schtroumpfed' à chaque token qui a besoin d'être schtroumpfé
                 $tokens = $this->schtroumpfizerVerbsService->schtroumfizeTokens($tokens);
                 $tokens = $this->schtroumpfizerApostropheService->schtroumfizeTokens($tokens);

                 // Construit une nouvelle phrase en remplaçant les tokens schtroumpfés
                 $final_sentence = $this->mainService->replaceBySchtroumpfedTokens($sentence, $tokens);

                 $output->writeln("$sentence => $final_sentence");
             } catch (Exception $e) {
                 $output->writeln("/!\ ERROR : $sentence : " . $e->getMessage());
             }
        }

        return 0;
    }
}
