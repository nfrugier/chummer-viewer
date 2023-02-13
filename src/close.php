<?php
$file = ("uploads/".$_GET['char']);
if (is_file($file)){
    unlink($file);
}

header('Location: ../index.php');