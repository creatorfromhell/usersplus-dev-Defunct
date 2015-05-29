<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/7/14
 * Time: 4:43 PM
 * Version: Beta 1
 * Last Modified: 8/7/14 at 4:43 PM
 * Last Modified by Daniel Vidmar.
 */
include_once("include/header.php");
if(checkSession("usersplusprofile")) { die("You're already logged in."); }
include_once("include/handling/login.php");
?>
    <header>
        <h1>Login</h1>
    </header>
    <main>
        <form method="post" action="login.php">
            <h3>Login</h3>
            <div id="holder">
                <div id="page_1" class="form-page">
                    <fieldset id="inputs">
                        <input id="username" name="username" type="text" placeholder="Username or Email">
                        <input id="password" name="password" type="password" placeholder="Password">
                        <?php
                            $captcha = new Captcha();
                            $captcha->printImage();
                            $_SESSION['userspluscaptcha'] = $captcha->code;
                        ?>
                        <br />
                        <input id="captcha" name="captcha" type="text" placeholder="Enter characters above">
                    </fieldset>
                    <fieldset id="links">
                        <input type="submit" class="submit" name="login" value="Login">
                        <label id="other">Need an account? <a href="register.php">Register</a></label>
                    </fieldset>
                </div>
            </div>
        </form>
    </main>
<?php
include_once("include/footer.php");
?>