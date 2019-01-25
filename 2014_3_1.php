<?php
define('ENV', 'test');

function solve($N, $p, $q, $r, $s) {
    // calculate sum
    $sum = [0];
    for ($i = 0; $i < $N; $i ++) {
        $sum[] = $sum[$i] + ($i * $p + $q) % $r + $s;
    }
    //dd($sum, 'sum');
    // find 1/3 and 2/3 boundry
    $total = $sum[$N];
    $l = bin_search($total / 3, $sum);
    $r = min(bin_search($total / 3 * 2, $sum) + 1, $N);
    dd($total, 'total');
    dd($l.', '.$r.', '.($total / 3), 'l, r, 1/3');
    // get the optimal option
    $s = $total;
    for ($i = $l; $i <= $r; $i ++) {
        for ($j = $r; $j >= $i; $j --) {
            $mp = max_part($i, $j, $sum);
            if ($mp < $s) $s = $mp;
            if ($sum[$j] - $sum[$i] < $total / 3) break;
        }
        if ($sum[$r] - $sum[$i] < $total / 3) break;
    }
    dd($s.', '.($total - $s), 's, a');
    return ($total - $s) / $total;
}

function solve_small($N, $p, $q, $r, $s) {
    // calculate sum
    $sum = [0];
    for ($i = 0; $i < $N; $i ++) {
        $sum[] = $sum[$i] + ($i * $p + $q) % $r + $s;
    }
    // for every pair of i, j, find optimal s
    $total = $sum[$N];
    $s = $total;
    for ($i = 0; $i <= $N; $i ++) {
        for ($j = $i; $j <= $N; $j ++) {
            $mp = max_part($i, $j, $sum);
            if ($mp < $s) $s = $mp;
        }
    }
    return ($total - $s) / $total;
}

function max_part($i, $j, $sum) {
    $N = count($sum) - 1;
    if ($j > $N || $i > $j) return $sum[$N]; // invalid
    $r = max($sum[$i], $sum[$j] - $sum[$i], $sum[$N] - $sum[$j]);
    dd($r, 'max_part('.$i.', '.$j.')');
    return $r;
}

function bin_search($num, $arr) {
    $l = 0; $r = count($arr) - 1; $m = 0;
    for ($K = 0; $K <= 20; $K ++) { // untill m = l or m = r
        $m = floor(($l + $r) / 2);
        if ($m == $l || $m == $r) return $l;
        if ($arr[$m] == $num) return $m;
        if ($arr[$m] < $num) $l = $m;
        else $r = $m;
    }
    dd('K might not big enough');
}

function fake() {
    $N = rand(1, 10); $N = 4;
    $p = rand(1, 10); $q = rand(1, 10);
    $r = rand(1, 10); $s = rand(1, 10);
    $str = $N.' '.$p.' '.$q.' '.$r.' '.$s."\n";
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
    $hw = fopen('../下载/OUT_1.txt', 'w');
    $hw = fopen('../下载/OUT_1.txt', 'a');
} else {
    $hr = STDIN;
    $hw = STDOUT;
}

//---- start process ----
file_put_contents('../下载/IN.txt', "99\n"); for ($i = 0; $i < 99; $i ++) fake();
$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    $C = explode(' ', read($hr)); //if ($c != 94) continue;
    write(solve($C[0], $C[1], $C[2], $C[3], $C[4])."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-1-25
 * Time: 下午3:56
 */