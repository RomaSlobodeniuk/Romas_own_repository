<?php
include_once('function.php');
include_once('navigation.php');
$curr_page = 1;
if (isset($_GET) && isset($_GET['cPag'])) {
    $curr_page = $_GET['cPag'];
}
$file = './data/gallery.json';
$data = getContent($file);
$content = getTemplate();
$content = parseNavigation($content, $navContent);
if (isset($data['page_content'])) {
    $action = './action/upload.php';
    $content = parseForm($content, $data['page_content'], $action);
}
$gallery_data = './data/data_gallery.json';
$array_data = getContent($gallery_data);
if (!empty($array_data) && is_array($array_data)) {
    $count = count($array_data);
    $pagination = '<ul>';
    for ($i = 0, $j = 1; $i < $count; $i += PICTURES_PER_PAGE, $j++) {
        if ($j == $curr_page) {
            $pagination .= '<li><span>' . $j . '</span></li>';
        } else {
            $pagination .= '<li><a href="?cPag=' . $j . '">' . $j . '</a></li>';
        }
    }
    $pagination .='</ul>';
    $content = parseAdditional($content, $pagination);

    $start = ($curr_page - 1) * PICTURES_PER_PAGE; // 0
    $end = $count > ($curr_page * PICTURES_PER_PAGE) ? $curr_page * PICTURES_PER_PAGE : $count; // 4
    for ($i = $start; $i < $end; $i++) {
        $file = './template/page_content.tpl';
        $tpl = getTpl($file);
        $tpl = str_replace("[upload_file_name]", $array_data[$i]['title'], $tpl);
        $tpl = str_replace("[upload_picture_name]", $array_data[$i]['file_name'], $tpl);
        $content = parseAdditional($content, $tpl);
    }
}
$content = parseContent($content, $data);
showContent($content);