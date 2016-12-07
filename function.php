<?php

include_once('config/const.php');


function getContent($file)
{
    $content = file_get_contents($file);
    return json_decode($content, true);
}

function getTpl($file)
{
    return file_get_contents($file);
}

function getTemplate()
{
    return file_get_contents('template.html');
}

function parseNavigation($content, $data)
{
    $content = str_replace("[navigation]", $data, $content);
    return $content;
}

function parseAdditional($content, $data)
{
    $content = str_replace("[additional_content]", $data . '[additional_content]', $content);
    return $content;
}

function parseContent($content, $data)
{
    $content = str_replace("[title_page]", 'Page: ' . $data['title'], $content);
    $content = str_replace("[title]", $data['title'], $content);
    $content = str_replace("[description]", $data['description'], $content);
    $content = str_replace("[additional_content]", '', $content);
    return $content;
}

function showContent($content)
{
    echo $content;
}

function parseForm($content, $data, $action = '')
{
    if ($action) {
        $action = ' action="' . $action . '"';
    }
    $form = '<form method="post" class="form"' . $action . ' enctype="multipart/form-data">';
    foreach ($data as $value) {
        switch ($value['type']) {
            case 'password':
            case 'text':
            case 'file':
                $form .= '<p><label>' . $value['title'] . '<input name="' . $value['name'] . '" type="' . $value['type'] . '"></label></p>';
                break;
            case 'textarea':
                $form .= '<p><label>' . $value['title'] . '<textarea name="' . $value['name'] . '"></textarea></label></p>';
                break;
            case 'button':
            case 'submit':
                $form .= '<p><input name="' . $value['name'] . '" type="' . $value['type'] . '" id="submit" value="Send"></p>';
                break;
        }
    }
    $form .= '</form>';
    $content = str_replace("[additional_content]", $form . '[additional_content]', $content);
    return $content;
}

//function getGalleryData($file) {
//    $content = file_get_contents($file);
//}
function saveGallery($file, $gallery)
{
    $fh = fopen($file, 'w+');
    $json_gallery = json_encode($gallery, JSON_FORCE_OBJECT);
    fwrite($fh, $json_gallery);
    fclose($fh);
}

function setNewFileInGallery($data)
{
    $file_gallery = '../data/data_gallery.json';
    $gallery = getContent($file_gallery);
    if (!is_array($gallery)) {
        $gallery = array();
    }
    array_push($gallery, $data);
    saveGallery($file_gallery, $gallery);
}

/**
 * @param $data mixed
 */
function dump($data)
{
    echo '<pre>';
    var_export($data);
    echo '</pre>';
}

function getMessageFromRegistration($post_arr)
{
    $tmp = '';
    $last_user_arr = json_decode(file_get_contents('./Last_user_information.json'), true);
    if (($last_user_arr['fio'] === $post_arr['fio']) && ($last_user_arr['email'] === $post_arr['email']) && ($last_user_arr['message'] === $post_arr['message'])) {
        header("Location: /".ROOT_PATH."contact.php");
    } elseif (!empty($post_arr)) {
        foreach ($post_arr as $key => $value) {
            if ($value == "") {
                $key = strtoupper($key);
                $tmp .= 'Enter your ' . $key . ', please!' . '<br>';
            }
        }
        if ($tmp == "") {
            recordToFile($post_arr);
            $tmp = "Thanks for giving us the whole registration information ;)" . '<br>';
        }
    }
    return $tmp;
}

function recordToFile($get_or_post)
{
    $json_array = file_put_contents('Last_user_information.json', json_encode($get_or_post, JSON_FORCE_OBJECT)/*, FILE_APPEND | LOCK_EX*/);
//    $post_array_content = file_get_contents('Registration_information.json', true);

    if (!empty($get_or_post)) {
        $txt = file_put_contents('Registration_information.txt', "\n" . 'Information about user:' . "\n", FILE_APPEND | LOCK_EX);
        foreach ($get_or_post as $key => $value) {
            $tmp_str = "";
            $key = strtoupper($key);
            $tmp_str = 'User ' . $key . ' is: ' . $value;
            $txt = file_put_contents('Registration_information.txt', $tmp_str . "\n", FILE_APPEND | LOCK_EX);
        }
    }
    return $json_array;
}