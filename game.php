<?php
require_once("configs/constants.php");
require_once("classes/Database.php");
require_once("classes/Event.php");
session_start();
if(!isset($_SESSION["username"]))   // FORCE LOGIN
{
////    reroute to login
    header("Location: login.php");
}

$db = new Database("configs/config.ini");
function getEventData($path="configs/eventData.json")
{
    $file = fopen($path, "r");
    $fileString = "";
    while(! feof($file))
    {
        $fileString .= fgets($file);
    }
    $eventData = json_decode($fileString, true);
    return $eventData;
}
function finishGameButton()
{

    if(isset($_COOKIE["skripte"]) && isset($_COOKIE["nauceno"]) && isset($_SESSION["username"]) && isset($_GET["spot"]) && htmlspecialchars($_GET["spot"]) == "amfiteatar")
    {
        return "<div>
                <h> Spreman si! Izadji na ispit</h>
                <form action=\"finished.php\" method=\"post\">
                <input type=\"hidden\" name=\"username\" value=" . $_SESSION["username"] . " >
                <input type=\"submit\" name=\"finishRun\" value=\"Polozi ispit\">
                </form>
                </div>";
    }
    return "";
}
function startNewRun($username, $db)
{
    // Ubijamo cookieje sa prethodnih pokusaja
    setcookie("timeDiff", "");
    setcookie("skripte", "");
    setcookie("nauceno", "");
    unset($_COOKIE["skripte"]);
    unset($_COOKIE["nauceno"]);
    unset($_COOKIE["timeDiff"]);

    if($db->checkIfRunStarted($username))
    {
        $db->dropRun($username);
    }
    $db->insertRun($username, date('Y-m-d h:i:s', time()));
}

$currentSpot = "start";
$currentEvent = null;
$eventData = getEventData();

if(isset($_POST["username"]) && isset($_POST["newRun"]))
{

    startNewRun($_SESSION["username"], $db);
}





if(isset($_GET["spot"]))
{
    if(!  array_key_exists(htmlspecialchars($_GET["spot"]), $eventData))
    {
        echo '<script type="text/javascript">';
        echo 'alert("Nije validna lokacija. Bices vracen na pocetak igre.");';
        echo 'window.location.href = "game.php";';
        echo '</script>';
        exit();
    }
    $currentSpot = $_GET["spot"];
    switch ($currentSpot)
    {
        case "skriptarnica":   setcookie("skripte", true); break;
        case "citaonica": setcookie("nauceno", true); break;
    }

}
$currentEvent = new Event($eventData[$currentSpot]["tekst"], $eventData[$currentSpot]["slika"], $eventData[$currentSpot]["opcije"]);
?>

<html>
<head>
<title> Currently Exploring</title>
</head>
<body>
    <div>
        <?php
        echo $currentEvent->getHtml();
        ?>
    </div>
    <?php
    echo finishGameButton();
    ?>
</body>
</html>

