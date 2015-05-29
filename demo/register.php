<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/7/14
 * Time: 4:44 PM
 * Version: Beta 1
 * Last Modified: 8/7/14 at 4:44 PM
 * Last Modified by Daniel Vidmar.
 */
include_once("include/header.php");
if(checkSession("usersplusprofile")) { die("You're already logged in."); }
include_once("include/handling/register.php");
?>
    <header>
        <h1>Register</h1>
    </header>
    <main>
        <?php if($registration) { ?>
        <form method="post" action="register.php">
            <h3>Register</h3>
            <div id="holder">
                <div id="page_1" class="form-page">
                    <fieldset id="inputs">
                        <input id="username" name="username" type="text" placeholder="Username">
                        <input id="email" name="email" type="text" placeholder="Email">
                        <input id="password" name="password" type="password" placeholder="Password">
                        <input id="c_password" name="c_password" type="password" placeholder="Confirm Password">
                        <?php
                            $captcha = new Captcha();
                            $captcha->printImage();
                            $_SESSION['userspluscaptcha'] = $captcha->code;
                        ?>
                        <br />
                        <input id="captcha" name="captcha" type="text" placeholder="Enter characters above">
                    </fieldset>
                    <fieldset id="links">
                        <input type="submit" class="submit" name="register" value="Register">
                        <label id="other">Have an account? <a href="login.php">Login</a></label>
                    </fieldset>
                </div>
            </div>
        </form>
    <?php } else { ?>
        <p>Registration has been disabled!</p>
    <?php } ?>
    </main>
<?php
include_once("include/footer.php");
?>