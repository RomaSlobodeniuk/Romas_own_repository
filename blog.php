<?php
include_once('function.php');
include_once('navigation.php');
$current = 1;
$per_page = 2;
$file = './data/blog.json';
$data = getContent($file);
$content = getTemplate();
$content = parseNavigation($content, $navContent);
$articles = '';
if (isset($_GET['Cpag']) and is_numeric($_GET['Cpag'])) {
    $current = $_GET['Cpag'];
}
$pagination = function ($all) use ($per_page, $current) {
    $pag = '<ul>';
    for ($i = 0, $j = 0; $i < count($all); $i += $per_page, $j++) {
        if ($current == $j + 1) {
            $pag .= '<li><span>' . ($j + 1) . '</span></li>';
        } else {
            $pag .= '<li><a href="?Cpag=' . ($j + 1) . '">' . ($j + 1) . '</a></li>';
        }
    }
    $pag .= '</ul>';
    return $pag;
};
if (isset($data['page_content'])) {
    $file = './template/article.tpl';
    $all_count = count($data['page_content']);
    $start = ($current - 1) * $per_page;
    $end = (($current * $per_page) < $all_count) ? $current * $per_page : $all_count;
    for ($i = $start; $i < $end; $i++) {
        $tpl = getTpl($file);
        $article = $data['page_content']['field_' . ($i + 1)];
        $tpl = str_replace("[article_title]", $article['title'], $tpl);
        $tpl = str_replace("[article_date]", $article['date'], $tpl);
        $tpl = str_replace("[article_author]", $article['author'], $tpl);
        $tpl = str_replace("[article_text]", $article['text'], $tpl);
        $articles .= $tpl;
    }
    $articles .= $pagination($data['page_content']);
    $content = parseAdditional($content, $articles);
}
$content = parseContent($content, $data);
showContent($content);
