<?php

class Crawler
{
    private $visitedLinks = [];

    public function start(string $url, string $word): void
    {
        $pageNumber = 1;
        $baseDomain = parse_url($url, PHP_URL_HOST);
        $directory = $baseDomain;

        if (!is_dir($directory)) {
            mkdir($directory);
        }

        $this->crawl($url, $word, $baseDomain, $directory, $pageNumber);
    }

    private function crawl(string $url, string $word, string $baseDomain, string $directory, &$pageNumber): void
    {
        if (in_array($url, $this->visitedLinks)) {
            return;
        }

        $this->visitedLinks[] = $url;
        $html = $this->getWebPageContent($url);
        if (empty($html)) {
            echo "خطا! بارگیری اطلاعات از این منبع امکان پذیر نبود: $url.\n";
            return;
        }

        $visibleText = WebPageProcessor::extractVisibleText($html);
        if (empty($visibleText)) {
            echo "خطا! هیچ متن قابل مشاهده‌ای در این منبع یافت نشد: $url.\n";
            return;
        }

        $pageLinks = UrlHelper::extractAllLinks($html, $url);
        $occurrences = WebPageProcessor::countWordOccurrences($visibleText, $word);

        if ($occurrences > 0) {
            $totalWords = WebPageProcessor::countTotalWords($visibleText);
            $imageCount = WebPageProcessor::countImages($html);
            FileHandler::saveToFile($directory, $pageNumber, $url, $word, $occurrences, $totalWords, $imageCount, $pageLinks);
            $pageNumber++;
        }

        foreach ($pageLinks['internal'] as $internalLink) {
            if (!in_array($internalLink, $this->visitedLinks)) {
                $this->crawl($internalLink, $word, $baseDomain, $directory, $pageNumber);
            }
        }
    }

    private function getWebPageContent(string $url): bool|string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }


}
