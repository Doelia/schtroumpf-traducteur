<?php

namespace App\Service;

class SchtroumpfizerService
{
    public function __construct(
    ) {}

    public function replaceBySchtroumpfedTokens(string $sentence, array $tokens, bool $alsoUnknown = true): string // TODO mettre à true quand mode dev
    {

        $final_sentence = $sentence;

        for ($j = 0; $j < count($tokens); $j++) {
            $token = $tokens[$j];
            $previous_token = $tokens[$j - 1] ?? null;

            if ($token['schtroumpfed'] ?? false) {

                $unknown = str_contains($token['schtroumpfed'], '?');

                if ($unknown && !$alsoUnknown) {
                    continue;
                }

                $start = $token['start'];
                $end = $token['end'];

                $replace = $token['schtroumpfed'];
                $before = mb_substr($final_sentence, 0, $start);
                $after = mb_substr($final_sentence, $end);
                $final_sentence = $before . $replace . $after;

                for ($i = $j + 1; $i < count($tokens); $i++) {
                    $tokens[$i]['start'] += $token['schtroumpfed_offset'];
                    $tokens[$i]['end'] += $token['schtroumpfed_offset'];
                }
            }
        }

        return $final_sentence;
    }


}
