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
if(pageLockedAdmin($currentUser)) { die("Invalid permissions!"); }
$page = "users";
if(isset($_GET['page'])) {
    $page = $_GET['page'];
}
?>
    <header>
        <h1>Administrator Panel</h1>
    </header>
    <nav id="admin-nav">
        <ul>
            <li <?php if($page == "groups") { echo "class='active';"; } ?>><a href="?page=groups">Groups</a></li>
            <li <?php if($page == "permissions") { echo "class='active';"; } ?>><a href="?page=permissions">Permissions</a></li>
            <li <?php if($page == "users") { echo "class='active';"; } ?>><a href="?page=users">Users</a></li>
        </ul>
    </nav>
    <main>
        <?php
            if($page == "groups") {
                include_once("include/pages/admin/groups.php");
            } else if($page == "permissions") {
                include_once("include/pages/admin/permissions.php");
            } else {
                include_once("include/pages/admin/users.php");
            }
        ?>
    </main>
<?php
include_once("include/footer.php");
?>