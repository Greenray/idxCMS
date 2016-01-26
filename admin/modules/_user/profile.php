<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Administration: User profile.

if (!defined('idxADMIN') || !USER::$root) die();
#
# Check admin's rights
#
if (!empty($REQUEST['login'])) {
    try {
        $fields = [];
        CMS::call('USER')->checkUser($REQUEST['user'], $REQUEST['password'], FALSE, $fields);
        if (file_exists(TEMP.'rights.dat')) {
            $tmp = json_decode(file_get_contents(TEMP.'rights.dat'), TRUE);
            if (!empty($tmp[2])) {
                $rights = '*';
                $access = 9;
            } else {
                $rights = '';
                $access = ($tmp[3] == 9) ? 1 : empty($tmp[3]) ? 1 : $tmp[3];
                if (!empty($tmp[1])) $rights = implode(' ', $tmp[1]);
            }
            USER::changeProfileField($tmp[0], 'rights', $rights);
            USER::changeProfileField($tmp[0], 'access', $access);
            ShowMessage('Rights changed', '', MODULE.'admin&id=_user.profile&act=rights.'.$tmp[0]);
            unlink(TEMP.'rights.dat');
        }
    } catch (Exception $error) {
        ShowError($error->getMessage());
    }

} elseif (!empty($REQUEST['act'])) {
    $action  = explode('.', $REQUEST['act'], 2);

    try {
        switch ($action[0]) {
            case 'search':
                #
                # Search and show list of users
                #
                $output  = [];
                $users   = CMS::call('USER')->getUsersList($REQUEST['search']);
                if (!empty($users)) {
                    $count   = sizeof($users);
                    $keys    = array_keys($users);
                    $page    = empty($REQUEST['page']) ? 0 : $REQUEST['page'];
                    $perpage = 20;
                    $pagination = GetPagination($page, $perpage, $count);
                    for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                        if (!empty($users[$keys[$i]])) {
                            $output['users'][$i] = $users[$keys[$i]];
                            if (!empty($users[$keys[$i]]['blocked'])) {
                                $output['users'][$i]['blocked'] = 'unblock.'.$users[$keys[$i]]['user'];
                                $output['users'][$i]['blocking'] = __('Unblock');
                            } else {
                                $output['users'][$i]['blocked'] = 'block.'.$users[$keys[$i]]['user'];
                                $output['users'][$i]['blocking'] = __('Block');
                            }
                        }
                    }

                    $TPL = new TEMPLATE(__DIR__.DS.'list.tpl');
                    $TPL->set($output);
                    echo $TPL->parse();
                    #
                    # Pagination
                    #
                    if ($count > $perpage) echo Pagination($count, $perpage, $page, MODULE.'admin&id=_user.profile&search='.$REQUEST['search']);
                } else {
                    ShowMessage('Nothing founded', '', 'admin&id=_user.profile');
                }
                break;

            case 'profile':
                if (!empty($action[1]) && $action[1] === 'save') {
                    #
                    # Save user profile
                    #
                    try {
                        CMS::call('USER')->updateUser($REQUEST['user'], $REQUEST['nick'], $REQUEST['fields']);
                        USER::changeProfileField($REQUEST['user'], 'status', $REQUEST['status']);
                    } catch (Exception $error) {
                        ShowError($error->getMessage());
                    }
                } else {
                    $user = USER::getUserData($action[1]);
                    #
                    # Edit user profile
                    #
                    $user['avatar']    = GetAvatar($user['user']);
                    $user['regdate']   = FormatTime('d F Y', $user['regdate']);
                    $user['lastvisit'] = FormatTime('d F Y', $user['lastvisit']);
                    $output = $user;
                    $output['utz'] = SelectTimeZone('fields[tz]', $LANG['tz'], $user['tz']);

                    $TPL = new TEMPLATE(__DIR__.DS.'profile.tpl');
                    $TPL->set($output);
                    echo $TPL->parse();
                }
                break;

            case 'rights':
                if (!empty($action[1]) && $action[1] === 'save') {
                    #
                    # Save rights for user
                    #
                    file_put_contents(
                        TEMP.'rights.dat',
                        json_encode([
                            0 => $REQUEST['user'],
                            1 => empty($REQUEST['rights']) ? []    : $REQUEST['rights'],
                            2 => empty($REQUEST['root'])   ? FALSE : $REQUEST['root'],
                            3 => empty($REQUEST['access']) ? 1     : (int) $REQUEST['access']
                        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                    );
                    $TPL = new TEMPLATE(TEMPLATES.'login.tpl');
                    $TPL->set('locale', SYSTEM::get('locale'));
                    echo $TPL->parse();
                } else {
                    #
                    # Edit user rights
                    #
                    if (file_exists(TEMP.'rights.dat')) {
                        unlink(TEMP.'rights.dat');
                    }
                    $rights = USER::getUserRights($action[1], $root, $user);
                    if (!empty($user)) {
                        $output = [];
                        $output['user']   = $user['user'];
                        $output['nick']   = $user['nick'];
                        $output['root']   = $root;
                        $output['access'] = $user['access'];
                        $system_rights    = USER::$system_rights;
                        if (!$root) {
                            foreach ($system_rights as $id => $desc) {
                                $output['rights'][$id]['right'] = $id;
                                $output['rights'][$id]['desc']  = $desc;
                                if (array_key_exists($id, $rights)) {
                                    $output['rights'][$id]['set'] = TRUE;
                                }
                            }
                        }

                        $TPL = new TEMPLATE(__DIR__.DS.'rights.tpl');
                        $TPL->set($output);
                        echo $TPL->parse();
                    }
                }
                break;

            case 'block':
                USER::changeProfileField($action[1], 'blocked', 1);
                break;

            case 'unblock':
                USER::changeProfileField($action[1], 'blocked', 0);
                break;

            case 'delete':
                unlink(USERS.basename($action[1]));
                unlink(PM_DATA.basename($action[1]));
                break;
        }
    } catch (Exception $error) {
        ShowError($error->getMessage());
    }
} else {
    $TPL = new TEMPLATE(__DIR__.DS.'search.tpl');
    echo $TPL->parse();
}
