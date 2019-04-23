<?php
ini_set('xdebug.max_nesting_level', 100);
define('ENV', 'test');

function solve($N, $B) {
    $num = 0;
    for ($K = 1; $K <= $B; $K ++) $num += count2($K, $N, $B);
    return $num;
}

// divide N into K part to get all valid combinations
function count2($K, $N, $B) {
    dd($K, 'count2');
    $F = [-1 => 0];   // the i-th summand is F[i]
    return fill2(0, $K, $F, $N, $B);
}

// find the k-th summand of N, record it in F
function fill2($k, $K, &$F, $N, $B) {
    // previous sum
    $s = 0;
    for ($i = 0; $i < $k; $i ++) $s += $F[$i];
    // finished
    if ($k >= $K) {
        return $s == $N && check($F, $B) ? 1 : 0;
    }
    // try to find summand
    $num = 0;
    for ($i = $F[$k - 1] + 1; $i <= $N - $K + $k + 1; $i ++) {
        $F[$k] = $i;
        if ($s + ($K - $k) * $i > $N) break;
        $num += fill2($k + 1, $K, $F, $N, $B);
    }
    unset($F[$k]);
    return $num;
}

// check whether this set is valid
function check($set, $B) {
    $column = []; $valid = true;
    foreach ($set as $member) {
        $c = 0;
        while ($member > 0) {
            $s = $member % $B;
            if (isset($column[$c][$s])) { $valid = false; break 2; }
            $column[$c][$s] = 1;
            $member = intval($member / $B);
            $c ++;
        }
    }
    return $valid;
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
    $hr = fopen('../下载/D-small-practice.in', 'r');
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
    //if ($c > 2) continue;
    write(solve($conf[0], $conf[1])."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-4-23
 * Time: 下午3:17
 */