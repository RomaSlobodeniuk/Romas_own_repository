<?php
include_once('function.php');
include_once('navigation.php');
$file = './data/contact.json';
$data = getContent($file);
$content = getTemplate();
$content = parseNavigation($content, $navContent);
if (isset($data['page_content'])) {
    $action = './action/contact.php';
    $content = parseForm($content, $data['page_content'], $action);
}
$content = parseContent($content, $data);
showContent($content);