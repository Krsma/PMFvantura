<?php
require_once("configs/constants.php");
require_once("classes/Database.php");
require_once("classes/Event.php");

$db = new Database("configs/config.ini");
$runData = array();

function showAllRuns($runs)
{
    $table = "<table style=\"width:100%\"> <tr> <th>Run number</th> <th>Time spent</th> <th>Start Time</th> <th> End Time</th> ";
    $runNumber = 1;
    foreach($runs as $run)
    {
        $startTime = $run["startTime"];
        $endTime = $run["endTime"];
        $timeDiff = Event::getTimeDiff($endTime, $startTime);
        $table .= "<tr> <td> $runNumber </td><td> $timeDiff</td> <td>$startTime</td> <td>$endTime</td> </tr>";
        $runNumber += 1;
    }
    $table .= "</table>";
    echo $table;
}

session_start();
if(! isset($_SESSION["username"]))
{
    header("Location: login.php");
}



if(isset($_POST["username"]))
{
    $startTime = $db->getRunStartTime($_SESSION["username"]);
    $endTime = date('Y-m-d h:i:s', time());

    $timeDiff = Event::getTimeDiff($endTime, $startTime);
    setcookie("timeDiff", $timeDiff);
    $db->endRun($_SESSION["username"], $endTime);

    $runData = $db->getRuns($_SESSION["username"]);
    //print_r($runData);
}
elseif(isset($_GET["diploma"]) && isset($_COOKIE["timeDiff"]))
{
    generateDiploma($_COOKIE["timeDiff"], $_SESSION["username"]);
}
else
{
    header("Location: game.php");
}

?>

<html>
<body>
    <h>Svaka cast, presao si igricu</h><br>
    <h>Tvoje vreme je <?php echo $timeDiff?></h><br>
    <button onclick="location.href='diploma.php?timeDiff=<?php echo $timeDiff?> '" type="button"> Generate a diploma for game completion</button><br>
    <h>Da li zelis diplomu za prelazak?></h><br>
    <div>
        <?php showAllRuns($runData); ?>
    </div>
    <button onclick= location.href='landing.php' type=button >Go back to home </button>
</body>
</html>
