<?php
define('ENV', 'test');

function solve($R, $B) {
    // limit r and b
    $lr = $R; $lb = $B;
    // assume B == 0, max R
    for ($i = 0; $i <= $R; $i ++) {
        $max_r = $i * ($i + 1) / 2;
        if ($max_r >= $R) { $lr = $i; break; }
    }
    // assume R == 0, max B
    for ($j = 0; $j <= $B; $j ++) {
        $max_b = $j * ($j + 1) / 2;
        if ($max_b >= $B) { $lb = $j; break; }
    }
    // candidate set
    $V = [];
    for ($i = $lr; $i >= 0; $i --) {
        for ($j = $lb; $j >= 0; $j --) $V[] = [$i, $j];
    }
    // backpack, remove [0, 0], which is the last one
    $DP = []; unset($V[count($V) - 1]);
    $r = backpack(count($V) - 1, $R, $B, $V, $DP);
    // final choice, only for output
    dd($R.', '.$B, 'solve'); dd($lr.', '.$lb,'lr, lb');
    for ($i = count($V) - 1; $i > 0; $i --) {
        if ($R == 0 && $B == 0) break;
        $row = explode(' ', $DP[$i.' '.$R.' '.$B]);
        if ($row[0] == 'T') { $R -= $V[$i][0]; $B -= $V[$i][1]; dd($V[$i][0].', '.$V[$i][1]); }
    }
    // return
    return $r;
}

function backpack($I, $R, $B, $V, &$DP) {
    if (isset($DP[$I.' '.$R.' '.$B])) {
        $rs = explode(' ', $DP[$I.' '.$R.' '.$B]);
        return $rs[1] == '' ? false : intval($rs[1]);
    }
    // final
    $s = $V[$I]; $r = $s[0]; $b = $s[1];
    if ($R == 0 && $B == 0) return 0;
    if ($I == 0) { return $R == $r && $B == $b ? 1 : false; }
    // with or without it
    $a1 = false;
    if ($R >= $r && $B >= $b) $a1 = backpack($I - 1, $R - $r, $B - $b, $V, $DP);
    $a2 = backpack($I - 1, $R, $B, $V, $DP);
    // return and record
    $rs = $a1 === false ? $a2 : max($a1 + 1, $a2);
    $DP[$I.' '.$R.' '.$B] = ($rs - 1) === $a1 ? 'T '.$rs : 'F '.$rs;
    return $rs;
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
    $hr = fopen('../下载/IN.txt', 'r');
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
    $conf = explode(' ', read($hr)); //if ($c != 6) continue;
    write(solve($conf[0], $conf[1])."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-1-10
 * Time: 下午6:05
 */