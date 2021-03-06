<?php
class CoreTraining {
    public function solve($N, $K, $U, $P) {
        if ($N == $K) return $this->solve_small($N, $K, $U, $P);
        return $this->solve_large($N, $K, $U, $P);
    }

    private function solve_small($N, $K, $U, $P) {
        sort($P);
        $P = $this->distributeUnit($N, $U, $P, 0);
        $p = $this->calculateProbability($P, $N, $K);
        return number_format($p, 8);
    }

    private function solve_large($N, $K, $U, $P) {
        sort($P);
        $max_p = 0;
        for ($i = 0; $i < $N; $i ++) {  // 把训练单元都给第 i 开始的核心
            $new_p = $this->distributeUnit($N, $U, $P, $i);
            $p = $this->calculateProbability($new_p, $N, $K);
            if ($p > $max_p) $max_p = $p;
            if ($i > 0 && $new_p[$i - 1] > $P[$i - 1]) break;
        }
        return number_format($max_p, 8);
    }

    private function distributeUnit($N, $U, $P, $I) { echo 'distributeUnit('.$I.', '.$U.')'."\n";
        $p = 0; for ($i = 0; $i < $N; $i ++) $p += $P[$i]; // echo $p.'+'.$U.'='.($p + $U)."\n";
        if (''.($p + $U) == $N) { for ($i = 0; $i < $N; $i ++) $P[$i] = 1; return $P; }
        if ($U == 0) return $P;

        $P[] = 1; $total = 0;
        for ($i = $I + 1; $i <= $N; $i ++) {  // 从 i - 1 提高到 i 的水平需要多少单元
            $u = $P[$i] - $P[$i - 1]; $n = $i - $I;
            if ($total + $u * $n >= $U) { echo "用完啦!\n";
                $p = ($U - $total) / $n;
                for ($j = $I; $j < $i; $j ++) $P[$j] = $P[$i - 1] + $p;
                $total += $p * $n;
                break;
            }
            $total += $u * $n;
            for ($j = $I; $j < $i; $j ++) $P[$j] = $P[$i];  // 没用完也要更新
        }
        if ($U - $total > 0) { echo "没用完!\n"; print_r($P);
            $P[$I - 1] += $U - $total;
        }
        unset($P[$N]); return $P;
    }

    private function calculateProbability($P, $N, $K) {
        $DP = [];      // 前 i 个核心里有 j 个成功的概率
        $DP[0][0] = 1;
        for ($i = 1; $i <= $N; $i ++) {
            $p = $P[$i - 1];
            $DP[$i][0] = $DP[$i - 1][0] * (1 - $p);
            for ($j = 1; $j < $i; $j ++) {
                $DP[$i][$j] = $DP[$i - 1][$j - 1] * $p + $DP[$i - 1][$j] * (1 - $p);
            }
            $DP[$i][$i] = $DP[$i - 1][$i - 1] * $p;
        }

        $p = 0;       // 至少有 K 个成功的概率
        for ($i = $K; $i <= $N; $i ++) $p += $DP[$N][$i];
        return $p;
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
                $U = floatval(fgets($handle));
                $P = explode(' ', trim(fgets($handle)));
                $C = new CoreTraining(); // if ($c != 56) continue;
                $r = $C->solve($N[0], $N[1], $U, $P);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
//$i = new Input('../下载/IN.txt','../下载/OUT_3.txt');
//$i = new Input('../下载/C-small-practice-1.in','../下载/OUT_3.txt');
$i = new Input('../下载/C-small-practice-2.in','../下载/OUT_3.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-19
 * Time: 下午3:53
 */