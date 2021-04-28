<?php

// bootstrap
$paragraphs = $_GET["pars"];
$query = $_GET["query"];
$queryclean = str_replace(" ", "_", $query);
// more filters

// wikipedia text
$url = "https://it.wikipedia.org/w/api.php?format=json&action=query&redirect=1&prop=extracts&explaintext=1&titles=".$query;
$response = json_decode(file_get_contents($url, true));
$page = $response->query->pages;
$api_text = ((object)reset($page)->extract);
$content = $api_text->scalar;
// wikipedia text content filter
$content_filter = array("\n\n\n", "\n\n");
$content = str_replace($content_filter, "\n", $content);
$content_filter = array(" =="," ==="," ==="," ==="," =====");
$content = str_replace($content_filter, ". ", $content);
$content_filter = array("====","===","=="," =\n");
$content = str_replace($content_filter, " ", $content);
$content = preg_replace( '/\s+/', ' ', $content);
$content = preg_replace('/\([^\)]+\)/', '', $content);
$a = explode(". ", $content);
$a = array_slice($a, 0, $paragraphs);
$content = implode('. ', $a);
$content = str_replace("  ", " ", $content);

// wikipedia image API
$wiki_image_api = "https://it.wikipedia.org/w/api.php?action=query&redirect=1&titles=".$query."&prop=pageimages&format=json&pithumbsize=640";
$wiki_image = json_decode(file_get_contents($wiki_image_api, true));
$image = $wiki_image->query->pages;
$image_url = ((object)reset($image)->thumbnail);
$image_content = $image_url->source;
$image_content_data = file_get_contents($image_content);

// hash
$hash = md5(rand(0,99999999)."fhoweufwe");

// filenames
$wiki_txt = "tmp/wiki_".$hash.".txt";
$wiki_page = "tmp/wiki_".$hash.".page";
$wiki_img = "tmp/wiki_".$hash.".png";
file_put_contents($wiki_txt, $content);
file_put_contents($wiki_page, $query);
file_put_contents($wiki_img, $image_content_data);
?>