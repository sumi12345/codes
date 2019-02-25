<?php
define('ENV', 'test');

function solve($S) {
    $R = array_pop($S);  // fake last one is result
    $N = count($S);

    // calculate P(S|BAD)
    $pmove = 1 / $N;
    $pstay = 1 - $pmove;

    $prev = [];
    for ($i = 0; $i < $N; $i ++) {
        for ($j = 0; $j < $N; $j ++) {
            $prev[$i][$j] = $i == $j ? 1 : 0;
        }
    }

    // swap number at previous kth position
    // number at previous kth position will split to every position
    // number not at previous kth position
    // will have 1/N chance to move to k, 1-1/N stay where it is
    // next[i][k] = sum(1/N * prev[][])
    $next = [];
    for ($k = 0; $k < $N; $k ++) {    // swap num at prev kth position
        for ($i = 0; $i < $N; $i ++) {      // number
            $next[$i] = []; $next[$i][$k] = 0;
            for ($j = 0; $j < $N; $j ++) {  // previous position
                $next[$i][$k] += $prev[$i][$j] * $pmove;  // others move to k or k stay
                if ($j != $k) $next[$i][$j] = $prev[$i][$j] * $pstay  // others stay
                                            + $prev[$i][$k] * $pmove; // k move to others
            }
        }
        //dd($next[$N - 1], $k);
        $prev = $next;
    }

    // return
    $P_BAD = 1;
    foreach ($S as $k => $v) $P_BAD *= $next[$v][$k];
    $P_GOOD = 1 / pow($N, $N);
    $P = $P_GOOD / ($P_GOOD + $P_BAD);
    $r = $P > 0.5 ? 'GOOD' : 'BAD';
    dd($P.', '.$P_BAD, 'P, P_BAD');
    dd($R.'|'.$r, 'result');
    return $r;
}

function factorial($N) {
    if ($N <= 1) return 1;
    $r = $N * factorial($N - 1);
    //dd($r, 'factorial '.$N);
    return $r;
}

function fake() {
    $N = 3;
    $S = [];
    for ($k = 0; $k < $N; $k ++) $S[$k] = $k;
    if (rand(0, 1)) {
        for ($k = 0; $k < $N; $k ++) {
            $p = rand($k, $N - 1);
            $t = $S[$k]; $S[$k] = $S[$p]; $S[$p] = $t;
        }
        $S[] = 'GOOD';
    } else {
        for ($k = 0; $k < $N; $k ++) {
            $p = rand(0, $N - 1);
            $t = $S[$k]; $S[$k] = $S[$p]; $S[$p] = $t;
        }
        $S[] = 'BAD';
    }
    $str = implode(' ', $S)."\n";
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
    $hr = fopen('../下载/IN.txt', 'r');
    $hw = fopen('../下载/OUT_3.txt', 'w');
    $hw = fopen('../下载/OUT_3.txt', 'a');
} else {
    $hr = STDIN;
    $hw = STDOUT;
}

//---- start process ----
file_put_contents('../下载/IN.txt', "99\n"); for ($i = 0; $i < 99; $i ++) fake();
$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    //if ($c != 1) continue;
    write(solve(explode(' ', read($hr)))."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-2-25
 * Time: 下午1:26
 *
 * 问题是, 计算出来的概率并不是全排列 (01, 10), 而是 (00, 01, 10, 11),
 * 会造成 1/N! 远大于这些相乘的概率, 如果换成 1/(N^N), 准确率又很低.
 */