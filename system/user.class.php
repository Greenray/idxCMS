<?php
/** Users and their profiles.
 *
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      system/user.class.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-Share Alike 4.0 Unported License
 * @package   User
 * @overview  Users and their profiles.
 *            Processes information about registration, visits and user activity.
 */

class USER {

    /** @var string Cookie with user nick */
    private static $cookie_nick = '';

    /** @var string Cookie with user name */
    private static $cookie_user = '';

    /** @var array Disallowed names for registration */
    private static $disallowed_names = [
        'administrator', 'false', 'guest', 'idxcms', 'moderator', 'noavatar', 'null', 'root',
        'superuser', 'supervisor', 'sponsor', 'system', 'test', 'true', 'unknown', 'user'
    ];

    /** @var boolean Is user logged in? */
    public static $logged_in = FALSE;

    /** @var array System rights */
    public static $system_rights = [];

    /** @var boolean Is user admin? */
    public static $root = FALSE;

    /** @var array User's profile */
    private static $user = [];

    /** @var array User profile fields */
    private static $user_fields = [
        'username', 'nickname', 'password', 'email', 'tz', 'access', 'rights', 'status', 'stars', 'regdate', 'visits',
        'lastvisit','posts', 'comments', 'topics', 'replies', 'blocked', 'icq', 'website', 'country', 'city', 'last_prr'
    ];

    /** Class initialization. */
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

    /** Initializes user and load his profile.
     * @return boolean TRUE if user is logged in
     */
    public function initUser() {
        # If user cookie is not present...
        $cookie_user = FILTER::get('COOKIE', self::$cookie_user);
        if (empty($cookie_user) || empty($_SESSION['user'])) {
            self::$logged_in = FALSE;
            return FALSE;
        }
        if ($cookie_user !== $_SESSION['user']) {
            return self::clearCookie();
        }
        # Now we must validate user's data
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

    /** Checks if user have the right to access.
     * @param  string $object Object: secton, category, article an so on
     * @return boolean        The result of operation
     */
    public static function checkAccess($object) {
        return ((int) $object['access'] <= self::$user['access']) ? TRUE : FALSE;
    }

    /** Checks password.
     * @param  string  $password Password
     * @param  string  $confirm  Password confirm
     * @return boolean           The result of operation
     */
    private function checkPassword($password, $confirm) {
        if (empty($password) || empty($confirm) || $password !== $confirm) {
            return CMS::call('LOG')->logError('Passwords are not equal');
        }
        return TRUE;
    }

    /** Check the user's rights.
     * @param  string $right    Right description
     * @param  string $user     Username (default = '')
     * @param  array  $userdata User's profile (default = [])
     * @return boolean          User is admin or has specified right
     */
    public static function checkRight($right, $user = '', $userdata = []) {
        $rights = self::getUserRights($user, $root, $userdata);
        if ($rights === FALSE) {
            return FALSE;
        }
        return $root || !empty($rights[$right]);
    }

    /** Checks user's data and validates his data file.
     * @param  string $username Username
     * @param  string $password Password
     * @param  string $hash	    Password hash
     * @param  array  $userdata Profile data
     * @return boolean|array    User's profile or the result of operation
     */
    public function checkUser($username, $password, $hash, &$userdata) {
        if (!$this->checkUserName($username, 'Name')) return FALSE;
        if (!file_exists(USERS.$username))            return FALSE;

        $userdata = self::getUserData($username);

        # If userdata is invalid we must exit with error
        if (empty($userdata)) {
            return CMS::call('LOG')->logError('Invalid login or password');
        }

        # If password is invalid - exit with error
        if ((!$hash && (md5($password) !== $userdata['password'])) || ($hash && ($password !== $userdata['password']))) {
            return CMS::call('LOG')->logError('Invalid login or password');
        }
        if (!empty($userdata['blocked'])) {
            return CMS::call('LOG')->logPut('Note', $username, 'Attempt to log in. This account has been blocked by administrator');
        }
        return TRUE;
    }

    /** Checks user name or nick.
     * @param  string  $name User name or nick
     * @param  string  $type Type: "Name" or "Nick"
     * @return boolean       The result
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

    /** Changes the value of the specified fiels in user's profile.
     * @param  string $user	 Username
     * @param  string $field Fieldname
     * @param  string $value Value of the field
     * @return boolean       The result of operation
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

    /** Removes cookie.
     * @return boolean FALSE
     */
    private function clearCookie() {
        setcookie(self::$cookie_user, NULL, time() - 3600);
        unset($_SESSION['user']);
        unset($_SESSION['pass']);
        return FALSE;
    }

    /** Gets user's profile or value of the specified field.
     * @param  string $field	Fieldname (défaut : '')
     * @return array|string  User's profile or fielddata
     */
    public static function getUser($field = '') {
        return empty($field) ? self::$user : self::$user[$field];
    }

    /** Gets user's profile.
     * @param  string $name Username
     * @return array  User's profile
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

    /** Gets user rights.
     * @param  string $user      Username               (defaut : '')
     * @param  string &$root     Reference to root flag (defaut : '')
     * @param  string &$userdata Reference to userdata  (defaut : '')
     * @return boolean|array     FALSE or list of user rights
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
        } else $root = TRUE;

        return $rights;
    }

    /** Gets the list of registered users with their profiles.
     * @param  string $mask Mask to seach user in database (défaut : '*')
     * @return array        List of registered users with their profiles
     */
    public function getUsersList($mask = '*') {
        $return = [];
        $users  = AdvScanDir(USERS, $mask);
        foreach ($users as $user) {
            $return[] = self::getUserData($user);
        }
        return $return;
    }

    /** Sets userdata if login is successful.
     * @param array $user Userdata
     */
    private function loginSuccess($user) {
        self::$user      = $user;
        self::$logged_in = TRUE;
        self::$root      = $user['rights'] === '*';
    }

    /** Checks user's data and log in him.
     * @return boolean TRUE if user successfully logged in
     */
    public function logInUser() {
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

        } else return CMS::call('LOG')->logPut('Note', self::$user['username'], 'Attempt to log in as '.$user);
    }

    /** Logout user.
     * Clean session and cookie parameters and set default guest data.
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

    /** Checks if user has right to edit or remove the article, topic, comment or replay.
     * @param  string  $module Module (ex. articles, forum, etc.)
     * @param  integer $item   Item ID
     * @return boolean         The result of right checking
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

    /** Adds new user to user's database.
     * @throws Exception "Invalid username"
     * @throws Exception "Invalid nickname"
     * @throws Exception "Invalid password"
     * @throws Exception "User with this username already exists'
     * @throws Exception "Invalid email"
     * @throws Exception "Cannot save profile"
     * @return boolean TRUE if registration is successful
     */
    public function registerUser() {
        $username = basename(FILTER::get('REQUEST', 'user'));
        $nickname = basename(FILTER::get('REQUEST', 'nick'));
        if (!$this->checkUserName($username, 'Name')) {
            throw new Exception('Invalid username');
        }
        if (!$this->checkUserName($nickname, 'Nick')) {
            throw new Exception('Invalid nickname');
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

        # Also we must set a md5 hash of user's password to userdata
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
            return file_put_contents(PM_DATA.$username, serialize(['inbox' => [], 'outbox' => []]), LOCK_EX);
        }
        CMS::call('LOG')->logError('Cannot save profile '.$username);
        throw new Exception('Cannot save profile');
    }

    /** Sets system rights.
     * @param array $rights The the set of rights
     */
    public static function setSystemRights($rights) {
        self::$system_rights = array_merge(self::$system_rights, $rights);
    }

    /** Saves user profile.
     * @param  string $user     User name
     * @param  array  $userdata User profile
     * @return boolean          The result
     */
    public static function saveUserData($user, $userdata) {
        $result = implode(LF, array_values($userdata));
        return file_put_contents(USERS.$user, $result, LOCK_EX);
    }

    /** Updates userdata in user's profile after editing.
     * @param  string $username Username
     * @param  string $nickname Nickname
     * @param  array  $userdata Userdata
     * @throws Exception "Invalid username"
     * @throws Exception "Invalid nickname"
     * @throws Exception "Invalid password"
     * @throws Exception "Invalid email"
     * @throws Exception "Cannot save profile"
     * @return boolean TRUE if update is successful
     */
    public function updateUser($username, $nickname, $userdata) {
        $username = basename($username);
        $nickname = basename($nickname);

        if (!file_exists(USERS.$username))            throw new Exception('Invalid username');
        if (!$this->checkUserName($nickname, 'Nick')) throw new Exception(__('Invalid nickname'));

        $email = FILTER::get('REQUEST', 'email');
        if (!CMS::call('FILTER')->validEmail($email)) throw new Exception('Invalid email');

        $user = self::getUserData($username);
        if ($user === FALSE) throw new Exception('Cannot get userdata');

        $password = FILTER::get('REQUEST', 'password');
        $confirm  = FILTER::get('REQUEST', 'confirm');
        if (!empty($password) && !empty($confirm)) {
            if (!$this->checkPassword($password, $confirm))
                 throw new Exception('Invalid password');
            else $password = md5($password);

        } else $password = $user['password'];

        # Also we must set a md5 hash of user's password to userdata
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
}
