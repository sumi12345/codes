<?php
ini_set("max_execution_time","75");
ini_set("memory_limit","75M");

class Bilingual {
    public function solve($N, $S) {
        // 记录所有单词
        $W = [];
        for ($i = 0; $i < $N; $i ++) foreach ($S[$i] as $w) $W[$w] = 1;
        // 构造有向容量图
        $C = [];
        foreach ($W as $w => $v) {   // 拆结点, 记得反向的路补0, 因为 C - F 用的 foreach
            $C[$w.'1'][$w.'2'] = 1; $C[$w.'2'][$w.'1'] = 0;
        }
        for ($i = 0; $i < $N; $i ++) foreach ($S[$i] as $w) {  // S->w1, w2->S, 反向补0
            $C['S'.$i][$w.'1'] = 1000; $C[$w.'1']['S'.$i] = 0;
            $C[$w.'2']['S'.$i] = 1000; $C['S'.$i][$w.'2'] = 0;
        }
        // 从句0到句1计算最大流
        return $this->findMaxFlow($C, 'S0', 'S1');
    }

    public function findMaxFlow(&$C, $s, $t) {
        $F = []; $f = 0;
        for ($K = 0; $K < 1000; $K ++) {   // 循环直到找不到路径
            $parent = [];
            $visited = [];
            $queue = [$s];
            while (!empty($queue)) {
                $u = array_shift($queue);
                foreach ($C[$u] as $v => $c) {
                    if (isset($visited[$v])) continue;
                    if (!isset($F[$u][$v])) { $F[$u][$v] = 0; $F[$v][$u] = 0; }
                    if (!isset($visited[$v]) && $C[$u][$v] - $F[$u][$v] > 0) {
                        $queue[] = $v;
                        $parent[$v] = $u;
                        $visited[$v] = 1;
                        if ($v == $t) break 2;
                    }
                }
            }
            if (!isset($visited[$t])) break;  // 找不到路径了
            // 更新流量图
            for ($v = $t; $v != $s; $v = $parent[$v]) {
                $u = $parent[$v];
                $F[$u][$v] += 1;
                $F[$v][$u] -= 1;
            }
            $f ++;
        }
        return $f;
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
            $B = new Bilingual();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $N = intval($conf[0]); $S = array();
                for ($i = 0; $i < $N; $i ++) $S[] = explode(' ', trim(fgets($handle)));
                $r = $B->solve($N, $S);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
// $i = new Input('../下载/C-small-practice.in','../下载/OUT_3.txt');
$i = new Input('../下载/C-large-practice.in','../下载/OUT_3.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Elliot 的父母在家跟他说法语和英语. 他已经听过很多词, 但不明白哪些词是哪个语言!
 * 有些句子他能分清是英语还是法语, 有些分不清.
 * 如果一个词出现在英语句子里, 则它一定是英语词, 出现在法语句子里, 一定是法语词.
 * 给出所有句子, 至少有多少词一定, 既是英语词, 也是法语词呢?
 *
 * 思路: 构造一个图, 每个句子和词是其中的结点. 在句子和它包含的词中间加上一条边.
 * 对于一条从句0到句1的路径, 路径是交替经过句子和词语的.
 * 因为一定在某个结点上, 路径从英语变成法语, 所以至少有一个结点属于两个语言.
 * 我们需要找到一个词语集合, 让从句0到句1的所有路径, 都经过这个词语集合中的一个词语.
 * 我们可以把这个问题转化为有向图割(edge cut problem)的问题.
 * 对于每个词w, 构造两个结点 Aw, Bw, 从 Aw 到 Bw 的容量为 1.
 * 对于包含词 w 的句 S, 加一条从 S 到 Aw 的有向边, 和一条从 Bw 到 S 的有向边, 容量无限大.
 * 用最大流算法, 找到最小边割集, 这些最小边割集会切断一个词w从 Aw 到 Bw 之间的连接
 * 这些被最小边割集切割的单词就是答案.
 * 即找到几条单词结点不相交的从 S0 到 S1 的路径, 因为每一条路径一定是交替经过词语和句子,
 * 所以每一条路径找一个词即可, 所以等于最大流.
 *
 * 执行速度太慢了...小数据集要 53 秒...大数据集要 71 秒
 */