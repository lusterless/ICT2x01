<!DOCTYPE html>

<?php
include "classes/users.class.php";

session_start();
$Details = "";
if (isset($_SESSION['sessionInfo'])) {
    $Details = $_SESSION['sessionInfo'];
    if($Details->getRole()=="student")
        header("Location:visualGame.php");
    else
        header("Location:createPageProf.php");
}
else{
    unset($_SESSION["sessionInfo"]);
    session_destroy();
}
?>

<html lang="en">
    <head>
        <title>SIT Login</title>
        <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/login.css">
    </head>
    <body>
        <div id="loginForm">
            <form action="loginAuth.php" method="post">
                <div>
                    <label for="username"><b>Username:</b></label>
                    <input type="text" placeholder="Enter Username" name="username" required/>
                </div>
                <div style="margin-top: 7px;">
                    <label for="password"><b>Password:</b></label>
                    <input minlength="8" type="password" placeholder="Enter Password" name="password" required/>
                </div>
                <div>
                    <button type="submit" class="loginBtn">Login</button>
                </div>
            </form>
        </div>
    </body>
</html>