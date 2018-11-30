<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Neighbors {

    public function solve($R, $C, $N) {
        // 一定可以为0
        if ($N <= ceil($R * $C / 2)) return 0;

        // 住满的 unhappiness
        $unhappiness = 2 * $R * $C - $R - $C; //($R - 1) * $C + ($C - 1) * $R
        $K = $R * $C - $N;  // 要搬走的人数

        // 只有一行/一列
        if ($R == 1 || $C == 1) {
            return $K * 2;
        }

        // 行或列是偶数
        if ($R % 2 == 0 || $C % 2 == 0) {
            $S = $this->score_even($K, $R, $C);
            return $unhappiness - $S;
        }

        // 如果行列都是奇数
        // 方法1 移除中心
        $K1 = $K;
        $S1 = 0;
        if ($K1 > 0) $S1 += $this->get_score($K1, ceil(($R - 2) * ($C - 2) / 2), 4);
        if ($K1 > 0) $S1 += $this->get_score($K1, $R + $C - 2, 3);  // floor($R / 2) * 2 + floor($C / 2) * 2
        if ($K1 > 0) $S1 += $this->get_score($K1, 4, 2);

        // 方法2 不移除中心
        $K2 = $K;
        $S2 = 0;
        if ($K2 > 0) $S2 += $this->get_score($K2, floor(($R - 2) * ($C - 2) / 2), 4);
        if ($K2 > 0) $S2 += $this->get_score($K2, $R + $C + 2, 3);  // ceil($R / 2) * 2 + ceil($C / 2) * 2

        $S = max($S1, $S2);
        return $unhappiness - $S;
    }

    // 行或列是偶数的情况
    private function score_even($K, $R, $C) {
        $S = 0;
        if ($K > 0) $S += $this->get_score($K, ($R - 2) * ($C - 2) / 2, 4);
        if ($K > 0) $S += $this->get_score($K, $R + $C - 4, 3);
        if ($K > 0) $S += $this->get_score($K, 2, 2);
        return $S;
    }

    private function get_score(&$to_be_removed, $max_num, $score_per_remove) {
        $r = $to_be_removed > $max_num ? $max_num : $to_be_removed;
        $to_be_removed -= $r;
        return $score_per_remove * $r;
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
            $N = new Neighbors();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $r = $N->solve($conf[0], $conf[1], $conf[2]);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

$i = new Input('IN.txt','OUT.txt');
//$i = new Input('B-small-practice.in','OUT_2.txt');
//$i = new Input('B-large-practice.in','OUT_2.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
