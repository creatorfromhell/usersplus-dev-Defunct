<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/23/14
 * Time: 12:10 PM
 * Version: Beta 1
 * Last Modified: 8/23/14 at 12:10 PM
 * Last Modified by Daniel Vidmar.
 */
if(isset($_POST['register'])) {
    if(isset($_POST['username']) && trim($_POST['username']) != '') {
        if(isset($_POST['email']) && trim($_POST['email']) != '' && validEmail($_POST['email'])) {
            if(isset($_POST['password']) && trim($_POST['password']) != '') {
                if(isset($_POST['c_password']) && trim($_POST['c_password']) != '') {
                    if(!User::exists($_POST['username'], false) && !User::exists($_POST['email'], true)) {
                        if($_POST['password'] == $_POST['c_password']) {
                            if(isset($_POST['captcha']) && trim($_POST['captcha']) != '' && checkCaptcha(cleanInput($_POST['captcha']))) {
                                $date = date("Y-m-d H:i:s");
                                $user = new User();
                                $user->id = User::getAvailableID();
                                $user->ip = User::getIP();
                                $user->name = cleanInput($_POST['username']);
                                $user->email = cleanInput($_POST['email']);
                                $user->registered = $date;
                                $user->loggedIn = $date;
                                $user->password = generateHash(cleanInput($_POST['password']));
                                $user->group = Group::load(Group::preset());
                                $user->activationKey = generateSessionID(40);
                                User::add($user);
                                destroySession("userspluscaptcha");
                                global $emailActivation;
                                if($emailActivation) {
                                    $user->sendActivation();
                                }
                                ?>
                                <script>
                                    window.location.assign("index.php");
                                </script>
                            <?php
                            } else {
                                die("Invalid captcha entered.");
                            }
                        } else {
                            die("Passwords do not match.");
                        }
                    } else {
                        die("Username or email address already in use.");
                    }
                } else {
                    die("You must confirm your password.");
                }
            } else {
                die("You must enter a password.");
            }
        } else {
            die("You must enter a valid email address.");
        }
    } else {
        die("You must enter a username.");
    }
}
?>