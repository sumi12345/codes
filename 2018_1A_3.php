<?php
define('ENV', 'test');

function solve($N, $P, $W, $H) {
    //return solve_small($N, $P, $W, $H);
    return solve_large($N, $P, $W, $H);
}

function solve_large($N, $P, $W, $H) {
    // sum of the perimeters of all cookies before any cuts are made
    $sum = 0;
    for ($i = 0; $i < $N; $i ++) $sum += 2 * ($W[$i] + $H[$i]); dd($P, 'P'); dd($sum, 'sum');
    // range of extra perimeters each cookie can provide
    $low = []; $high = [];
    for ($i = 0; $i < $N; $i ++) {
        $low[$i] = 2 * min($W[$i], $H[$i]);
        $high[$i] = 2 * sqrt($W[$i] * $W[$i] + $H[$i] * $H[$i]);
    }
    // if P is not reachable
    $h = 0;
    for ($i = 0; $i < $N; $i ++) $h += $high[$i];
    if ($sum + $h < $P) return $sum + $h;
    // if P is reachable, find a subset
    $DP = [];
    return $sum + subset($N - 1, $low, $high, $P - $sum, $P - $sum, $DP);
}

function subset($I, $low, $high, $L, $H, &$DP) {
    // logged
    if (isset($DP[$I][$L][$H])) return $DP[$I][$L][$H];
    // hit, go with high bound
    if ($low[$I] <= $L && $high[$I] >= $H) return $H;
    // one left and not hit
    if ($I == 0) return $low[$I] > $L ? 0 : $high[$I];
    // without & with this item
    $s1 = subset($I - 1, $low, $high, $L, $H, $DP);
    $s2 = $low[$I] > $L ? 0 : subset($I - 1, $low, $high, $L - $low[$I], $H - $high[$I], $DP) + $high[$I];
    dd('subset: I='.$I.', L='.$L.', H='.$H.', low[I]='.$low[$I].', high[I]='.$high[$I].', s1='.$s1.', s2='.$s2);
    // return
    $r = $L - $s1 > $L - $s2 ? $s2 : $s1;
    if (!isset($DP[$I])) $DP[$I] = [];
    if (!isset($DP[$I][$L])) $DP[$I][$L] = [];
    $DP[$I][$L][$H] = $r;
    return $r;
}

function solve_small($N, $P, $W, $H) {
    $sum = $N * (2 * $W[0] + 2 * $H[0]); dd($sum, 'sum');
    $low = 2 * min($W[0], $H[0]);        dd($low, 'low');
    $high = 2 * sqrt($W[0] * $W[0] + $H[0] * $H[0]); dd($high, 'high');
    dd($P, 'P');
    for ($i = 0; $i <= $N; $i ++) {
        $l = $i * $low; $h = $i * $high;
        if ($sum + $l > $P) { dd($i.': sum + l > P'); return $sum + $high * ($i - 1); }
        if ($sum + $l <= $P && $sum + $h >= $P) { dd('hit '.$i); return $P; }
    }
    dd('not reachable');
    return $sum + $high * $N;
}

function fake() {
    $N = rand(1, 100); $W = []; $H = []; $sum = 0; $diag_sum = 0;
    for ($i = 0; $i < $N; $i ++) {
        $w = rand(1, 250); $h = rand(1, 250);
        //if ($i > 0) { $w = $W[$i - 1]; $h = $H[$i - 1]; }
        $W[] = $w; $H[] = $h;
        $sum += 2 * $w + 2 * $h; $diag_sum += 2 * sqrt($w * $w + $h * $h);
    }
    $P = rand($sum, $sum + floor($diag_sum));
    $in = $N.' '.$P."\n";
    for ($i = 0; $i < $N; $i ++) $in .= $W[$i].' '.$H[$i]."\n";
    file_put_contents('../下载/IN.txt', $in, FILE_APPEND);
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
file_put_contents('../下载/IN.txt', "100\n"); for ($i = 0; $i < 100; $i ++) fake();
$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    $conf = explode(' ', read($hr));
    $W = []; $H = [];
    for ($i = 0; $i < $conf[0]; $i ++) {
        $r = explode(' ', read($hr));
        $W[] = $r[0]; $H[] = $r[1];
    }
    write(solve($conf[0], $conf[1], $W, $H)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-25
 * Time: 下午1:39
 */