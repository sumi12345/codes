<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Kiddie_pool {

    /**
     * @param $N int N个水源
     * @param $V float 目标体积
     * @param $X float 目标温度
     * @param $R array 各水源的最大流量
     * @param $C array 各水源的温度
     */
    public function solve($N, $V, $X, $R, $C) {
        // 如果所有水源都以最大流量放水 1 时间, 得到的体积和温度
        $temp = $this->calculate_temp($R, $C);

        // 如果水温偏低/偏高 调整最冷/最热水源的流量
        $t = $temp['t'];
        $v = $temp['v'];
        if ($t / $v < $X) asort($C);
        elseif ($t / $v > $X) arsort($C);
        if ($t / $v == $X) return $V / $v;

        // 调整流量
        foreach ($C as $key => $c) {
            if ($t / $v == $X) break;
            $this->ajust_temp($X, $t, $v, $R[$key], $c);
        }

        // 没办法达到目标水温
        if ($v == 0) return 'IMPOSSIBLE';

        // 如果已经达到温度 计算时间
        return number_format($V / $v, 9, '.', '');
    }

    // 计算温度
    private function calculate_temp($R, $C) {
        $v = 0;
        $t = 0;
        foreach ($C as $key => $temp) {
            $v += $R[$key];
            $t += $temp * $R[$key];
        }
        return array('t' => $t, 'v' => $v);
    }

    // 调整温度
    private function ajust_temp($X, &$t, &$v, $R, $C) {
        if ($X == $C) return true;
        $t -= $R * $C;
        $v -= $R;
        $r = ($t - $X * $v) / ($X - $C);
        if ($r >= 0) {
            $t += $r * $C;
            $v += $r;
        }
    }

}
$P = new Kiddie_pool();
echo $P->solve(1, 10, 50, [0.2000], [50.0000])."\n";
echo $P->solve(2, 30.0000, 65.4321, [0.0001, 100.0000], [50.0000, 99.9000])."\n";
echo $P->solve(2, 5.0000, 99.9000, [30, 20], [99.8999, 99.7000])."\n";
echo $P->solve(2, 0.0001, 77.2831, [0.0001, 0.0001], [97.3911, 57.1751])."\n";
echo $P->solve(2, 100.0000, 75.6127, [70.0263, 27.0364], [75.6127, 27.7990])."\n";
echo $P->solve(4, 5000.0000, 75.0000, [10, 20, 300, 40], [30, 50, 95, 2])."\n";
exit;

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
            $P = new Kiddie_pool();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $N = intval($conf[0]); $V = floatval($conf[1]); $X = floatval($conf[2]); $R = array(); $C = array();
                for ($i = 0; $i < $N; $i ++) {
                    $row = explode(' ', trim(fgets($handle)));
                    $R[] = floatval($row[0]); $C[] = floatval($row[1]);
                }
                $r = $P->solve($N, $V, $X, $R, $C);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
// $i = new Input('../下载/B-small-practice.in','../下载/OUT_2.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * 一个儿童游泳池是一个容器, 可以盛水, 小朋友可以在里面玩.
 * 你有 N 种不同的水源. 第 i 个水源以速率 Ri 和温度 Ci 放水.
 * 泳池可以放无限量的水, 但你希望用最少的时间向泳池中放正好是 V 体积, X 温度的水.
 * 举例, 5升10度的水和10升40度的水混合将得到 (5*10+10*40)/(5+10)=30 度的 15 升水
 *
 * 思路: 你在放水的时候, 用到的每个水源在某个时间段内是打开的,
 * 但这相当于在整个放水的时间内以更低的速率打开着.
 * 所以问题转化为, 每个水源应该以多大的速率打开, 才能达到正确的温度和最大的流量.
 * 我们先将所有水源都放到最大流量. 如果温度正好是我们要的温度, 问题解决.
 * 如果温度太高, 我们要降低某些水源的流速, 为了让流量的减少最少, 我们从最热的水源开始减.
 * 如果达不到理想温度, 那就不可能解决. 太冷的解决方案也是一样的.
 */