<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

// Thanks to https://grafikart.fr/tutoriels/vitejs-symfony-1895

class ViteAssetExtension extends AbstractExtension
{


    public function __construct(
        private string $isDev,
        private string $manifest,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset', [$this, 'asset'], ['is_safe' => ['html']])
        ];
    }

    public function asset(string $entry, array $deps)
    {
        if ($this->isDev) {
            return $this->assetDev($entry, $deps);
        }
        return $this->assetProd($entry);
    }

    public function assetDev(string $entry, array $deps): string
    {
        $html = <<<HTML
            <script type="module" src="http://localhost:5173/build/@vite/client"></script>
        HTML;

        $html .= <<<HTML
            <link rel="stylesheet" href="http://localhost:5173/build/assets_vite/style.scss"/>
        HTML;

        $html .= <<<HTML
            <script type="module" src="http://localhost:5173/build/{$entry}" defer></script>
        HTML;

        return $html;
    }

    public function assetProd(string $entry): string
    {
        $manifestData = json_decode(file_get_contents($this->manifest), true);

        $file = $manifestData[$entry]['file'];
        $css = $manifestData[$entry]['css'] ?? [];
        $imports = $manifestData[$entry]['imports'] ?? [];

        $html = <<<HTML
            <script type="module" src="/build/{$file}" defer></script>
        HTML;

        foreach($css as $cssFile) {
            $html .= <<<HTML
                <link rel="stylesheet" media="screen" href="/build/{$cssFile}"/>
            HTML;
        }

        foreach($imports as $import) {
            $html .= <<<HTML
                <link rel="modulepreload" href="/build/{$import}"/>
            HTML;
        }

        return $html;
    }

}
