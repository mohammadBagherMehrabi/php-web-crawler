<?php

require_once 'UrlHelper.php';
require_once 'FileHandler.php';
require_once 'WebPageProcessor.php';
require_once 'Crawler.php';

$configPath = 'config.json';
if (!file_exists($configPath)) {
    die("فایل پیکربندی یافت نشد: $configPath\n");
}
$configContent = file_get_contents($configPath);
$config = json_decode($configContent, true);
if (!$config || !isset($config['url']) || empty($config['url']) || !isset($config['word']) || empty($config['word'])) {
    die("پیکربندی نادرست است. لطفا فایل config.json را بررسی کنید");
}
define("URL", $config['url']);
define("WORD", $config['word']);
$crawler = new Crawler();
$crawler->start(URL, WORD);
