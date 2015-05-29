<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/11/14
 * Time: 6:28 PM
 * Version: Beta 1
 * Last Modified: 8/11/14 at 6:28 PM
 * Last Modified by Daniel Vidmar.
 */
include_once("include/header.php");
if(pageLocked($currentUser, "node.example.common", true)) { die("Invalid permissions!"); }
$user = $currentUser;
if(isset($_GET['name']) && User::exists($_GET['name'])) {
    $user = User::load($_GET['name']);
}
if($user === null) { die("User not set."); }
?>
    <header>
        <h1>Profile</h1>
    </header>
    <main>
        <div id="profile">
            <div id="avatar"></div>
            <div id="information">
                <label id="name">Username: <?php echo $user->name; ?></label>
                <label id="group">Group: <?php echo $user->group->name; ?></label>
                <label id="email">Email: <?php echo $user->email; ?></label>
                <label id="joined">Joined: <?php echo $user->registered; ?></label>
            </div>
        </div>
    </main>
<?php
include_once("include/footer.php");
?>