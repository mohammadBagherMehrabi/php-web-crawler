<?php

class FileHandler
{
    public static function saveToFile($directory, $pageNumber, $url, $word, $occurrences, $totalWords, $imageCount, $pageLinks): void
    {
        $filePath = $directory . '/' . $pageNumber . '.txt';
        $content = "URL: {$url}\n";
        $content .= "کلمه '{$word}' تعداد {$occurrences} بار در این صفحه تکرار شده است.\n";
        $content .= "تعداد کل کلمات در صفحه: {$totalWords}\n";
        $content .= "تعداد تصاویر: {$imageCount}\n";
        $externalLinks = $pageLinks['external'];
        $internalLinks = $pageLinks['internal'];
        $content .= "تعداد لینک‌های خارجی: " . count($externalLinks) . "\n";
        $content .= "تعداد لینک‌های داخلی: " . count($internalLinks) . "\n";

        foreach ($externalLinks as $externalLink) {
            $content .= "لینک خارجی: {$externalLink}\n";
        }
        foreach ($internalLinks as $internalLink) {
            $content .= "لینک داخلی: {$internalLink}\n";
        }

        file_put_contents($filePath, $content);
    }
}
