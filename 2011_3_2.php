<?php
define('ENV', 'test');

function solve($N, $A) {
    // N = 0
    if ($N == 0) return 0;
    // record number => count
    $cnt = []; sort($A); dd(implode(' ', $A), 'A');
    foreach ($A as $a) {
        if (!isset($cnt[$a])) $cnt[$a] = 0;
        $cnt[$a] ++;
    }
    // find the start points and end points
    $start = []; $end = []; $A = array_flip($A);
    foreach ($A as $a => $v) {
        if (!isset($A[$a - 1]) || $cnt[$a] > $cnt[$a - 1]) {
            $start_num = isset($A[$a - 1]) ? $cnt[$a] - $cnt[$a - 1] : $cnt[$a];
            for ($i = 0; $i < $start_num; $i ++) $start[] = $a;
        }
        if (!isset($A[$a + 1]) || $cnt[$a] > $cnt[$a + 1]) {
            $end_num = isset($A[$a + 1]) ? $cnt[$a] - $cnt[$a + 1] : $cnt[$a];
            for ($i = 0; $i < $end_num; $i ++) $end[] = $a;
        }
    }
    dd($start, 'start');
    dd($end, 'end');
    // find minimal length
    $min_len = $N; $num = count($start);
    for ($i = 0; $i < $num; $i ++) {
        $len = $end[$i] - $start[$i] + 1;
        if ($len < $min_len) $min_len = $len;
    }
    return $min_len;
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
    $hr = fopen('../下载/B-large-practice.in', 'r');
    //$hr = fopen('../下载/IN.txt', 'r');
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
    $A = explode(' ', read($hr));
    $N = $A[0]; array_shift($A);
    //if ($c > 8) continue;
    write(solve($N, $A)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-4-17
 * Time: 下午4:13
 */