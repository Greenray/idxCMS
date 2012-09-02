<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# STATISTIC

if (!defined('idxCMS')) die();

# Detect utf8 encoding
function detect_utf($string) {
    for ($i = 0; $i < strlen($string); $i++) {
        if     (ord($string[$i]) < 0x80)           $n = 0;  # 0bbbbbbb
        elseif ((ord($string[$i]) & 0xE0) == 0xC0) $n = 1;  # 110bbbbb
        elseif ((ord($string[$i]) & 0xF0) == 0xE0) $n = 2;  # 1110bbbb
        elseif ((ord($string[$i]) & 0xF0) == 0xF0) $n = 3;  # 1111bbbb
        else return FALSE;                                  # Не UTF
        for ($j = 0; $j < $n; $j++) {
            if ((++$i == strlen($string)) || ((ord($string[$i]) & 0xC0) != 0x80)) return FALSE;
        }
    }
    return TRUE;
}

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
            if (strpos($components['host'], $engine) !== FALSE && !empty($query_items[$param]))
                return $engine."|".urldecode($query_items[$param]);

        }
    }
    return FALSE;
}

function DetectBadBot($agent) {
    $engines = array(
        'email exractor','sitesucker','w3af.sourceforge.net','xpymep'
    );
    foreach ($engines as $engine) {
        if (stristr($agent, $engine)) return TRUE;
    }
    return FALSE;
}

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
        if (stristr($agent, $engine)) return TRUE;
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
        if (!empty($config['spider-ip'])) $spiders['ip'][$ip] = 1;
        if (!empty($config['spider-ua'])) $spiders['ua'][$agent] = 1;
        $spiders['update'] = $time;
        file_put_contents(CONTENT.'spiders', serialize($spiders), LOCK_EX);
    } else {
        if (empty($spiders['ip'][$ip])) {
            if ($spiders['update'] < mktime(0, 0, 0, date('n'), date('j'), date('Y')))
                 $spiders['today'] = 1;
            else $spiders['today'] = $spiders['today'] + 1;
            $spiders['total'] = $spiders['total'] + 1;
            if (!empty($config['spider-ip'])) $spiders['ip'][$ip] = empty($spiders['ip'][$ip]) ? 1 : $spiders['ip'][$ip] + 1;
            if (!empty($config['spider-ua'])) $spiders['ua'][$agent] = empty($spiders['ua'][$agent]) ? 1 : $spiders['ua'][$agent] + 1;
            $spiders['update'] = $time;
            file_put_contents(CONTENT.'spiders', serialize($spiders), LOCK_EX);
        }
    }
} else {
    $user  = USER::getUser();
    $stats = GetUnserialized(CONTENT.'stats');
    if (empty($stats)) {
        $stats['total'] = 1;
        $stats['today'] = 1;
        $stats['ip'][$ip] = 1;
        $stats['hosts'] = array();
        $stats['users'] = array();
        if (!empty($config['user-ua'])) $stats['ua'][$agent] = 1;
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
        } else $stats['ip'][$ip] = $stats['ip'][$ip] + 1;
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
?>