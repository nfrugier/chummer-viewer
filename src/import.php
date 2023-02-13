<?php
/**
 * TWIG
 */
require_once('../vendor/autoload.php');

$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader);
$targetDir = "../uploads/";
$fileName = basename(convertToXML($_FILES["fileToUpload"]["name"], 'xml'));
$targetFile = $targetDir . $fileName;
$uploadOk = false;
$fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

if (isset($_POST["submit"])) {
    if ($fileType !== 'chum5' && $fileType !== 'xml') {
        echo "Sorry, wrong file type, only chum5 and xml are allowed" . PHP_EOL;
    } else {
        $uploadOk = true;
    }
}
$array = null;
if ($uploadOk && move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile)) {
    $json = json_encode(simplexml_load_string(file_get_contents($targetFile)), JSON_THROW_ON_ERROR);
    $array = json_decode($json, true);
}

if (!empty($array)) {
    echo $twig->render('result.html.twig', [
        'character' => $array,
        'file' => $fileName ]);
}

/**
 * @param string $targetFile
 * @param string $newExtension
 * @return string
 */
function convertToXML(string $targetFile, string $newExtension): string
{
    $info = pathinfo($targetFile);
    return $info["filename"] . "." . $newExtension;
}
