<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class SmoothWindow {
    public function solve($N, $K, $T) { // echo 'solve: ('.$N.', '.$K.', '.implode(' ', $T).')'."\n";
        // 构造一个可行的序列
        $S = [];
        for ($i = 0; $i < $K - 1; $i ++) $S[] = 0;
        for ($i = $K - 1; $i < $N; $i ++) {
            $sum = 0;
            for ($j = 0; $j < $K - 1; $j ++) $sum += $S[$i - $j - 1];
            $S[] = $T[$i - $K + 1] - $sum; // 题目给的是和, 不是平均数, 所以不用乘以K
        }
        // 分组, 对每一组记录最大最小值
        $G = [];
        for ($i = 0; $i < $N; $i ++) {
            $g = $i % $K;
            if (!isset($G[$g])) { $G[$g] = [$S[$i], $S[$i]]; continue; }
            if ($S[$i] < $G[$g][0]) $G[$g][0] = $S[$i];
            if ($S[$i] > $G[$g][1]) $G[$g][1] = $S[$i];
        }
        // 正常化, 并记录所有的移动值
        $Q = 0;
        for ($g = 0; $g < $K; $g ++) { $Q += $G[$g][0]; $G[$g][1] -= $G[$g][0]; $G[$g][0] = 0; }
        // 找到所有组的最大温差
        $L = 0;
        for ($g = 0; $g < $K; $g ++) if ($G[$g][1] > $L) $L = $G[$g][1];
        // 计算调整空间
        $A = 0;
        for ($g = 0; $g < $K; $g ++) $A += $L - $G[$g][1];
        // 调整空间只能向上, 所以如果 Q % K 小于0, 如 K=3, Q=-1, 加上 K 保持 Q 是正数
        $Q = $Q % $K; if ($Q < 0) $Q += $K;
        return $Q <= $A ? $L : $L + 1;
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
                $K = explode(' ', trim(fgets($handle)));
                $T = explode(' ', trim(fgets($handle)));
                $S = new SmoothWindow();
                $r = $S->solve($K[0], $K[1], $T);
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

echo '<br/>execution time: '.(time() - $t).'<br/>';
echo '<br/>memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-5
 * Time: 下午1:59
 *
 * Adamma 是一个气象学家. 每分钟, 他记录下当前温度, 形成一个list: x1, x2, ..., xN
 * 今天他想画一条滑动平均值曲线(sliding average), 选取 K 作为窗口 (smoothing window)
 * 就是说, 把 N 个温度变成 N - K + 1 个平均温度, 每个温度是 xi 到 x(i+K-1) 的平均温度
 * 糟糕的是, 他忘记保存原值了! 但他现在想知道最高和最低温度之间的温度差.
 * 想想最大温差的结果可能有很多个, 那就给一个最小的吧.
 *
 * 我们可以从构造一个基础序列开始. 这个序列的前 N-K+1 个数字都是 0. 这样我们得到一个初始序列.
 * 我们将这个序列分组, 如 K=3, 则第1, 4, 7为一组, 2, 5为一组, 3, 6为一组. 每一组的数字共同进退.
 * 如果某一组数字共同加 d, 则需要有另一组数字共同减去 d, 才能维持平衡.
 * 我们定义 lo(i) 为第 i 组内的最小值
 * hi(i) 为第 i 组内的最大值
 * interval(i)为 [lo(i), hi(i)]
 * SHIFT(i,y) 为一个操作, 将第 i 组的数字全部 +y
 * 这样, 我们可以将问题重新描述为:
 * 给你 K 个 interval, 第 i 个是 [lo(i), hi(i)]
 * 你可以对任意组进行整组调整操作, 目的是让所有组的覆盖范围最小.
 * 我们看到, 调整可以说一次性的, 且是独立的.
 * 记 Q 为全部的调整值.
 * 我们有两个 interval, [-10, 8] 和 [333, 777].
 * 我们可以将它们正常化为 [0, 2] 和 [0, 444], Q=-10+333
 * 最后再将Q平均分配回每一组即可.
 * 假设我们有 3 个 interval. [0, 4], [0, 9], [0, 7], Q=40
 * 我们可以将 Q=39 平均分配回 3 组, 每一组 13. 剩下 1.
 * 但是题目中我们只对范围差感兴趣, 所以不用分配 13 也行.
 * 记 L 为最大范围差. 如果剩下 Q=0, 那就没有得分配了.
 * 如果还有剩, 每一组可以多分配 L-(hi(i)-lo(i))
 * 如果还有剩, 那只能扩大现有的最大范围差 L 了.
 * 将剩下的 Q, 选Q组分配 1, 这样最大范围差 + 1
 */