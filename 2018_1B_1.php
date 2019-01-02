<?php
define('ENV', 'test');

function solve($N, $L, $C) {
    $unit = 1 / $N * 100; dd('N='.$N.', unit='. $unit, 'solve'); dd($C, 'C');

    // how many support you need to form a group can be rounded up
    $new_lang = need(0, $unit);
    if ($new_lang == 0) return 100;    // unit is int
    dd($new_lang, 'new_lang');

    $old_lang = [];
    for ($i = 0; $i < $L; $i ++) {
        $old_lang[$i] = need($C[$i], $unit);
    }
    dd($old_lang, 'old_lang');

    // distribute the rest to existing group by need
    $support_rate = [];
    $cnt = $N; foreach ($C as $l => $n) $cnt -= $n;
    asort($old_lang);
    foreach ($old_lang as $l => $n) {
        if ($n > $new_lang || $cnt < $n) break;
        $support_rate[$l] = round($unit * ($C[$l] + $n));
        $cnt -= $n;
    }
    dd($support_rate, 'support_rate_1');

    // groups getting no more support
    foreach ($C as $l => $n) {
        if (isset($support_rate[$l])) continue;
        $support_rate[$l] = round($unit * $C[$l]);
    }
    dd($support_rate, 'support_rate_2');

    // return
    $total = 0; foreach ($support_rate as $r) $total += $r;
    $new_up = floor($cnt / $new_lang) * round($new_lang * $unit);
    $new_down = ($cnt % $new_lang) * $unit;
    dd($total, 'total'); dd($new_up, 'new_up'); dd($new_down, 'new_down');
    return $total + $new_up + round($new_down);
}

function need($current_support, $unit) {
    $current_rate = $current_support * $unit;
    if (round($current_rate) > $current_rate) return 0;          // already can be rounded up
    $i = floor($current_rate); if ($i + 0.5 < $current_rate) $i += 1;   // find a start point
    while ($i < 100) {                                           // check 0.5, 1.5, ..., 99.5
        $n0 = ceil(($i + 0.5) / $unit);       // at least how many support you need to reach i + 0.5
        $n = $n0 * $unit; dd('i='.$i.', n0='.$n0.', n='.$n);
        if (round($n) > $n) return $n0 - $current_support;      // if round(n) < n, impossible in this range
        $i = max(floor($n), $i + 1);                    // next i
    }
    return 0;
}

function fake() {
    $N = rand(2, 25); $L = rand(1, $N); $C = []; $sum = $N - $L;
    for ($i = 0; $i < $L; $i ++) {
        $c = $sum >= 1 ? rand(1, floor($sum / 4)) : 0;
        $C[] = $c + 1;
        $sum -= $c;
    }
    $str = $N.' '.$L."\n".implode(' ', $C)."\n";
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
    $hw = fopen('../下载/OUT_1.txt', 'w');
    $hw = fopen('../下载/OUT_1.txt', 'a');
} else {
    $hr = STDIN;
    $hw = STDOUT;
}

//---- start process ----
//file_put_contents('../下载/IN.txt', "100\n"); for ($i = 0; $i < 100; $i ++) fake();
$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    $conf = explode(' ', read($hr));
    $C = explode(' ', read($hr)); //if ($c != 5) continue;
    write(solve($conf[0], $conf[1], $C)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-1-2
 * Time: 下午2:39
 */