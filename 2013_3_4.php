<?php
ini_set('memory_limit', '256M');
define('ENV', 'test');

function solve($W) {
    $N = strlen($W);
    $free_num = 0;
    for ($I = 0; $I < $N; $I ++) {
        if ($W[$I] == '.') $free_num ++;
    }
    $r = Util::price($W);
    dd(count(Util::$DP), 'count DP');
    return $r;
}

class Util {
    static $DP = [];
    static function price($W) {
        $N = strlen($W);
        for ($i = 1; $i <= $N; $i ++) {
            $W = self::rotate($W, $i);
            if (isset(self::$DP[$W])) return self::$DP[$W];
        }

        // free num
        $free_num = 0;
        for ($i = 0; $i < $N; $i ++) if ($W[$i] == '.') $free_num ++;
        if ($free_num == 0) return 0;

        // total price of this wheel
        $total = 0;
        for ($I = 0; $I < $N; $I ++) {
            if ($W[$I] == 'X') continue;

            $wait = 0;
            for ($i = $I - 1; $i >= 0; $i --) {
                if ($W[$i] == '.') break;
                $wait ++;
            }
            if ($i == -1) for ($i = $N - 1; $i > $I; $i --) {
                if ($W[$i] == '.') break;
                $wait ++;
            }

            $total_this = 0;
            for ($i = 0; $i <= $wait; $i ++) {
                $total_this += $N - $i;
            }

            $W[$I] = 'X';
            $total += $total_this + self::price($W) * ($wait + 1);
            $W[$I] = '.';
        }

        // return
        $r = $total / $N;
        self::$DP[$W] = $r;
        return $r;
    }

    static function rotate($W, $I) {
        $w = substr($W, $I).substr($W, 0, $I);
        //dd($W.' rotate '.$I.' is '.$w);
        return $w;
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
    $hr = fopen('../下载/D-small-practice.in', 'r');
    $hw = fopen('../下载/OUT_3.txt', 'w');
    $hw = fopen('../下载/OUT_3.txt', 'a');
} else {
    $hr = STDIN;
    $hw = STDOUT;
}

//---- start process ----
//file_put_contents('../下载/IN.txt', "99\n"); for ($i = 0; $i < 99; $i ++) fake();
$t = microtime(true);
$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    $W = read($hr);
    //if ($c != 1) continue;
    write(solve($W)."\n", $hw);
}
dd(microtime(true) - $t, 'execution_time');
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-3-14
 * Time: 下午1:24
 */