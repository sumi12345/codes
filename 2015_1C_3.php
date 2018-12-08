<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Money {
    /**
     * 解决方法
     * 如果 1~N 都可以买到, 那么加上 N + 1 面值可以买到 1 ~ N + (N + 1) * C
     * @param $C int 最多可以使用的硬币数量
     * @param $D int 目前有的硬币面值数量
     * @param $V int 最多需要支持支付额度
     * @param $denomination array 目前有的额度
     */
    public function solve($C, $D, $V, $denomination) {
        sort($denomination);

        $S = array();
        $T = 0;

        for ($i = 1; $i < $V; $i ++) {
            // 如果有小于等于这个面值的货币 加入篮子
            while (!empty($denomination) && $denomination[0] <= $i) {
                $T += $denomination[0] * $C;
                array_shift($denomination);
            }

            // 如果这个面值买不到, 作为新货币, 加入篮子
            if ($T < $i) {
                $T += $i * $C;
                $S[] = $i;
            }

            echo "\n".$i.'|'.$T;

            // 找下一个
            $i = $T;
        }

        echo "\n".'new denomination: '.implode(', ', $S);
        return count($S);
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
            $M = new Money();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $denomination = explode(' ', trim(fgets($handle)));
                $r = $M->solve($conf[0], $conf[1], $conf[2], $denomination);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
//$i = new Input('../下载/C-small-practice.in','../下载/OUT_3.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * 你住的国家已经发行了 D 种不同面值的硬币.
 * 但是今天, 当一个人想要用一堆低面值的硬币来付税款的时候, 国王生气了!
 * 他说任何一次交易中, 每一种面值的硬币不能被使用超过 C 次.
 * 你不能违反规定, 但你刚好有权发行新面值的硬币.
 * 你想在符合规定的情况下, 使付 1-V 元成为可能, 并且确保引入的新面值货币最少.
 * 求至少需要发行多少新面值硬币?
 *
 * 思路:
 * 我们会逐步建立一组面值组合 S. N 是我们能付的最多的钱. 1-N 都能付.
 * 当我们加一个新面值 X 到 S 的时候, 新组合就能够付 N + X * C 元钱.
 * 所以我们将 S 设为空集, N = 0;
 * 当 N < V 的时候, 我们做下面的操作:
 * 最小的我们不能付的面值是 N + 1.
 * 如果存在一个已有面值, 我们还没使用, 且 <= N+1, 将它加入篮子, N = N + X * C
 * 否则我们必须发行新货币. X = N + 1. N = N + X * C
 */