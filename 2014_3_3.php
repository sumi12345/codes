<?php
define('ENV', 'test');

function solve($A) {
    // every ID and records about them R = [ID => [idx => action]]
    $R = []; $R[0] = [];
    foreach ($A as $k => $a) {
        if (!isset($R[$a[1]])) $R[$a[1]] = [];
        $R[$a[1]][$k] = $a[0];
    }
    // try to prove there is no other door
    foreach ($A as $k => $a) dd($a[0].' '.$a[1], '-'.$k);
    $r = strait($A, $R);
    foreach ($A as $k => $a) dd($a[0].' '.$a[1], '+'.$k);
    if ($r === false) return 'CRIME TIME';
    // count R[0]
    ksort($R[0]); $stack = [];
    foreach ($R[0] as $k => $a) {
        if ($a == 'E') { $stack[] = $k; continue; }
        if (count($stack) > 0) array_pop($stack);
    }
    // return
    dd($stack, 'stack'); dd($r, 'r');
    return count($stack) + $r;
}

function strait(&$A, &$R) {
    $LL = []; $EE = []; $EL = []; // record LL, EE and EL intervals
    $FL = []; $LE = []; // record first Ls and last Es
    $n = count($A);
    foreach ($R as $id => $v) {
        if ($id == 0) continue;
        $v[$n] = 'E'; $pre_a = 'L'; $pre_k = -1;
        foreach ($v as $k => $a) {
            if ($a != $pre_a) {
                if ($a == 'L') $EL[$k] = $pre_k;
                $pre_a = $a; $pre_k = $k; continue;
            }
            if ($pre_k == -1) $FL[] = $k;
            elseif ($k == $n) $LE[] = $pre_k;
            elseif ($a == 'L') $LL[$k] = $pre_k;
            elseif ($a == 'E') $EE[$k] = $pre_k;
            $pre_a = $a; $pre_k = $k;
        }
    }
    dd($LL, 'LL'); dd($EE, 'EE');
    // from first event, assign 'L 0' to earliest finished EE interval
    ksort($EE);
    foreach ($R[0] as $k0 => $a) if ($a == 'L') {
        foreach ($EE as $k => $pre_k) if ($pre_k < $k0 && $k > $k0) {
            unset($R[0][$k0]); unset($EE[$k]); $A[$k0][1] = $A[$k][1].'*'; break;
        }
    }
    // from first event, assign 'L 0' left to earlest left Es
    sort($LE);
    foreach ($R[0] as $k0 => $a) if ($a == 'L') {
        $k = $n;
        foreach ($LE as $key => $pre_k) if ($pre_k < $k0 && $k > $k0) {
            unset($R[0][$k0]); unset($LE[$key]); $A[$k0][1] = $A[$pre_k][1].'-'; break;
        }
    }
    // from first event, break an EL interval, creating an LL interval
    krsort($EL);
    foreach ($R[0] as $k0 => $a) if ($a == 'L') {
        foreach ($EL as $k => $pre_k) if ($pre_k < $k0 && $k > $k0) {
            for ($i = $k0; $i < $k; $i ++) if ($A[$i] == ['E', 0]) break;
            if ($i == $k) continue; // without E 0 in new LL interval
            unset($R[0][$k0]); unset($EL[$k]); $LL[$k] = $k0;
            $A[$k0][1] = $A[$k][1].'c'; break;
        }
    }
    // from last event, assign 'E 0' to latest started LL interval
    krsort($R[0]); arsort($LL);
    foreach ($R[0] as $k0 => $a) if ($a == 'E') {
        foreach ($LL as $k => $pre_k) if ($pre_k < $k0 && $k > $k0) {
            unset($R[0][$k0]); unset($LL[$k]); $A[$k0][1] = $A[$k][1].'*'; break;
        }
    }
    // from last event, assign 'E 0' left to latest left Ls
    rsort($FL);
    foreach ($R[0] as $k0 => $a) if ($a == 'E') {
        $pre_k = -1;
        foreach ($FL as $key => $k) if ($pre_k < $k0 && $k > $k0) {
            unset($R[0][$k0]); unset($FL[$key]); $A[$k0][1] = $A[$k][1].'-'; break;
        }
    }
    // return
    return empty($LL) && empty($EE) ? count($LE) : false;
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
    $N = read($hr); $A = [];
    for ($i = 0; $i < $N; $i ++) {
        $A[$i] = explode(' ', read($hr));
    }
    //if ($c != 24) continue;
    write(solve($A)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-1-31
 * Time: 0:45
 */