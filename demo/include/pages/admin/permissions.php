<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/7/14
 * Time: 6:53 PM
 * Version: Beta 1
 * Last Modified: 8/7/14 at 6:53 PM
 * Last Modified by Daniel Vidmar.
 */
include_once("include/handling/permission.php");
$pn = 1;
$editing = false;
if(isset($_GET['pn'])) {
    if($_GET['pn'] > 0) {
        $pn = $_GET['pn'];
    }
}
if(isset($_GET['action'])) {
    $action = cleanInput($_GET['action']);

    if($action == "edit" && isset($_GET['id']) && nodeValidID(cleanInput($_GET['id']))) {
        $editing = true;
    } else if($action == "delete" && isset($_GET['id']) && nodeValidID(cleanInput($_GET['id']))) {
        nodeDelete(cleanInput($_GET['id']));
    }
}
global $prefix;
$pagination = new Pagination($prefix."_nodes", "id, node_name, node_description", $pn, 10, "?page=permissions&");
?>
<form method="post" action="admin.php?page=permissions">
    <h3><?php echo ($editing) ? "Edit Node" : "Add Node"; ?></h3>
    <div id="form-holder">
        <?php
        if($editing) {
            $details = nodeDetails(cleanInput($_GET['id']));
            ?>
            <div id="page_1" class="form-page">
                <fieldset id="inputs">
                    <input id="id" name="id" type="hidden" value="<?php echo cleanInput($_GET['id']); ?>">
                    <input id="node" name="node" type="text" value="<?php echo $details['node_name']; ?>" placeholder="Node">
                    <textarea id="description" name="description" ROWS="3" COLS="40"><?php echo $details['node_description']; ?></textarea>
                    <?php
                    $captcha = new Captcha();
                    $captcha->printImage();
                    $_SESSION['userspluscaptcha'] = $captcha->code;
                    ?>
                    <br />
                    <input id="captcha" name="captcha" type="text" placeholder="Enter characters above">
                </fieldset>
                <fieldset id="links">
                    <input type="submit" class="submit" name="edit_permission" value="Edit">
                </fieldset>
            </div>
        <?php
        } else {
            ?>
            <div id="page_1" class="form-page">
                <fieldset id="inputs">
                    <input id="node" name="node" type="text" placeholder="Node">
                    <textarea id="description" name="description" ROWS="3" COLS="40"></textarea>
                    <?php
                    $captcha = new Captcha();
                    $captcha->printImage();
                    $_SESSION['userspluscaptcha'] = $captcha->code;
                    ?>
                    <br />
                    <input id="captcha" name="captcha" type="text" placeholder="Enter characters above">
                </fieldset>
                <fieldset id="links">
                    <input type="submit" class="submit" name="add_permission" value="Add">
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
        <th>Node</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
        $nodes = $pagination->paginateReturn();
        foreach($nodes as &$n) {
            echo "<tr>";
            echo "<td>".$n['node_name']."</td>";
            echo "<td>".$n['node_description']."</td>";
            echo "<td class='actions'>";
            echo "<a title='Edit' class='actionEdit' href='?page=permissions&action=edit&id=".$n['id']."'></a>";
            echo "<a title='Delete' class='actionDelete' onclick='return confirm(\"Are you sure you want to delete node ".$n['node_name']."?\");' href='?page=permissions&action=delete&id=".$n['id']."'></a>";
            echo "</td>";
            echo "</tr>";
        }
    ?>
    </tbody>
</table>