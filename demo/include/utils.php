<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/6/14
 * Time: 9:42 AM
 * Version: Beta 1
 * Last Modified: 8/9/14 at 3:29 AM
 * Last Modified by Daniel Vidmar.
 */

/*
 * Miscellaneous Functions
 */
function cleanInput($input) {
    return strip_tags(trim($input));
}

function validUsername($value) {
    return preg_match('/^[a-zA-Z0-9_.-]+$/i', $value);
}

function validEmail($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL);
}

function checkCaptcha($value) {
    if($value === null) { return false; }
    if(!isset($_SESSION['userspluscaptcha'])) {
        return false;
    }
    return $_SESSION['userspluscaptcha'] == $value;
}

/*
 * User Functions
 */
function isAdmin() {
    if(isset($_SESSION['usersplusprofile']) && User::exists($_SESSION['usersplusprofile']) && User::load($_SESSION['usersplusprofile'])->isAdmin()) { return true; }
    return false;
}

/*
 * Page Functions
 */
function pageLocked($user, $node = "", $guest = false, $admin = false, $group = "", $useGroup = false, $name = "", $useName = false) {
    if($useGroup) { return pageLockedGroup($user, $group); }
    if($useName) { return pageLockedUser($user, $name); }
    if($admin) { return pageLockedAdmin($user); }
    return pageLockedNode($user, $node, $guest);
}

function pageLockedNode($user, $node, $guest = false) {
    if($guest) { return false; }
    if($user === null) { return true; }
    if(!is_a($user, "User")) { return true; }
    if($user->isAdmin()) { return false; }
    if(!nodeExists($node)) { return true; }
    if($user->hasPermission(nodeID($node))) { return false; }
    if($user->group->hasPermission(nodeID($node))) { return false; }
    return true;
}

function pageLockedAdmin($user) {
    if($user === null) { return true; }
    if(!is_a($user, "User")) { return true; }
    if($user->isAdmin()) { return false; }
    return true;
}

function pageLockedGroup($user, $group) {
    if($user === null) { return true; }
    if(!is_a($user, "User")) { return true; }
    if($user->isAdmin()) { return false; }
    if($user->group->id == $group) { return false; }
    return true;
}

function pageLockedUser($user, $name) {
    if($user === null) { return true; }
    if(!is_a($user, "User")) { return true; }
    if($user->isAdmin()) { return false; }
    if($user->name == $name) { return false; }
    return true;
}

/*
 * Permission Functions
 */
function nodeID($node) {
    global $pdo, $prefix;
    $t = $prefix."_nodes";
    $stmt = $pdo->prepare("SELECT id FROM `".$t."` WHERE node_name = ?");
    $stmt->bindParam(1, $node);
    $stmt->execute();
    $return = $stmt->fetch(PDO::FETCH_ASSOC);
    return $return['id'];
}

function nodeName($id) {
    global $pdo, $prefix;
    $t = $prefix."_nodes";
    $stmt = $pdo->prepare("SELECT node_name FROM `".$t."` WHERE id = ?");
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $return = $stmt->fetch(PDO::FETCH_ASSOC);
    return $return['node_name'];
}

function nodeDetails($id) {
    global $pdo, $prefix;
    $t = $prefix."_nodes";
    $stmt = $pdo->prepare("SELECT node_name, node_description FROM `".$t."` WHERE id = ?");
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function nodeValidID($id) {
    global $pdo, $prefix;
    $t = $prefix."_nodes";
    $stmt = $pdo->prepare("SELECT node_name FROM `".$t."` WHERE id = ?");
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result) {
        return true;
    }
    return false;
}

function nodeExists($node) {
    global $pdo, $prefix;
    $t = $prefix."_nodes";
    $stmt = $pdo->prepare("SELECT id FROM `".$t."` WHERE node_name = ?");
    $stmt->bindParam(1, $node);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result) {
        return true;
    }
    return false;
}

function nodeAdd($node, $description) {
    global $pdo, $prefix;
    $t = $prefix."_nodes";
    $stmt = $pdo->prepare("INSERT INTO `".$t."` (id, node_name, node_description) VALUES('', ?, ?)");
    $stmt->bindParam(1, $node);
    $stmt->bindParam(2, $description);
    $stmt->execute();
}

function nodeEdit($id, $node, $description) {
    global $pdo, $prefix;
    $t = $prefix."_nodes";
    $stmt = $pdo->prepare("UPDATE `".$t."` SET node_name = ?, node_description = ? WHERE id = ?");
    $stmt->bindParam(1, $node);
    $stmt->bindParam(2, $description);
    $stmt->bindParam(3, $id);
    $stmt->execute();
}

function nodeDelete($id) {
    global $pdo, $prefix;
    $t = $prefix."_nodes";
    $stmt = $pdo->prepare("DELETE FROM `".$t."` WHERE id = ?");
    $stmt->bindParam(1, $id);
    $stmt->execute();
}

/*
 * Session Functions
 */

function checkSession($identifier) {
    if($identifier === null) { return; }
    return isset($_SESSION[$identifier]);
}

function destroySession($identifier) {
    if($identifier === null) { return; }
    if(isset($_SESSION[$identifier])) {
        unset($_SESSION[$identifier]);
    }
}

function destroyEntireSession() {
    session_destroy();
}

/*
 * Hashing/Generation Functions
 */
function generateSalt($length = 25) {
    return substr(md5(generateUUID()), 0, $length);
}

function generateHash($value, $useSalt = false, $salt = "") {
    if($useSalt) {
        if(trim($salt) != "" && strlen(trim($salt)) == 25) {
            return hash('sha256', $salt.$value);
        }
    }
    return hash('sha256', $value);
}

function checkHash($hash, $value) {
    return $hash == hash('sha256', $value);
}

//Thanks to this comment: http://php.net/manual/en/function.uniqid.php#94959
function generateUUID() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function generateSessionID($length = 35) {
    return substr(md5(generateSalt(30).generateUUID()), 0, $length);
}
?>