<?php
/**
 * Created by PhpStorm.
 * User: akhil
 * Date: 11/8/17
 * Time: 1:15 PM
 */
    require_once ("sessions.php");
    require_once ("db_connect.php");

    session_create();
    if (!session_check()) {
        $username = cleanup($_POST['username'], $con);
        $password = cleanup($_POST['password'], $con);
        if ($username!='' && $password!='') {
            $password = sha1($password);
            $result = $con->query("select id from users where username='$username' and password='$password'");
            if ($result) {
                if ($result->num_rows>0) {
                    $row = $result->fetch_assoc();
                    $_SESSION['USER_ID'] = $row['id'];
                    $_SESSION['USER_NAME'] = $username;
                    $_SESSION['PWD'] = '~';
                    session_set_gamer();// fetch if user is admin and set shadow session in such a case
                    header("location:game.php");
                    return;
                }
            }
            else {
                $_SESSION['MESSAGE']="Such a combination does not exist";
                header("location:index.php");
                return;
            }
        }
        else {
            $_SESSION['MESSAGE']="All fields are mandatory";
            header("location:index.php");
            return;
        }
    }
    else {
        if (isset($_SESSION['SHADOW'])) {
            header("location:shadow.php");
        }
        else if (isset($_SESSION['GAMER'])) {
            header("location:gamer.php");
        }
    }

?>