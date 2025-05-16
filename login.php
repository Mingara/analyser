<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require_once("classes/Database.php");
    $conn = (new Database())->dbConnection();

    $errmsg = "";
    if($_GET){
        $valEmail = $_GET['email'];
        echo '<style type="text/css">#pass{border:1px solid red;}</style>';
    } else {
        $valEmail = "";
    }

    /*
    KRzayev@kufner.com
    */

    if(isset($_POST["submit"]) && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty($_POST['password']) ) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        //checkPassword($password);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            echo '<style type="text/css">#mail{border:1px solid red;}</style>';
            $errmsg = $email . " is NOT a valid email address.\nPlease try again...";

        } else {
            $sql = "SELECT userpass, ident AS username FROM analyser..Users WHERE email='" . $email . "'";
            $stmt = sqlsrv_query( $conn, $sql);
            
            if( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true));
            }
            
            if( sqlsrv_fetch( $stmt ) === null ) {
                echo '<style type="text/css">#mail{border:1px solid red;}</style>';
                $errmsg = "We couldn't find an account with that email address.\nPlease try again...";
            } else {
                $passwordHashed = sqlsrv_get_field( $stmt, 0);
                $ident = sqlsrv_get_field( $stmt, 1);

                $checkPassword = password_verify($password, $passwordHashed);
                
                if($checkPassword) {
                    //error_log("password_verify: gut" . PHP_EOL, 3, "login.log");
                    $_SESSION["ident"] = $ident;
                    $_SESSION["password"] = $password;
                    $_SESSION["email"] = $email;
                    header("Location: index.php");
                } else {
                    $_SESSION["ident"] = "";
                    header("Location: index.php?email=".$email);
                    exit;
                }
            }
        }
    }
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/gif" href="img/kufner.gif">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <form action="login.php" method="POST">
        <img src="img/kufner_logo1.png" class="ribbon" alt="" />
        <h1>Analyser</h1>
        <div class="inputs_container">
            <input id="mail" type="text" placeholder="eMail" name="email" value="<?php echo $valEmail; ?>" autocomplete="off" required = "">
            <input id="pass" type="password" placeholder="Password" name="password" autocomplete="off" required = "">
        </div>
        <button name="submit">Login</button>
        <textarea id="msg" rows="1" cols="50"><?php echo htmlspecialchars($errmsg); ?></textarea>
    </form>
</body>
</html>
