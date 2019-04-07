<?php
define('ENV', 'test');

function solve($k, $S) {
    dd($S, 'S');
    if ($k == 2) return solve_small($S);
}

// k = 2, using Eulerian paths
function solve_small($S) {
    // record graph, in-degree, out-degree
    $char = 'abcdefghijklmnopqrstuvwxyz';
    $ex = ['o' => 0, 'i' => 1, 'e' => 3, 'a' => 4, 's' => 5, 't' => 7, 'b' => 8, 'g' => 9];
    $G = []; $in = []; $out = [];
    for ($i = 0; $i < 26; $i ++) {
        $c = $char[$i]; $G[$c] = []; $in[$c] = 0; $out[$c] = 0;
    }
    $len = strlen($S);
    for ($i = 0; $i < $len - 1; $i ++) {
        $u = $S[$i]; $v = $S[$i + 1];
        if (isset($G[$u][$v])) continue;
        $G[$u][$v] = 1;
        $out[$u] += isset($ex[$v]) ? 2 : 1;
        $in[$v] += isset($ex[$u]) ? 2 : 1;
    }
    // record num of path we already have by in-degree, and balance of nodes
    $in_num = 0; $in_need = 0; $out_need = 0;
    foreach ($in as $c => $v) {
        $ni = $in[$c]; $no = $out[$c]; $d = isset($ex[$c]) ? 2 : 1;
        $in_num += $d * $ni;
        if ($ni < $no) $in_need += $d * ($no - $ni);
        elseif ($no < $ni) $out_need += $d * ($ni - $no);
    }
    // how many paths we have to add
    // it is allowed to have one node with 1 in-degree and one with 1 out-degree
    $need = intval(($in_need + $out_need) / 2);
    if ($in_need > 0 && $in_need == $out_need) $need -= 1;
    // return
    dd($in_num, 'in_num');
    dd($in_need, 'in_need');
    dd($out_need, 'out_need');
    return 1 + $in_num + $need;
}

function fake() {
    $k = 2;
    $char = 'abcdefghijklmnopqrstuvwxyz';
    $len = 5;
    $arr = [];
    for ($i = 0; $i < $len; $i ++) {
        $arr[] = $char[rand(0, 25)];
    }
    $str = $k."\n".implode('', $arr)."\n";
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
    $hr = fopen('../下载/D-small-practice.in', 'r');
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
    $k = read($hr);
    $S = read($hr);
    write(solve($k, $S)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-4-7
 * Time: 下午5:41
 */