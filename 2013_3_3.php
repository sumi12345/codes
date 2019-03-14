<?php
define('ENV', 'test');
function solve($N, $M, $P, $shuttle, $route) {
    // build graph, G[from][to] = shuttle_id
    $G = [];
    foreach ($shuttle as $k => $s) {
        $u = $s[0]; $v = $s[1];
        if (!isset($G[$u])) $G[$u] = [];
        $G[$u][$v] = $k;
    }
    // find first not possible
    for ($i = 0; $i < $P; $i ++) {
        $r = check_prefix($i, $shuttle, $route, $G);
        if ($r === false) {
            dd($i.'/'.$P, 'check_prefix');
            return $route[$i];
        }
    }
    return 'Looks Good To Me';
}

// is it possible to be shortest if Ith part of route is preserved
function check_prefix($I, $shuttle, $route, $G) {
    // prefix total cost, end city, prefix shuttle id, prefix city
    $cost = 0; $city = 0; $prefix = []; $prefix_city = [];
    for ($i = 0; $i <= $I; $i ++) {
        $k = $route[$i];
        $city = $shuttle[$k][1];
        $cost += $shuttle[$k][2];
        $prefix[$k] = 1;
        $prefix_city[$city] = 1;
    }
    // Dijkstra
    // * don't include prefix in D, bad robot is allowed to visit prefix city earlier
    $D = [1 => 0, $city => $cost - 0.5];
    $visited = [];
    for ($K = 0; $K <= 2000; $K ++) { // until all cities visited
        $min_dis = -1; $min_city = -1;
        foreach ($D as $c => $d) if (!isset($visited[$c])) {
            if ($d < $min_dis || $min_dis == -1) { $min_dis = $d; $min_city = $c; }
        }
        $visited[$min_city] = $min_dis;
        $is_good = $min_dis - floor($min_dis) > 0.1;
        if (isset($G[$min_city])) foreach ($G[$min_city] as $v => $k) {
            if (isset($visited[$v])) continue;
            // * if a shortest route has a circle, then it could not be shortest
            if ($is_good && isset($prefix_city[$v])) continue;
            $d = $is_good || isset($prefix[$k]) ? $shuttle[$k][2] : $shuttle[$k][3];
            if (!isset($D[$v]) || $D[$v] > $D[$min_city] + $d) {
                $D[$v] = $D[$min_city] + $d;
            }
        }
        if (isset($visited[2])) { return $is_good; }
    }
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
    $shuttle = [];
    for ($i = 1; $i <= $conf[1]; $i ++) {
        $shuttle[$i] = explode(' ', read($hr));
    }
    $route = explode(' ', read($hr));
    //if ($c != 9) continue;
    write(solve($conf[0], $conf[1], $conf[2], $shuttle, $route)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-3-13
 * Time: 下午8:56
 */