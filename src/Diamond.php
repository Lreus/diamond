<?php

namespace App;

class Diamond
{
    const REGEX_ANY_CHAR_MISSING_FROM_LIST = "/[^%s]/";
    const REGEX_ANY_SINGLE_CHAR_IN_LIST = "/[%s]/";
    private string $legend;
    private string $filler;

    public function __construct(string $legend = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', $filler = '.')
    {
        $this->legend = $legend;
        $this->filler = $filler;
    }

    public function draw(string $letter): string
    {
        $template = $this->getTemplate($letter);

        $diamond = $this->buildDiamondLines($letter, $template);

        return implode(PHP_EOL, $diamond).PHP_EOL;

    }

    public function getTemplate(string $char): string
    {
        $rightPart = $this->truncateLegend($char);

        return $this->mirror($rightPart);
    }

    private function truncateLegend(string $limit): string
    {
        $pattern = sprintf(self::REGEX_ANY_SINGLE_CHAR_IN_LIST, $limit);
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
        $middle = $this->purge($template, $letter);
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
            $top[] = $this->purge($template, $this->legend[$i]);
            $i++;
        }

        return $top;
    }

    public function purge(string $source, string $keep): string
    {
        $pattern = sprintf(self::REGEX_ANY_CHAR_MISSING_FROM_LIST, $keep);

        return preg_replace($pattern, $this->filler, $source);
    }
}
