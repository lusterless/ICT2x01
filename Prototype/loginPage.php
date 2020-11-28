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

<!--<html lang="en">
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
                    <input minlength="8" maxlength="16" type="password" placeholder="Enter Password" name="password" required/>
                </div>
                <div>
                    <input type='hidden' name='login' value='login'>
                    <button type="submit" class="loginBtn">Login</button>
                </div>
            </form>
//php
                if(isset($_SESSION["errormsg"])){
                    echo "<h3 style='color: red;'>".$_SESSION['errormsg']."</h3>";
                }
        </div>
    </body>
</html>-->
<html lang="en">
    <head>    
        <title>SIT Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/login.css">
    </head>
    <body>
        <section class="wrapper">
            <div class="content">
                <header>
                    <h1>SIT Feedback Portal</h1>
                </header>
                <section>
                    <form action="loginAuth.php" class="login-form" method="post">
                        <div class="input-group">
                            <b><label for="username">Username or Email</label></b>
                            <input type="text" placeholder="Username or Email" id="username" name="username">
                        </div>
                        <div class="input-group">
                            <b><label for="password">Password</label></b>
                            <input type="password" placeholder="Password" id="password" name="password" minlength="8" required>
                        </div>
                        <input type='hidden' name='login' value='login'>
                        <div class="input-group"><button>Login</button></div>
                    </form>
                </section>
            </div>
        </section>
    </body>
</html>

<?php
unset($_SESSION["errormsg"]);
?>