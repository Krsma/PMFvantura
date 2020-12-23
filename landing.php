<?php
session_start();

if(! isset($_SESSION["username"]))
{
    header("Location: login.php");
}

if(isset($_GET["logout"]))
{
    session_destroy();

    setcookie("timeDiff", "");
    setcookie("skripte", "");
    setcookie("nauceno", "");
    unset($_COOKIE["skripte"]);
    unset($_COOKIE["nauceno"]);
    unset($_COOKIE["timeDiff"]);


    header("Location: login.php");

}
?>

<html>
<body>
    <h1>Currently logged in as player <?php echo $_SESSION["username"];?></h1><br>
    <button onclick= location.href='?logout' type=button >Log out </button>

    <div>
        <p>If you have a run you already started, it will be discarded</p><br>
        <p>Najkraci nacin da predjete igru je napolje &rarr; plava &rarr; napolje &rarr; matematika &rarr; citaonica &rarr; matematika &rarr; amfiteatar &rarr; polozi ispit</p>
        <form action="game.php" method="post">
            <input type="hidden" name="username" value=<?php echo $_SESSION["username"]?> >
            <input type="hidden" name="newRun" value="yes">
            <input type="submit" name="startRun" value="START NEW RUN">
        </form>
    </div>
</body>
</html>
