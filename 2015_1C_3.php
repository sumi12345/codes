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

$i = new Input('IN.txt','OUT.txt');
//$i = new Input('A-small-practice.in','OUT_1.txt');
//$i = new Input('A-large-practice.in','OUT_1.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);