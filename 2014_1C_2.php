<?php
define('ENV', 'test');

function solve($N, $S) {
    // index of letters at first, last, middle / number of single letter str
    $first = []; $last = []; $middle = []; $single = [];
    foreach ($S as $k => $s) {
        // remove repeated letters
        $arr = []; $len = strlen($s); $pre = '.';
        for ($i = 0; $i < $len; $i ++) {
            if ($s[$i] == $pre) continue;
            // check multiple times at the same string *
            if (isset($arr[$s[$i]])) return 0;
            $arr[$s[$i]] = 1; $pre = $s[$i];
        }
        $arr = array_keys($arr);
        dd(implode('', $arr), $k);
        // record
        $len = count($arr);
        if (count($arr) == 1) {
            if (!isset($single[$arr[0]])) $single[$arr[0]] = 0;
            $single[$arr[0]] ++;
        } else {
            if (isset($first[$arr[0]])) return 0;
            $first[$arr[0]] = $k;
            if (isset($last[$arr[$len - 1]])) return 0;
            $last[$arr[$len - 1]] = $k;
            for ($i = 1; $i < $len - 1; $i ++) {
                if (isset($middle[$arr[$i]])) return 0;
                $middle[$arr[$i]] = $k;
            }
        }
    }
    // check conflict of middle
    foreach ($middle as $l => $idx) {
        if (isset($first[$l]) || isset($last[$l]) || isset($single[$l])) return 0;
    }
    // count jointed string
    $joint = array_intersect(array_keys($last), array_keys($first));
    $num = count($first) - count($joint);
    // check circle
    if ($num == 0 && !empty($first)) return 0;
    // calculate permutation
    $r = 1;
    foreach ($single as $l => $n) {
        if (!isset($first[$l]) && !isset($last[$l])) $num ++;
        $r *= Util::factorial($n);
        $r %= 1000000007;
    }
    $r *= Util::factorial($num);
    $r %= 1000000007;
    return $r;
}

class Util {
    static $F = [0 => 1];
    static function factorial($N) {
        if (isset(self::$F[$N])) return self::$F[$N];
        $r = $N * self::factorial($N - 1);
        $r %= 1000000007;
        self::$F[$N] = $r;
        return $r;
    }
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
    $hr = fopen('../下载/B-large-practice (1).in', 'r');
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
    $N = read($hr);
    $S = explode(' ', read($hr));
    //if ($N > 4) continue;
    write(solve($N, $S)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-3-12
 * Time: 下午1:15
 */