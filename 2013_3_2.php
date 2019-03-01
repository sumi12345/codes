<?php
define('ENV', 'test');

function solve($N, $A) {
    // outer posts
    $matched = outer_posts($N, $A);
    $list = array_keys($matched); $list[] = $list[0];
    // match outer lines with points
    for ($K = $N - count($list); $K >= 0; $K --) { // until all match
        dd(implode(' ', $list), 'match '.$K);
        $min_area = []; $min_post = [];
        for ($i = 0; $i < count($list) - 1; $i ++) { // lines
            $min_a = -1; $min_j = -1;
            for ($j = 0; $j < $N; $j ++) if (!isset($matched[$j])) { // point
                $area = triangle_area($list[$i], $list[$i + 1], $j, $A);
                if ($area > 0 && ($min_a == -1 || $area < $min_a)) {
                    if (intersect($j, $list[$i], $i, $list, $A)) continue;
                    if (intersect($j, $list[$i + 1], $i, $list, $A)) continue;
                    $min_a = $area; $min_j = $j;
                }
            }
            if ($min_a != -1) { $min_area[$i] = $min_a; $min_post[$i] = $min_j; }
        }
        asort($min_area);
        dd($min_area, 'min_area '.$K); dd($min_post, 'min_post '.$K);
        foreach ($min_area as $i => $area) {
            $new_post = $min_post[$i];
            $new_list = [];
            foreach ($list as $idx => $k) {
                $new_list[] = $k;
                if ($idx == $i) $new_list[] = $new_post;
            }
            $matched[$new_post] = 1;
            $list = $new_list;
            break;
        }
        if (count($matched) == $N) break;
    }
    for ($i = 0; $i < $N - 1; $i ++) {
        for ($j = $i + 1; $j < $N - 1; $j ++) {
            $r1 = side($list[$i], $list[$i + 1], $list[$j], $A)
                * side($list[$i], $list[$i + 1], $list[$j + 1], $A);
            $r2 = side($list[$j], $list[$j + 1], $list[$i], $A)
                * side($list[$j], $list[$j + 1], $list[$i + 1], $A);
            if ($r1 < 0 && $r2 < 0) { dd(implode(' ', $list), 'intersect!'.$i.' and '.$j); exit; }
        }
    }
    array_pop($list);
    return implode(' ', $list);
}

function outer_posts($N, $A) {
    $list = [];
    dd($A, 'A');
    // find right most post
    arsort($A);
    $first = -1;
    foreach ($A as $k => $p) { $first = $k; break; }
    // counter-clockwise find next outer post
    $pk = $first; $pa = 90;           // previous index and angle
    for ($K = 0; $K < $N; $K ++) {    // untill reach first
        $list[$pk] = 1;
        $angle = [];
        for ($i = 0; $i < $N; $i ++) if ($i != $pk) {
            $angle[$i] = angle($pk, $i, $A);
        }
        asort($angle); //dd($angle, 'angle '.$pk);
        $nk = -1; $na = $pa;            // next index and angle
        foreach ($angle as $k => $a) {  // pa ~ 180
            if ($a < $pa) continue;
            $nk = $k; $na = $a; break;
        }
        if ($nk == -1) foreach ($angle as $k => $a) { // -180 ~ pa
            $nk = $k; $na = $a; break;
        }
        dd($pk.'->'.$nk.', '.$na, 'outer');
        $pk = $nk; $pa = $na;
        if ($nk == $first) break;
    }
    return $list;
}

// check if line between a1 and a2 intersect with other line
function intersect($a1, $a2, $I, $list, $A) {
    for ($i = 0; $i < count($list) - 1; $i ++) {
        if ($i == $I) continue;
        $r1 = side($list[$i], $list[$i + 1], $a1, $A)
            * side($list[$i], $list[$i + 1], $a2, $A);
        $r2 = side($a1, $a2, $list[$i], $A)
            * side($a1, $a2, $list[$i + 1], $A);
        if ($r1 < 0 && $r2 < 0) return true;
    }
}

// whether b is at the left side of a1-a2
function side($a1, $a2, $b, $A) {
    // (x1-x3)*(y2-y3)-(y1-y3)*(x2-x3)
    $a = ($A[$a1][0] - $A[$b][0]) * ($A[$a2][1] - $A[$b][1])
        - ($A[$a1][1] - $A[$b][1]) * ($A[$a2][0] - $A[$b][0]);
    return $a;
}

function triangle_area($k1, $k2, $k3, $A) {
    $a = sqrt(pow($A[$k3][0] - $A[$k1][0], 2) + pow($A[$k3][1] - $A[$k1][1], 2));
    $b = sqrt(pow($A[$k3][0] - $A[$k2][0], 2) + pow($A[$k3][1] - $A[$k2][1], 2));
    $angle = (angle($k3, $k1, $A) - angle($k3, $k2, $A)) / 180 * pi();
    $area = 1 / 2 * $a * $b * abs(sin($angle));
    // dd($area, 'area of '.$k1.', '.$k2.', '.$k3);
    return $area;
}

function angle($k1, $k2, $A) {
    $x = $A[$k2][0] - $A[$k1][0];
    $y = $A[$k2][1] - $A[$k1][1];
    $a = $x == 0 ? pi() / 2 : atan($y / $x);
    if ($y == 0) {                // 0, 180
        $a = $x >= 0 ? 0 : pi();
    } elseif ($x == 0) {          // 90, -90
        $a = $y > 0 ? pi() / 2 : - pi() / 2;
    } elseif ($y < 0 && $x < 0) { // 0 ~ 90 -> -180 ~ -90
        $a = atan($y / $x) - pi();
    } elseif ($y > 0 && $x < 0) { // 0 ~ -90 -> 180 ~ 90
        $a = atan($y / $x) + pi();
    }
    return $a / pi() * 180;
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
    $hr = fopen('../下载/B-small-practice.in', 'r');
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
    $N = read($hr); $A = [];
    for ($i = 0; $i < $N; $i ++) {
        $A[$i] = explode(' ', read($hr));
    }
    //if ($c != 35) continue;
    write(solve($N, $A)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-3-1
 * Time: 下午1:23
 *
 * small 通过, 但有个问题, 计算 outer 的时候, 相同方向随机选择,
 * 会导致有点在线段上, 而在内部判断的时候为了防止出现点在线段延长线的情况,
 * 已经屏蔽掉了面积为 0 的情况, 所以本来线段上的点要跟其他线段匹配.
 * 但是由于角度计算的误差, 线段上的点有一点点的面积, 正好稍带把问题解决了.
 * large 太慢.
 */