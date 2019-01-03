01234567890123456789012345678901234567890123456789012345678901234567890123456789
<?php
define('ENV', 'test');

function solve($S, $D, $A, $B) {
    $r1 = []; $r2 = []; // start, end
    for ($i = 0; $i < $S; $i ++) {
        $a = $D[$i] + $A[$i];
        if (!isset($r1[$a])) $r1[$a] = [$i, $i];
        else $r1[$a][1] = $i;
        $b = $D[$i] - $B[$i];
        if (!isset($r2[$b])) $r2[$b] = [$i, $i];
        else $r2[$b][1] = $i;
    }
    $cnt = []; $max_len = 0;
    foreach ($r1 as $m => $ran1) {
        foreach ($r2 as $n => $ran2) {
            if ($ran2[1] < $ran1[0] - 1 || $ran2[0] > $ran1[1] + 1) continue;

            $start = min($ran1[0], $ran2[0]); $end = max($ran1[1], $ran2[1]);
            if ($end - $start + 1 < $max_len) continue;

            $len = check($m, $n, $D, $A, $B, $start, $end);
            if ($len[0] < $max_len) continue;

            if ($len[0] > $max_len) $max_len = $len[0];
            if (!isset($cnt[$len[0]])) $cnt[$len[0]] = [];
            foreach ($len[1] as $start_point) $cnt[$len[0]][$start_point] = 1;
        }
    }

    //for ($i = 0; $i < $S; $i ++) { $A[$i] += $D[$i]; $B[$i] = $D[$i] - $B[$i];}
    //dd($A, 'A'); dd($B, 'B');

    krsort($cnt);
    foreach ($cnt as $n => $s) return $n.' '.count($s);
}

function check($M, $N, $D, $A, $B, $start, $end) {
    dd('M='.$M.', N='.$N.', start='.$start.', end='.$end, 'check');
    $len = []; $p = -1;
    for ($i = $start; $i <= $end; $i ++) {
        if ($D[$i] + $A[$i] == $M || $D[$i] - $B[$i] == $N) {
            if ($p == -1) $p = $i;
        } else {
            if ($p != -1) {
                if (!isset($len[$i - $p])) $len[$i - $p] = [];
                $len[$i - $p][] = $p;
            }
            $p = -1;
        }
    }
    if ($p != -1) {
        if (!isset($len[$i - $p])) $len[$i - $p] = [];
        $len[$i - $p][] = $p;
    }
    krsort($len);
    foreach ($len as $n => $start_points) {
        dd($n.'=>'.count($start_points));
        return [$n, $start_points];
    }
}

function fake() {
    $S = rand(1, 999);
    $s = []; $pre_d = 0;
    for ($i = 0; $i < $S; $i ++) {
        $d = rand($pre_d + 1, 999 - $S + $i);
        $a = rand(1, 999);
        $b = rand(1, 999);
        $s[] = $d.' '.$a.' '.$b;
        $pre_d = $d;
    }
    $str = $S."\n".implode("\n", $s)."\n";
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
    $hw = fopen('../下载/OUT_1.txt', 'w');
    $hw = fopen('../下载/OUT_1.txt', 'a');
} else {
    $hr = STDIN;
    $hw = STDOUT;
}

//---- start process ----
file_put_contents('../下载/IN.txt', "99\n"); for ($i = 0; $i < 99; $i ++) fake();
$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    $S = read($hr); $D = []; $A = []; $B = [];
    for ($i = 0; $i < $S; $i ++) {
        $r = explode(' ', read($hr));
        $D[] = $r[0]; $A[] = $r[1]; $B[] = $r[2];
    }
    write(solve($S, $D, $A, $B)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-1-3
 * Time: 下午1:46
 */