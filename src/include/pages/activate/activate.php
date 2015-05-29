<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/18/14
 * Time: 1:52 PM
 * Version: Beta 1
 * Last Modified: 8/18/14 at 1:52 PM
 * Last Modified by Daniel Vidmar.
 */
if(!isset($_GET['name']) || !isset($_GET['key'])) {
    die("Invalid parameters!");
}
$email = (!validEmail($_GET['name'])) ? false : true;
if(!User::exists(cleanInput($_GET['name']), $email)) {
    die("Invalid parameters!");
}
$user = User::load(cleanInput($_GET['name']), $email);
if($user->activationKey != $_GET['key']) {
    die("Invalid parameters!");
}

$user->activationKey = "";
$user->activated = 1;
$user->save();
?>
<p>Your account has been activated!</p>