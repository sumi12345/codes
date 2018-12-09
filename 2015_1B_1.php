<?php
ini_set("max_execution_time","10");
ini_set("memory_limit","30M");

class Couter {

    public function solve($N) {
        $this->tree = array(1 => 1);  // 叶子节点
        $this->archived = array();    // 非叶子节点
        while (!isset($this->archived[$N]) && !isset($this->tree[$N])) $this->bfs($N);

        if (isset($this->archived[$N])) return $this->archived[$N];
        return $this->tree[$N];
    }

    private function bfs($N) {
        foreach ($this->tree as $num => $cnt) {
            $r = $num + 1;
            if (!isset($this->tree[$r])) {
                $this->tree[$r] = $this->tree[$num] + 1;
            }
            if ($r == $N) return;

            $r = $this->reverse($num);
            if (!isset($this->tree[$r])) {
                $this->tree[$r] = $this->tree[$num] + 1;
            }
            if ($r == $N) return;

            $this->archived[$num] = $this->tree[$num];
            unset($this->tree[$num]);
        }
    }

    private function reverse($num) {
        $r = 0;
        while($num > 0) {
            $n = $num % 10;
            $r = $r * 10 + $n;
            $num = floor($num / 10);
        }
        return $r;
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
            $C = new Couter();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $n = intval(trim(fgets($handle)));
                $r = $C->solve($n);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
//$i = new Input('../下载/A-small-practice.in','../下载/OUT_1.txt');
//$i = new Input('../下载/A-large-practice.in','../下载/OUT_1.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
/**
 * 在一个数数诗朗诵会上, 每个表演者拿着麦克风, 选择一个数字 N, 然后大声从 1 数到 N.
 * 轮到你的时候, 你觉得这个过程太无聊了, 你改了一下规则. 每次你可以念 +1 的数字, 也可以把前面的数字反过来.
 * 你念的第一个数字一定是 1. 要念到 N 你至少需要念多少数字?
 * 思路:
 * 对于小数据集, BFS 就够了. 最多只有 10^6 种状态.
 * 对于大数据集, 首先, 从 10, 到 100, 到 1000, 直到最终数字 N 所需的位数.
 * 从 1, X 个 0, 到 1, X + 1 个 0 的策略是, 数后 X / 2 + 1 位数到都是 9, 然后翻转, 数剩下的, 加一.
 * 然后, 从 1, X 个 0, 到所需数字 N, 我们最多只做一次翻转.
 * 策略是一样的, 比如 123456. 100000 -> 100321 -> 123001 -> 123456.
 * 但是当 N 的右半部分都是 0, 上面的策略就不管用了. 那我们就先数到 N - 1.
 * 比如 300000. 100000 -> 100992 -> 299001 -> 299999 -> 300000.
 * 但是如果 N - 1 的左半部分是 1 加上几个 0, 我们可以跳过前两步.
 * 比如 101000. 从 100000 直接数比较快.
 */