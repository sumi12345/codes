<?php

class Erdos–Szekeres {

    function solve($N, $A, $B) {
        $this->N = $N;
        $this->A = $A;
        $this->B = $B;
        //nodes 表示结点 保存 pa => A树父结点 pb => B树父节点 la => A树左兄弟 lb => B树左兄弟 links => 链接数
        $this->nodes = array();
        for($i = 0; $i < $this->N; $i ++) $this->nodes[$i] = array('ln' => 0);

        //按层级分类 A
        $arr = array();
        for($k = $this->N - 1; $k >= 0; $k --) {
            $v = $this->A[$k];
            if(!isset($arr[$v])) $arr[$v] = array();
            $arr[$v][] = $k;
        }
        ksort($arr);
        //echo 'arr_A: '; print_r($arr); echo '<br/>';
        foreach($arr as $level => $nodes) {
            $last_parent = $this->N;
            foreach($nodes as $k => $n) {
                //确定链接到父节点或左兄弟节点
                if($level > 1 && $last_parent > $n) {
                    foreach($arr[$level - 1] as $p) if($p < $n) break 1;
                    $this->nodes[$n]['pa'] = $p;
                    $this->nodes[$p]['ln'] ++;
                    $last_parent = $p;
                } if($k > 0) {
                    $l = $nodes[$k - 1];
                    $this->nodes[$n]['la'] = $l;
                    $this->nodes[$l]['ln'] ++;
                }
            }
        }

        //按层级分类 B
        $arr = array();
        for($k = 0; $k < $this->N; $k ++) {
            $v = $this->B[$k];
            if(!isset($arr[$v])) $arr[$v] = array();
            $arr[$v][] = $k;
        }
        ksort($arr);
        //echo 'arr_B: '; print_r($arr); echo '<br/>';
        foreach($arr as $level => $nodes) {
            $last_parent = -1;
            foreach($nodes as $k => $n) {
                if($level > 1 && $last_parent < $n) {
                    foreach($arr[$level - 1] as $p) if($p > $n) break 1;
                    $this->nodes[$n]['pb'] = $p;
                    $this->nodes[$p]['ln'] ++;
                    $last_parent = $p;
                } if($k > 0) {
                    $l = $nodes[$k - 1];
                    $this->nodes[$n]['lb'] = $l;
                    $this->nodes[$l]['ln'] ++;
                }
            }
        }
        //echo 'nodes: '; print_r($this->nodes); echo '<br/>';
        //排序
        $pool = array(); $no = $this->N; $r = array();
        for($i = $this->N - 1; $i >= 0; $i --) if($this->nodes[$i]['ln'] == 0) array_push($pool, $i);
        while($pool) {
            $n = array_shift($pool); $r[$n] = $no; $no --;
            unset($this->nodes[$n]['ln']);
            if($this->nodes[$n]) foreach($this->nodes[$n] as $k => $c) {
                $this->nodes[$c]['ln'] --;
                if($this->nodes[$c]['ln'] == 0) array_push($pool, $c);
            }
            rsort($pool);
        }

        ksort($r);
        return implode(' ', $r);
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
            $E = new Erdos–Szekeres();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': '; echo '<br/>';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = trim(fgets($handle));
                $A = explode(' ', trim(fgets($handle)));
                $B = explode(' ', trim(fgets($handle)));
                $r = $E->solve($conf, $A, $B);
                echo ($r).'<br/>';
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
$i = new Input('../下载/C-large-practice.in','../下载/OUT_3.txt');
$i->process();

/*
	说明：
	A是以节点的出现顺序构建的升序树 即每出现一个节点 判断现有树中数字比它小的最长序列 接在后面
	B是以节点的出现倒序构建的升序树
	Ai Bi 代表节点i在A树和B树中的层级
	A树中 同一层的兄弟节点都是严格的降序关系 (否则一定可以接在其后到下一层)
	B树中 同一层的兄弟节点都是严格的升序关系

	解法：
	只要某个结点没有子树也没有右兄弟 则可以出队
	由于顺序有关 序号大的结点优先出队
	有没有子树或右兄弟 用links属性表示 另外保存父节点和左兄弟的序号 方便查找更新和入队

	修改1: 队列 入队时要排序
	修改2: A树：每个节点要接在上一层 比它序号小的结点下
		   B树：每个节点要接在上一层 比它序号大的结点下
	修改3: 不管父节点是哪个 同一层的兄弟节点都是严格的降序/升序关系
*/
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-3-14
 * Time: 下午4:51
 */