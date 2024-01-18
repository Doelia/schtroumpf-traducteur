<?php

namespace App\Service;

class SchtroumpfizerApostropheService
{
    public function __construct(
    ) {}

    public function schtroumfizeTokens(array $tokens): array
    {
        foreach ($tokens as $key => &$token) {
            $nextToken = $tokens[$key + 1] ?? null;

            if ($nextToken && ($nextToken['schtroumpfed_type'] ?? '') === 'VERB') {
                if (($token['token']) === "j'") {
                    $token['schtroumpfed'] = "je ";
                    $token['schtroumpfed_offset'] = 1;
                    $token['schtroumpfed_type'] = 'APPO';
                }
                if (($token['token']) === "J'") {
                    $token['schtroumpfed'] = "Je ";
                    $token['schtroumpfed_offset'] = 1;
                    $token['schtroumpfed_type'] = 'APPO';
                }
                if (($token['token']) === "s'") {
                    $token['schtroumpfed'] = "se ";
                    $token['schtroumpfed_offset'] = 1;
                    $token['schtroumpfed_type'] = 'APPO';
                }
                if (($token['token']) === "d'") {
                    $token['schtroumpfed'] = "de ";
                    $token['schtroumpfed_offset'] = 1;
                    $token['schtroumpfed_type'] = 'APPO';
                }
            }
        }

        return $tokens;
    }


}
