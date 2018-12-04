<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Pancakes {
    private $P;

    public function solve($P) {
        rsort($P);
        $this->P = $P;
        $min_time = $P[0];
        for($i = $P[0]; $i > 0; $i --) {
            $need_time = $this->per_plate($i);
            if($need_time < $min_time) $min_time = $need_time;
        }
        return $min_time;
    }

    // 分配到每个盘子有i个月饼以下需要多少分钟
    private function per_plate($i) {
        $need_time = $i;
        foreach($this->P as $num) {
            $need_time += ceil($num / $i) - 1;
        }
        return $need_time;
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
            $P = new Pancakes();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $D = trim(fgets($handle));
                $plates = explode(' ', trim(fgets($handle)));
                $r = $P->solve($plates);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

//$i = new Input('IN.txt','OUT.txt');
//$i = new Input('B-small-practice.in','OUT.txt');
$i = new Input('B-large-practice.in','OUT.txt');
$i->process();

echo '<br/>execution time: '.(time() - $t).'<br/>';
echo '<br/>memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
/**
 * 在一家无限馅饼屋, 馅饼是有限的, 顾客是无限的!
 * 在开店的时候, 有 D 个人盘子里有馅饼, 第 i 个人盘子里有 Pi 个馅饼.
 * 1 个顾客 1 分钟吃掉 1 个馅饼
 * 你可以选择几个特别分钟, 这分钟没人吃饭, 你可以把 1 个馅饼从一个顾客的盘子挪到另一个顾客盘子
 * 求如何指定特别分钟, 能让顾客最快吃完所有馅饼
 *
 * 思路: 1. 因为永远有空盘子, 所以每次一定是把饼分给空盘子的人
 *       2. 挪一次吃一次, 不如全部分完, 大家一起吃, 吃的效率更高
 *          所以, 看把饼分到每个人盘子里都有 i 个以下需要多少时间, 再加上 i 分钟吃饼
 *
 * 对于现有算法: 你看, ceil(a/1), ceil(a/2)... 的值最多改变 2 * sqrt(a) 次,
 * 因为从 1 到 sqrt(a), 最多可以找到 sqrt(a) 个因数和对应的乘数.
 * 所以, 我们可以把数字的变化记录下来, 比如, Pi=10, 我们可以有一个表 Ti,
 * 因为 ceil(a/1), ceil(a/2)...对于 10 的值是 10, 5, 3, 3, 2, 2, 2, 2, 2, 1
 * Ti 就是 10, -5, -2, 0, -1, 0, 0, 0, 0, -1, 0
 * 如果我们对所有的 Ti 做矢量和运算, 我们就能得到总的变化向量.
 * 那计算 sum(ceil(Pi/x)), 只需要将前 i 个数字相加即可...我算这个干什么...
 *
 * "I don't need that! The previous algorithm is fast enough. Please run it," you say impatiently.
 * "Sigh. I won't tell you how to code the faster solution then. The answer is..." Everyone gasps while the program blinks. "... 42."
 */