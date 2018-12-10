<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Mushroom {

    public function solve($N, $M) {
        $instant = $this->instant($N, $M);
        $constant = $this->constant($N, $M);

        return $instant.' '.$constant;
    }

    private function instant($N, $M) {
        $num = 0;
        for ($i = 1; $i < $N; $i ++) {
            $num += $M[$i - 1] - $M[$i] > 0 ? $M[$i - 1] - $M[$i] : 0;
        }

        return $num;
    }

    private function constant($N, $M) {
        $max = 0;
        for ($i = 1; $i < $N; $i ++) {
            $max = $M[$i - 1] - $M[$i] > $max ? $M[$i - 1] - $M[$i] : $max;
        }
        if ($max <= 0) return 0;

        $num = 0;
        for ($i = 1; $i < $N; $i ++) {
            $num += $M[$i - 1] > $max ? $max : $M[$i - 1];
        }
        return $num;
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
            $M = new Mushroom();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $n = intval(trim(fgets($handle)));
                $m = explode(' ', trim(fgets($handle)));
                $r = $M->solve($n, $m);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
//$i = new Input('../下载/A-small-practice.in','../下载/OUT_1.txt');
$i = new Input('../下载/A-large-practice.in','../下载/OUT_1.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
/**
 * Kaylin 喜欢吃蘑菇. Kaylin 吃蘑菇, Bartholomew 放蘑菇.
 * 我们每 10 秒看一次盘子里的蘑菇数量.
 * 我们想知道 Kaylin 至少吃掉了多少蘑菇, 有两种可能:
 * 1. Kaylin 能在任何时候吃掉任何数量的蘑菇.
 * 2. 只要盘子里有蘑菇, Kaylin 就以恒定的速度吃蘑菇.
 * 计算两种可能下, Kaylin 至少吃掉了多少蘑菇.
 *
 * 思路:
 * 第一种情况, 每次盘子里的蘑菇减少了, 那就一定是被吃掉了.
 * 第二种情况, 首先我们要确定 Kaylin 每 10 秒最多能吃多少, 比如 k 个.
 * 那么只要前一次盘子里有 k 个, 那一定都被吃掉了.
 */