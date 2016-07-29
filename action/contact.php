<?php
include_once('../config/const.php');
include_once('../function.php');

/*Parse navigation menu*/
$file = '../data/navigation.json';
$navigation = getContent($file);
$navContent = '<nav><menu>';
foreach ($navigation as $nav) {
    $navContent .= '<li><a href="../' . $nav['link'] . '">' . $nav['title'] . '</a></li>';
}
$navContent .= '</menu></nav>';
$content = file_get_contents('../template.html');
$content = parseNavigation($content, $navContent);

/*Add new path for style*/
$pathToStyle = '../style.css';
$content = str_replace("style.css", $pathToStyle, $content);

/*Get message from form with registration information*/
$message = getMessageFromRegistration($_POST);

/*Add new "answer.php" path, then replace the title of the page*/
$ans_path = '../data/answer.json';
$answer = getContent($ans_path);
$content = str_replace("[title_page]", $answer['title'], $content);

/*Get the right information for [title] in "template.html" and replace it*/
if ($message === 'Thanks for giving us the whole registration information ;)<br>') {
    $content = str_replace("[title]", $answer['success'], $content);
} else {
    $content = str_replace("[title]", $answer['fail'], $content);
}
$content = str_replace("[description]", $answer['inf'], $content);

/*Parse the form with information for users*/
if (isset($message)) {
    $answer_form = '';
    foreach ($answer as $key => $value) {
        switch ($key) {
            case 'action':
                $answer_form .= '<form method="post" class="answer_form" action="' . $value . '">';
                break;
            case 'redirect':
                $answer_form .= '<p><span class="message"><b>' . $message . '</b></span></p>';
                $answer_form .= '<input name="' . $value . '" type="submit" value="' . $value . '" id="go_back_button"></p>';
                break;
        }
    }
    $answer_form .= '</form>';
    $content = str_replace("[additional_content]", $answer_form, $content);
}

showContent($content);
//unset($_POST);
