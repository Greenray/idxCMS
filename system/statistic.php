<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# STATISTIC

if (!defined('idxCMS')) die();

/** Site ststistic.
 *
 * Registers a visitы to the website by visitors, users, bots and spiders
 *
 * @package   idxCMS
 * @ingroup   SYSTEM
 * @author    Victor Nabatov <greenray.spb@gmail.com>\n
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *            http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @copyright (c) 2011 - 2014 Victor Nabatov
 * @file      system/modules/aphorisms/module.php
 * @link      https://github.com/Greenray/idxCMS/system//modules/aphorisms/module.php
 */

 /**
 * Extractes keywords from the user`s query.
 * @param  string $url User`s URL
 * @return mixed Decoded keywords or FALSE
 */
function ExtractKeyword($url) {
    # Searching  keys
    $search_queries = array(
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
    );
    $components = parse_url($url);
    if (isset($components['query'])) {
        $query_items = array();
        parse_str($components['query'], $query_items);
        foreach ($search_queries as $engine => $param) {
            if (strpos($components['host'], $engine) !== FALSE && !empty($query_items[$param])) {
                return $engine."|".urldecode($query_items[$param]);
            }
        }
    }
    return FALSE;
}

 /**
 * Detects bad bots.
 * @param  string $agent $_SERVER['HTTP_USER_AGENT']
 * @return boolean Is bad bot detected?
 */
function DetectBadBot($agent) {
    $engines = array(
        'email exractor','sitesucker','w3af.sourceforge.net','xpymep'
    );
    foreach ($engines as $engine) {
        if (stristr($agent, $engine)) {
            return TRUE;
        }
    }
    return FALSE;
}

 /**
 * Detects spiders.
 * @param  string $agent $_SERVER['HTTP_USER_AGENT']
 * @return boolean Is spider detected?
 */
function DetectSpider($agent) {
    $engines = array(
        '110search','12move',
        'a-counter','abcdatos','acoon','aesop','alexa','alkaline','allesklar','almaden','altavista','aport','appie','arachnoidea','architext','archiver','artabus','ask','aspdeek','aspseek','asterias','atomz','augurfind','austronaut',
        'batsch','baidu','bdcindexer','bellnet','bestoftheweb','bigfoot','blitzsuche','boitho','bot',
        'club-internet','cnn','cobion','cortina','crawler',
        'datafountains','daum','deepnet','digout4u','ditto','dmoz','docomo',
        'earthcom','ec2linkfinder','echo.com','elsop','estyle','eule','euroseek','excite','ezresult','ezooms',
        'find','fireball','fluffy','flunky','freenet','fujitsu',
        'galaxy','gazz','gendoor','genieknows','gigablast','google','goto','gulliver',
        'heritrix','hoppa','hubat','hubater',
        'ichiro','incywincy','informatch','infoseek','inktomi','internetseer','ip3000','ixquick',
        'jayde',
        'kit_fireball',
        'lachesis','larbin','lexis-nexis','libwww-perl','linkwalker','live','lnspiderguy','lockstep','looksmart','lycos',
        'mail','mantraagent','mariner','markwatch','mercator','meta','mirago','moget','muscatferret',
        'najdi','nameprotect','national directory','nazilla','nbci','netcraft','netmechanic','netsprint','news','ng','nico','northernlight','nutch',
        'openportal4u','osis-project',
        'pchome','pinpoint','pompos','portaljuice',
        'qualigo','quepasacreep',
        'rambler','refer','roach','robot','robozilla','rotondo',
        'scooter','scoutabout','scrubby','search','seventwentyfour','seznam','sidewinder','singingfish','sitecheck','slurp','spider',
        'steeler','supersnooper','surfnomore','szuka',
        'teoma','t-h-u-n-d-e-r-s-t-o-n-e','tivra','toutatis','tracerlock','twiceler',
        'ultraseek',
        'vagabondo','validator','virgilio',
        'w8net','walhello','webalta','webclipping','wespe','wget','whizbang','wholeweb','wiseguys','worldonline','wotbox',
        'xenu',
        'yahoo','yam','yandex','yanga','yeti',
        'zeus','zippy','zyborg'
    );
    foreach ($engines as $engine) {
        if (stristr($agent, $engine)) {
            return TRUE;
        }
    }
    return FALSE;
}

# Save counter data
$agent   = $_SERVER['HTTP_USER_AGENT'];
$ip      = $_SERVER['REMOTE_ADDR'];
$referer = $_SERVER['HTTP_REFERER'];
$page    = $_SERVER['REQUEST_URI'];

if (DetectBadBot($agent)) {
    $bans   = file_get_contents(CONTENT.'bans');
    $result = $bans.$ip.LF;
    file_put_contents(CONTENT.'bans', $result, LOCK_EX);
    die();
}

$config = CONFIG::getSection('statistic');
$time = time();

if (DetectSpider($agent)) {
    # Detect and register of searching bot
    $spiders = GetUnserialized(CONTENT.'spiders');
    if (empty($spiders)) {
        $spiders['total'] = 1;
        $spiders['today'] = 1;
        if (!empty($config['spider-ip'])) {
            $spiders['ip'][$ip] = 1;
        }
        if (!empty($config['spider-ua'])) {
            $spiders['ua'][$agent] = 1;
        }
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
            if (!empty($config['spider-ip'])) {
                $spiders['ip'][$ip] = empty($spiders['ip'][$ip]) ? 1 : $spiders['ip'][$ip] + 1;
            }
            if (!empty($config['spider-ua'])) {
                $spiders['ua'][$agent] = empty($spiders['ua'][$agent]) ? 1 : $spiders['ua'][$agent] + 1;
            }
            $spiders['update'] = $time;
            file_put_contents(CONTENT.'spiders', serialize($spiders), LOCK_EX);
        }
    }
} else {
    $user  = USER::getUser();
    $stats = GetUnserialized(CONTENT.'stats');
    if (empty($stats)) {
        $stats['total']   = 1;
        $stats['today']   = 1;
        $stats['ip'][$ip] = 1;
        $stats['hosts']   = array();
        $stats['users']   = array();
        $stats['online']  = array();
        if (!empty($config['user-ua'])) {
            $stats['ua'][$agent] = 1;
        }
        $stats['update'] = $time;
        file_put_contents(CONTENT.'stats', serialize($stats), LOCK_EX);
    } else {
        if ($stats['update'] < mktime(0, 0, 0, date('n'), date('j'), date('Y'))) {
            $stats['hosts'] = array();
            $stats['users'] = array();
        }
        if (empty($stats['ip'][$ip])) {
            $stats['total'] = $stats['total'] + 1;
            $stats['ip'][$ip] = 1;
        } else {
            $stats['ip'][$ip] = $stats['ip'][$ip] + 1;
        }
        $stats['hosts'][$ip] = $time;
        if (!empty($config['user-ua'])) {
            $stats['ua'][$agent] = empty($stats['ua'][$agent]) ? 1 : $stats['ua'][$agent] + 1;
        }
        if (!empty($referer)) {
            if (($ref = parse_url($referer)) !== FALSE) {
                if (!empty($ref['host'])) {
                    $stats['ref'][$ref['host']] = empty($stats['ref'][$ref['host']]) ? 1 : $stats['ref'][$ref['host']] + 1;
                }
            }
        }
    }

    $online = $stats['online'];
    $online[$ip]['name'] = $user['username'];
    $online[$ip]['nick'] = $user['nickname'];
    $online[$ip]['time'] = $time;
    $stats['online'] = array();
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
    $stats['update'] = $time;
    
    file_put_contents(CONTENT.'stats', serialize($stats), LOCK_EX);
    $keyword = ExtractKeyword($referer);
    if (!empty($keyword)) {
        $file = (file_exists(CONTENT.'keywords')) ? file_get_contents(CONTENT.'keywords') : '';
        # Save bot|keyword|page
        file_put_contents(CONTENT.'keywords', $file.$keyword."|".$page.LF, LOCK_EX);
    }
}
