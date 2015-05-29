<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/18/14
 * Time: 1:52 PM
 * Version: Beta 1
 * Last Modified: 8/18/14 at 1:52 PM
 * Last Modified by Daniel Vidmar.
 */
if(!isset($_GET['name'])) {
    die("Invalid parameters!");
}
$email = (!validEmail($_GET['name'])) ? false : true;
if(!User::exists(cleanInput($_GET['name']), $email)) {
    die("Invalid parameters!");
}

$user = User::load(cleanInput($_GET['name']), $email);
$user->activationKey = generateSessionID(40);
$user->save();
$user->sendActivation();
?>
<p>You've been sent a new activation key to your email.</p>