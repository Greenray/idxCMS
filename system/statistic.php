<?php
# idxCMS Flat Files Content Management Sysytem

/** Site ststistic - registers a visitы to the website by visitors, users, bots and spiders.
 *
 * @file      system/statistic.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Core
 */

if (!defined('idxCMS')) die();

/** Extracts keywords from the user`s query.
 *
 * @param  string $url User`s URL
 * @return mixed       Decoded keywords or FALSE
 */
function ExtractKeyword($url) {

    # Searching queries mask
    $search_queries = [
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
    $components = parse_url($url);
    if (isset($components['query'])) {
        $query_items = [];
        parse_str($components['query'], $query_items);
        foreach ($search_queries as $engine => $param) {
            if (strpos($components['host'], $engine) !== FALSE && !empty($query_items[$param])) {
                return $engine."|".urldecode($query_items[$param]);
            }
        }
    }
    return FALSE;
}

/** Detect bad bots.
 *
 * @param  string $agent $_SERVER['HTTP_USER_AGENT']
 * @return boolean       Is bad bot detected?
 */
function DetectBadBot($agent) {
    $engines = ['email exractor','sitesucker','w3af.sourceforge.net','xpymep'];
    foreach ($engines as $engine) {
        if (stristr($agent, $engine)) {
            return TRUE;
        }
    }
    return FALSE;
}

/** Detect spiders.
 * 
 * @param  string $agent $_SERVER['HTTP_USER_AGENT']
 * @return boolean       Is spider detected?
 */
function DetectSpider($agent) {

    # List of spider engines
    $engines = [
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

    foreach ($engines as $engine) {
        if (stristr($agent, $engine)) {
            return TRUE;
        }
    }
    return FALSE;
}

$agent   = $_SERVER['HTTP_USER_AGENT']; # Header from the current request, if there is one
$ip      = $_SERVER['REMOTE_ADDR'];     # User`s IP address
$referer = $_SERVER['HTTP_REFERER'];    # The page which referred the user agent, if there is one
$page    = $_SERVER['REQUEST_URI'];     # The URI which was given in order to access site

if (DetectBadBot($agent)) {
    $bans   = file_get_contents(CONTENT.'bans');
    $result = $bans.$ip.LF;
    file_put_contents(CONTENT.'bans', $result, LOCK_EX);
    die();
}

$config = CONFIG::getSection('statistic');
$time = time();

if (DetectSpider($agent)) {

    # Detect and register of searching bot.
    $spiders = GetUnserialized(CONTENT.'spiders');
    if (empty($spiders)) {
        $spiders['total'] = 1;
        $spiders['today'] = 1;

        if (!empty($config['spider-ip'])) $spiders['ip'][$ip] = 1;
        if (!empty($config['spider-ua'])) $spiders['ua'][$agent] = 1;

        $spiders['update'] = $time;
        file_put_contents(CONTENT.'spiders', serialize($spiders), LOCK_EX);
    } else {
        if (empty($spiders['ip'][$ip])) {

            if ($spiders['update'] < mktime(0, 0, 0, date('n'), date('j'), date('Y'))) {
                $spiders['today'] = 1;
            } else {
                $spiders['today'] = $spiders['today'] + 1;
            }
            $spiders['total'] = $spiders['total'] + 1;

            if (!empty($config['spider-ip'])) $spiders['ip'][$ip]    = empty($spiders['ip'][$ip])    ? 1 : $spiders['ip'][$ip]    + 1;
            if (!empty($config['spider-ua'])) $spiders['ua'][$agent] = empty($spiders['ua'][$agent]) ? 1 : $spiders['ua'][$agent] + 1;

            $spiders['update'] = $time;
            file_put_contents(CONTENT.'spiders', serialize($spiders), LOCK_EX);
        }
    }
} else {
    $user  = USER::getUser();                   # User profile
    $stats = GetUnserialized(CONTENT.'stats');  # Statistic data storage
    if (empty($stats)) {
        $stats['total']   = 1;
        $stats['today']   = 1;
        $stats['ip'][$ip] = 1;
        $stats['hosts']   = [];
        $stats['users']   = [];
        $stats['online']  = [];
        if (!empty($config['user-ua'])) $stats['ua'][$agent] = 1;
        $stats['update'] = $time;
        file_put_contents(CONTENT.'stats', serialize($stats), LOCK_EX);
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

    $online = $stats['online'];               # Users and visitors online at the current time
    $online[$ip]['name'] = $user['username']; # Current username
    $online[$ip]['nick'] = $user['nickname']; # Current nickname
    $online[$ip]['time'] = $time;             # Current time
    $stats['online'] = [];                    # Users and visitors online

    foreach ($online as $ip => $data) {
        if ($data['time'] > ($time - 300)) {
            $stats['online'][$ip] = $data;
            if ($user['username'] !== 'guest') {
                if (empty($stats['users']) || !in_array($user['username'], $stats['users'])) {
                    $stats['users'][] = $user['username'];
                }
            }
        }
    }
    $stats['update'] = $time;   # Set the time of the last ststistic data update

    file_put_contents(CONTENT.'stats', serialize($stats), LOCK_EX);

    # Keyword from $_SERVER['HTTP_REFERER']
    $keyword = ExtractKeyword($referer);

    if (!empty($keyword)) {
        $file = (file_exists(CONTENT.'keywords')) ? file_get_contents(CONTENT.'keywords') : '';

        # Save bot|keyword|page
        file_put_contents(CONTENT.'keywords', $file.$keyword."|".$page.LF, LOCK_EX);
    }
}

unset ($agent);
unset ($ip);
