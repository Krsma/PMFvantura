<?php
require_once("configs/constants.php");
require_once("classes/Database.php");

function showAllRuns($runs)
{
    $table = "<table style=\"width:100%\"> <tr> <th>Run number</th> <th>Time spent</th> <th>Start Time</th> <th> End Time</th> ";
    $runNumber = 1;
    foreach($runs as $run)
    {
        $startTime = $run["startTime"];
        $endTime = $run["endTime"];
        $timeDiff = strtotime($endTime) - strtotime($endTime); // fix
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


$db = new Database("configs/config.ini");

if(isset($_POST["username"]))
{
    $startTime = $db->getRunStartTime($_SESSION["username"]);
    $endTime = date('d/m/Y h:i:s', time());
    $timeDiff = strtotime($endTime) - strtotime($startTime);
    $db->endRun($_SESSION["username"], $endTime);

    $runData = $db->getRuns($_SESSION["username"]);
    print_r($runData);
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
    <h>Da li zelis diplomu za prelazak?></h><br>
    <div>
        <?php showAllRuns($runData); ?>
    </div>
    <button onclick= location.href='landing.php' type=button >Go back to home </button>
</body>
</html>
