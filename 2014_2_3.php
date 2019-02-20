<?php
define('ENV', 'test');

function solve($W, $H, $B, $buildings) {
    $M = [];                            // map
    $M['s'] = []; $M['s']['t'] = $W;    // source and destination
    foreach ($buildings as $k => $b) {  // building's distance to s, t
        $M[$k] = [];
        $M['s'][$k] = $b[0]; $M[$k]['t'] = $W - $b[2] - 1;
    }
    for ($i = 0; $i < $B; $i ++) {      // distance between buildings
        for ($j = $i + 1; $j < $B; $j ++) {
            $b1 = $buildings[$i]; $b2 = $buildings[$j];
            $dis = max(xdis($b1, $b2), ydis($b1, $b2));
            $M[$i][$j] = $dis; $M[$j][$i] = $dis;
        }
    }
    // find min distance from s to t by Dijkstra
    $D = $M['s']; $visited = [];
    for ($K = 0; $K <= $B; $K ++) {  // untill t is visited
        $min_d = $W; $min_k = 't';
        foreach ($D as $k => $d) {
            if (!isset($visited[$k]) && $d < $min_d) {
                $min_d = $d; $min_k = $k;
            }
        }
        $visited[$min_k] = $min_d;
        if (isset($visited['t'])) {
            //dd($visited, 'visited');
            return $min_d;
        }
        dd($min_k.', '.$min_d, 'min_k, d');
        foreach ($D as $k => $d) {
            if (!isset($visited[$k])) {
                $new_d = $min_d + $M[$min_k][$k];
                if ($new_d < $d) $D[$k] = $new_d;
            }
        }
    }
}

function xdis($b1, $b2) {  // x distance between building 1 and 2
    if ($b1[0] > $b2[0]) { $t = $b1; $b1 = $b2; $b2 = $t; } // b1 left
    if ($b2[0] <= $b1[2]) return 0; // share the same column
    return $b2[0] - $b1[2] - 1;
}

function ydis($b1, $b2) { // y distance between building 1 and 2
    if ($b1[1] > $b2[1]) { $t = $b1; $b1 = $b2; $b2 = $t; } // b1 lower
    if ($b2[1] <= $b1[3]) return 0; // share the same row
    return $b2[1] - $b1[3] - 1;
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
    //$hr = fopen('../下载/C-small-practice.in', 'r');
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
    $buildings = [];
    for ($i = 0; $i < $conf[2]; $i ++) {
        $buildings[] = explode(' ', read($hr));
    }
    //if ($c != 83) continue;
    write(solve($conf[0], $conf[1], $conf[2], $buildings)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-2-20
 * Time: 下午1:11
 */