<?php
define('ENV', 'test');
ini_set('xdebug.max_nesting_level', 256);

function solve($P, $Q, $N, $H, $G) {
    //dd($P.', '.$Q, 'P, Q'); dd($H, 'H'); dd($G, 'G');
    $DP = [];
    $gold = shoot(0, $H[0], 1, $P, $Q, $H, $G, $DP);
    return $gold;
}

function shoot($I, $hp, $extra_shot, $P, $Q, $H, $G, &$DP) {
    // monster already dead, and no more monster
    if ($hp <= 0 && !isset($H[$I + 1])) return 0;
    // monster already dead, try next
    if ($hp <= 0) {
        return shoot($I + 1, $H[$I + 1], $extra_shot, $P, $Q, $H, $G, $DP);
    }
    // leave this monster to the tower untill the last hit
    $t = ceil($hp / $Q) - 1;
    $hp -= $Q * $t;
    $extra_shot += $t;
    // if has memorized
    if (isset($DP[$I][$hp][$extra_shot])) return $DP[$I][$hp][$extra_shot];
    // if choose to not shoot
    $r1 = shoot($I, $hp - $Q, $extra_shot + 1, $P, $Q, $H, $G, $DP);
    // if it is possible to shoot it dead and gain the gold at this turn
    $r2 = 0;
    $t = ceil($hp / $P);
    if ($extra_shot >= $t) {
        $r2 = $G[$I] + shoot($I, $hp - $t * $P, $extra_shot - $t, $P, $Q, $H, $G, $DP);
    }
    // memorize and return
    $r = max($r1, $r2);
    if (!isset($DP[$I])) $DP[$I] = [];
    if (!isset($DP[$I][$hp])) $DP[$I][$hp] = [];
    $DP[$I][$hp][$extra_shot] = $r;
    return $r;
}

function fake() {
    $R = rand(1, 50); $B = rand(0, 50);
    $str = $R.' '.$B."\n";
    file_put_contents('../下载/IN.txt', $str, FILE_APPEND);
}

function dd($item, $name = '') {
    if (ENV != 'test') return;
    if ($name != '') echo $name.': ';
    if (is_array($item)) print_r($item);
    else echo $item."\n";
}

function read($hr) {
    return trim(fgets($hr));
}

function write($str, $hw) {
    if (ENV == 'test') echo $str;
    fwrite($hw, $str);
}

if (ENV == 'test') {
    //$hr = fopen('../下载/IN.txt', 'r');
    $hr = fopen('../下载/B-large-practice.in', 'r');
    $hw = fopen('../下载/OUT_2.txt', 'w');
    $hw = fopen('../下载/OUT_2.txt', 'a');
} else {
    $hr = STDIN;
    $hw = STDOUT;
}

//---- start process ----
//file_put_contents('../下载/IN.txt', "99\n"); for ($i = 0; $i < 99; $i ++) fake();
$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    $conf = explode(' ', read($hr));
    $H = []; $G = [];
    for ($i = 0; $i < $conf[2]; $i ++) {
        $row = explode(' ', read($hr));
        $H[] = intval($row[0]); $G[] = intval($row[1]);
    }
    //if ($c != 1) continue;
    write(solve($conf[0], $conf[1], $conf[2], $H, $G)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-1-26
 * Time: 下午4:51
 *
 * 把 extra_shot 当成是, 塔每打击一次释放的资源就行了.
 * 塔每打击一次, 释放一个 extra_shot, 每一个 extra_shot 只能用来打击一个怪.
 * 不要考虑回合.
 */