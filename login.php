<?php

require_once("configs/constants.php");
require_once("classes/Database.php");

$db = new Database("configs/config.ini");
$errors = [];
$messages = [];

// reroute to login and register to game.php

if(isset($_POST["registerButton"]))
{
    //print_r($_POST);
    $registerData = array();
    if($_POST["username"])
    {
        $registerData["username"] = htmlspecialchars($_POST["username"]);
    }
    if($_POST["password"])
    {
        $registerData["password"] = htmlspecialchars($_POST["password"]);
    }
    if($_POST["email"])
    {
        $registerData["email"] = htmlspecialchars($_POST["email"]);
    }
    if(isset($registerData["username"]) && isset($registerData["password"]) && isset($registerData["email"]))
    {

       $registerResult = $db->insertUser($registerData["username"], $registerData["password"], $registerData["email"]);
       print_r($registerResult);
       $messages[] = $registerResult;
    }

}
if(isset($_POST["loginButton"]))
{
    //print_r($_POST["loginButton"]);
    if($_POST["username"])
    {
        $username = htmlspecialchars($_POST["username"]);
    }
    if($_POST["password"])
    {
        $password = htmlspecialchars($_POST["password"]);
    }

    if(isset($password) && isset($username))
    {
        $login = $db->loginUser($username, $password);
        print_r($login);
        if($login)
        {
            print_r("Uspesan login");
            session_start();
            $_SESSION["username"] = $username;
            header("Location: landing.php");
        }
        else
        {
            print_r("Nije uspeo login");
        }
    }
}

?>


<html>
<head>
    <title>Login i Register stranica </title>

</head>
<body>
    <h1> Prijavi se ili se registruj </h1>
    <div>

    </div>
    <div>

        <h2> Log In</h2>
        <form method="POST">
            <label for="username"> Username</label>
            <input type="text" name="username"> <br>

            <label for="password"> Password</label>
            <input type="password" name="password"> <br>

            <input type="submit" name="loginButton" value="Login">
        </form>

    </div>
    <div>
        <h3> First Time? <br>Register!</h3>
        <form method="post">
            <label for="username"> Username</label>
            <input type="text" name="username"> <br>

            <label for="password"> Password</label>
            <input type="password" name="password"> <br>

            <label for=""email>Email</label>
            <input type="email" name="email"><br>

            <input type="submit" name="registerButton" value="Register">
        </form>
    </div>
</body>


</html>