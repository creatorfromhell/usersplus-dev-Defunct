<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/23/14
 * Time: 12:09 PM
 * Version: Beta 1
 * Last Modified: 8/23/14 at 12:09 PM
 * Last Modified by Daniel Vidmar.
 */
if(isset($_POST['login'])) {
    if(isset($_POST['username']) && trim($_POST['username']) != '') {
        if(isset($_POST['password']) && trim($_POST['password']) != '') {
            if(isset($_POST['captcha']) && trim($_POST['captcha']) != '' && checkCaptcha(cleanInput($_POST['captcha']))) {
                $name = cleanInput($_POST['username']);
                $email = (!validEmail($name)) ? false : true;
                if(User::exists($name, $email) && checkHash(User::getHashedPassword($name, $email), cleanInput($_POST['password']))) {
                    $user = User::load($name, $email);
                    if($emailActivation && $user->activated == 1 || !$emailActivation) {
                        $user->loggedIn = date("Y-m-d H:i:s");
                        $user->online = 1;
                        $user->save();

                        $_SESSION['usersplusprofile'] = $user->name;
                        destroySession("userspluscaptcha");
                        ?>
                        <script>
                            window.location.assign("index.php");
                        </script>
                    <?php
                    } else {

                        die("You must activate your account first.");
                    }
                } else {
                    die("Failed to login.");
                }
            } else {
                die("Invalid captcha entered.");
            }
        } else {
            die("You must enter a password.");
        }
    } else {
        die("You must enter a username.");
    }
}
?>