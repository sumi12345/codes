<?php
define('ENV', 'test');

function solve($N, $T, $P) {
    dd($N, 'N');
    // original sequence
    $S = [];
    for ($i = 0; $i < $N; $i ++) $S[] = $i;
    // bubble sorting
    for ($j = $N - 1; $j > 0; $j --) {
        for ($i = 0; $i < $j; $i ++) {
            $behind = larger($i, $i + 1, $S, $T, $P);
            if ($behind != $i + 1) {
                $t = $S[$i]; $S[$i] = $S[$i + 1]; $S[$i + 1] = $t;
            }
        }
        // dd(implode(' ', $S), 'S '.$j);
    }
    // return
    return implode(' ', $S);
}

// which one of position I and J in sequence S should be put behind
function larger($I, $J, $S, $T, $P) {
    // dd($I.' '.$J, 'cmp');
    $i = $S[$I]; $j = $S[$J];
    return $P[$i] * $T[$j] >= $P[$j] * $T[$i] ? $J : $I;
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
    $hr = fopen('../下载/A-large-practice.in', 'r');
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
    $N = read($hr);
    $L = explode(' ', read($hr));
    $P = explode(' ', read($hr));
    write(solve($N, $L, $P)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-4-5
 * Time: 下午7:33
 */