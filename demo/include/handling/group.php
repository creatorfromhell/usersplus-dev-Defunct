<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/23/14
 * Time: 12:12 PM
 * Version: Beta 1
 * Last Modified: 8/23/14 at 12:12 PM
 * Last Modified by Daniel Vidmar.
 */

//name, admin, preset, permissions-value, captcha
if(isset($_POST['add_group'])) {
    if(isset($_POST['name']) && trim($_POST['name']) != '') {
        if(isset($_POST['admin']) && trim($_POST['admin']) != '') {
            if(isset($_POST['preset']) && trim($_POST['preset']) != '') {
                if(isset($_POST['captcha']) && trim($_POST['captcha']) != '' && checkCaptcha(cleanInput($_POST['captcha']))) {
                    $group = new Group();
                    $group->name = cleanInput($_POST['name']);
                    $group->admin = (cleanInput($_POST['admin']) == '1') ? true : false;
                    $group->preset = (cleanInput($_POST['preset']) == '1') ? true : false;
                    $group->permissions = explode(",", cleanInput($_POST['permissions-value']));
                    Group::add($group);
                    destroySession("userspluscaptcha");
                } else {
                    die("Invalid captcha entered.");
                }
            } else {
                die("Invalid preset value entered.");
            }
        } else {
            die("Invalid admin value entered.");
        }
    } else {
        die("Invalid name entered.");
    }
}

if(isset($_POST['edit_group'])) {
    if(isset($_POST['id']) && trim($_POST['id']) != '' && Group::validID(cleanInput($_POST['id']))) {
        if(isset($_POST['name']) && trim($_POST['name']) != '') {
            if(isset($_POST['admin']) && trim($_POST['admin']) != '') {
                if(isset($_POST['preset']) && trim($_POST['preset']) != '') {
                    if(isset($_POST['captcha']) && trim($_POST['captcha']) != '' && checkCaptcha(cleanInput($_POST['captcha']))) {
                        if($_POST['preset'] == '1') {
                            $old = Group::load(Group::preset());
                            $old->preset = 0;
                            $old->save();
                        }
                        $group = Group::load(cleanInput($_POST['id']));
                        $group->name = cleanInput($_POST['name']);
                        $group->admin = (cleanInput($_POST['admin']) == '1') ? true : false;
                        $group->preset = (cleanInput($_POST['preset']) == '1') ? true : false;
                        $group->permissions = explode(",", cleanInput($_POST['permissions-value']));
                        $group->save();
                        destroySession("userspluscaptcha");
                    } else {
                        die("Invalid captcha entered.");
                    }
                } else {
                    die("Invalid preset value entered.");
                }
            } else {
                die("Invalid admin value entered.");
            }
        } else {
            die("Invalid name entered.");
        }
    } else {
        die("Invalid id entered.");
    }
}