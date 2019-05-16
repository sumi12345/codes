<?php
ini_set("memory_limit", "128M");
define('ENV', 'test');

function solve($M, $R, $G) {
    array_unshift($R, 0); unset($R[0]);
    array_unshift($G, 0); unset($G[0]);
    //return solve_small($R, $G);
    //return solve_middle($R, $G);
    return solve_large($R, $G);
}

function solve_middle($R, $G) {
    // total weight of all metal left, determine when to stop
    $total_g = 0;
    foreach ($G as $m => $g) $total_g += $g;
    // expand set until need more than total weight left
    $need = [1]; $total = $G[1]; $G[1] = 0;
    for ($K = 0; $K < 10000; $K ++) {
        $produce = create_middle($need, $R, $G);
        foreach ($need as $m) $G[$m] -= $produce;
        $total += $produce; $total_g -= count($need) * $produce;
        if ($total_g < count($need)) break;
    }
    return $total;
}

// return new produce
function create_middle(&$need, $R, $G) {
    // expand
    $new_need = [];
    foreach ($need as $m) {
        $G[$m] --;
        if ($G[$m] >= 0) $new_need[] = $m;
        else { $new_need[] = $R[$m][0]; $new_need[] = $R[$m][1]; }
    }
    foreach ($need as $m) $G[$m] ++;
    $need = $new_need;
    // check if this set can produce new lead
    $sum = [];
    foreach ($need as $m) {
        if (!isset($sum[$m])) $sum[$m] = 0;
        $sum[$m] ++;
    }
    $min_g = 100;
    foreach ($sum as $m => $n) {
        $g = $G[$m] / $n;
        if ($g < $min_g) $min_g = $g;
    }
    $min_g = intval($min_g);
    //dd($min_g, 'produce');
    return $min_g;
}

function solve_small($R, $G) {
    for ($K = 0; $K < 64; $K ++) {
        $r = create(1, $R, $G, 0);
        if ($r == false) return $K;
    }
}

function create($m, $R, &$G, $level) {
    if ($G[$m] > 0) { $G[$m] --; return true; }
    if ($level == count($R)) return false;
    $l = create($R[$m][0], $R, $G, $level + 1);
    $r = create($R[$m][1], $R, $G, $level + 1);
    if ($l && $r) return true;
}

function fake() {
    $M = 8; $R = []; $G = [];
    for ($i = 1; $i <= $M; $i ++) {
        $r1 = $i; $r2 = $i;
        while ($r1 == $i || $r2 == $i) {
            $r1 = rand(1, $M - 1);
            $r2 = rand($r1 + 1, $M);
        }
        $R[] = $r1.' '.$r2;
        $G[] = rand(1, 8);
    }

    $str = $M."\n".implode("\n", $R)."\n".implode(' ', $G)."\n";
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
    $M = read($hr); $R = [];
    for ($i = 0; $i < $M; $i ++) {
        $R[] = explode(' ', read($hr));
    }
    $G = explode(' ', read($hr)); if ($c != 59) continue;
    write(solve($M, $R, $G)."\n", $hw);
}
dd(memory_get_usage() / 1024 / 1024);
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-1-4
 * Time: 下午2:24
 */