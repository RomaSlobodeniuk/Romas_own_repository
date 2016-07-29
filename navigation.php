<?php
$file = './data/navigation.json';
$navigation = getContent($file);
$navContent = '<nav><menu>';
foreach ($navigation as $nav) {
    $navContent.= '<li><a href="'.$nav['link'].'">'.$nav['title'].'</a></li>';
}
$navContent.= '</menu></nav>';