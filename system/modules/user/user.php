<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module USER

if (!defined('idxCMS')) die();

$just_reg = FALSE;

if (!empty($REQUEST['save'])) {
    if (!USER::$logged_in) {
        if (!empty($REQUEST['act'])) {
            if ($REQUEST['act'] === 'register') {
                #
                # Registration of a new user
                #
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
                    SYSTEM::showError($error->getMessage());
                }
            } else {
                if ($REQUEST['act'] === 'password_request') {
                    SYSTEM::set('pagename', 'Password recovery');
                    try {
                        $username = basename($REQUEST['user']);
                        $user     = USER::getUserData($username);
                        if (!empty($user)) {
                            if (!empty($user['last_prr']) && ($time <= ($user['last_prr'] + CONFIG::getValue('user', 'flood')))) {
                                CMS::call('LOG')->logError('Too many requests in limited period of time. Try later.');
                                Redirect('index');
                            }
                            if ($REQUEST['email'] === $user['email']) {
                                $new_password = RandomString(8);
                                $siteurl = parse_url(SYSTEM::get('url'));
                                $time = time();
                                $user['last_prr'] = $time;
                                $user['password'] = md5($new_password);
                                CMS::call('USER')->saveUserData($username, $user);
/*                                if (!SendMail(
                                    $user['email'],
                                    'no_reply@'.$siteurl['host'],
                                    __('Password'),
                                    __('Your new password at').' '.$siteurl['host'],
                                    __('Your username at').' '.$siteurl['host'].': '.$username.LF.__('Your new password at').' '.$siteurl['host'].': '.$new_password
                                )) {*/
                                    $FEEDBACK = new MESSAGE(CONTENT, 'feedback');
                                    $FEEDBACK->sendFeedback(
                                        'Password request for '.$username.'. New password is '.$new_password.' Email was not sent.',
                                        '',
                                        $user['email']
                                    );
                                    unset($FEEDBACK);
                                    SYSTEM::showMessage('Your request was sent to Administrator');
//                                } else {
//                                    SYSTEM::showMessage('Your request was sent to your email');
//                                }

                            } else SYSTEM::showError('Error in email');

                        } else SYSTEM::showError('Error in username');

                    } catch (Exception $error) {
                        SYSTEM::showError($error->getMessage());
                    }
                }
                unset($REQUEST);
            }
        }
    } else {
        if (USER::$logged_in) {
            if (!empty($REQUEST['profile'])) {
                #
                # Update profile
                #
                SYSTEM::set('pagename', __('My profile'));
                if (!empty($REQUEST['current_password'])) {
                    if (md5($REQUEST['current_password']) === USER::getUser('password')) {
                        try {
                            CMS::call('USER')->updateUser(USER::getUser('user'), USER::getUser('nick'), $REQUEST['fields']);
                            if (!empty($REQUEST['avatar']['name'])) {
                                $IMAGE = new IMAGE(
                                    AVATARS,
                                    CONFIG::getValue('avatar', 'size'),
                                    CONFIG::getValue('avatar', 'width'),
                                    CONFIG::getValue('avatar', 'height')
                                );
                                $IMAGE->upload($REQUEST['avatar']);
                                $IMAGE->generateIcon(USER::getUser('user'));
                            }
                        } catch (Exception $error) {
                            SYSTEM::showError($error->getMessage());
                        }
                    } else SYSTEM::showError('Invalid password');
                } else SYSTEM::showError('Invalid password');
            }
        }
    }
}

if (!USER::$logged_in) {
    if (!empty($REQUEST['act'])) {

        if (($REQUEST['act'] === 'register') && !$just_reg) {
            $user['user']     = (empty($REQUEST['user']) || ($REQUEST['user'] == __('Your login')))        ? '' : $REQUEST['user'];
            $user['nick']     = (empty($REQUEST['nick']) || ($REQUEST['nick'] == __('Your visible name'))) ? '' : $REQUEST['nick'];
            $user['avatar']   = GetAvatar($user['user']);
            $user['password'] = '';
            $user['confirm']  = '';
            $user['email']    = (empty($REQUEST['email']) || ($REQUEST['email'] == __('Enter your e-mail'))) ? '' : $REQUEST['email'];
            $tz = empty($REQUEST['fields']['tz']) ? CONFIG::getValue('main', 'tz') : $REQUEST['fields']['tz'];
            $user['utz']     = SelectTimeZone('fields[tz]', $LANG['tz'], $tz);
            $user['website'] = empty($REQUEST['fields']['website']) ? '' : $REQUEST['fields']['website'];
            $user['country'] = empty($REQUEST['fields']['country']) ? '' : $REQUEST['fields']['country'];
            $user['city']    = empty($REQUEST['fields']['city'])    ? '' : $REQUEST['fields']['city'];
            $user['captcha'] = ShowCaptcha();
            SYSTEM::set('pagename', __('Registration'));
            $TPL = new TEMPLATE(__DIR__.DS.'registration.tpl');
            $TPL->set($user);
            SYSTEM::defineWindow('Registration', $TPL->parse());

        } elseif ($REQUEST['act'] === 'password_request') {
            SYSTEM::set('pagename', __('Password recovery'));
            $TPL = new TEMPLATE(__DIR__.DS.'panel.tpl');
            $TPL->set('captcha', ShowCaptcha());
            SYSTEM::defineWindow('Password recovery', $TPL->parse());

        } elseif ($just_reg) {
            $TPL = new TEMPLATE(__DIR__.DS.'greeting.tpl');
            SYSTEM::defineWindow('Greeting', $TPL->parse());

        } else Redirect('index');

    } elseif (!empty($REQUEST['user'])) {
         Redirect('user&amp;act=register');
    } else {
        if (!empty($REQUEST['user'])) Redirect('user&amp;act=register');
    }
} else {
    if (!empty($REQUEST['user'])) {
        $user = USER::getUserData($REQUEST['user']);
        if (!empty($user)) {
            $user['avatar']    = GetAvatar($user['user']);
            $user['regdate']   = FormatTime('d F Y', $user['regdate']);
            $user['lastvisit'] = FormatTime('d F Y', $user['lastvisit']);

            if ($user['blocked'] === 0) {
                unset($user['blocked']);
            }
            unset($user['rights']);

            if (USER::getUser('user') !== $REQUEST['user']) {
                $user['allow_pm'] = TRUE;
            }
            SYSTEM::set('pagename',  __('User profile').': '.$user['nick']);

            $TPL = new TEMPLATE(__DIR__.DS.'view.tpl');
            $TPL->set($user);
            SYSTEM::defineWindow('Profile'.': '.$user['nick'], $TPL->parse());
        } else {
            Redirect('index');
        }
    } else {
        $user = USER::getUser();
        $user['avatar']    = GetAvatar($user['user']);
        $user['regdate']   = FormatTime('d F Y', $user['regdate']);
        $user['lastvisit'] = FormatTime('d F Y', $user['lastvisit']);
        $user['utz']       = SelectTimeZone('fields[tz]', $LANG['tz'], $user['tz']);
        $user['website']   = empty($REQUEST['fields']['website']) ? $user['website'] : $REQUEST['fields']['website'];
        $user['country']   = empty($REQUEST['fields']['country']) ? $user['country'] : $REQUEST['fields']['country'];
        $user['city']      = empty($REQUEST['fields']['city'])    ? $user['city']    : $REQUEST['fields']['city'];
        if ($user['rights'] !== '*') {
            $user_rights = USER::getUserRights();
            $user['rights'] = '';
            if (!empty($user_rights)) {
                foreach ($user_rights as $right => $right_desc) {
                    $user['rights'] .= $right_desc.'<br />';
                }
            } else $user['rights'] = __('You are the registered user');
        } else {
            $user['admin'] = TRUE;
        }

        $TPL = new TEMPLATE(__DIR__.DS.'profile.tpl');
        $TPL->set($user);
        SYSTEM::set('pagename', __('Profile'));
        SYSTEM::defineWindow('Profile', $TPL->parse());
    }
}
