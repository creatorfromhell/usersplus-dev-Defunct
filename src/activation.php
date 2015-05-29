<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/18/14
 * Time: 12:49 PM
 * Version: Beta 1
 * Last Modified: 8/18/14 at 12:49 PM
 * Last Modified by Daniel Vidmar.
 */
include_once("include/header.php");
if(pageLocked($currentUser, "", true)) { die("Invalid permissions!"); }
$page = "activate";
if(isset($_GET['page'])) {
    $page = $_GET['page'];
}
?>
    <header>
        <h1>User Activation</h1>
    </header>
<main>
<?php
if($page == "resend") {
    include_once("include/pages/activate/resend.php");
} else {
    include_once("include/pages/activate/activate.php");
}
?>
</main>
<?php
include_once("include/footer.php");
?>