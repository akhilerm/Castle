<?php
/**
 * Created by PhpStorm.
 * User: akhil
 * Date: 11/8/17
 * Time: 1:03 PM
 */
    require_once ("sessions.php");
    if (!session_check()) {
        if(!isset($_SESSION['GAMER']) && !isset($_SESSION['SHADOW'])) {
            if (isset($_SESSION['MESSAGE'])) {
                echo $_SESSION['MESSAGE'];
                unset($_SESSION['MESSAGE']);
            }
?>
            <br>
            <form method="post" action="login.php">
                <input name="username" id="username"/>
                <label for="username">Username</label>
                <input name="password" id="password"/>
                <label for="password">Password</label>
                <button name="btn_login">Login</button>
            </form>
<?php
        }
    }
?>