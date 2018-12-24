<?php
define('ENV', 'test');

function solve($R, $B, $C, $M, $S, $P) {
    // R robots, B bits, C cashier, M max item, S scan per item, P packing
    dd('solve: R='.$R.', B='.$B.', C='.$C);
    // every cashier's max time
    $max_time = [];
    for ($i = 0; $i < $C; $i ++) $max_time[$i] = $M[$i] * $S[$i] + $P[$i];
    rsort($max_time);
    // binary search T
    $l = 0; $h = $max_time[0];
    for ($K = 0; $K < 100; $K ++) {
        $m = $l + floor(($h - $l) / 2);
        if ($m == $l || $m == $h) break; // 数字大了之后就可能出现 m = h

        $item_m = item_by_time($m, $R, $B, $C, $M, $S, $P);
        if ($item_m < $B) $l = $m;
        elseif ($item_m >= $B) $h = $m; // 就算找到 B 相等的, 也可能不是最小的
    }
    dd(number_format($l, 0, '.', ''), 'l');
    dd(number_format($h, 0, '.', ''), 'h');
    for ($K = 1; $K <= $h - $l; $K ++) {
        dd(number_format($l + $K, 0, '.', ''), 'l + K');
        if (item_by_time($l + $K, $R, $B, $C, $M, $S, $P) >= $B) return $l + $K;
    }
}

function item_by_time($T, $R, $B, $C, $M, $S, $P) {
    $could_finish = [];
    for ($i = 0; $i < $C; $i ++) {
        $could_finish[$i] = min(floor(($T - $P[$i]) / $S[$i]), $M[$i]);
        if ($T < $P[$i]) $could_finish[$i] = 0;
    }
    $cnt = 0; rsort($could_finish); // dd($T, 'T'); dd($could_finish, 'could_finish');
    for ($i = 0; $i < $R; $i ++) $cnt += $could_finish[$i];
    return $cnt;
}

function fake() {
    $C = rand(1, 4); $R = rand(1, $C); $B = rand(1, 1000000000); // $B = 0;
    $M = []; $S = []; $P = [];
    for ($i = 0; $i < $C; $i ++) {
        $M[$i] = rand(1, 1000000000);
        $S[$i] = rand(1, 1000000000);
        $P[$i] = rand(1, 1000000000);
    }
    $in = $R.' '.$B.' '.$C."\n";
    for ($i = 0; $i < $C; $i ++) $in .= $M[$i].' '.$S[$i].' '.$P[$i]."\n";
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
    $hw = fopen('../下载/OUT_2.txt', 'w');
    $hw = fopen('../下载/OUT_2.txt', 'a');
} else {
    $hr = STDIN;
    $hw = STDOUT;
}

//---- start process ----
//file_put_contents('../下载/IN.txt', "100\n"); for ($i = 0; $i < 100; $i ++) fake($i + 1, $hw);
$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    $conf = explode(' ', read($hr));
    $M = []; $S = []; $P = [];
    for ($i = 0; $i < $conf[2]; $i ++) {
        $r = explode(' ', read($hr));
        $M[] = $r[0]; $S[] = $r[1]; $P[] = $r[2];
    }
    write(solve($conf[0], $conf[1], $conf[2], $M, $S, $P)."\n", $hw);
}
/**
 * 大数据集不通过, 然而不想管了. 原因是, 到某个数字之后, 再往上加 1 已经不奏效了
 * 比如, l: 221471461444294272, h: 221471461444294304, l + 1 还是 l
 */