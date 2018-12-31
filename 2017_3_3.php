<?php
define('ENV', 'test');

function solve($C, $E, $L, $D) {
    dd($C, 'C'); dd($E, 'E'); dd($L, 'L'); dd($D, 'D');
    $min = 2 * $C * (1000 + 24);
    for ($i = pow(2, $C) - 1; $i >= 0; $i --) {
        $t = calculateTime(decbin($i), $C, $E, $L, $D);
        if ($t !== false && $t < $min) $min = $t;
    }
    return $min;
}

function calculateTime($str, $C, $E, $L, $D) {
    for ($i = $C + 1 - strlen($str); $i > 0; $i --) $str = '0'.$str; //dd($str, 'calculateTime');
    $total = 0; $T = 0; $visited = [];
    $tour = $str[1] + 1;  // 起始团
    for ($i = 1; $i <= 2 * $C; $i ++) {
        $visited[$tour] = $i;
        $e = $E[$tour]; $l = $L[$tour]; $d = $D[$tour]; // 到达团, 离开时间, 耗时
        $t = $d + ($l - $T + 24) % 24;
        $n1 = 2 * $e - 1 + $str[$e]; $n2 = 2 * $e - 1 + 1 - $str[$e];
        $total += $t; $T = ($T + $t) % 24;
        $tour = ! isset($visited[$n1]) ? $n1 : (! isset($visited[$n2]) ? $n2 : false);
        if ($tour == false) break;
    }
    // dd($visited, 'visited');
    if (count($visited) < 2 * $C) return false;
    return $total;
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

$hr = fopen('../下载/C-small-practice.in', 'r');
$hw = fopen('../下载/OUT_3.txt', 'w');
$hw = fopen('../下载/OUT_3.txt', 'a');
//---- start process ----
$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    $C = read($hr); $E = []; $L = []; $D = [];
    for ($i = 1; $i <= 2 * $C; $i ++) {
        $r = explode(' ', read($hr));
        $E[$i] = $r[0]; $L[$i] = $r[1]; $D[$i] = $r[2];
    } //if ($c != 2) continue;
    write(solve($C, $E, $L, $D)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-31
 * Time: 下午3:58
 */