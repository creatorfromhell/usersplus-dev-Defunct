<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/7/14
 * Time: 11:02 AM
 * Version: Beta 1
 * Last Modified: 8/9/14 at 2:23 AM
 * Last Modified by Daniel Vidmar.
 */

class Group {
    public $id = null;
    public $name = null;
    public $admin = null;
    public $permissions = array();
    public $preset = null;

    public function hasPermission($id) {
        foreach($this->permissions as &$perm) {
            if($perm == $id) {
                return true;
            }
        }
        return false;
    }

    public function isAdmin() {
        return ($this->admin == 1) ? true : false;
    }

    public function save() {
        global $pdo, $prefix;
        $perm = implode(",", $this->permissions);
        $t = $prefix."_groups";
        $stmt = $pdo->prepare("UPDATE `".$t."` SET group_name = ?, group_permissions = ?, group_admin = ?, group_preset = ? WHERE id = ?");
        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $perm);
        $stmt->bindParam(3, $this->admin);
        $stmt->bindParam(4, $this->preset);
        $stmt->bindParam(5, $this->id);
        $stmt->execute();
    }

    public static function add($group) {
        if(!is_a($group, "Group")) { return; }
        global $pdo, $prefix;
        $t = $prefix."_groups";
        $perm = implode(",", $group->permissions);
        $stmt = $pdo->prepare("INSERT INTO `".$t."` (id, group_name, group_permissions, group_admin, group_preset) VALUES(?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $group->id);
        $stmt->bindParam(2, $group->name);
        $stmt->bindParam(3, $perm);
        $stmt->bindParam(4, $group->admin);
        $stmt->bindParam(5, $group->preset);
        $stmt->execute();
    }

    public static function load($id) {
        global $pdo, $prefix;
        $t = $prefix."_groups";
        $group = new Group();
        $stmt = $pdo->prepare("SELECT group_name, group_permissions, group_admin, group_preset FROM `".$t."` WHERE id = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $group->id = $id;
        $group->name = $result['group_name'];
        $group->permissions = explode(",", $result['group_permissions']);
        $group->admin = $result['group_admin'];
        $group->preset = $result['group_preset'];
        return $group;
    }

    public static function exists($name) {
        global $pdo, $prefix;
        $t = $prefix."_groups";
        $stmt = $pdo->prepare("SELECT id FROM `".$t."` WHERE group_name = ?");
        $stmt->bindParam(1, $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            return true;
        }
        return false;
    }

    public static function preset() {
        global $pdo, $prefix;
        $t = $prefix."_groups";
        $stmt = $pdo->prepare("SELECT id FROM `".$t."` WHERE group_preset = 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }

    public static function getName($id) {
        global $pdo, $prefix;
        $t = $prefix."_groups";
        $stmt = $pdo->prepare("SELECT group_name FROM `".$t."` WHERE id = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['group_name'];
    }

    public static function getAvailableID() {
        global $pdo, $prefix;
        $t = $prefix."_groups";
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `".$t."`");
        $stmt->execute();
        $id = $stmt->fetchColumn();
        $id++;
        return $id;
    }

    public static function delete($id) {
        global $pdo, $prefix;
        $t = $prefix."_groups";
        $stmt = $pdo->prepare("DELETE FROM `".$t."` WHERE id = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();
    }

    public static function validID($id) {
        global $pdo, $prefix;
        $t = $prefix."_groups";
        $stmt = $pdo->prepare("SELECT group_name FROM `".$t."` WHERE id = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            return true;
        }
        return false;
    }
}