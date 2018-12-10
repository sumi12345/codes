<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Neighbors {

    public function solve($R, $C, $N) {
        // 可以棋盘形摆放
        if ($N <= ceil($R * $C / 2)) return 0;
        // 住满的不幸福指数, 要移除的租客数目
        $U = ($R - 1) * $C + ($C - 1) * $R;  $K = $R * $C - $N;
        // 只有 1 行或 1 列
        if ($R == 1 || $C == 1) return $U - 2 * $K;
        // 行列有偶数
        if ($R % 2 == 0 || $C % 2 == 0) return $this->count_even($R, $C, $U, $K);
        // 行列都是奇数
        return min($this->count_odd_1($R, $C, $U, $K), $this->count_odd_2($R, $C, $U, $K));
    }

    private function count_even($R, $C, $U, $K) { // 图 1 图 2
        $m4 = ($R - 2) * ($C - 2) / 2;            // 移除一个减 4
        $m3 = ($R - 2) + ($C - 2);                // 移除一个减 3
        $m2 = 2;                                  // 移除一个减 2
        return $this->remove_tenant($U, $K, $m4, $m3, $m2);
    }

    private function count_odd_1($R, $C, $U, $K) {  // 图 3
        $m4 = ceil(($R - 2) * ($C - 2) / 2);
        $m3 = floor(($R - 2) / 2) * 2 + floor(($C - 2) / 2) * 2;
        $m2 = 4;
        return $this->remove_tenant($U, $K, $m4, $m3, $m2);
    }

    private function count_odd_2($R, $C, $U, $K) {  // 图 4
        $m4 = floor(($R - 2) * ($C - 2) / 2);
        $m3 = ceil(($R - 2) / 2) * 2 + ceil(($C - 2) / 2) * 2;
        return $this->remove_tenant($U, $K, $m4, $m3);
    }

    // 计算返回值
    private function remove_tenant($U, $K, $m4, $m3, $m2 = 0) {
        $n = $m4;
        if ($K <= $n) return $U - 4 * $K;
        $U -= $n * 4; $K -= $n;

        $n = $m3;
        if ($K <= $n) return $U - 3 * $K;
        $U -= $n * 3; $K -= $n;

        if ($K <= $m2) return $U - 2 * $K;
    }
}
$N = new Neighbors();
echo $N->solve(2, 3, 6)."\n";
echo $N->solve(4, 1, 2)."\n";
echo $N->solve(3, 3, 8)."\n";
echo $N->solve(5, 2, 0)."\n";
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
            $N = new Neighbors();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $r = $N->solve($conf[0], $conf[1], $conf[2]);
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
 * 你是一个房东, 拥有一座楼, 分隔成 R x C 间公寓.
 * 每个公寓是一个四方格, 有四面墙.
 * 你想把楼租给 N 个租客, 每个租客一个公寓, 其他空着.
 * 但是, 你的租客都很吵, 所以如果两个人共用一面墙, 不幸福指数会加一.
 * 如果你好好安排这 N 个租客, 最小不幸福指数是多少?
 *
 * 小数据集, N <= ceil(R * C / 2)
 * 小数据集, 我们可以棋盘形地安排住户, 最小不幸福指数为0
 *
 * 大数据集, N > ceil(R * C / 2)
 * 大数据集, 不幸福指数一定 > 0. 我们就不能从一个空房间来填充客人, 相反从满客减去租客比较容易.
 * 我们要想的是, 如何减去租客, 才能最大限度地减少不幸福指数.
 * 我们从 R=1 或 C=1 的情况开始. 这种情况, 我们总是能够将不幸福指数每次减少 2.
 *
 * 现在我们来看一般情况
 * .3.2     .3.3.     2.3.2     .3.3.
 * 3.4.     3.4.3     .4.4.     3.4.3
 * .4.3     .4.4.     3.4.3     .4.4.
 * 2.3.     2.3.2     .4.4.     3.4.3
 *                    2.3.2     .3.3.
 *
 * 4 x 4     4 x 5     5 x 5     5 x 5
 *
 * 所以, 当 K <= (R - 2) * (C - 2) / 2, 我们总是可以从中间开始移除, -4.
 * 然后, 当 K > (R - 2) * (C - 2) / 2, 我们开始移除靠墙的租客, -3.
 * 最后, 我们开始移除边角的租客, -2.
 * 注意, 当 R 和 C 都是奇数的时候, 有 2 种情况, 都要考虑, 选出最优的那种.
 */