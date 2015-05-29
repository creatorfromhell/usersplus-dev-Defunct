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
if(!checkSession("usersplusprofile")) { die("You're currently not logged in."); }
$date = date("Y-m-d H:i:s");
$currentUser->loggedIn = $date;
$currentUser->online = 0;
$currentUser->save();
destroySession("usersplusprofile");
?>
    <main>
        <p>You have been logged out successfully.</p>
        <script>
            window.location.assign("login.php");
        </script>
    </main>
<?php
include_once("include/footer.php");
?>