<?php
define('ENV', 'test');

function solve($S) {
    $N = count($S);

    // calculate P_BAD
    Bad::swap($N);
    $next = Bad::$next;

    // return
    $P_BAD = 1;
    foreach ($S as $k => $v) $P_BAD *= $next[$v][$k];
    $P_GOOD = 1 / pow($N, $N);
    $r = $P_GOOD > $P_BAD ? 'GOOD' : 'BAD';
    dd($P_GOOD.', '.$P_BAD, 'P_GOOD, P_BAD');
    return $r;
}

class Bad {
    static $next;

    static public function swap($N) {
        // set only once with N
        if (!empty(self::$next)) return;

        // calculate P(S|BAD)
        dd('calculate P(S|BAD)', 'Bad::swap('.$N.')');
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
        self::$next = $next;
    }
}

function fake() {
    $N = 125;
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
    $str = $N."\n".implode(' ', $S)."\n";
    file_put_contents('../下载/IN.txt', $str, FILE_APPEND);
}

function dd($item, $name = '') {
    // if (ENV != 'test') return;
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
    $hr = fopen('../下载/C-small-practice.in', 'r');
    $hw = fopen('../下载/OUT_3.txt', 'w');
    $hw = fopen('../下载/OUT_3.txt', 'a');
}

//---- start process ----
file_put_contents('../下载/IN.txt', "99\n"); for ($i = 0; $i < 99; $i ++) fake();
$T = read($hr);
$correct = 0;
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    $N = read($hr);
    $S = explode(' ', read($hr));
    $R = ENV == 'test' ? array_pop($S) : 'GOOD';
    //if ($c != 1) continue;
    $r = solve($S);
    if ($R == $r) $correct ++;
    write($r."\n", $hw);
}
if (ENV == 'test') dd($correct.'/'.$T, 'correct / total');
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-2-25
 * Time: 下午1:26
 *
 * 问题是, 计算出来的概率并不是全排列 (01, 10), 而是 (00, 01, 10, 11),
 * 会造成 1/N! 远大于这些相乘的概率, 如果换成 1/(N^N), 准确率又很低.
 * 测试案例中 N=1000, 计算精度不允许, 会造成 P_GOOD 和 P_BAD 都是 0
 * 事实上 N=150 就已经不支持精度了, N=125 时正确率大概在 70%
 */