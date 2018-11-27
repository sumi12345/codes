<?php
class Technobabble {

    public function solve($N, $titles) {
        $C = [];                            // 容量图 C[A][B] = 1 代表有一条从A到B的路径
        $F = [];                            // 流量图, 从流量0开始, 找路径
        $pool = ['s' => 1, 't' => 1];       // 记录所有结点
        foreach ($titles as $title) {       // 记录结点, 初始化容量图
            $splited = explode(' ', $title);
            $A = 'a'.$splited[0]; $B = 'b'.$splited[1];
            $pool[$A] = 1; $pool[$B] = 1;
            $C[$A][$B] = 1; $C['s'][$A] = 1; $C[$B]['t'] = 1;
        }

        foreach ($pool as $u => $k) {        // 没有的填充为0
            foreach ($pool as $v => $k) {
                if (!isset($C[$u][$v])) $C[$u][$v] = 0;
                if (!isset($F[$u][$v])) $F[$u][$v] = 0;
            }
        }

        $f = 0;                      // 流量
        for ($i = 0; $i < 99999; $i ++) {  // 循环直到找不到路径
            $visited = [];          // 最大流问题要记录从s到当前结点的最小残量, 以计算最后的新增流量, 但这里只要能通过, 残量就是1
            $queue = ['s'];         // 队列
            $parent = [];           // 记录每个节点的前一个节点
            while (!empty($queue)) {
                $u = array_shift($queue);   // 询问每个节点v, 如果v未被访问, 且从u到v有残量, 则加入路径
                foreach ($pool as $v => $k) if (!isset($visited[$v]) && $C[$u][$v] > $F[$u][$v]) {
                    $parent[$v] = $u;
                    $queue[] = $v;
                    $visited[$v] = 1;
                }
            }

            if (empty($visited['t'])) break;               // 从s找不到路径到达t, 已经是最大流量

            // echo "\n".'road found: ';
            for ($u = 't'; $u != 's'; $u = $parent[$u]) {  // 更新路径上的流量
                // echo $u.' ';
                $v = $parent[$u];
                $F[$v][$u] += 1;
                $F[$u][$v] -= 1;
            }

            $f ++;
        }
        echo "\n".'最大匹配: '.$f."\n".'非原创: ';
        return $N - ($f + count($pool) - 2 - 2 * $f);
    }

}

//$T = new Technobabble();
//echo $T->solve(4, ['A B', 'A C', 'D B', 'D C']);
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
                $T = new Technobabble();
                $N = trim(fgets($handle));
                $titles = [];
                for ($i = 0; $i < $N; $i ++) $titles[] = trim(fgets($handle));
                $r = $T->solve($N, $titles);
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

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * 二分图的最小边覆盖：用尽量少的不相交简单路径覆盖有向无环图(DAG)G的所有顶点；
 * 可以转换为最大流问题, 左边新增结点s, 右边新增结点t, 求从s到t的最大流量
 * 大数据集要执行74秒, 且占用空间很大, 可以尝试给每个结点编号, 然后都用代号
 */