<?php

namespace App\Service;

class SchtroumpfizerVerbsService
{
    public function __construct(
    ) {}

    public function schtroumfizeTokens(array $tokens): array
    {
        foreach ($tokens as $key => &$token) {
            $next_token = $tokens[$key + 1] ?? null;
            $nextIsAVerb = $next_token && $next_token['pos'] === 'VERB';
            if ($token['pos'] === 'VERB' && !$nextIsAVerb) {

                $uppercase = $token['token'][0] === mb_strtoupper($token['token'][0]);
                $conjugatedEnding = $this->conjugateVerb($token);

                $token['schtroumpfed'] = ($uppercase ? 'S' : 's') . 'chtroumpf' . $conjugatedEnding;
                $token['schtroumpfed_offset'] = mb_strlen($token['schtroumpfed']) - ($token['end'] - $token['start']);
                $token['schtroumpfed_type'] = 'VERB';
            }

        }

        return $tokens;
    }


    private function conjugateVerb(array $token): string
    {
        $morphes = [];
        foreach (explode('|', $token['morph']) as $morph) {
            $parts = explode('=', $morph);
            $morphes[$parts[0]] = $parts[1];
        }

        if ($morphes['VerbForm'] === 'Inf') {
            return 'er';
        }

        if ($morphes['VerbForm'] === 'Part') {

            $tense = $morphes['Tense'];

            if ($tense === 'Pres') {
                return 'ant';
            }

            if ($tense === 'Past') {
                $number = $morphes['Number'] ?? '?'; // Sing
                $gender = $morphes['Gender'] ?? 'Masc';

                $conjs = [
                    'Sing_Masc' => 'é',
                    'Sing_Fem' => 'ée',
                    'Plur_Masc' => 'és',
                    'Plur_Fem' => 'ées',
                ];

                return $conjs[$number . '_' . $gender] ?? '?';
            }

            return '??';
        }

        if ($morphes['Mood'] === 'Cnd') {
            $tense = $morphes['Tense'];
            $person = $morphes['Person']; // 1, 2, 3
            $number = $morphes['Number']; // Sing, Plur

            $conjs = [
                'Pres' => [
                    'Sing_1' => 'erais',
                    'Sing_2' => 'erais',
                    'Sing_3' => 'erait',
                    'Plur_1' => 'erions',
                    'Plur_2' => 'eriez',
                    'Plur_3' => 'eraient',
                ],
            ];

            return $conjs[$tense][$number . '_' . $person] ?? '?';

        }

        if ($morphes['Mood'] === 'Sub') {
            $tense = $morphes['Tense'] ?? '?';
            $person = $morphes['Person'] ?? '?'; // 1, 2, 3
            $number = $morphes['Number'] ?? '?'; // Sing, Plur

            $conjs = [
                'Pres' => [
                    'Sing_1' => 'e',
                    'Sing_2' => 'es',
                    'Sing_3' => 'e',
                    'Plur_1' => 'ions',
                    'Plur_2' => 'iez',
                    'Plur_3' => 'ent',
                ],
            ];

            return $conjs[$tense][$number . '_' . $person] ?? '?';
        }

        if ($morphes['Mood'] === 'Imp') {

            $tense = $morphes['Tense'] ?? '?';

            if ($tense === 'Past') {
                return 'é';
            }

            if ($tense === 'Pres') {
                $number = $morphes['Number'] ?? '?'; // Sing, Plur
                $person = $morphes['Person'] ?? '?'; // 1, 2, 3

                $conjs = [
                    'Sing_2' => 'e',
                    'Plur_1' => 'ons',
                    'Plur_2' => 'ez',
                ];

                return $conjs[$number . '_' . $person] ?? '?';
            }

            return '??';
        }

        if ($morphes['Mood'] === 'Ind') {
            $tense = $morphes['Tense'];
            $person = $morphes['Person']; // 1, 2, 3
            $number = $morphes['Number']; // Sing, Plur

            $conjs = [
                'Pres' => [
                    'Sing_1' => 'e',
                    'Sing_2' => 'es',
                    'Sing_3' => 'e',
                    'Plur_1' => 'ons',
                    'Plur_2' => 'ez',
                    'Plur_3' => 'ent',
                ],
                'Fut' => [
                    'Sing_1' => 'erai',
                    'Sing_2' => 'eras',
                    'Sing_3' => 'era',
                    'Plur_1' => 'erons',
                    'Plur_2' => 'erez',
                    'Plur_3' => 'eront',
                ],
                'Imp' => [
                    'Sing_1' => 'ais',
                    'Sing_2' => 'ais',
                    'Sing_3' => 'ait',
                    'Plur_1' => 'ions',
                    'Plur_2' => 'iez',
                    'Plur_3' => 'aient',
                ],
                'Past' => [
                    'Sing_1' => 'ai',
                    'Sing_2' => 'as',
                    'Sing_3' => 'a',
                    'Plur_1' => 'âmes',
                    'Plur_2' => 'âtes',
                    'Plur_3' => 'èrent',
                ],
                'Cond' => [
                    'Sing_1' => 'erais',
                    'Sing_2' => 'erais',
                    'Sing_3' => 'erait',
                    'Plur_1' => 'erions',
                    'Plur_2' => 'eriez',
                    'Plur_3' => 'eraient',
                ],
                'Sub' => [
                    'Sing_1' => 'e',
                    'Sing_2' => 'es',
                    'Sing_3' => 'e',
                    'Plur_1' => 'ions',
                    'Plur_2' => 'iez',
                    'Plur_3' => 'ent',
                ],
            ];

            return $conjs[$tense][$number . '_' . $person] ?? '?';
        }

        return '??';

    }
}
