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
if(pageLocked($currentUser, "node.example.common", true)) { die("Invalid permissions!"); }
?>
    <header>
        <h1>Welcome</h1>
    </header>
<main>
    <p>Example guest page.</p>
</main>
<?php
include_once("include/footer.php");
?>