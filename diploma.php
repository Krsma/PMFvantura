<?php
session_start();
if(isset($_SESSION["username"])  && isset($_GET["timeDiff"]))
{

    $namefile = "files/diploma.txt";
    $username = $_SESSION["username"];
    $timeDiff = htmlspecialchars($_GET["timeDiff"]);
    $content = "Congratulations $username on finishing the game \n It took you $timeDiff to finish it";

    $file = fopen($namefile, "w") or die("Unable to open file!");
    fwrite($file, $content);
    fclose($file);

    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename='.basename($namefile));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($namefile));
    header("Content-Type: text/plain");
    readfile($namefile);

    //echo $content;
    exit();
}
else
{
    header("Location: landing.php");
//    echo '<script type="text/javascript">';
//    echo 'alert("Can acesses diploma directly. Bices poslat na pocetak ");';
//    echo 'window.location.href = "landing.php";';
//    echo '</script>';
}
