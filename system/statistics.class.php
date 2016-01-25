<?php
/**
 * Site statistics - registers a visits to the website by visitors, users, bots and spiders.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2016 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      system/statistics.php
 * @package   Statistics
 * @overview  Website statistics.
 *            It registers visitors, bots, spiders, their IP addresses, keywords, search queries.
 */

class STATISTICS {

    /** Bad bots */
    public $bots = ['email exractor','sitesucker','w3af.sourceforge.net','xpymep'];

    /** Searching queries mask */
    public $search_queries = [
            'a-counter' => 'sub_data', 'about'  => 'terms',  'alice'     => 'qs',
            'alltheweb' => 'q',        'altavista' => 'q',   'aol'       => 'encquery',
            'aol'       => 'q',        'aol'    => 'query',  'aport'     => 'r',
            'ask'       => 'q',        'baidu'  => 'wd',     'bigmir'    => 'q',
            'club-internet' => 'q',    'cnn'    => 'query',  'gigablast' => 'q',
            'google'    => 'q',        'i.ua'   => 'q',      'live'      => 'q',
            'looksmart' => 'qt',       'lycos'  => 'query',  'mail.ru'   => 'q',
            'mama'      => 'query',    'mamma'  => 'query',  'meta.ua'   => 'q',
            'msn'       => 'q',        'najdi'  => 'q',      'netscape'  => 'query',
            'netsprint' => 'q',        'pchome' => 'q',      'rambler'   => 'words',
            'search'    => 'q',        'seznam' => 'q',      'szukacz'   => 'q',
            'szukaj'    => 'qt',       'szukaj' => 'szukaj', 'virgilio'  => 'qs',
            'voila'     => 'rdata',    'yahoo'  => 'p',      'yam'       => 'k',
            'yandex'    => 'text'
        ];

    /** List of spider engines */
    public $spiders = [
            '110search','12move',
            'a-counter','abcdatos','acoon','aesop','alexa','alkaline','allesklar','almaden','altavista','aport','appie','arachnoidea','architext','archiver','artabus',
            'ask','aspdeek','aspseek','asterias','atomz','augurfind','austronaut',
            'batsch','baidu','bdcindexer','bellnet','bestoftheweb','bigfoot','blitzsuche','boitho','bot','butterfly',
            'club-internet','cnn','cobion','cortina','crawler',
            'datafountains','daum','deepnet','digout4u','ditto','dmoz','docomo',
            'earthcom','ec2linkfinder','echo.com','elsop','estyle','eule','euroseek','excite','ezresult','ezooms',
            'fast','find','fireball','firefly','fluffy','flunky','freenet','froogle','fujitsu',
            'galaxy','gazz','gendoor','genieknows','gigablast','google','goto','gulliver',
            'heritrix','hoppa','hubat','hubater',
            'ichiro','incywincy','informatch','infoseek','inktomi','internetseer','ip3000','ixquick',
            'jayde',
            'kit_fireball',
            'lachesis','larbin','lexis-nexis','libwww-perl','linkwalker','live','lockstep','looksmart','lycos',
            'mail','mantraagent','mariner','markwatch','me.dium','mercator','meta','mirago','moget','muscatferret',
            'najdi','nameprotect','nationaldirectory','nazilla','nbci','netcraft','netmechanic','netsprint','news','ng','nico','northernlight','nutch',
            'openportal4u','osis-project',
            'pchome','pinpoint','pompos','portaljuice',
            'qualigo','quepasacreep',
            'rabaz','rambler','refer','roach','robozilla','rotondo',
            'scooter','scoutabout','scrubby','search','seventwentyfour','seznam','sidewinder','singingfish','sitecheck','slurp','spade','spider','steeler','supersnooper',
            'surfnomore','szuka',
            'teoma','technoratisnoop','tecnoseek','t-h-u-n-d-e-r-s-t-o-n-e','tivra','toutatis','tracerlock','twiceler','twitturls',
            'ultraseek',
            'vagabondo','validator','virgilio',
            'w8net','walhello','webalta','webbug','webclipping','wespe','wget','whizbang','wholeweb','wiseguys','worldonline','wotbox',
            'xenu',
            'yahoo','yam','yandex','yanga','yeti',
            'zeus','zippy','zyborg'
        ];

    /** Class initialization */
    public function __construct() {}

    /**
     * Detects spiders or bad bots.
     *
     * @param  string $agent  $_SERVER['HTTP_USER_AGENT']
     * @param  string $object What to search
     * @return boolean        Is spider or bot detected?
     */
    function detect($agent, $object) {
        foreach ($this->$object as $engine) {
            if (stristr($agent, $engine)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * Extracts keywords from the user`s query.
     *
     * @param  string $url User`s URL
     * @return mixed       Decoded keywords or FALSE
     */
    function extractKeyword($url) {
        $components = parse_url($url);
        if (isset($components['query'])) {
            $query_items = [];
            parse_str($components['query'], $query_items);
            foreach ($this->search_queries as $engine => $param) {
                if (strpos($components['host'], $engine) && !empty($query_items[$param])) {
                    return $engine."|".urldecode($query_items[$param]);
                }
            }
        }
        return FALSE;
    }
}

$agent   = $_SERVER['HTTP_USER_AGENT']; # Header from the current request, if there is one
$ip      = $_SERVER['REMOTE_ADDR'];     # User`s IP address
$referer = $_SERVER['HTTP_REFERER'];    # The page which referred the user agent, if there is one
$page    = $_SERVER['REQUEST_URI'];     # The URI which was given in order to access site

if (CMS::call('STATISTICS')->detect($agent, 'bots')) {
    $bans   = file_get_contents(CONTENT.'bans');
    $result = $bans.$ip.LF;
    file_put_contents(CONTENT.'bans', $result, LOCK_EX);
    die();
}

$config = CONFIG::getSection('statistics');
$time = time();

if (CMS::call('STATISTICS')->detect($agent, 'spiders')) {
    #
    # Detect and register of searching bot
    #
    $spiders = json_decode(file_get_contents(CONTENT.'spiders'), TRUE);
    if (empty($spiders)) {
        $spiders['total'] = 1;
        $spiders['today'] = 1;

        if (!empty($config['spider-ip'])) $spiders['ip'][$ip]    = 1;
        if (!empty($config['spider-ua'])) $spiders['ua'][$agent] = 1;

    } else {
        if (empty($spiders['ip'][$ip])) {

            $spiders['today'] = $spiders['update'] < mktime(0, 0, 0, date('n'), date('j'), date('Y')) ? 1 : $spiders['today'] + 1;
            $spiders['total'] = $spiders['total'] + 1;

            if (!empty($config['spider-ip'])) $spiders['ip'][$ip]    = empty($spiders['ip'][$ip])    ? 1 : $spiders['ip'][$ip]    + 1;
            if (!empty($config['spider-ua'])) $spiders['ua'][$agent] = empty($spiders['ua'][$agent]) ? 1 : $spiders['ua'][$agent] + 1;
        }
    }
    $spiders['update'] = $time;
    file_put_contents(CONTENT.'spiders', json_encode($spiders, JSON_UNESCAPED_UNICODE), LOCK_EX);

} else {
    $user  = USER::getUser();                                        # User profile
    $stats = json_decode(file_get_contents(CONTENT.'stats'), TRUE);  # Statistics data storage
    if (empty($stats)) {
        $stats['total']   = 1;
        $stats['today']   = 1;
        $stats['ip'][$ip] = 1;
        $stats['hosts']   = [];
        $stats['users']   = [];
        $stats['online']  = [];
        if (!empty($config['user-ua'])) $stats['ua'][$agent] = 1;
        $stats['update'] = $time;
        file_put_contents(CONTENT.'stats', json_encode($stats, JSON_UNESCAPED_UNICODE), LOCK_EX);

    } else {
        if ($stats['update'] < mktime(0, 0, 0, date('n'), date('j'), date('Y'))) {
            $stats['hosts'] = [];
            $stats['users'] = [];
        }

        if (empty($stats['ip'][$ip])) {
            $stats['total'] = $stats['total'] + 1;
            $stats['ip'][$ip] = 1;
        } else {
            $stats['ip'][$ip] = $stats['ip'][$ip] + 1;
        }
        $stats['hosts'][$ip] = $time;

        if (!empty($config['user-ua'])) $stats['ua'][$agent] = empty($stats['ua'][$agent]) ? 1 : $stats['ua'][$agent] + 1;

        if (!empty($referer)) {
            if (($ref = parse_url($referer)) !== FALSE) {
                if (!empty($ref['host'])) {
                    $stats['ref'][$ref['host']] = empty($stats['ref'][$ref['host']]) ? 1 : $stats['ref'][$ref['host']] + 1;
                }
            }
        }
    }

    $online = $stats['online'];           # Users and visitors online at the current time
    $online[$ip]['name'] = $user['user']; # Current username
    $online[$ip]['nick'] = $user['nick']; # Current nickname
    $online[$ip]['time'] = $time;         # Current time
    $stats['online'] = [];                # Users and visitors online

    foreach ($online as $ip => $data) {
        if ($data['time'] > ($time - 300)) {
            $stats['online'][$ip] = $data;
            if ($user['user'] !== 'guest') {
                if (empty($stats['users']) || !in_array($user['user'], $stats['users'])) {
                    $stats['users'][] = $user['user'];
                }
            }
        }
    }
    $stats['update'] = $time;   # Set the time of the last ststistic data update

    file_put_contents(CONTENT.'stats', json_encode($stats, JSON_UNESCAPED_UNICODE), LOCK_EX);

    $keyword = CMS::call('STATISTICS')->extractKeyword($referer);  # Keyword from $_SERVER['HTTP_REFERER']
    if (!empty($keyword)) {
        $file = (file_exists(CONTENT.'keywords')) ? file_get_contents(CONTENT.'keywords') : '';
        file_put_contents(CONTENT.'keywords', $file.$keyword."|".$page.LF, LOCK_EX);  # Save bot|keyword|page
    }
}

unset ($agent);
unset ($ip);
