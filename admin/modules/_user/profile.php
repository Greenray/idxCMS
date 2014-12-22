<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - PROFILES

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

# Check admin's rights
if (FILTER::get('REQUEST', 'login')) {
    if (CMS::call('USER')->checkUser(FILTER::get('REQUEST', 'username'), FILTER::get('REQUEST', 'password'), FALSE, $user_data) === TRUE) {
        $tmp = '';
        if (file_exists(TEMP.'rights.dat')) {
            $tmp = GetUnserialized(TEMP.'rights.dat');
            unlink(TEMP.'rights.dat');
        }
        if (!empty($tmp)) {
            $level = empty($tmp[3]) ? 1 : (int) $tmp[3];
            if (!empty($tmp[2])) {
                $rights = '*';
            } else {
                $rights = '';
                if (!empty($tmp[1])) {
                    $rights = implode(' ', $tmp[1]);
                }
            }
            USER::changeProfileField($tmp[0], 'rights', $rights);
            USER::changeProfileField($tmp[0], 'access', $level);
            ShowMessage('Rights changed');
        }
    } else {
        ShowMessage('Invalid password');
    }
}

if (!empty($REQUEST['act'])) {
    $action  = explode('.', $REQUEST['act'], 2);
    try {
        switch ($action[0]) {
            case 'profile':
                $edit = $action[1];
                break;
            case 'rights':
                $username = $action[1];
                break;
            case 'block':
                USER::changeProfileField($action[1], 'blocked', TRUE);
                break;
            case 'unblock':
                USER::changeProfileField($action[1], 'blocked', FALSE);
                break;
            case 'delete':
                unlink(USERS.basename($action[1]));
                unlink(PM_DATA.basename($action[1]));
                break;
        }
    } catch (Exception $error) {
        ShowMessage(__($error->getMessage()));
    }
}

if (FILTER::get('REQUEST', 'save')) {
    $username = FILTER::get('REQUEST', 'username');
    if (!empty($username)) {
        # Save user profile
        try {
            CMS::call('USER')->updateUser(
                $username,
                FILTER::get('REQUEST', 'nickname'),
                FILTER::get('REQUEST', 'fields'),
                TRUE
            );
            USER::changeProfileField($username, 'status', FILTER::get('REQUEST', 'status'));
            ShowMessage('Profile updated');
        } catch (Exception $error) {
            ShowMessage(__($error->getMessage()));
        }
    } else {
        $user = FILTER::get('REQUEST', 'user');
        if (!empty($user)) {
            # Save rights for user
            $rights = FILTER::get('REQUEST', 'rights');
            $level  = FILTER::get('REQUEST', 'level');
            file_put_contents(
                TEMP.'rights.dat',
                serialize(
                    array(
                        0 => $user,
                        1 => empty($rights) ? [] : $rights,
                        2 => FILTER::get('REQUEST', 'root'),
                        3 => empty($level) ? 1 : $level
                    )
                )
            );
            $message  = __('Identification');
            $message .= LoginForm();
            include(ADMIN.'error.php');
        }
    }
}

$search = FILTER::get('REQUEST', 'search');

if (!empty($edit) && ($userdata = USER::getUserData($edit))) {
    # Edit user profile
    $fields = FILTER::get('REQUEST', 'fields');
    $output = $userdata;
    $output['avatar']    = GetAvatar($userdata['username']);
    $output['regdate']   = FormatTime('d F Y', $userdata['regdate']);
    $output['lastvisit'] = FormatTime('d F Y', $userdata['lastvisit']);
    $output['utz'] = SelectTimeZone(
        'fields[tz]',
        $LANG['tz'],
        empty($fields['tz']) ? $userdata['tz'] : (int) $fields['tz']
    );
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'profile.tpl');
    echo $TPL->parse($output);

} elseif (!empty($username)) {
    # Edit user rights
    if (file_exists(TEMP.'rights.dat')) {
        unlink(TEMP.'rights.dat');
    }
    $rights = USER::getUserRights($username, $root, $user);
    if ($user !== FALSE) {
        $output = [];
        $output['user']   = $user['username'];
        $output['nick']   = $user['nickname'];
        $output['admin']  = $root;
        $output['access'] = $user['access'];
        $system_rights    = USER::getSystemRights();
        if (!$root) {
            foreach ($system_rights as $id => $desc) {
                $output['rights'][$id]['right'] = $id;
                $output['rights'][$id]['desc']  = $desc;
                if (array_key_exists($id, $rights)) {
                    $output['rights'][$id]['set'] = TRUE;
                }
            }
        }
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'rights.tpl');
        echo $TPL->parse($output);
    } else {
        ShowMessage('Invalid ID');
    }
} elseif (!empty($search)) {
    # Search and show user profile
    $output  = [];
    $users   = CMS::call('USER')->getUsersList($search);
    $count   = sizeof($users);
    $keys    = array_keys($users);
    $page    = (int) FILTER::get('REQUEST', 'page');
    $perpage = 20;
    $pagination = GetPagination($page, $perpage, $count);
    for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
        $output['user'][$i] = $users[$keys[$i]];
        if (!empty($users[$keys[$i]]['blocked'])) {
            $output['user'][$i]['blocked'] = 'unblock.'.$users[$keys[$i]]['username'];
            $output['user'][$i]['blocking'] = __('Unblock');
        } else {
            $output['user'][$i]['blocked'] = 'block.'.$users[$keys[$i]]['username'];
            $output['user'][$i]['blocking'] = __('Block');
        }
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'list.tpl');
    echo $TPL->parse($output);
    # Pagination
    if ($count > $perpage) {
        echo Pagination($count, $perpage, $page, MODULE.'admin&amp;id=_user.profile&amp;search='.$search);
    }
} else {
    $output['search'] = empty($search) ? '*' : $search;
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'search.tpl');
    echo $TPL->parse($output);
}
?>