12345678901234567890123456789012345678901234567890123456789012345678901234567890
<?php
ini_set("memory_limit", "128M");
define('ENV', 'test');

function solve($M, $R, $G) {
    array_unshift($R, 0); unset($R[0]);
    array_unshift($G, 0); unset($G[0]);
    //return solve_small($R, $G);
    return solve_large($R, $G);
}

function solve_large($R, $G) {
    $need = [1];
    $parent = [[]];
    $total = 0;
    for ($i = 0; $i <= 100; $i ++) {  // untill cannot produce more lead
        $r = createMetal($need, $parent, $total, $R, $G);
        if ($r === false) break;
    }
    dd($i, 'final I'); // dd($need, 'need'); dd($parent, 'parent');
    return $total;
}

function createMetal(&$need, &$parent, &$total, $R, &$G) {
    // check this set
    $min_g = -1; $cnt = [];
    foreach ($need as $k => $m) {
        if (!isset($cnt[$m])) $cnt[$m] = 0;
        $cnt[$m] ++;
        $g = floor($G[$m] / $cnt[$m]);
        if ($min_g == -1 || $g < $min_g) $min_g = $g;
    }
    // valid set
    if ($min_g > 0) {
        $total += $min_g;
        foreach ($cnt as $m => $n) $G[$m] -= $n * $min_g;
    }
    //dd($min_g, 'min_g'); dd($cnt, 'cnt'); dd(implode(' ', $G), 'G');
    // next
    $new_need = []; $new_parent = []; $cnt = [];
    foreach ($need as $k => $m) {
        if (!isset($cnt[$m])) $cnt[$m] = 0; // has consumed
        if ($G[$m] - $cnt[$m] > 0) {        // ensure left most first
            $new_need[] = $m; $new_parent[] = $parent[$k]; $cnt[$m] ++;
        } else {                            // split
            $lm = $R[$m][0]; $rm = $R[$m][1];  // has circle
            if (isset($parent[$k][$lm]) || isset($parent[$k][$rm])) return false;
            $new_need[] = $lm;              // left, add m to its parent
            $lp = $parent[$k]; $lp[$m] = 1; $new_parent[] = $lp;
            $new_need[] = $rm;              // right
            $rp = $parent[$k]; $rp[$m] = 1; $new_parent[] = $rp;
        }
    }
    $need = $new_need; $parent = $new_parent;
}

// $T = [1 => 1]; buildTree($T, 1, $R);
// not used; need 2^100 as index
function buildTree(&$T, $I, &$R) {
    $l = $R[$T[$I]][0]; $r = $R[$T[$I]][1]; $valid = true;
    for ($K = 0; $K < 100; $K ++) {
        $i = floor($I / pow(2, $K));
        if ($T[$i] == $l || $T[$i] == $r) $valid = false;
        if ($i == 1) break;
    }
    if ($valid == false) return;
    $T[$I * 2] = $l;
    $T[$I * 2 + 1] = $r;
    buildTree($T, $I * 2, $R);
    buildTree($T, $I * 2 + 1, $R);
}

function solve_small($R, $G) {
    for ($K = 0; $K < 64; $K ++) {
        $r = create(1, $R, $G, 0);
        if ($r == false) return $K;
    }
}

function create($m, $R, &$G, $level) {
    if ($G[$m] > 0) { $G[$m] --; return true; }
    if ($level == count($R)) return false;
    $l = create($R[$m][0], $R, $G, $level + 1);
    $r = create($R[$m][1], $R, $G, $level + 1);
    if ($l && $r) return true;
}

function fake() {
    $M = 100; $R = []; $G = [];
    for ($i = 1; $i <= $M; $i ++) {
        $r1 = $i; $r2 = $i;
        while ($r1 == $i || $r2 == $i) {
            $r1 = rand(1, $M - 1);
            $r2 = rand($r1 + 1, $M);
        }
        $R[] = $r1.' '.$r2;
        $G[] = rand(1, 100);
    }

    $str = $M."\n".implode("\n", $R)."\n".implode(' ', $G)."\n";
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
    $M = read($hr); $R = [];
    for ($i = 0; $i < $M; $i ++) {
        $R[] = explode(' ', read($hr));
    }
    $G = explode(' ', read($hr)); //if ($c != 2) continue;
    write(solve($M, $R, $G)."\n", $hw);
}
dd(memory_get_peak_usage() / 1024 / 1024);
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-1-4
 * Time: 下午2:24
 */