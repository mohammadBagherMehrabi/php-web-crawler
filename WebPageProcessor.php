<?php

class WebPageProcessor
{
    public static function extractVisibleText($htmlContent): string
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlContent);
        $xpath = new DOMXPath($dom);

        foreach (['script', 'style', 'noscript'] as $tag) {
            $nodes = $xpath->query("//{$tag}");
            foreach ($nodes as $node) {
                $node->parentNode->removeChild($node);
            }
        }

        $nodes = $xpath->query("//body//text()");
        $visibleText = '';
        foreach ($nodes as $node) {
            $visibleText .= ' ' . $node->nodeValue;
        }

        return trim($visibleText);
    }

    public static function countWordOccurrences($content, $word): int
    {
        $contentLower = mb_strtolower($content, 'UTF-8');
        $wordLower = mb_strtolower($word, 'UTF-8');
        return mb_substr_count($contentLower, $wordLower);
    }

    public static function countTotalWords($content): int
    {
        $cleanContent = preg_replace('/[^\p{L}\p{N}\s]/u', '', $content);
        return count(mb_split('\s+', $cleanContent));
    }

    public static function countImages($htmlContent): int
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlContent);
        $imageNodes = $dom->getElementsByTagName('img');
        return $imageNodes->length;
    }
}
