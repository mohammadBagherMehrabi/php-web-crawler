<?php

class UrlHelper
{
    public static function getAbsoluteUrl($baseUrl, $relativeUrl): string
    {
        if (str_starts_with($relativeUrl, 'http://') || str_starts_with($relativeUrl, 'https://')) {
            return $relativeUrl;
        }

        $baseParts = parse_url($baseUrl);
        $scheme = isset($baseParts['scheme']) ? $baseParts['scheme'] . '://' : '';
        $host = $baseParts['host'] ?? '';
        $path = $baseParts['path'] ?? '';
        $path = rtrim(dirname($path), '/') . '/';

        if (str_starts_with($relativeUrl, '/')) {
            return $scheme . $host . $relativeUrl;
        }

        return $scheme . $host . $path . $relativeUrl;
    }
    public static function extractAllLinks($htmlContent, $baseUrl): array
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlContent);
        $linkNodes = $dom->getElementsByTagName('a');
        $allLinks = ['external' => [], 'internal' => []];

        foreach ($linkNodes as $linkNode) {
            $linkHref = $linkNode->getAttribute('href');
            if (strlen(trim($linkHref)) < 1 || $linkHref[0] == '#') {
                continue;
            }

            $absoluteUrl = UrlHelper::getAbsoluteUrl($baseUrl, $linkHref);

            if (parse_url($absoluteUrl, PHP_URL_HOST) !== parse_url($baseUrl, PHP_URL_HOST)) {
                $allLinks['external'][] = $absoluteUrl;
            } else {
                $allLinks['internal'][] = $absoluteUrl;
            }
        }

        return $allLinks;
    }
}
