<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Barbershop {

    public function solve($B, $N, $M) {
        $T = array();
        foreach ($M as $k => $v) $T[$k + 1] = $v;

        $this->B = $B;
        $this->T = $T;

        rsort($M);
        $min = 0;
        $max = $M[0] * $N;

        while ($min < $max - 1) {
            $mid = floor(($min + $max) / 2);
            $served = $this->served_customers_at($mid);

            if ($served < $N) $min = $mid;
            else $max = $mid;
        }

        $served = $this->served_customers_at($min);
        $to_be_served = $N - $served;

        for ($i = 1; $i <= $B; $i ++) {
            if ($min % $T[$i] == 0) $to_be_served --;
            if ($to_be_served == 0) return $i;
        }
    }

    private function served_customers_at($t) {
        $num = 0;
        foreach ($this->T as $v) $num += ceil($t / $v);
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
            $B = new Barbershop();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $b = $conf[0];
                $n = $conf[1];
                $m = explode(' ', trim(fgets($handle)));
                $r = $B->solve($b, $n, $m);
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

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
/**
 * 你在一家生意很好的理发店排队.
 * 理发店有 B 个理发师, 编号从 1 到 B.
 * 第 k 个理发师理一次头发需要 Mk 分钟.
 * 店开门的时候, 顾客总是找编号最低的理发师理发. 当所有理发师都忙的时候, 顾客需要等至少 1 个理发师空闲.
 * 理发店刚刚开门, 你是队里的第 N 个人, 哪个理发师会帮你剪?
 *
 * 思路:
 * 方法1: 直接模拟. 每一分钟, 轮询每一个理发师, 如果刚好空闲下来, 顾客补位.
 * 方法2: 找出理发师服务事件的最小公倍数, 再执行直接模拟, 只需要模拟最多最小公倍数分钟.
 * 方法3: 二分查找. 计算到某一分钟, 已经服务的客人的数量, 找到你刚好开始被服务的那一分钟.
 *        如果这一分钟正好有多个理发师刚好空闲, 排在你前面的先匹配.
 */