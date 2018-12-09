<?php
ini_set("max_execution_time","10");
ini_set("memory_limit","100M");

class HikingDeer {
    public function solve($N, $G) {
        $T = []; $C = [];  // 每个散步者第一次经过起点的时间, 和绕一圈的时间
        foreach ($G as $g) for ($i = 0; $i < $g[1]; $i ++) {
            $T[] = (360 - $g[0]) / 360 * ($g[2] + $i); // g0 是起始角度, g1 是在这个角度的人数
            $C[] = $g[2] + $i;                         // g2 是走一圈需要的时间, 每个人 + 1
        }
        asort($T); $H = count($T); $n = 0; $min_e = $H;  // n 个人已经经过了起点
        foreach ($T as $hiker => $hikerT) {
            $n ++; $e = $H - $n;
            foreach ($T as $pre => $preT) {
                if ($pre == $hiker) break;
                $e += floor(($hikerT - $preT) / $C[$pre]);
            }
            if ($e < $min_e) $min_e = $e; echo $hikerT.', '.$e.'|';
        }
        return $min_e;
    }
}

$H = new HikingDeer();
echo $H->solve(4, [[1, 1, 12], [359, 1, 12], [2, 1, 12], [358, 1, 12]])."\n";
echo $H->solve(2, [[180, 1, 10000], [180, 1, 1]])."\n";
echo $H->solve(1, [[180, 2, 1]])."\n";
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
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $N = trim(fgets($handle)); $G = [];
                for ($i = 0; $i < $N; $i ++) $G[] = explode(' ', trim(fgets($handle)));
                $H = new HikingDeer();
                $r = $H->solve($N, $G);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
//$i = new Input('../下载/C-small-practice-1.in','../下载/OUT_3.txt');
$i = new Input('../下载/C-small-practice-2.in','../下载/OUT_3.txt');
//$i = new Input('../下载/C-large-practice.in','../下载/OUT_3.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-8
 * Time: 下午4:13
 *
 * Herbert Hooves 是一只要去散步的鹿. 他最喜欢的是从 0 度开始, 顺时针走一圈.
 * 他能控制自己的速度在任意正整数, 能随时改变速度, 走一圈就停.
 * 人类也走相同的路径. 他们从某个起点开始, 以某个速度一圈一圈地走.
 * Herber 知道人类散步者的起点和速度. 求与人类至少要相遇多少次.
 *
 * H 记为散步者的数量. 相遇次数不会大于 H, 因为不管散步者最初的位置和速度, Herbert 总是能够跟上他.
 * 另一个重要的观察是, 让 Herbert 停下或者改变速度并不能改变结果.
 * 如果 Herbert 到达终点的时间已知. 我们就能根据他超过的人数和超过他的人数, 算出一个最小值.
 * 这个数字跟他以恒定速度移动时是一样的, 停下或改变速度没有用.
 * 所以现在我们只需要找一个到达时间, 来计算最少相遇次数.
 * 对于单个散步者而言, 因为道路是环形的, 他不停地走, 会一次一次地经过 Herbert 的起点.
 * X 记为 Herbert 完成散步的时间, T1, T2, T3...记为散步者第 1 次, 第 2 次, 第 3 次经过 Herbert 的起点的时间.
 * 1. 如果 X <= T1, Herbert 超过散步者 1 次. (Herbert 以最快的速度跑一圈, 超过散步者)
 * 2. 如果 T1 < X < T2, 他们不相遇. (散步者始终在 Herbert 前面)
 * 3. 如果 T2 <= X < T3, 散步者超过 Herbert 1 次.
 * 4. 如果 T3 <= X < T4, 散步者超过 Herbert 2 次.
 * 所以如果我们延长 Herbert 的完成时间, 会有多个事件发生, 这些事件会改变 Herbert 的相遇次数.
 * 如果我们有多个散步者, 就会有多组事件.
 * 但是, Herbert 不会等到在一个散步者身上发生比H多的事件,
 * 所以对于小数据集, 我们可以根据时间排序, 然后做如下操作:
 * 1. 将相遇次数设为 H.
 * 2. 对于每一个事件, 按时间顺序, 如果这是这个散步者的第一个事件, -1, 否则 +1.
 * 达到的最小值即是答案.
 * 对于大数据集, 我们知道只有 H 个事件让计数减1, 其余都加1.
 * 所以如果我们已经处理了 2H 个事件, 我们不可能找到一个比 H 小的数字了, 就可以停止搜索.
 * 我们还要避免保存 H^2 个事件. 可以维持一个事件的优先队列.
 * 对每个队列, 初始化为这个散步者的第一个事件 T1.
 * 每次我们处理了这个事件, 将队列更新为这个散步者的下一个事件.
 * 有一点需要注意, 如果多个散步者同时经过 Herbert 的起点, 加的操作一定放在减的操作前面.
 *
 * 大数据集, case 7 执行超过 30 秒, 就放弃了, 需改进方法.
 */