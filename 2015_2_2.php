<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Kiddie_pool {
    public function solve($N, $V, $X, $R, $C) {
        $r = $this->getTime($N, $V, $X, $R, $C);
        if ($r === false) return 'IMPOSSIBLE';
        return number_format($r, 8, '.', '');
    }

    /**
     * @param $N int N个水源
     * @param $V float 目标体积
     * @param $X float 目标温度
     * @param $R array 各水源的最大流量
     * @param $C array 各水源的温度
     */
    public function getTime($N, $V, $X, $R, $C) { echo 'solve(V='.$V.', X='.$X.')';
        // 从冷往热计算体积和温度
        $cold_r = []; $cold_c = []; asort($C);
        $this->calculateRC($R, $C, $cold_r, $cold_c);
        // 从热往冷计算体积和温度
        $hot_r = []; $hot_c = []; arsort($C);
        $this->calculateRC($R, $C, $hot_r, $hot_c);
        // 如果达不到温度
        if ($X < $cold_c[0] || $X > $hot_c[0]) return false;
        // 如果最终温度就是所需温度
        if (''.$X == ''.$cold_c[$N - 1]) return $V / $cold_r[$N - 1];
        // 如果最终温度太热
        if ($X <= $cold_c[$N - 1]) { echo ' too hot! ('; echo implode(' ', $cold_c).')';
            sort($C);
            for ($i = $N - 1; $i > 0; $i --) {
                if ($cold_c[$i - 1] > $X) continue;
                $r = ($X - $cold_c[$i - 1]) * $cold_r[$i - 1] / ($C[$i] - $X);
                return $V / ($r + $cold_r[$i - 1]);
            }
        }
        // 如果最终温度太冷
        if ($X >= $hot_c[$N - 1]) { echo ' too cold! ('; echo implode(' ', $hot_c).')';
            rsort($C);
            for ($i = $N - 1; $i > 0; $i --) {
                if ($hot_c[$i - 1] < $X) continue;
                $r = ($X - $hot_c[$i - 1]) * $hot_r[$i - 1] / ($C[$i] - $X);
                return $V / ($r + $hot_r[$i - 1]);
            }
        }
    }

    public function calculateRC($R, $C, &$lr, &$lc) {
        $p = -1;
        foreach ($C as $k => $c) {
            if ($p == -1) { $lc[] = $c; $lr[] = $R[$k]; $p ++; continue; }
            $lc[] = ($lr[$p] * $lc[$p] + $R[$k] * $c) / ($lr[$p] + $R[$k]);
            $lr[] = $lr[$p] + $R[$k];
            $p ++;
        }
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
            $P = new Kiddie_pool();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $R = array(); $C = array();
                for ($i = 0; $i < $conf[0]; $i ++) {
                    $r = explode(' ', trim(fgets($handle)));
                    $R[] = $r[0]; $C[] = $r[1];
                }
                $r = $P->solve($conf[0], $conf[1], $conf[2], $R, $C);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
//$i = new Input('../下载/IN.txt','../下载/OUT_2.txt');
//$i = new Input('../下载/B-small-practice.in','../下载/OUT_2.txt');
$i = new Input('../下载/B-large-practice.in','../下载/OUT_2.txt');
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
 *
 * pitfalls:
 * 思路是, 如果最终结果太热, 将水源温度升序排列
 * 如果前 i - 1 个温度不太热了, 保留所有前 i - 1 个, 第 i 个视所需体积而定.
 * 所以, N = 1 时, 没有第 i - 1 个, 就不会进入循环.
 * $r = ($X - $cold_c[$i - 1]) * $cold_r[$i - 1] / ($C[$i] - $X); 这里 C[i] - X 可能为 0
 * 小数据集 case #41, X=5.9770, 水源温度为 5.9770, 5.9770, 但是 X == cold_r[N - 1] 为假
 * 只好改为 ''.$X 和 ''.$cold_r[$N - 1] 转换为字符串再比较
 */