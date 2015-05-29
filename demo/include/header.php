<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/7/14
 * Time: 6:53 PM
 * Version: Beta 1
 * Last Modified: 8/9/14 at 1:13 AM
 * Last Modified by Daniel Vidmar.
 */
require_once("common.php");
session_start();
$currentUser = null;

if(isset($_SESSION['usersplusprofile'])) {
    if(User::exists($_SESSION['usersplusprofile'])) {
        $currentUser = User::load($_SESSION['usersplusprofile']);
    }
}
?>
<!DOCTYPE html>
<html lang="en-us">
    <head>
        <meta charset="utf-8">
        <title>UsersPlus Development</title>
        <link rel="stylesheet" href="include/style.css" type="text/css" />
        <script src="include/usersplus.js" defer></script>
        <!--[if le IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script src="http://css3-mediaqueries-js.googlecode.com/files/css3-mediaqueries.js"></script>
        <![endif]-->
    </head>
    <body>
    <div id="user">
    <?php
        if($currentUser === null) {
    ?>
        <p><a href="login.php">Login</a> or <a href="register.php">Register</a></p>
    <?php
        } else {
    ?>
        <p>Welcome, <a href="profile.php"><?php echo $currentUser->name; ?></a>. <a href="logout.php">Logout</a>.</p>
    <?php
            if($currentUser->isAdmin()) {
    ?>
                <p>Go to <a href="admin.php">administrator panel</a>.</p>
    <?php
            }
        }
    ?>
    </div>