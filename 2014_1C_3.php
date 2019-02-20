<?php
define('ENV', 'test');

function solve($N, $M, $K) {  // N rows, M columns, K points to enclose
    //dd($N.', '.$M.', '.$K, 'N, M, K');
    if ($K <= 4) return $K;   // need K stones anyway
    if ($N > $M) { $t = $N; $N = $M; $M = $t; }  // make R <= C
    $min_stone = $K;
    for ($R = 1; $R <= $N; $R ++) {  // cut from rectangle size R*C
        for ($C = 1; $C <= $M; $C ++) {
            if ($R * $C < $K) continue;
            for ($I = 0; $I <= 2 * $R; $I ++) {
                $cover = $R * $C;    // cut triangle from 4 corners
                $cover -= empty_triangle(floor($I / 4));
                $cover -= empty_triangle(floor(($I + 1) / 4));
                $cover -= empty_triangle(floor(($I + 2) / 4));
                $cover -= empty_triangle(floor(($I + 3) / 4));
                if ($cover < $K) break;
                $stone = 2 * ($R + $C) - 4 - $I;
                if ($stone < $min_stone) $min_stone = $stone;
                //dd($R.', '.$C.', '.$I.' = '.$stone, 'RCI, stone');
            }
        }
    }
    return $min_stone;
}

function empty_triangle($size) {
    return $size * ($size + 1) / 2;
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
    $hr = fopen('../下载/C-large-practice.in', 'r');
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
    //if ($c != 2) continue;
    write(solve($conf[0], $conf[1], $conf[2])."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-2-20
 * Time: 下午9:27
 */