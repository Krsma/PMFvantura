<?php
session_start();

if(! isset($_SESSION["username"]))
{
    header("Location: login.php");
}

if(isset($_GET["logout"]) && $_GET["logout"] == "true")
{
    session_destroy();
    unset($_COOKIE["skripte"]);
    unset($_COOKIE["nauceno"]);

    setcookie("skripte", "");
    setcookie("nauceno", "");

    header("Location: login.php");

}
?>

<html>
<body>
    <h>Currently logged in as player <?php echo $_SESSION["username"];?></h><br>
    <button onclick= location.href='?logout=true' type=button >Log out </button>
    <h> Current best time is: TO BE ADDED</h><br>

    <div>
        <h>If you have a run you already started, it will be discarded</h>

        <form action="game.php" method="post">
            <input type="hidden" name="username" value=<?php echo $_SESSION["username"]?> >
            <input type="hidden" name="newRun" value="yes"><
            <input type="submit" name="startRun" value="START NEW RUN">
        </form>
    </div>
</body>
</html>
