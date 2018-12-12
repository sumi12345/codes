<?php
class Ratatouille {
    // N 种原料, 每种原料有 P 包, 第 i 种原料需要 Ri 克, 第 i 种原料的第 j 包有 Iij 克
    public function solve($N, $P, $R, $I) {
        // 计算每一种原料 i 的每一包原料 j 能制作的份数的范围, 下限和上限
        $L = []; $H = [];
        for ($i = 0; $i < $N; $i ++) {     // 对每种原料
            for ($j = 0; $j < $P; $j ++) { // 第 i 包能做多少
                $h = ceil($I[$i][$j] / $R[$i] / 0.9);
                $l = floor($I[$i][$j] / $R[$i] / 1.1);
                $low = -1; $high = -1;
                for ($k = $l; $k <= $h; $k ++) if ($I[$i][$j] >= $R[$i] * $k * 0.9 && $I[$i][$j] <= $R[$i] * $k * 1.1) {
                    if ($low == -1) $low = $k; $high = $k;
                }
                if ($low != -1) { $L[$i][$j] = $low; $H[$i][$j] = $high; }
            }
        }
        echo 'L'; print_r($L); echo 'H: '; print_r($H);

        for ($K = 0; $K <= 50; $K ++) {  // 循环直到没有匹配
            // 每种原料可以提供的最小份数的最大值
            $M = -1;
            for ($i = 0; $i < $N; $i ++) {
                if (empty($L[$i])) return $K;
                $l = -1;
                foreach ($L[$i] as $j => $n) {
                    if ($l == -1) { $l = $n; continue; }
                    if ($L[$i][$j] < $l) $l = $L[$i][$j];
                }
                if ($l > $M) $M = $l; echo 'i='.$i.', l='.$l."\n";
            }
            echo 'M: '.$M."\n";
            // 每种原料选择一个符合份数的包, 移除
            for ($i = 0; $i < $N; $i ++) {
                asort($H[$i]); $found = 0;
                foreach ($H[$i] as $j => $n) {
                    echo 'i='.$i.', j='.$j.', L='.$L[$i][$j].', H='.$H[$i][$j]."\n";
                    if ($L[$i][$j] <= $M && $H[$i][$j] >= $M) {
                        unset($L[$i][$j]); unset($H[$i][$j]); $found = 1; echo 'found: '.$i.', '.$j."\n"; break;
                    }
                }
                if ($found == 0) return $K;
            }
            // 最高份数 < M 的, 移除
            for ($i = 0; $i < $N; $i ++) {
                foreach ($H[$i] as $j => $n) if ($n < $M) { unset($L[$i][$j]); unset($H[$i][$j]); }
            }
        }
    }
}

class Input {
    private $in_file;
    private $out_file;

    function __construct($i, $o) {
        $this->in_file = $i;
        $this->out_file = $o;
    }

    function process() {
        $handle = fopen($this->in_file, "r");
        if ($handle) {
            $cases = intval(fgets($handle, 32));
            file_put_contents($this->out_file, '');
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $N = explode(' ', trim(fgets($handle)));
                $R = explode(' ', trim(fgets($handle)));
                $I = []; for ($i = 0; $i < $N[0]; $i ++) $I[] = explode(' ', trim(fgets($handle)));
                $Ra = new Ratatouille(); //if ($c != 5) continue;
                $r = $Ra->solve($N[0], $N[1], $R, $I);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
//$i = new Input('../下载/B-small-practice.in','../下载/OUT_2.txt');
$i = new Input('../下载/B-large-practice.in','../下载/OUT_2.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-12
 * Time: 下午3:26
 */