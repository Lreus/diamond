<?php

namespace App;

class Diamond
{
    private string $legend;
    private string $filler;

    public function __construct(string $legend = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', $filler = '.')
    {
        $this->legend = $legend;
        $this->filler = $filler;
    }

    public function draw(string $letter): string
    {
        $template = $this->buildDiamondTemplate($letter);

        $diamond = $this->buildDiamondLines($letter, $template);

        return implode("\n", $diamond)."\n";

    }

    private function buildDiamondTemplate(string $letter): string
    {
        $rightPart = $this->truncateLegend($letter);

        return $this->mirror($rightPart);
    }

    private function truncateLegend(string $limit): string
    {
        $pattern = sprintf("/[%s]/", $limit);
        $split = preg_split($pattern, $this->legend);
        return $split[0] . $limit;
    }

    private function mirror(string $rightPart): string
    {
        $leftPart = strrev($rightPart);
        $leftPart = substr($leftPart, 0, strlen($leftPart) - 1);

        return $leftPart . $rightPart;
    }

    /**
     * @return string[]
     */
    private function buildDiamondLines(string $letter, string $template): array
    {
        $top = $this->buildTopLines($letter, $template);
        $middle = $this->filterWithChar($letter, $template);
        $bottom = array_reverse($top);

        return array_merge($top, [$middle], $bottom);
    }

    /**
     * @return string[]
     */
    private function buildTopLines(string $letter, string $template): array
    {
        $top = [];
        $i = 0;
        while ($this->legend[$i] !== $letter) {
            $top[] = $this->filterWithChar($this->legend[$i], $template);
            $i++;
        }

        return $top;
    }

    private function filterWithChar(string $keptChar, string $template): string
    {
        $pattern = sprintf("/[^%s]/", $keptChar);

        return preg_replace($pattern, $this->filler, $template);
    }
}
