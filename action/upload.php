<?php
include('../function.php');
//dump($_FILES);
//die;

if ($_FILES['file']['error'] == 0) {
    $file_destination = __DIR__ . '/../upload/' . $_FILES['file']['name'];
//    dump($_FILES);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $file_destination)) {
        if (isset($_POST['title'])) {
            $info_file = array(
                'title' => $_POST['title'],
                'file_name' => $_FILES['file']['name']
            );
            setNewFileInGallery($info_file);
        }
    }
}
header("Location: /".ROOT_PATH."gallery.php");