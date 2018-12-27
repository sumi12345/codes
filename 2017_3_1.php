<?php
define('ENV', 'test');

function solve($G) { dd($G, 'solve');
    $L = strlen($G);
    // if sum of length > L, then it must be just appeared
    $sum = 0;
    for ($i = 0; $i < $L; $i ++) $sum += $G[$i];
    if ($sum > $L) return 1;
    // generate all possible status
    $visited = []; $str = '';
    generateDigit($str, $L, $visited);
    // decay link
    $decay_link = [];
    foreach ($visited as $g => $v) {
        $d = decay($g);
        if (!isset($decay_link[$d])) $decay_link[$d] = [];
        $decay_link[$d][] = $g;
    }
    // number of possible ancestor
    $ancestor = [];
    foreach ($visited as $g => $v) $ancestor[$g] = countAncestor($g);
    // BFS count all middle nodes
    $queue = [$G]; $v = [$G => 1]; $leaves = 0;
    for ($K = 0; $K < 100000; $K ++) {
        if (empty($queue)) break;
        $g = array_shift($queue);
        if (!isset($decay_link[$g])) $leaves += $ancestor[$g];
        else foreach ($decay_link[$g] as $u) if (!isset($v[$u])) {
            $v[$u] = 1; $queue[] = $u;
        }
    }
    // return
    return $leaves + count($v);
}

// pitfalls: str 不能是数组, str[l]=i, 因为赋值之后长度回不来
function generateDigit($str, $L, &$visited) {
    // length of current string, if l = L, then record
    $l = strlen($str);
    if ($l == $L) { $visited[$str] = 1; return; }
    // find the max value of this digit
    $sum = 0;
    for ($i = 0; $i < $l; $i ++) $sum += $str[$i];
    $max = $L - $sum;
    // get next digit
    for ($i = 0; $i <= $max; $i ++) {
        generateDigit($str.$i, $L, $visited);
    }
}

// pitfalls: PHP 把 visited key 的字符串转换成 int 了, 所以 G[i] 为空
function decay($G) { $G = ''.$G;
    $cnt = []; $L = strlen($G);
    for ($i = 1; $i <= $L; $i ++) $cnt[$i] = 0;
    for ($i = 0; $i < $L; $i ++) if ($G[$i] != 0) $cnt[$G[$i]] ++;
    //dd($G.' decayed to '.implode('', $cnt));
    return implode('', $cnt);
}

// pitfalls: left = 0 的时候不该调用 C(0, 0)
function countAncestor($G) { $G = ''.$G; // dd($G, 'countAncestor');
    $l = strlen($G);
    $left = $l; $p = 1;
    for ($i = 0; $i < $l; $i ++) {
        $p *= Util::C($left, $G[$i]); // dd($left.', '.$G[$i]); // dd(Util::$c, 'c');
        $left -= $G[$i]; if ($left == 0) break;
    }
    return $p;
}

// pitfalls: 需要初始化 n=0 和 n=1 的情况, 否则会无限递归调用下去
class Util {
    static $c = [];
    static function C($m, $n) {
        if ($n > ceil($m / 2)) $n = $m - $n;
        if (!isset(self::$c[$m][$n])) {
            if (!isset(self::$c[$m])) self::$c[$m] = [];
            if ($n == 0) { self::$c[$m][$n] = 1; }
            elseif ($n == 1) { self::$c[$m][$n] = $m; }
            else {
                self::$c[$m][$n] = self::C($m - 1, $n) + self::C($m - 1, $n - 1);
            }
        }
        return self::$c[$m][$n];
    }
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

$hr = fopen('../下载/A-large-practice.in', 'r');
$hw = fopen('../下载/OUT_1.txt', 'w');
$hw = fopen('../下载/OUT_1.txt', 'a');
//---- start process ----
$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw); // if ($c != 1) continue;
    write(solve(read($hr))."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-26
 * Time: 下午2:47
 */