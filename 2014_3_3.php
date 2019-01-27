<?php
define('ENV', 'test');

function solve($N, $A) {
    // every ID and records about them R = [ID => [idx => action]]
    $R = []; $R[0] = []; $first = []; $last = [];
    foreach ($A as $k => $a) {
        if (!isset($R[$a[1]])) $R[$a[1]] = [];
        $R[$a[1]][$k] = $a[0];
        if ($a[1] != 0) $last[$a[1]] = $k;
        if ($a[1] != 0 && !isset($first[$a[1]])) $first[$a[1]] = $k;
    }
    dd($R, 'R');
    // for every ID which is not 0, try to prove there is no other door
    foreach ($R as $id => $v) if ($id > 0) {
        $r = strait($id, $R);
        if ($r === false) return 'CRIME TIME';
    }
    // for every ID which is not 0, if first action is L or last is E
    foreach ($first as $id => $k) if ($A[$k][0] == 'E') unset($first[$id]);
    foreach ($last as $id => $k) if ($A[$k][0] == 'L') unset($last[$id]);
    rsort($first); sort($last);  // drop id and sort by time
    dd(implode(' ', $first), 'first leave');
    dd(implode(' ', $last), 'last stay');
    // count R[0]
    $stack = [];
    foreach ($R[0] as $k => $a) {
        if ($a == 'E') { $stack[] = $k; continue; }
        $m = false;   // if a = L, match found in last stay
        foreach ($last as $p => $ke) if ($ke < $k) {
            unset($last[$p]); $R[0][$k] .= '<-'.$ke; $m = true; break;
        }
        if (!$m && count($stack) > 0) array_pop($stack);
    }
    // match stack with first leave after it, start from the last one
    rsort($stack); dd($stack, 'stack');
    foreach ($stack as $ks => $k) {
        foreach ($first as $p => $kl) if ($kl > $k) {
            unset($first[$p]); $R[0][$k] .= '->'.$kl;
            unset($stack[$ks]); break;
        }
    }
    dd($R[0], 'R[0]');
    return count($stack) + count($last);
}

function strait($I, &$R) {
    $pre_k = -1; $pre_a = '';
    foreach ($R[$I] as $k => $a) {
        if ($a == $pre_a && $a == 'E') {
            for ($i = $pre_k; $i < $k; $i ++) {
                if (!isset($R[0][$i]) || $R[0][$i] != 'L') continue;
                unset($R[0][$i]); $R[$I][$i] = 'L'; break;
            }
            if ($i == $k) return false;
        }
        if ($a == $pre_a && $a == 'L') {
            for ($i = $k; $i > $pre_k; $i --) {
                if (!isset($R[0][$i]) || $R[0][$i] != 'E') continue;
                unset($R[0][$i]); $R[$I][$i] = 'E *'; break;
            }
            if ($i == $pre_k) return false;
        }
        $pre_a = $a; $pre_k = $k;
    }
    ksort($R[$I]); dd($R[$I], 'strait '.$I);
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
    $hr = fopen('../下载/C-small-practice.in', 'r');
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
    //if ($c != 5) continue;
    write(solve($N, $A)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-1-27
 * Time: 下午1:51
 */