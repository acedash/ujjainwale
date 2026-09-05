<?php
require 'vendor/autoload.php';
$feed = new \SimplePie\SimplePie();
$feed->set_feed_url('https://news.google.com/rss/search?q=Indore');
$feed->enable_cache(false);
$feed->init();
$item = $feed->get_item(0);
print_r([
    'title' => $item->get_title(),
    'enclosure' => $item->get_enclosure(),
    'description' => $item->get_description(),
]);
