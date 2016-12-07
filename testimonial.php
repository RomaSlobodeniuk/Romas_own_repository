<?php
include_once('function.php');
include_once('navigation.php');
$file = './data/testimonial.json';
$data = getContent($file);
$content = getTemplate();
$content = parseNavigation($content, $navContent);
$testimonials = '';
if (isset($data['page_content'])) {
    $file = './template/testimonial.tpl';
    for ($i = 0; $i < count($data['page_content']); $i++) {
        $tpl = getTpl($file);
        $testimonial = $data['page_content']['field_' . ($i + 1)];
        $tpl = str_replace("[testimonial_name]", $testimonial['name'], $tpl);
        $tpl = str_replace("[testimonial_type]", $testimonial['type'], $tpl);
        $testimonials .= $tpl;
    }
    $content = parseAdditional($content, $testimonials);
}
$content = parseContent($content, $data);
showContent($content);