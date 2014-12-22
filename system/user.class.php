<?php
/**
 * @file      system/user.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2014 Victor Nabatov
 * @license   <http://creativecommons.org/licenses/by-nc-sa/3.0/> Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Core
 */

/** Class USER - Users and their profiles. */

class USER {

    /** User profile fields.
     * @param array
     */
    private static $user_fields = [
        'username', 'nickname', 'password', 'email', 'tz', 'access', 'rights', 'status', 'stars', 'regdate', 'visits',
        'lastvisit','posts', 'comments', 'topics', 'replies', 'blocked', 'icq', 'website', 'country', 'city', 'last_prr'
    ];

    /** Disallowed names for registration.
     * @param array
     */
    private static $disallowed_names = [
        'administrator', 'false', 'guest', 'idxcms', 'moderator', 'noavatar', 'null', 'root',
        'superuser', 'supervisor', 'sponsor', 'system', 'test', 'true', 'unknown', 'user'
    ];

    /** User`s profile.
     * @param array
     */
    private static $user = [];

    /** Is user logged in?
     * @param boolean
     */
    private static $logged_in = FALSE;

    /** Cookie with user name.
     * @param string
     */
    private static $cookie_user = '';

    /** Cookie with user nick.
     * @param string
     */
    private static $cookie_nick = '';

    /** System rights.
     * @param array
     */
    private static $system_rights = [];

    /** Is user admin?
     * @param boolean
     */
    private static $root = FALSE;

    /** Class initialization */
    public function __construct() {
        # Set default guest userdata
        self::$user = [
            'username' => 'guest',
            'nickname' => __('Guest'),
            'status'   => 'Passer-by',
            'rights'   => '',
            'tz'       => CONFIG::getValue('main', 'tz'),
            'access'   => 0
        ];
        self::$cookie_user = CONFIG::getValue('main', 'cookie').'_user';
        self::$cookie_nick = CONFIG::getValue('main', 'cookie').'_nick';
    }

    # Initialize user and load his profile.
    /**
    * @todo Comment
    * @return 
    */
    public function initUser() {
        # If user cookie is not present...
        $cookie_user = FILTER::get('COOKIE', self::$cookie_user);
        if (empty($cookie_user) || empty($_SESSION['user'])) {
            self::$logged_in = FALSE;
            return TRUE;
        }
        if ($cookie_user !== $_SESSION['user']) {
            return self::clearCookie();
        }
        # Now we must validate user's data.
        if (!$this->checkUser($cookie_user, $_SESSION['pass'], TRUE, self::$user)) {
            self::$logged_in = FALSE;
            return self::clearCookie();
        }
        $userdata = self::getUserData($cookie_user);
        if (empty($userdata)) {
            self::$logged_in = FALSE;
            return self::clearCookie();
        }
        $this->loginSuccess($userdata);
        return TRUE;
    }

    /** Remove cookie.
     * @return boolean FALSE
     */
    private function clearCookie() {
        setcookie(self::$cookie_user, NULL, time() - 3600);
        unset($_SESSION['user']);
        unset($_SESSION['pass']);
        return FALSE;
    }

    # Check user's data and log in him.
    /**
    * @todo Comment
    * @return 
    */
    function logInUser() {
        $user = basename(FILTER::get('REQUEST', 'username'));
        if (($user === 'guest') || self::$logged_in) {
            return CMS::call('LOG')->logPut('Note', self::$user['username'], 'Attempted to log in as '.$user);
        }
        $userdata = [];
        if ($this->checkUser($user, FILTER::get('REQUEST', 'password'), FALSE, $userdata)) {
            $_SESSION['user'] = $user;
            $_SESSION['pass'] = $userdata['password'];
            setcookie(self::$cookie_user, $user, time() + 3600);
            setcookie(self::$cookie_nick, $userdata['nickname'], time() + 3600);
            $userdata['visits']++;
            $userdata['lastvisit'] = time();
            $userdata['last_prr']  = 0;
            $this->loginSuccess($userdata);
            self::saveUserData($user, $userdata);
            CMS::call('LOG')->logPut('Note', self::$user['username'], 'Logged in as '.$user);
            return TRUE;
        } else {
            return CMS::call('LOG')->logPut('Note', self::$user['username'], 'Attempt to log in as '.$user);
        }
    }

    /** Check if user already logged in.
     * @return boolean The result.
     */
    public static function loggedIn() {
        return self::$logged_in;
    }

    /**
    * @todo Comment
    * @param string $user	...
    * @return 
    */
    private function loginSuccess($user) {
        self::$user      = $user;
        self::$logged_in = TRUE;
        self::$root      = $user['rights'] === '*';
    }

    /**
    * @todo Comment
    * @return 
    */
    public function registerUser() {
        $username = basename(FILTER::get('REQUEST', 'user'));
        $nickname = basename(FILTER::get('REQUEST', 'nick'));
        if (!$this->checkUserName($username, 'Name')) {
            throw new Exception('Invalid username');
        }
        if (!$this->checkUserName($nickname, 'Nick')) {
            throw new Exception('Invalid usernick');
        }
        if (!$this->checkPassword(FILTER::get('REQUEST', 'password'), FILTER::get('REQUEST', 'confirm'))) {
            throw new Exception('Invalid password');
        }
        if (file_exists(USERS.$username)) {
            CMS::call('LOG')->logError('User with this username already exists');
            throw new Exception('User with this username already exists');
        }
        $email = FILTER::get('REQUEST', 'email');
        if (!CMS::call('FILTER')->validEmail($email)) {
            throw new Exception('Invalid email');
        }
        global $LANG;
        $userdata = FILTER::get('REQUEST', 'fields');
        # Also we must set a md5 hash of user's password to userdata.
        $user['username']  = $username;
        $user['nickname']  = $nickname;
        $user['password']  = md5(FILTER::get('REQUEST', 'password'));
        $user['email']     = $email;
        # Parse some system fields.
        $user['tz']        = $userdata['tz'];
        $user['access']    = 1;
        $user['rights']    = '';
        $user['status']    = '';
        $user['stars']     = 0;
        $user['regdate']   = time();
        $user['visits']    = 1;
        $user['lastvisit'] = $user['regdate'];
        $user['posts']     = 0;
        $user['comments']  = 0;
        $user['topics']    = 0;
        $user['replies']   = 0;
        $user['blocked']   = FALSE;
        $user['icq']       = $userdata['icq'];
        $user['website']   = $userdata['website'];
        $user['country']   = $userdata['country'];
        $user['city']      = $userdata['city'];
        $user['last_prr']  = 0;
        if (self::saveUserData($username, $user)) {
            CMS::call('LOG')->logPut('Note', self::$user['username'], 'Registation');
            # Create user's PM file.
            file_put_contents(PM_DATA.$username, serialize(['inbox' => [], 'outbox' => []]), LOCK_EX);
            return TRUE;
        }
        CMS::call('LOG')->logError('Cannot save profile '.$username);
        throw new Exception('Cannot save profile');
    }

    /**
    * @todo Comment
    * @param string $username	...
    * @param string $nickname	...
    * @param string $userdata	...
    * @return 
    */
    public function updateUser($username, $nickname, $userdata) {
        $username = basename($username);
        $nickname = basename($nickname);
        if (!file_exists(USERS.$username)) {
            throw new Exception('Invalid username');
        }
        if (!$this->checkUserName($nickname, 'Nick')) {
            throw new Exception(__('Invalid nickname'));
        }
        $email = FILTER::get('REQUEST', 'email');
        if (!CMS::call('FILTER')->validEmail($email)) {
            throw new Exception('Invalid email');
        }
        $user = self::getUserData($username);
        if ($user === FALSE) {
            throw new Exception('Cannot get userdata');
        }
        $password = FILTER::get('REQUEST', 'password');
        $confirm  = FILTER::get('REQUEST', 'confirm');
        if (!empty($password) && !empty($confirm)) {
            if (!$this->checkPassword($password, $confirm)) {
                throw new Exception('Invalid password');
            } else {
                $password = md5($password);
            }
        } else $password = $user['password'];
        # Also we must set a md5 hash of user's password to userdata.
        $user = array_merge($user, $userdata);
        $user['password'] = $password;
        $user['email']    = $email;
        if (self::saveUserData($username, $user)) {
            if (self::$user['username'] === $username) {
                self::$user = $user;
            }
            CMS::call('LOG')->logPut('Note', self::$user['username'], 'Updated userinfo for '.$username);
            return TRUE;
        }
        CMS::call('LOG')->logError('Cannot save profile '.$username);
        throw new Exception('Cannot save profile');
    }

    /**
    * @todo Comment
    * @param string $user	...
    * @param string $field	...
    * @param string $value	...
    * @return 
    */
    public static function changeProfileField($user, $field, $value) {
        if ($user === self::$user['username']) {
            $profile = self::$user;
        } else {
            $profile = self::getUserData($user);
            if (empty($profile)) {
                throw new Exception('Cannot get userdata');
            }
        }
        if ($value === '+')     $profile[$field]++;
        elseif ($value === '-') $profile[$field]--;
        else                    $profile[$field] = $value;
        return self::saveUserData($user, $profile);
    }

    /**
    * @todo Comment
    * @param string $field	... (défaut : '')
    * @return 
    */
    public static function getUser($field = '') {
        return empty($field) ? self::$user : self::$user[$field];
    }

    /**
    * @todo Comment
    * @param string $name	...
    * @return 
    */
    public static function getUserData($name) {
        if ($name === self::$user['username']) {
            return self::$user;
        }
        $user = [];
        if (file_exists(USERS.$name)) {
            $data = file(USERS.$name, FILE_IGNORE_NEW_LINES);
            $user = array_combine(self::$user_fields, $data);
        }
        return $user;
    }

    /**
    * @todo Comment
    * @param string $mask	... (défaut : '*')
    * @param string $field	... (défaut : '')
    * @return 
    */
    public function getUsersList($mask = '*', $field = '') {
        $return = [];
        $users  = AdvScanDir(USERS, $mask);
        foreach ($users as $user) {
            $data = self::getUserData($user);
            if (!empty($field) && !empty($data[$field])) {
                $return[$data[$field]] = $data;
            } else {
                $return[] = $data;
            }
        }
        return $return;
    }

    /** Save user profile.
     * @param  string $user     User name.
     * @param  array  $userdata User profile.
     * @return boolean          The result.
     */
    public static function saveUserData($user, $userdata) {
        $result = implode(LF, array_values($userdata));
        return file_put_contents(USERS.$user, $result, LOCK_EX);
    }

    /**
    * @todo Comment
    * @return 
    */
    public function logOutUser() {
        if (self::$logged_in) {
            self::$logged_in = FALSE;
            CMS::call('LOG')->logPut('Note', self::$user['username'], 'Logged out');
            $_SESSION['user'] = '';
            $_SESSION['pass'] = '';
            setcookie(self::$cookie_user, '', time() - 3600);
            setcookie(self::$cookie_nick, '', time() - 3600);
            self::$user = [
                'username' => 'guest',
                'nickname' => __('Guest'),
                'status'   => 'Passer-by',
                'rights'   => '',
                'tz'       => CONFIG::getValue('main', 'tz'),
                'access'   => 0
            ];
        }
    }

    /**
    * @todo Comment
    * @param string $rights	...
    * @return 
    */
    public static function setSystemRights($rights) {
        self::$system_rights = array_merge(self::$system_rights, $rights);
    }

    /**
    * @todo Comment
    * @return 
    */
    public static function getSystemRights() {
        return self::$system_rights;
    }

    /**
    * @todo Comment
    * @param string $user	... (défaut : '')
    * @param string $root	... (défaut : '')
    * @param string $userdata	... (défaut : '')
    * @return 
    */
    public static function getUserRights($user = '', &$root = '', &$userdata = '') {
        $rights = [];
        $root   = FALSE;
        if (empty($user)) {
            $userdata = self::$user;
        } else {
            $userdata = self::getUserData($user);
            if (empty($userdata)) {
                return FALSE;
            }
        }
        if ($userdata['rights'] !== '*') {
            if (!empty($userdata['rights'])) {
                $user_rights = explode(' ', $userdata['rights']);
                foreach ($user_rights as $right) {
                    $rights[$right] = self::$system_rights[$right];
                }
            }
        } else {
            $root = TRUE;
        }
        return $rights;
    }

    /**
    * @todo Comment
    * @param string $object	...
    * @return 
    */
    public static function checkAccess($object) {
        return ((int) $object['access'] <= self::$user['access']) ? TRUE : FALSE;
    }

    /** Check password.
     * @param  string $password Password.
     * @param  string $confirm  Password confirm.
     * @return boolean          The result.
     */
    private function checkPassword($password, $confirm) {
        if (empty($password) || empty($confirm) || $password !== $confirm) {
            return CMS::call('LOG')->logError('Passwords are not equal');
        }
        return TRUE;
    }

    /**
    * @todo Correct $userdata.
    */
    public static function checkRight($right, $user = '', $userdata = '') {
        $rights = self::getUserRights($user, $root, $userdata);
        if ($rights === FALSE) {
            return FALSE;
        }
        return $root || !empty($rights[$right]);
    }

    /** Check if user is website admin.
     * @return boolean The result.
     */
    public static function checkRoot() {
        return self::$root;
    }

    # Check user's data and validate his data file.
    /**
    * @todo Comment
    * @param string $username	...
    * @param string $password	...
    * @param string $hash	...
    * @param string $userdata	...
    * @return 
    */
    public function checkUser($username, $password, $hash, &$userdata) {
        if (!$this->checkUserName($username, 'Name')) {
            return FALSE;
        }
        if (!file_exists(USERS.$username)) {
            return FALSE;
        }
        $userdata = self::getUserData($username);
        # If userdata is invalid we must exit with error.
        if (empty($userdata)) {
            return CMS::call('LOG')->logError('Invalid login or password');
        }
        # If password is invalid - exit with error.
        if ((!$hash && (md5($password) !== $userdata['password'])) || ($hash && ($password !== $userdata['password']))) {
            return CMS::call('LOG')->logError('Invalid login or password');
        }
        if (!empty($userdata['blocked'])) {
            return CMS::call('LOG')->logPut('Note', $username, 'Attempt to log in. This account has been blocked by administrator');
        }
        return TRUE;
    }

    /** Check user name or nick.
     * @param  string $name User name or nick.
     * @param  string $type Type: 'Name' or 'Nick'.
     * @return boolean      The result.
     */
    private function checkUserName($name, $type) {
        if (!empty($name)) {
            if (!in_array(strtolower($name), self::$disallowed_names)) {
                if ($type === 'Name') {
                    if (OnlyLatin($name)) {
                        return TRUE;
                    }
                } else {
                    if ($type === 'Nick') {
                        if (mb_strlen($name, 'UTF-8') <= CONFIG::getValue('user', 'nick-length')) {
                            return TRUE;
                        }
                    }
                }
            }
        }
        return FALSE;
    }

    /** Check if user has right to edit or remove the article, topic, comment or replay.
     * @param  string  $module Module (ex. articles, forum, etc.)
     * @param  integer $item   Item ID.
     * @return boolean         The result of right checking.
     */
    public static function moderator($module, $item = '') {
        if (self::$user['username'] === 'guest') {
            return FALSE;
        }
        if (!empty($item)) {
            return self::checkRight($module) || ((self::$user['username'] === $item['author']) && ((time() - $item['time']) < 300));
        }
        return self::checkRight($module);
    }

}
