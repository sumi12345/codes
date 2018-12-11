<?php
ini_set("max_execution_time","60");
ini_set("memory_limit","60M");

class Logging {
    public function solve($N, $P) { echo 'solve: '.$N."\n";
        $list = [];     // 最终的列表

        if ($N <= 3) {  // 如果树的数量 < 3 不用砍
            for ($i = 0; $i < $N; $i ++) $list[] = 0;
            return "\n".implode("\n", $list);
        }
        /* if ($N <= 15) { // 小数据集
            for ($i = 0; $i < $N; $i ++) $list[] = $this->solve_small($N, $P, $i);
            return "\n".implode("\n", $list);
        } */
        if ($N <= 3000) { // 大数据集
            for ($i = 0; $i < $N; $i ++) $list[] = $this->solve_large($N, $P, $i);
            return "\n".implode("\n", $list);
        }
    }

    private function solve_large($N, $P, $i) { // echo 'i = '.$i.'--------------------';
        // 记录树 i 到每个点的角度
        $S = [];
        for ($j = 0; $j < $N; $j ++) {
            if ($j == $i) continue;
            $x = $P[$j][0] - $P[$i][0]; $y = $P[$j][1] - $P[$i][1];
            if ($x == 0) $a = $y > 0 ? pi() / 2 : - pi() / 2; // 90 度, -90 度
            elseif ($y == 0) $a = $x > 0 ? 0 : pi();          // 0 度, 180 度
            else {
                $a = atan($y / $x);
                if ($a > 0 && $y < 0) $a -= pi();            // atan 为正, 但 x < 0, y < 0
                if ($a < 0 && $y > 0) $a += pi();            // atan 为负, 但 y > 0, x < 0
            }
            $S[$j] = $a / pi() * 180 * 1000000;              // 转换为 角度 x 10^6
        }
        sort($S); // print_r($S);
        // 计算至少需要砍多少树
        $min_log = $N; $head = 0;
        for ($tail = 0; $tail < $N - 1; $tail ++) {  // 尾指针每次进 1
            if ($head == $tail) $head = $this->moveHead($head, 1, $N - 1); // 一开始头尾指针相等
            for (; $this->angleDiff($S, $tail, $head) < 180 * 1000000 && $head != $tail; $head = $this->moveHead($head, 1, $N - 1));
            $head = $this->moveHead($head, -1, $N - 1);   // 找到这条线左边的最后一个点
            if ($this->angleDiff($S, $tail, $head) >= 180 * 1000000) $log = 0;  // 左边没有点, 方向相同也算, 所以设相同为 360 度
            else { $log = $head - $tail; if ($log < 0) $log += $N - 1; } // 需要砍多少树, 如果窗口为负数, 加上 N - 1
            if ($log < $min_log) $min_log = $log; // echo 'log (i='.$i.', tail=' .$tail.', head='.$head.') ='.$log."\n";
        }
        return $min_log;
    }

    // 移动头指针
    private function moveHead($head, $d, $n) {  // if ($d == -1) echo 'found: '.$head.', '.$d;
        $head = $head + $d;
        if ($head > $n - 1) $head -= $n;
        if ($head < 0) $head += $n;             // if ($d == -1) echo ' = '.$head."\n";
        return $head;
    }

    // 计算从 a1 到 a2 经过多少角度
    private function angleDiff(&$S, $i, $j) {
        $diff = $S[$j] - $S[$i];
        if ($diff <= 0) $diff += 360 * 1000000; // echo 'angleDiff: ('.$i.', '.$j.') = '.($diff / 1000000)."\n";
        return $diff;
    }

    // 小数据集的解决方法
    private function solve_small($N, $P, $i) { // 对于编号为 i 的树
        $min_log = $N;  // 至少要砍多少棵
        for ($j = 0; $j < $N; $j ++) {         // 沿着 ij 方向看过去
            $log = 0;
            if ($j == $i) continue;
            for ($k = 0; $k < $N; $k ++) {     // 有多少棵树在 ij 的左边
                if ($k == $i || $k == $j) continue;
                $x1 = $P[$j][0] - $P[$i][0]; $y1 = $P[$j][1] - $P[$i][1];  // ij 方向
                $x2 = $P[$k][0] - $P[$i][0]; $y2 = $P[$k][1] - $P[$i][1];  // ik 方向
                if ($x1 * $y2 - $x2 * $y1 < 0) $log ++;
            }
            if ($log < $min_log) $min_log = $log;
        }
        return $min_log;
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
                $N = trim(fgets($handle)); $P = [];
                for ($i = 0; $i < $N; $i ++) $P[] = explode(' ', trim(fgets($handle)));
                $L = new Logging();
                $r = $L->solve($N, $P);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}
$t = time();
//$i = new Input('../下载/C-small-practice.in','../下载/OUT_3.txt');
$i = new Input('../下载/C-large-practice.in','../下载/OUT_3.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-9
 * Time: 下午9:38
 *
 * 某个森林有 N 棵树组成, 每棵数有一只松鼠居住.
 * 森林的边界上包含每棵树的最小凸多边形, 就好像用一条巨大的橡皮筋套在森林外围.
 * 有些树位于森林的边界, 就是说它们在多边形的角上或边上.
 * 每一只松鼠爬到树上, 记录下, 如果要让它们的树在边界上, 至少需要砍伐多少树. 确定写在日志上的数字列表.x
 *
 * 思路:
 * 如果森林中只有一棵树. 那就没有必要砍树了. 答案是 0.
 * 否则的话, 想象我们已经砍掉了一些树, 让 P 在边界上. 如果我们从 P 开始, 顺时针走, 会遇到另一个边界点 Q.
 * 想象我们站在 P 点, 看向 PQ 组成的边界线, 线的左边应该没有树.
 * 所以一个方法是, 对于每一棵数 Q, 看多少树在 PQ 线的左边, 最少的就是答案.
 * 但是我们还有方法改进算法的效率.
 * 对每一个可能的 Q, 维持一个数组 S, 按照 PQ 的角度排序, 遍历数组 S, PQ 就像逆时针走一圈.
 * 这个方法的好处是, 对于任何一个 Q, 任何在 PQ 左侧的点都会在 Q 之后出现.
 * 所以我们可以计算一个窗口, 尾指针指向 Q, 头指针指向在 PQ 左边的最后一个点, 记为点 R.
 * 要为下一个 Q 更新窗口, 我们需要做 2 件事情. 尾指针移到 Q, 然后移动头指针找到下一个在 PQ 左侧的点.
 * 有几点需要注意:
 * 头指针会比尾指针更早到达数组的末尾, 这时头指针需要从数组的头部开始.
 * PQ 的左侧可能没有点. 这时可以让头指针等于尾指针.
 * 在某个角度 P 上可能有多个点. 这几个点在 S 种是连续出现的. 但者不影响执行, 不需要额外处理.
 */