<?php

class RedTapeCommittee {

    public function solve($N, $K, $P) { // echo 'solve('.$N.', '.$K.', ['.implode(', ', $P).'])'."\n";
        sort($P);
        $max_probability = 0;

        for ($M = 0; $M <= $K; $M ++) {  // 取前 M 个和后 K - M 个
            $sets = [];
            for ($i = 0; $i < $M; $i ++) $sets[] = $P[$i];
            for ($i = $N - $K + $M; $i < $N; $i ++) $sets[] = $P[$i];
            $p = $this->calculateProbability($sets);
            if ($p > $max_probability) $max_probability = $p;
        }

        return number_format($max_probability, 6);
    }

    public function calculateProbability($sets) {
        $K = count($sets);
        $DP[0][0] = 1;   // 考虑集合中的前 k 个人的情况下, j 个人投支持的概率
        for ($k = 0; $k < $K; $k ++) { // echo "\n".$DP[$k][0].'*(1.0 - '.$sets[$k].')='.($DP[$k][0] * (1.0 - $sets[$k]));
            $DP[$k + 1][0] = $DP[$k][0] * (1.0 - $sets[$k]);  // 有 0 个人支持
            for ($j = 1; $j <= $k; $j ++) {                 // 有 1->k 个人支持
                $DP[$k + 1][$j] = $DP[$k][$j] * (1.0 - $sets[$k]) + $DP[$k][$j - 1] * $sets[$k];
            }
            $DP[$k + 1][$k + 1] = $DP[$k][$k] * $sets[$k]; // 有 k 个人支持
        }
        return $DP[$K][$K / 2];
    }

}

$R = new RedTapeCommittee();
echo $R->solve(3, 2, [0.75, 1.0, 0.5]);
//exit;

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
                $K = $N[1]; $N = $N[0];
                $P = explode(' ', trim(fgets($handle)));
                $R = new RedTapeCommittee();
                $r = $R->solve($N, $K, $P);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

// $i = new Input('../下载/B-small-practice.in','../下载/OUT_2.txt');
$i = new Input('../下载/B-large-practice.in','../下载/OUT_2.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-11-29
 * Time: 下午5:34
 *
 * 你是一个选举委员会的主席, 你们部门有N个成员, 每个人投Yes的概率是Pi
 * 你要选择其中的K个人, K是偶数, 让出现平票的概率最大
 *
 * 我们应该选择 M 个概率最大的成员, 和 K - M 个概率最小的成员
 * 证明: 假如我们已经有这样一个集合, X 在集合中, Y 和 Z 不在集合中, Y < X < Z
 * 其他成员的概率确定, 只考虑 X 的概率, 这是一个线性方程 (K = 2 的情况, a + x - 2ax)
 * 如果斜率为0, 哪个都无所谓, 如果斜率>0, 应该把X换成Z, 如果斜率<0, 应该把X换成Y
 * 所以没有X的位置
 * 对于小数据集, 可以尝试每一个M, 然后计算平票概率
 */