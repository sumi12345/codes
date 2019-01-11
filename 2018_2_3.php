<?php
define('ENV', 'test');

function solve($N, $A) {
    // for every costume, find max number of costume to keep
    $keep = 0; $checked = [];
    for ($i = 0; $i < $N; $i ++) {
        for ($j = 0; $j < $N; $j ++) {
            $costume = $A[$i][$j];
            if (isset($checked[$costume])) continue;
            $checked[$costume] = 1;
            $keep += keep($costume, $N, $A);
        }
    }
    // the rest must be changed
    return $N * $N - $keep;
}

function keep($costume, $N, $A) {
    $C = []; $F = [];
    for ($i = 0; $i < $N; $i ++) {
        for ($j = 0; $j < $N; $j ++) {
            if ($A[$i][$j] != $costume) continue;
            $r = 'r'.$i; $c = 'c'.$j;
            if (!isset($C[$r])) $C[$r] = [];
            if (!isset($C[$c])) $C[$c] = [];
            if (!isset($F[$r])) $F[$r] = [];
            if (!isset($F[$c])) $F[$c] = [];
            $C[$r][$c] = 1; $C[$c][$r] = 0;
            $F[$r][$c] = 0; $F[$c][$r] = 0;
        }
    }

    // no such costume
    if (empty($C)) return 0;

    // already unique
    $unique = true;
    foreach ($C as $u => $v) if (count($v) > 1) $unique = false;
    if ($unique) return count($C) / 2;

    // source and destination
    $nodes = array_keys($C);
    $C['s'] = []; $C['t'] = [];
    foreach ($nodes as $node) {
        if ($node[0] == 'r') {
            $C['s'][$node] = 1; $F['s'][$node] = 0;
            $C[$node]['s'] = 0; $F[$node]['s'] = 0;
        } elseif ($node[0] == 'c') {
            $C[$node]['t'] = 1; $F[$node]['t'] = 0;
            $C['t'][$node] = 0; $F['t'][$node] = 0;
        }
    }

    // find max flow
    $f = 0;
    for ($K = 0; $K <= $N; $K ++) {  // until no more flow
        $visited = [];
        $queue = ['s'];
        $parent = [];
        while (!empty($queue)) {
            $u = array_shift($queue);
            foreach ($C[$u] as $v => $k) {
                if (isset($visited[$v]) || $C[$u][$v] <= $F[$u][$v]) continue;
                $visited[$v] = 1;
                $queue[] = $v;
                $parent[$v] = $u;
            }
        }
        // can't reach t
        if (!isset($visited['t'])) break;
        // update flow
        for ($v = 't'; $v != 's'; $v = $parent[$v]) {
            $u = $parent[$v];
            $F[$u][$v] ++; $F[$v][$u] --;
        }
        // max flow + 1
        $f ++;
    }

    return $f;
}

function fake() {
    $N = rand(2, 4); $A = [];
    for ($i = 0; $i < $N; $i ++) {
        $str = '';
        for ($j = 0; $j < $N; $j ++) {
            $a = rand(-$N, $N);
            if ($a == 0) $a = 1;
            $str .= $a.' ';
        }
        $A[] = trim($str);
    }
    $str = $N."\n".implode("\n", $A)."\n";
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
//file_put_contents('../下载/IN.txt', "99\n"); for ($i = 0; $i < 99; $i ++) fake();
$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    $N = read($hr); $A = [];
    for ($i = 0; $i < $N; $i ++) {
        $A[$i] = explode(' ', read($hr));
    }
    write(solve($N, $A)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-1-11
 * Time: 下午12:11
 */