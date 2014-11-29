<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE USER

if (!defined('idxCMS')) die();

$just_reg = FALSE;

if (!empty($REQUEST['save'])) {
    if (!USER::loggedIn()) {
        if (!empty($REQUEST['act'])) {
            if ($REQUEST['act'] === 'register') {
                # Registration of a new user
                try {
                    CheckCaptcha();
                    CMS::call('USER')->registerUser();
                    $just_reg = TRUE;
                    if (!empty($REQUEST['avatar']['name'])) {
                        $IMAGE = new IMAGE(
                            AVATARS,
                            CONFIG::getValue('avatar', 'size'),
                            CONFIG::getValue('avatar', 'width'),
                            CONFIG::getValue('avatar', 'height')
                        );
                        $IMAGE->upload($REQUEST['avatar']);
                        $IMAGE->generateIcon($REQUEST['user']);
                    }
                } catch (Exception $error) {
                    ShowError(__($error->getMessage()));
                }
            } else {
                if ($REQUEST['act'] === 'password_request') {
                    SYSTEM::set('pagename', 'Password recovery');
                    try {
                        CheckCaptcha();
                        $username = basename($REQUEST['name']);
                        $data = USER::getUserData($username);
                        if (!empty($data)) {
                            if (!empty($data['last_prr']) && ((int) $time <= ((int) $data['last_prr'] + (int) CONFIG::getValue('user', 'flood')))) {
                                CMS::call('LOG')->logError('Too many requests in limited period of time. Try later.');
                                Redirect('index');
                            }
                            if (FILTER::get('REQUEST', 'email') === $data['email']) {
                                $new_password = RandomString(8);
                                $siteurl = parse_url(SYSTEM::get('url'));
                                $time = time();
                                $data['last_prr'] = $time;
                                $data['password'] = md5($new_password);
                                CMS::call('USER')->saveUserData($username, $data);
                                if (!SendMail(
                                    $data['email'],
                                    'no_reply@'.$siteurl['host'],
                                    __('Password'),
                                    __('Your new password at').' '.$siteurl['host'],
                                    __('Your username at').' '.$siteurl['host'].': '.$username.LF.__('Your new password at').' '.$siteurl['host'].': '.$new_password
                                )) {
                                    $FEEDBACK = new MESSAGE(CONTENT, 'feedback');
                                    $FEEDBACK->sendFeedback(
                                        'Password request for '.$username.'. New password is '.$new_password.' Email was not sent.',
                                        '',
                                        $data['email']
                                    );
                                    unset($FEEDBACK);
                                    $message = 'Your request was sent to Administrator';
                                } else {
                                        $message = 'Your request was sent to your email';
                                }
                                unset($REQUEST);
                                ShowWindow(__('Password recovery'), __($message), 'center');
                            } else {
                                CMS::call('LOG')->logError('Error in email');
                            }
                        } else {
                            CMS::call('LOG')->logError('Error in username');
                        }
                    } catch (Exception $error) {
                        ShowError(__($error->getMessage()));
                    }
                }
            }
        }
    } else {
        if (USER::loggedIn()) {
            if (!empty($REQUEST['profile'])) {
                # Update profile
                SYSTEM::set('pagename', __('My profile'));
                if (!empty($REQUEST['current_password'])) {
                    if (md5($REQUEST['current_password']) === USER::getUser('password')) {
                        try {
                            CMS::call('USER')->updateUser(
                                USER::getUser('username'),
                                USER::getUser('nickname'),
                                $REQUEST['fields']
                            );
                            if (!empty($REQUEST['avatar']['name'])) {
                                $IMAGE = new IMAGE(
                                    AVATARS,
                                    CONFIG::getValue('avatar', 'size'),
                                    CONFIG::getValue('avatar', 'width'),
                                    CONFIG::getValue('avatar', 'height')
                                );
                                $IMAGE->upload($REQUEST['avatar']);
                                $IMAGE->generateIcon(USER::getUser('username'));
                            }
                        } catch (Exception $error) {
                            ShowError(__($error->getMessage()));
                        }
                    } else {
                        ShowError(__('Invalid password'));
                    }
                } else {
                    ShowError(__('Invalid password'));
                }
            }
        }
    }
}

# INTERFACE
if (!USER::loggedIn()) {
    if (!empty($REQUEST['act'])) {

        if (($REQUEST['act'] === 'register') && !$just_reg) {
            $user['user']     = (empty($REQUEST['user']) || ($REQUEST['user'] == __('Your login')))        ? '' : $REQUEST['user'];
            $user['nick']     = (empty($REQUEST['nick']) || ($REQUEST['nick'] == __('Your visible name'))) ? '' : $REQUEST['nick'];
            $user['avatar']   = GetAvatar($user['user']);
            $user['password'] = '';
            $user['confirm']  = '';
            $user['email']    = (empty($REQUEST['email']) || ($REQUEST['email'] == __('Enter your e-mail'))) ? '' : $REQUEST['email'];
            $tz = empty($REQUEST['fields']['tz']) ? (int) CONFIG::getValue('main', 'tz') : (int) $REQUEST['fields']['tz'];
            $user['utz']     = SelectTimeZone('fields[tz]', $LANG['tz'], $tz);
            $user['icq']     = empty($REQUEST['fields']['icq'])     ? '' : $REQUEST['fields']['icq'];
            $user['website'] = empty($REQUEST['fields']['website']) ? '' : $REQUEST['fields']['website'];
            $user['country'] = empty($REQUEST['fields']['country']) ? '' : $REQUEST['fields']['country'];
            $user['city']    = empty($REQUEST['fields']['city'])    ? '' : $REQUEST['fields']['city'];
            $user['captcha'] = ShowCaptcha();
            SYSTEM::set('pagename', __('Registration'));
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'registration.tpl');
            ShowWindow(__('Registration'), $TPL->parse($user));

        } elseif ($REQUEST['act'] === 'password_request') {
            SYSTEM::set('pagename', __('Password recovery'));
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'restore-password.tpl');
            ShowWindow(__('Password recovery'), $TPL->parse(array('captcha' => ShowCaptcha())));

        } elseif ($just_reg) {
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'greeting.tpl');
            ShowWindow(__('Greeting'), $TPL->parse());

        } else {
            Redirect('index');
        }
    } else {
        if (!empty($REQUEST['user'])) {
            Redirect('user&act=register');
        }
    }
} elseif (USER::loggedIn()) {
    if (!empty($REQUEST['user'])) {
        $user = USER::getUserData($REQUEST['user']);
        if (!empty($user)) {
            $user['avatar']    = GetAvatar($user['username']);
            $user['regdate']   = FormatTime('d F Y', $user['regdate']);
            $user['lastvisit'] = FormatTime('d F Y', $user['lastvisit']);

            if ($user['blocked'] == '0') {
                unset($user['blocked']);
            }
            unset($user['rights']);
            if (USER::getUser('username') !== $REQUEST['user']) {
                $user['allow_pm'] = TRUE;
            }
            SYSTEM::set('pagename',  __('User profile').': '.$user['nickname']);
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'view.tpl');
            ShowWindow(__('Profile').': '.$user['nickname'], $TPL->parse($user));
        } else {
            Redirect('index');
        }
    } else {
        $user = USER::getUser();
        $user['avatar']    = GetAvatar($user['username']);
        $user['regdate']   = empty($user['regdate'])   ? '-' : FormatTime('d F Y', $user['regdate']);
        $user['lastvisit'] = empty($user['lastvisit']) ? '-' : FormatTime('d F Y', $user['lastvisit']);
        $user['utz']       = SelectTimeZone('fields[tz]', $LANG['tz'], (int) $user['tz']);

        if ($user['rights'] !== '*') {
            $user_rights = USER::getUserRights();
            $user['rights'] = '';
            if (!empty($user_rights)) {
                foreach ($user_rights as $right => $right_desc) {
                    $user['rights'] .= $right_desc.'<br />';
                }
            } else {
                $user['rights'] = __('You are the registered user');
            }
        } else {
            $user['admin'] = TRUE;
        }
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'profile.tpl');
        SYSTEM::set('pagename', __('Profile'));
        ShowWindow(__('Profile'), $TPL->parse($user));
    }
}
