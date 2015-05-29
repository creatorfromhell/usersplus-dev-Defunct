<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/7/14
 * Time: 6:53 PM
 * Version: Beta 1
 * Last Modified: 8/7/14 at 6:53 PM
 * Last Modified by Daniel Vidmar.
 */
include_once("include/handling/group.php");
$editing = false;
$subPage = "all";
if(isset($_GET['sub'])) {
    $subPage = $_GET['sub'];
}
if(isset($_GET['action'])) {
    $action = cleanInput($_GET['action']);

    if($action == "edit" && isset($_GET['id']) && Group::exists(Group::getName(cleanInput($_GET['id'])))) {
        $editing = true;
    } else if($action == "delete" && isset($_GET['id']) && Group::exists(Group::getName(cleanInput($_GET['id'])))) {
        Group::delete(cleanInput($_GET['id']));
    }
}
if($subPage == "group" && isset($_GET['name']) && Group::exists($_GET['name'])) {
    ?>
    <div id="group-view">

    </div>
<?php
} else {
    $pn = 1;
    if(isset($_GET['pn'])) {
        if($_GET['pn'] > 0) {
            $pn = $_GET['pn'];
        }
    }
    global $prefix;
    $pagination = new Pagination($prefix."_groups", "id, group_name, group_admin", $pn, 10, "?page=groups&");
?>
<form method="post" action="admin.php?page=groups">
    <h3><?php echo ($editing) ? "Edit Group" : "Add Group"; ?></h3>
    <div id="form-holder">
        <?php
        if($editing) {
            $group = Group::load($_GET['id']);
        ?>
            <div id="page_1" class="form-page">
                <fieldset id="inputs">
                    <input id="id" name="id" type="hidden" value="<?php echo $group->id; ?>">
                    <input id="name" name="name" type="text" value="<?php echo $group->name; ?>" placeholder="Name">
                    <label for="admin">Admin: </label>
                    <select name="admin" id="admin">
                        <option value="0" <?php if(!$group->isAdmin()) echo "selected"; ?>>No</option>
                        <option value="1" <?php if($group->isAdmin()) echo "selected"; ?>>Yes</option>
                    </select><br />
                    <label for="preset">Preset: </label>
                    <select name="preset" id="preset">
                        <option value="0" <?php if($group->preset == 0) echo "selected"; ?>>No</option>
                        <option value="1" <?php if($group->preset == 1) echo "selected"; ?>>Yes</option>
                    </select>
                </fieldset>
                <fieldset id="links">
                    <button class="submit" onclick="switchPage(event, 'page_1', 'page_2'); return false;">Next</button>
                </fieldset>
            </div>
            <div id="page_2" class="form-page">
                <fieldset id="inputs">
                    <div class="pick-field">
                        <div class="title">Permissions</div>
                        <div class="column-titles">
                            <label class="fmleft">Available</label>
                            <label class="fmright">Added</label>
                            <div class="clear"></div>
                        </div>
                        <?php $added = array(); ?>
                        <div id="permissions-available" class="column-left" ondrop="onDrop(event, 'permissions-value', 'remove')" ondragover="onDragOver(event)">
                            <?php
                            global $prefix, $pdo;
                            $t = $prefix."_nodes";
                            $stmt = $pdo->prepare("SELECT id, node_name FROM `".$t."`");
                            $stmt->execute();
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                if(in_array($row['id'], $group->permissions)) {
                                    $added[] = '<div id="node-'.$row['id'].'" class="draggable-node" draggable="true" ondragstart="onDrag(event)">'.$row['node_name'].'</div>';
                                } else {
                                    echo '<div id="node-'.$row['id'].'" class="draggable-node" draggable="true" ondragstart="onDrag(event)">'.$row['node_name'].'</div>';
                                }
                            }
                            ?>
                        </div>
                        <div id="permissions-added" class="column-right" ondrop="onDrop(event, 'permissions-value', 'add')" ondragover="onDragOver(event)">
                            <?php
                                foreach($added as &$a) {
                                    echo $a;
                                }
                            ?>
                        </div>
                        <input id="permissions-value" name="permissions-value" type="hidden" value="<?php echo implode(",", $group->permissions); ?>" >
                    </div>
                    <?php
                    $captcha = new Captcha();
                    $captcha->printImage();
                    $_SESSION['userspluscaptcha'] = $captcha->code;
                    ?>
                    <br />
                    <input id="captcha" name="captcha" type="text" placeholder="Enter characters above">
                </fieldset>
                <fieldset id="links">
                    <button class="submit-2" onclick="switchPage(event, 'page_2', 'page_1'); return false;">Back</button>
                    <input type="submit" class="submit" name="edit_group" value="Edit">
                </fieldset>
            </div>
        <?php
        } else {
        ?>
            <div id="page_1" class="form-page">
                <fieldset id="inputs">
                    <input id="name" name="name" type="text" placeholder="Name">
                    <label for="admin">Admin: </label>
                    <select name="admin" id="admin">
                        <option value="0" selected>No</option>
                        <option value="1">Yes</option>
                    </select><br />
                    <label for="preset">Preset: </label>
                    <select name="preset" id="preset">
                        <option value="0" selected>No</option>
                        <option value="1">Yes</option>
                    </select>
                </fieldset>
                <fieldset id="links">
                    <button class="submit" onclick="switchPage(event, 'page_1', 'page_2'); return false;">Next</button>
                </fieldset>
            </div>
            <div id="page_2" class="form-page">
                <fieldset id="inputs">
                    <div class="pick-field">
                        <div class="title">Permissions</div>
                        <div class="column-titles">
                            <label class="left">Available</label>
                            <label class="right">Added</label>
                            <div class="clear"></div>
                        </div>
                        <div id="permissions-available" class="column-left" ondrop="onDrop(event, 'permissions-value', 'remove')" ondragover="onDragOver(event)">
                            <?php
                                global $prefix, $pdo;
                                $t = $prefix."_nodes";
                                $stmt = $pdo->prepare("SELECT id, node_name FROM `".$t."`");
                                $stmt->execute();
                                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<div id="node-'.$row['id'].'" class="draggable-node" draggable="true" ondragstart="onDrag(event)">'.$row['node_name'].'</div>';
                                }
                            ?>
                        </div>
                        <div id="permissions-added" class="column-right" ondrop="onDrop(event, 'permissions-value', 'add')" ondragover="onDragOver(event)">

                        </div>
                        <input id="permissions-value" name="permissions-value" type="hidden" value="">
                    </div>
                    <?php
                    $captcha = new Captcha();
                    $captcha->printImage();
                    $_SESSION['userspluscaptcha'] = $captcha->code;
                    ?>
                    <br />
                    <input id="captcha" name="captcha" type="text" placeholder="Enter characters above">
                </fieldset>
                <fieldset id="links">
                    <button class="submit-2" onclick="switchPage(event, 'page_2', 'page_1'); return false;">Back</button>
                    <input type="submit" class="submit" name="add_group" value="Add">
                </fieldset>
            </div>
        <?php
        }
        ?>
    </div>
</form>
<?php
    echo $pagination->pageString;
?>
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Admin</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
        $groups = $pagination->paginateReturn();
        foreach($groups as &$g) {
            $a = ($g['group_admin'] == '1') ? "Yes" : "No";
            echo "<tr>";
            echo "<td>".$g['group_name']."</td>";
            echo "<td>".$a."</td>";
            echo "<td class='actions'>";
            echo "<a title='Edit' class='actionEdit' href='?page=groups&action=edit&id=".$g['id']."'></a>";
            echo "<a title='Delete' class='actionDelete' onclick='return confirm(\"Are you sure you want to delete group ".$g['group_name']."?\");' href='?page=groups&action=delete&id=".$g['id']."'></a>";
            echo "</td>";
            echo "</tr>";
        }
    ?>
    </tbody>
</table>
<?php
}
?>