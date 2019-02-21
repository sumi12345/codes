<?php
define('ENV', 'test');

function solve($A, $B, $K) {
    $DP = [];
    $I = max(log($A, 2), log($B, 2), log($K, 2));
    dd(floor($I), 'I'); dd(decbin($A), 'A'); dd(decbin($B), 'B'); dd(decbin($K), 'K');
    return countPairs(floor($I), 0, 0, 0, $A, $B, $K, $DP);
}

function countPairs($I, $lessA, $lessB, $lessK, $A, $B, $K, &$DP) {
    // extract from memory
    if (isset($DP[$I][$lessA][$lessB][$lessK])) return $DP[$I][$lessA][$lessB][$lessK];
    // final bit
    if ($I < 0) return $lessA && $lessB && $lessK;
    // if 1 is feasible for A, B, K
    $maxA = ($lessA || getBit($A, $I) == 1) ? 1 : 0;
    $maxB = ($lessB || getBit($B, $I) == 1) ? 1 : 0;
    $maxK = ($lessK || getBit($K, $I) == 1) ? 1 : 0;
    // 0 & 0 = 0
    $count = countPairs($I - 1, $maxA, $maxB, $maxK, $A, $B, $K, $DP);
    // 1 & 0 = 0
    if ($maxA) $count += countPairs($I - 1, $lessA, $maxB, $maxK, $A, $B, $K, $DP);
    // 0 & 1 = 0
    if ($maxB) $count += countPairs($I - 1, $maxA, $lessB, $maxK, $A, $B, $K, $DP);
    // 1 & 1 = 1
    if ($maxA && $maxB && $maxK)
        $count += countPairs($I - 1, $lessA, $lessB, $lessK, $A, $B, $K, $DP);
    // memorize and return
    if (!isset($DP[$I])) $DP[$I] = [];
    if (!isset($DP[$I][$lessA])) $DP[$I][$lessA] = [];
    if (!isset($DP[$I][$lessA][$lessB])) $DP[$I][$lessA][$lessB] = [];
    $DP[$I][$lessA][$lessB][$lessK] = $count;
    //dd($I.', '.$lessA.', '.$lessB.', '.$lessK.'='.$count, 'countPairs');
    return $count;
}

function getBit($num, $I) {  // get the Ith bit of num
    $r = $num & (1 << $I) ? 1 : 0;
    //dd(decbin($num).', '.$I.' = '.$r, 'getBit');
    return $r;
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
    $hr = fopen('../下载/B-large-practice.in', 'r');
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
    //if ($c != 1) continue;
    write(solve($conf[0], $conf[1], $conf[2])."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-2-21
 * Time: 下午2:13
 */