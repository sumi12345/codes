<?php
define('ENV', 'test');

function solve($C, $B) {
    // impossible
    array_unshift($B, 0); unset($B[0]);
    if ($B[1] == 0 || $B[$C] == 0) return 'IMPOSSIBLE';
    dd(implode(' ', $B), 'B');
    // every ball's final slot
    $final = []; $b = 1;
    foreach ($B as $slot => $num) {
        for ($i = 0; $i < $num; $i ++) $final[$b ++] = $slot;
    }
    // find the farthest ball
    $farthest = 0;
    foreach ($final as $ball => $slot) {
        $d = abs($ball - $slot);
        if ($d > $farthest) $farthest = $d;
    }
    // original map
    $M = []; $row = '';
    for ($j = 1; $j <= $C; $j ++) $row .= '.';
    for ($i = 0; $i <= $farthest; $i ++) $M[$i] = $row;
    // add ramp
    foreach ($final as $ball => $slot) {
        if ($slot == $ball) continue;
        $r = $slot - $ball > 0 ? '\\' : '/';
        $d = $slot - $ball > 0 ? 1 : -1;
        $row = abs($slot - $ball);
        for ($i = 0; $i < $row; $i ++) {
            $M[$i][$ball + $i * $d - 1] = $r;
        }
    }
    // return
    return ($farthest + 1)."\n".implode("\n", $M);
}

function fake() {
    $C = rand(2, 5); $B = []; $num = $C - 2;
    for ($i = 1; $i <= $C; $i ++) {
        $b = rand(0, floor($num / 2));
        $num -= $b;
        if ($i == 1) $b = $b + 1;
        if ($i == $C) $b = $num + 1;
        $B[] = $b;
    }
    $str = $C."\n".implode(' ', $B)."\n";
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
//file_put_contents('../下载/IN.txt', "99\n"); for ($i = 0; $i < 99; $i ++) fake();
$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    $C = read($hr);
    $B = explode(' ', read($hr)); //if ($c != 5) continue;
    write(solve($C, $B)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-1-9
 * Time: 下午4:34
 */