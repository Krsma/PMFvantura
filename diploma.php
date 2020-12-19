<?php
session_start();
if(isset($_SESSION["username"])  && isset($_GET["timeDiff"]))
{

    $namefile = "files/diploma.txt";
    $username = $_SESSION["username"];
    $timeDiff = htmlspecialchars($_GET["timeDiff"]);
    $content = "Congratulations $username on finishing the game \n It took you $timeDiff to finish it";
    //print_r("Stigao rada sa fajlom");
    $file = fopen($namefile, "w") or die("Unable to open file!");
    fwrite($file, $content);
    fclose($file);
    //print_r("Stigao do headera");
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    header("Content-Type: text/plain");
    readfile($file);

    //echo $content;
    exit();
}

