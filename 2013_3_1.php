<?php
define('ENV', 'test');

function solve($B, $N, $X) {
    // original
    sort($X);
    for ($i = 0; $i < 37 - $N; $i ++) array_unshift($X, 0);
    dd(implode(' ', $X), 'X');
    //$small = solve_small($B, $X);
    // sum
    $sum = [$X[0]];
    for ($i = 1; $i <= 36; $i ++) $sum[$i] = $X[$i] + $sum[$i - 1];
    // new
    $S = $X;
    $min = 0;
    for ($i = 0; $i < 36; $i ++) { // 0 to i <= i+1, smallest = min
        if ($B + $sum[$i] > $S[$i + 1] * ($i + 1)) continue;
        $min = floor(($B + $sum[$i]) / ($i + 1)); break;
    }
    if ($i == 36) $min = floor(($B + $sum[$i]) / ($i + 1));
    // try
    if ($min > 0) $min --;
    $B_left = $B;
    for ($j = 0; $j <= $i; $j ++) if ($S[$j] < $min) {
        $B_left -= $min - $S[$j]; $S[$j] = $min;
    }
    dd(implode(' ', $S), 'S');
    $max_profit = profit($X, $S);
    for ($i = 0; $i < $B_left; $i ++) {
        $min = $S[0];
        for ($j = 36; $j >= 0; $j --) if ($S[$j] == $min) {
            $S[$j] ++; break;
        }
        $profit = profit($X, $S);
        if ($profit > $max_profit) $max_profit = $profit;
    }
    //if ($max_profit != $small) { dd('wrong!'); exit; }
    return $max_profit;
}

function solve_small($B, $X) {
    // add budget one by one
    $S = $X;
    $max_profit = 0;
    for ($i = 0; $i < $B; $i ++) {
        $min = $S[0];
        for ($j = 36; $j >= 0; $j --) if ($S[$j] == $min) {
            $S[$j] ++; break;
        }
        $profit = profit($X, $S);
        if ($profit > $max_profit) $max_profit = $profit;
    }
    dd(implode(' ', $X), 'X');
    return $max_profit;
}

function profit($X, $S) {
    $min = $S[0];   // min total bet
    $min_num = 0;   // number of min total bet
    foreach ($S as $bet) if ($bet == $min) $min_num ++;
    $spend = 0;     // total spend
    foreach ($S as $k => $bet) $spend += $bet - $X[$k];
    $expect = 0;
    foreach ($S as $k => $bet) if ($bet == $min) {
        $expect += (1 / $min_num) * ($bet - $X[$k]) * 36;
    }
    dd('profit: '.($expect - $spend).', min: '.$min.', '.$min_num, $spend);
    return $expect - $spend;
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
    $conf = explode(' ', read($hr));
    $X = explode(' ', read($hr));
    //if ($c != 3) continue;
    write(solve($conf[0], $conf[1], $X)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-2-28
 * Time: 下午3:22
 */