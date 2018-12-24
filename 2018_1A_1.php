<?php
define('ENV', 'test');

function solve($R, $C, $H, $V, $W) {
    dd('solve: R='.$R.', C='.$C.', H='.$H.', V='.$V.', W='."\n".implode("\n", $W));
    // 先数巧克力, 看能不能整除
    $cnt = 0;
    for ($i = 0; $i < $R; $i ++)
        for ($j = 0; $j < $C; $j ++)
            if ($W[$i][$j] == '@') $cnt ++;
    if ($cnt % (($H + 1) * ($V + 1)) != 0) return 'IMPOSSIBLE';
    if ($cnt == 0) return 'POSSIBLE';
    // 横切
    $h = []; $p = 0;
    for ($i = 0; $i < $R; $i ++) {
        for ($j = 0; $j < $C; $j ++) {
            if ($W[$i][$j] == '@') $p ++;
        }
        if ($p > 0 && $p % ($cnt / ($H + 1)) == 0) $h[$p / ($cnt / ($H + 1))] = $i;
        // p > 0, 如果第一行是空的, 会有 0 的 key, 用下面 count 的方法会出错
        // if ($p / ($cnt / ($H + 1)) == 0) { dd($h, 'why there is 0'); exit; }
    }
    // 竖切
    $v = []; $p = 0;
    for ($j = 0; $j < $C; $j ++) {
        for ($i = 0; $i < $R; $i ++) {
            if ($W[$i][$j] == '@') $p ++;
        }
        if ($p > 0 && $p % ($cnt / ($V + 1)) == 0) $v[$p / ($cnt / ($V + 1))] = $j;
    }
    dd($h, 'h'); dd($v, 'v');
    if (count($h) != $H + 1 || count($v) != $V + 1) return 'IMPOSSIBLE';
    // 检查每个格子中的巧克力数量
    $h[0] = -1; $v[0] = -1;
    for ($i = 1; $i <= $H + 1; $i ++) {
        for ($j = 1; $j <= $V + 1; $j ++) {
            $c = check_chocolate($W, $h[$i - 1] + 1, $v[$j - 1] + 1, $h[$i], $v[$j]);
            if ($c != $cnt / (($H + 1) * ($V + 1))) return 'IMPOSSIBLE';
        }
    }
    return 'POSSIBLE';
}

function check_chocolate($W, $i1, $j1, $i2, $j2) {
    dd('check_chocolate: '.$i1.', '.$j1.', '.$i2.', '.$j2);
    $cnt = 0;
    for ($i = $i1; $i <= $i2; $i ++) {
        for ($j = $j1; $j <= $j2; $j ++) {
            if ($W[$i][$j] == '@') $cnt ++;
        }
    }
    return $cnt;
}

function fake($i) {
    write('Case #'.$i.': ');
    $R = rand(2, 10); $C = rand(2, 10);
    $H = 1; $V = 1;
    $W = [];
    for ($i = 0; $i < $R; $i ++) {
        $r = '';
        for ($j = 0; $j < $C; $j ++) {
            $r .= rand(1, 4) == 2 ? '@' : '.';
        }
        $W[] = $r;
    }
    write(solve($R, $C, $H, $V, $W)."\n");
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
// for ($i = 0; $i < 100; $i ++) fake($i + 1); exit;
$T = read($hr);
for ($c = 1; $c <= $T; $c ++) {
    write('Case #'.$c.': ', $hw);
    $conf = explode(' ', read($hr));
    $W = []; for ($i = 0; $i < $conf[0]; $i ++) $W[] = read($hr);
    write(solve($conf[0], $conf[1], $conf[2], $conf[3], $W)."\n", $hw);
}