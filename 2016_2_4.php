<?php
class FreedomFactory {
    public function solve($N, $M) {  echo 'solve: '; print_r($M);
        $C = [];      // 图
        for ($w = 0; $w < $N; $w ++) {
            for ($m = 0; $m < $N; $m ++) {
                if ($M[$w][$m] == 1) {
                    $C['w'.$w]['m'.$m] = 1;
                    $C['m'.$m]['w'.$w] = 1;
                }
            }
        }

        // 找连通分量
        $sets = $this->find_set($N, $C);

        // 记录每个连通分量中的工人数量, 机器数量, 已有配对
        $set_info = [];
        $D = 0; $P = 0; $w = 0;  // 增加边数, 确定配对数, 不组合所需配对数
        foreach ($sets as $set) {
            $info = $this->check_set($set, $C);
            $p = max($info['w'], $info['m']); $w += $p;
            if ($info['w'] == $info['m']) {  // 工人机器相等, 不用参加组合
                $D += $p * $p - $info['c'];
                $P += $p;
            } else {  // 准备参加组合
                $set_info[] = $info;
            }
        }

        // 如果不用组合
        if ($w <= $N) {
            foreach ($set_info as $info) {
                $p = max($info['w'], $info['m']);
                $D += $p * $p - $info['c'];
                $P += $p;
            }
            return $D + ($N - $P);
        }

        // 小数据集最终只剩下 2 组, 所以合并就好
        if (count($set_info) == 2) {
            $small = $this->merge($set_info);
            return $D + $small['d'] + ($N - $P - $small['p']);
        }

        // 大数据集应该遍历所有组合方式, 待解
        return - count($set_info);
    }

    // 合并两个连通分量
    private function merge($set_info) {
        $w = 0; $m = 0; $c = 0;
        foreach ($set_info as $info) {
            $w += $info['w']; $m += $info['m']; $c += $info['c'];
        }
        $p = max($w, $m);
        return ['p' => $p, 'd' => $p * $p - $c];
    }

    private function check_set($set, $C) {
        $w = 0; $m = 0; $c = 0;  // 机器的数量, 员工的数量, 已经会的员工机器配对的数量
        foreach ($set as $node) {
            if ($node[0] == 'w') { $w ++; $c += count($C[$node]); }
            else $m ++;
        }
        return ['w' => $w, 'm' => $m, 'c' => $c];
    }

    private function find_set($N, $C) {
        $visited = [];
        $sets = [];
        for ($i = 0; $i < $N; $i ++) {     // 从每个员工出发
            $w = 'w'.$i;
            // 已经访问过
            if (isset($visited[$w])) continue;
            // 如果什么都不会
            if (empty($C[$w])) continue;
            // 从当前结点开始遍历
            $queue = [$w]; $connected = [$w]; $visited[$w] = 1;
            while (!empty($queue)) {
                $u = array_shift($queue);
                foreach ($C[$u] as $v => $k) {
                    if (isset($visited[$v])) continue;
                    $queue[] = $v;
                    $connected[] = $v;
                    $visited[$v] = 1;
                }
            }
            // 保存当前的连通分量
            $sets[] = $connected;
        }
        echo "\n".'sets: '; print_r($sets);
        return $sets;
    }
}

$F = new FreedomFactory();
echo $F->solve(3, ['011', '100', '100']);
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
                $N = trim(fgets($handle));
                $M = [];
                for ($i = 0; $i < $N; $i ++) $M[$i] = trim(fgets($handle));
                //if ($c != 5) continue;
                $F = new FreedomFactory();
                $r = $F->solve($N, $M);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

$i = new Input('../下载/D-small-practice.in','../下载/OUT_3.txt');
//$i = new Input('../下载/D-large-practice.in','../下载/OUT_3.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-11-30
 * Time: 下午7:52
 * 你刚刚建了一家工厂, 有 N 台机器, 每一台机器需要一名员工操作
 * 你也雇佣了 N 名员工, 询问每个员工会不会操作某台机器
 * 每一个工作日, 员工来上班, 会随机找到一台自己会操作的未被占用的机器,
 * 如果没有就不工作.
 * 你可以每次花 1 美元教 1 名员工熟悉 1 台机器
 * 要让每一台机器都有人操作, 你至少需要花多少钱
 *
 * 思路:
 * 将问题转化为二分图, 两边各有 N 个顶点.
 * 我们的目标是, 加最少的边, 使得图中所有的最大匹配(无法再增加边)都是完全匹配.
 * 在模拟过几个问题之后, 我们形成一个假设:
 * 每个连通分量都是完全二分图的时候, 每个最大匹配都是完全匹配.
 * 但是反过来是不是也成立呢.
 */