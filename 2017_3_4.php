<?php
define('ENV', 'test');

function solve($R, $C, $N, $D, $fixed) {
    /* 构造图, 运行 Dijkstra 算法, 速度太慢, 放弃
    $G = [];
    for ($i = $R * $C; $i >= 0; $i --) $G[$i] = [];
    for ($i = ($R - 1) * $C; $i >= 1; $i --) { $G[$i][$i + $C] = $D; $G[$i + $C][$i] = $D; }
    for ($i = 0; $i < $R; $i ++) for ($j = 1; $j < $C; $j ++) {
        $c = $i * $C + $j; $G[$c][$c + 1] = $D; $G[$c + 1][$c] = $D;
    }
    for ($i = 0; $i < $N; $i ++) {
        $f = $fixed[$i];
        $G[0][($f[0] - 1) * $C + $f[1]] = $f[2];
    }
    $D = Dijkstra($G);
    */

    $D = min_distance($R, $C, $N, $D, $fixed);
    for ($i = 0; $i < $N; $i ++) {
        $f = $fixed[$i];
        if ($D[($f[0] - 1) * $C + $f[1]] != $f[2]) return 'IMPOSSIBLE';
    }

    $total = 0;
    foreach ($D as $n => $d) $total = ($total + $d) % (1000000007);
    return $total;
}

function min_distance($R, $C, $N, $D, $fixed) {
    $min_d = [];
    for ($k = 0; $k < $N; $k ++) {
        $f = $fixed[$k];
        for ($i = 1; $i <= $R; $i ++) {
            for ($j = 1; $j <= $C; $j ++) {
                $n = ($i - 1) * $C + $j;
                $d = $f[2] + $D * (abs($f[0] - $i) + abs($f[1] - $j));
                if (!isset($min_d[$n]) || $min_d[$n] > $d) $min_d[$n] = $d;
            }
        }
    }
    return $min_d;
}

function Dijkstra($G) {
    $D = $G[0]; $N = count($G) - 1; $visited = [];
    for ($K = 0; $K < $N; $K ++) {
        $min_dis = -1; $node = 0;
        foreach ($D as $n => $d) if (!isset($visited[$n])) {
            if ($min_dis == -1 || $d < $min_dis) { $min_dis = $d; $node = $n; }
        }
        foreach ($G[$node] as $i => $d) if (!isset($visited[$i])) {
            if (!isset($D[$i]) || $D[$node] + $d < $D[$i]) $D[$i] = $D[$node] + $d;
        }
        $visited[$node] = $K;
        if (isset($G[0][$node]) && $G[0][$node] != $min_dis) return false;
        if ($K % 100 == 0) dd($K, 'K');
    }
    return $D;
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

$hr = fopen('../下载/D-small-practice.in', 'r');
$hw = fopen('../下载/OUT_4.txt', 'w');
$hw = fopen('../下载/OUT_4.txt', 'a');
//---- start process ----
$t = time();

$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    $conf = explode(' ', read($hr)); $fixed = [];
    for ($i = 0; $i < $conf[2]; $i ++) $fixed[] = explode(' ', read($hr));
    write(solve($conf[0], $conf[1], $conf[2], $conf[3], $fixed)."\n", $hw);
}

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-31
 * Time: 下午4:46
 */