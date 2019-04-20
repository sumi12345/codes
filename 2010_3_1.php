<?php
define('ENV', 'test');

function solve($D, $K, $S) {
    dd($D.', '.implode(' ', $S), 'D, S');
    // get valid prime
    $max_element = 0;
    foreach ($S as $s) if ($s > $max_element) $max_element = $s;
    $prime = prime($max_element, pow(10, $D));
    // special cases
    if ($K == 1) return "I don't know.";
    if ($S[$K - 1] == $S[$K - 2]) return $S[$K - 1];
    if ($K == 2) return "I don't know.";
    // for every valid prime, solve A and B
    $next = -1;
    foreach ($prime as $p) {
        for ($a = 1; $a < $p; $a ++) {
            $b = ($S[1] - $a * $S[0]) % $p; $b = ($b + $p) % $p;
            $s = $S[1];
            for ($i = 2; $i <= $K; $i ++) {
                $s = ($a * $s + $b) % $p;
                if ($i == $K) {
                    dd($p.', '.$a.', '.$b, 'p, a, b');
                    if ($next != -1 && $s != $next) return "I don't know.";
                    $next = $s;
                } elseif ($s != $S[$i]) break;
            }
        }
    }
    // return the unique answer
    return $next;
}

// * min can't count because the prime itself won't be in the sequence
function prime($min = 1, $max = 1000000) {
    $P = [];
    for ($i = $min + 1; $i < $max; $i ++) {
        $is_prime = true;
        $n = intval(sqrt($i));
        for ($j = 2; $j <= $n; $j ++) if ($i % $j == 0) {
            $is_prime = false; break;
        }
        if ($is_prime) $P[] = $i;
    }
    return $P;
}

function fake() {
    $D = 2; $K = 5; $S = [];
    $P = prime(1, 100); $i = rand(0, count($P) - 1); $p = $P[$i];
    $a = rand(1, $p - 1); $b = rand(0, $p - 1); $s = rand(0, $p - 1);
    $S[] = $s;
    for ($i = 1; $i < $K; $i ++) {
        $s = ($s * $a + $b) % $p;
        $S[] = $s;
    }
    $answer = $a.' '.$b.' '.$p;
    $str = $D.' '.$K.' '.$answer."\n".implode(' ', $S)."\n";
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
    $hr = fopen('../下载/A-small-practice.in', 'r');
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
    $S = explode(' ', read($hr));
    //if ($c != 9) continue;
    write(solve($conf[0], $conf[1], $S)."\n", $hw);
}
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-4-20
 * Time: 下午3:46
 */