<?php
define('ENV', 'test');

function solve($L, $N, $B) {
    rsort($B); $A = $B[0];
    // with one length
    if ($N == 1) return $L % $A == 0 ? $L / $A : 'IMPOSSIBLE';
    // all length using boards shorter than the longest one
    $max_l = $B[0] * $B[1];
    $S = [0 => 0];
    for ($len = 1; $len < $max_l; $len ++) {
        $min_num = -1;
        foreach ($B as $b) if (isset($S[$len - $b])) {
            $num = $S[$len - $b];
            if ($min_num == -1 || $num < $min_num) $min_num = $num;
        }
        if ($min_num > -1) $S[$len] = $min_num + 1;
    }
    // find min num
    $min_num = -1;
    foreach ($S as $len => $num) {
        if (($L - $len) % $B[0] != 0) continue;
        $num += ($L - $len) / $B[0];
        if ($min_num == -1 || $num < $min_num) $min_num = $num;
    }
    if ($min_num > -1) return $min_num;
    return 'IMPOSSIBLE';
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
    $hr = fopen('../下载/B-small-practice.in', 'r');
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
    $conf = explode(' ', read($hr));
    $B = explode(' ', read($hr));
    write(solve($conf[0], $conf[1], $B)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-4-21
 * Time: 下午4:39
 */