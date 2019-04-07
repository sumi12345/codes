<?php
ini_set('memory_limit', '256M');
define('ENV', 'test');

function solve($M, $F, $N, $P, $S) {
    dd($M.', '.$F, 'M, F');
    asort($P);
    $C = []; $cost = 0; $day = 0;
    foreach ($P as $k => $p) {
        $d = $S[$k] + 1 - $day;
        if ($d < 0) continue;
        for ($i = 1; $i <= $d; $i ++) {
            $c = $cost + $i * $p;
            $C[] = $c + $F;
            if ($c > $M - $F) break;
        }
        $cost += $p * $d; $day = count($C);
        if ($cost > $M - $F) break;
    }
    // if we have X packages, how many days can we last
    $max_day = 0;
    $p = intval($M / $C[0]);
    $i = $day - 1;
    for ($X = 1; $X <= $p; $X ++) {
        $mperpackage = intval($M / $X);
        for (; $i >= 0; $i --) if ($C[$i] <= $mperpackage) {
            $mleft = $M - $X * $C[$i];
            $d = $X * ($i + 1);
            if (isset($C[$i + 1])) $d += intval($mleft / ($C[$i + 1] - $C[$i]));
            if ($d > $max_day) $max_day = $d;
            break;
        }
    }
    return $max_day;
}

function fake() {
    $str = "\n";
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
    $hr = fopen('../下载/C-small-practice.in', 'r');
    $hw = fopen('../下载/OUT_3.txt', 'w');
    $hw = fopen('../下载/OUT_3.txt', 'a');
} else {
    $hr = STDIN;
    $hw = STDOUT;
}

//---- start process ----
//file_put_contents('../下载/IN.txt', "99\n"); for ($i = 0; $i < 99; $i ++) fake();
$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    $P = []; $S = [];
    $conf = explode(' ', read($hr));
    for ($i = 0; $i < $conf[2]; $i ++) {
        $row = explode(' ', read($hr));
        $P[] = $row[0]; $S[] = $row[1];
    }
    //if ($c > 3) continue;
    write(solve($conf[0], $conf[1], $conf[2], $P, $S)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-4-6
 * Time: 下午2:42
 */