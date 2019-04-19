<?php
define('ENV', 'test');

function solve($R, $C, $map) {
    dd($map, 'map');
    // create graph, end => start, end point is what we care about
    $G = create_graph($R, $C, $map);
    if (count($G) < $R * $C) return 0;  // some cells can't be reached
    //dd($G, 'G');
    // delete nodes to make circles
    delete($G);
    foreach ($G as $to => $froms) if (empty($froms)) return 0;
    //dd($G, 'after delete 1');
    // recursively delete edges to count circles
    $answer = 1; $num = 2 * count($G);
    for ($K = 0; $K < $num; $K ++) {  // until no edges to delete
        if (empty($G)) break;
        // find a pair [from] and [to], delete edge between them
        foreach ($G as $t => $fs) foreach ($fs as $f => $v) {
            $answer = $answer * 2 % 1000003;
            unset($G[$t][$f]);
            dd($t.'<-'.$f, 'break');
            break 2;  // back to the K recursive
        }
        delete($G);
    }
    // return
    return $answer;
}

function delete(&$G) {
    for ($K = 0; $K < 10000; $K ++) {  // until no one is deleted
        $del = false;
        foreach ($G as $to => $froms) if (count($froms) == 1) {
            // the node [from]
            $from = -1; $del = true;
            foreach ($froms as $f => $v) { $from = $f; break; }
            // remove edges from [from]
            unset($G[$to]);
            //dd($to.'<-'.$from, 'stay');
            foreach ($G as $t => $v) if (isset($v[$from])) {
                //dd($t.'<-'.$from, 'dele');
                unset($G[$t][$from]);
                break 2;  // back to the K recursive
            }
        }
        if ($del === false) break;
    }
    return $K;
}

function create_graph($R, $C, $map) {
    $G = [];
    for ($i = 0; $i < $R; $i ++) {
        for ($j = 0; $j < $C; $j ++) {
            $from = idx($i, $j, $R, $C);
            switch ($map[$i][$j]) {
                case '|':
                    $to1 = idx($i - 1, $j, $R, $C);
                    $to2 = idx($i + 1, $j, $R, $C);
                    break;
                case '-':
                    $to1 = idx($i, $j - 1, $R, $C);
                    $to2 = idx($i, $j + 1, $R, $C);
                    break;
                case '\\':
                    $to1 = idx($i - 1, $j - 1, $R, $C);
                    $to2 = idx($i + 1, $j + 1, $R, $C);
                    break;
                case '/':
                    $to1 = idx($i - 1, $j + 1, $R, $C);
                    $to2 = idx($i + 1, $j - 1, $R, $C);
                    break;
                default:
                    $to1 = -1;
                    $to2 = -1;
            }
            $G[$to1][$from] = 1; $G[$to2][$from] = 1;
        }
    }
    return $G;
}

function idx($r, $c, $R, $C) {
    if ($r == -1) $r = $R - 1;
    if ($r == $R) $r = 0;
    if ($c == -1) $c = $C - 1;
    if ($c == $C) $c = 0;
    return $r * $C + $c;
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
    $map = [];
    for ($i = 0; $i < $conf[0]; $i ++) {
        $map[] = read($hr);
    }
    //if ($c > 10) continue;
    write(solve($conf[0], $conf[1], $map)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-4-19
 * Time: 下午1:58
 */