<?php
define('ENV', 'test');

function solve($W, $L, $U, $G, $LP, $UP) {
    // for every x, where is the upper and lower bound
    $X = [];
    foreach ($LP as $p) $X[$p[0]] = [$p[1], '.'];
    foreach ($UP as $p) {
        if (!isset($X[$p[0]])) $X[$p[0]] = ['.', $p[1]];
        else $X[$p[0]][1] = $p[1];
    }
    ksort($X);
    $i = 0; $j = 0;
    foreach ($X as $x => &$y) {
        if ($y[0] === '.') for (; $i < $L; $i ++) {
            if ($LP[$i][0] < $x) continue;
            $y[0] = ypos($LP[$i - 1], $LP[$i], $x); break;
        }
        if ($y[1] === '.') for (; $j < $U; $j ++) {
            if ($UP[$j][0] < $x) continue;
            $y[1] = ypos($UP[$j - 1], $UP[$j], $x); break;
        }
    }
    // area sum and avg
    // * can't use x => y, because previously used &$y, this will change the value of the last element
    $area_sum = [0 => 0]; $pre_x = 0;
    foreach ($X as $x => $v) {
        $area = area($X[$pre_x][1] - $X[$pre_x][0], $X[$x][1] - $X[$x][0], $x - $pre_x);
        $area_sum[$x] = $area_sum[$pre_x] + $area;
        $pre_x = $x;
    }
    $avg_area = $area_sum[$W] / $G;
    dd($X, 'X');
    dd($area_sum, 'area_sum');
    dd($avg_area, 'avg_area');
    // cut
    $cut = []; $cut_area = $avg_area; $pre_x = 0;
    foreach ($area_sum as $x => $a) {
        while($a >= $cut_area) {
            $pre_a = $area_sum[$pre_x];
            $need_a = $cut_area - $pre_a;
            dd($pre_x.', '.$pre_a.', '.$need_a, 'pre_x, pre_a, need_a');
            $pos = xpos($X[$pre_x][1] - $X[$pre_x][0], $X[$x][1] - $X[$x][0], $x - $pre_x, $need_a);
            $cut[] = $pre_x + $pos;

            $cut_area += $avg_area;
            if (isset($cut[$G - 2])) break 2;
        }
        $pre_x = $x;
    }
    return "\n".implode("\n", $cut);
}

// given point 1 and 2, find a point on the line, x is given, return y
function ypos($p1, $p2, $x) {
    $t = ($p2[1] - $p1[1]) / ($p2[0] - $p1[0]);
    return $p1[1] + $t * ($x - $p1[0]);
}

// find a distance from left, let left part's area equal to the given value
// t = (y2 - y1) / xdis; y = y1 + tx; new_area = (y + y1) * x / 2;
// tx^2 + 2y1x - 2area = 0;
function xpos($y1, $y2, $xdis, $area) {
    if ($xdis == 0) return 0;
    $a = ($y2 - $y1) / $xdis; $b = 2 * $y1; $c = - 2 * $area;
    if ($a == 0) {
        $r = $area / $y1;
    } else {
        $d = $b * $b - 4 * $a * $c;
        $r1 = (- $b + sqrt($d)) / (2 * $a);
        $r2 = (- $b - sqrt($d)) / (2 * $a);
        $r = $r1 <= $xdis && $r1 > 0 ? $r1 : $r2;
    }
    dd($y1.', '.$y2.', '.$xdis.', '.$area, 'xpos: y1, y2, xdis, area');
    return $r;
}

// area between two vertical cut
function area($y1, $y2, $xdis) {
    dd($y1.', '.$y2.', '.$xdis, 'area: y1, y2, xdis');
    $r = ($y1 + $y2) * $xdis / 2;
    return $r;
}

function fake() {
    $W = 10; $L = 3; $U = 3; $G = 2;
    $LP = ['0 '.rand(-10, 0), '5 '.rand(-10, 0), '10 '.rand(-10, 0)];
    $UP = ['0 '.rand(0, 10), '5 '.rand(0, 10), '10 '.rand(0, 10)];
    $str = $W.' '.$L.' '.$U.' '.$G."\n".implode("\n", $LP)."\n".implode("\n", $UP)."\n";
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
    $hr = fopen('../下载/A-large-practice.in', 'r');
    //$hr = fopen('../下载/IN.txt', 'r');
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
    $LP = []; $UP = [];
    for ($i = 0; $i < $conf[1]; $i ++) {
        $LP[] = explode(' ', read($hr));
    }
    for ($i = 0; $i < $conf[2]; $i ++) {
        $UP[] = explode(' ', read($hr));
    }
    //if ($c > 2) continue;
    write(solve($conf[0], $conf[1], $conf[2], $conf[3], $LP, $UP)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-4-16
 * Time: 下午4:29
 */