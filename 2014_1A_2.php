<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class BinaryTree {

    function solve($N, $G) {
        $this->N = $N;
        $this->G = $G;
        $this->map = array();

        $nodes = array();
        for($i = 1; $i <= $N; $i ++) {
            $nodes[$i] = $this->get_nodes($i, 0);
        }
        rsort($nodes);
        return $N - $nodes[0];
    }

    //以结点n为根 以p为父节点的满二叉树的结点数
    function get_nodes($n, $p) {
        if(!isset($this->map[$n][$p])) {
            $children = $this->G[$n]; unset($children[$p]);
            if(count($children) < 2) $this->map[$n][$p] = 1;
            else {
                $num = array();
                foreach($children as $c => $v) {
                    $num[$c] = $this->get_nodes($c, $n);
                }
                rsort($num);
                $this->map[$n][$p] = $num[0] + $num[1] + 1;
            }
        }
        return $this->map[$n][$p];
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
            $B = new BinaryTree();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $N = intval(trim(fgets($handle))); $G = array();
                for($i = 0; $i < $N - 1; $i ++) {
                    $e = explode(' ', trim(fgets($handle)));
                    $G[$e[0]][$e[1]] = 1; $G[$e[1]][$e[0]] = 1;
                }
                $r = $B->solve($N, $G);
                echo ($r).'<br/>';
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
$i = new Input('../下载/B-small-practice.in','../下载/OUT_2.txt');
$i->process();

echo '<br/>execution time: '.(time() - $t).'<br/>';
echo '<br/>memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 *	这里满二叉树的定义: A full binary tree is a rooted tree where every node has either exactly 2 children or 0 children.
 *  给出一个无向图 这个无向图本身是树 计算以每一个节点为根的树的最终结点数量
 */
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-2-1
 * Time: 下午9:44
 */