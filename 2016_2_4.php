<?php
class FreedomFactory {
    public function solve($N, $M) {  // echo 'solve: '; print_r($M);
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

        $P = 0; $D = 0;              // 完全匹配的员工机器的数量pair, 需要补充的数量dollar
        foreach ($sets as $set) {    // 对于每个连通分量
            $w = 0; $m = 0; $c = 0;  // 机器的数量, 员工的数量, 已经会的员工机器配对的数量
            foreach ($set as $node) {
                if ($node[0] == 'w') { $w ++; $c += count($C[$node]); }
                else $m ++;
            }
            echo 'w, m, c = '.$w.', '.$m.', '.$c."\n";
            $w = $w > $m ? $w : $m;
            $P += $w;
            $D += $w * $w - $c;
            echo 'P, D = '.$P.', '.$D."\n";
        }

        return $D + $N - $P;  // 加上未配对的数量
    }
}

$F = new FreedomFactory();
echo $F->solve(2, [[1, 1], [1, 0]]);
echo $F->solve(2, [[1, 0], [0, 0]]);
echo $F->solve(3, [[0, 0, 0], [0, 0, 0], [0, 0, 0]]);
echo $F->solve(1, [[1]]);
echo $F->solve(3, [[0, 0, 0], [1, 1, 0], [0, 0, 0]]);
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
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $N = trim(fgets($handle));
                $M = [];
                for ($i = 0; $i < $N; $i ++) $M[$i] = str_split(trim(fgets($handle)), 1);
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
 * 每一个工作日, 员工来上班, 会随机找到一台自己会操作的未被占用的机器, 如果没有就不工作.
 * 你可以每次花 1 美元教 1 名员工熟悉 1 台机器
 * 要让每一台机器都有人操作, 你至少需要花多少钱
 *
 * 思路: 找连通分量, 每个连通分量都要完全匹配, 不在连通分量里的两两匹配
 * 不通过, 原因不明
 */