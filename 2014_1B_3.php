<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class TravelingSalesman {

    /**
     * @param $N 城市数量
     * @param $M 边的数量
     * @param $Z 城市的zipcode
     * @param $G 出发的有向图
     */
    public function solve($N, $M, $Z, $G) {
        asort($Z);
        $this->Z = $Z;
        $this->G = $G;
        $this->N = $N;
        print_r($this->Z); echo '<br/>';

        foreach($this->Z as $k => $zip) {
            $this->root = $k;
            $this->stack = array($k);
            $this->seq = array();
            $this->reachable = array();
            $this->dead = array();
            $this->DFS();
            return $this->concatenate($this->seq);
        }
    }

    //从某个城市出发 深度优先遍历序列
    private function DFS() {
        echo 'DFS: '; print_r($this->stack); echo '<br/>';
        if(!$this->stack) return false;
        $c = $this->stack[count($this->stack) - 1]; $this->seq[] = $c; $this->reachable[$c] = 1;
        if(isset($this->G[$c])) foreach($this->G[$c] as $k => $v) if(!isset($this->reachable[$k])) $this->reachable[$k] = 0;
        foreach($this->Z as $k => $zip) if(isset($this->reachable[$k]) && $this->reachable[$k] == 0) {
            if(!$this->try_next($k)) continue;
            array_push($this->stack, $k);
            return $this->DFS();
        }
        return false;
    }

    //尝试分配某个节点为下一个节点
    private function try_next($n) {
        echo 'try_next: '.$n.'<br/>';
        $stack = $this->stack; $dead = $this->dead;
        $p = array_pop($stack);
        if(isset($this->G[$p][$n])) return true;    //直接子节点 不kill掉任何节点
        while($stack && !isset($this->G[$p][$n])) { $dead[$p] = 1; $p = array_pop($stack); }
        if(!isset($this->G[$p][$n])) { unset($this->reachable[$n]); return false; }    //节点不可达 因为只能被已经kill掉的节点访问
        array_push($stack, $p);
        echo 'try_stack: '; print_r($stack); echo '<br/>';
        echo 'try_dead: '; print_r($dead); echo '<br/>';

        //不通过已经访问过的但是不在栈中的点(dead)是否能遍历所有结点
        $s = array($this->root); $visited = array();
        while($s) {
            $node = array_pop($s); echo 'node: '.$node.'<br/>';
            $visited[$node] = 1;
            foreach($this->G[$node] as $k => $v) {
                if(isset($dead[$k]) || isset($visited[$k])) continue;
                array_push($s, $k);
            }
        }

        //如果不用dead的节点也可以访问所有节点 更新 返回成功
        if(count($visited) + count($dead) == $this->N) {
            $this->stack = $stack; $this->dead = $dead;
            echo 'success!!!<br/>';
            return true;
        };
        echo 'failed!!!<br/>';
        return false;
    }

    //生成最终zipcode
    private function concatenate($seq) {
        $str = '';
        foreach($seq as $s) $str .= $this->Z[$s];
        return $str;
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
            $T = new TravelingSalesman();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $N = intval($conf[0]); $M = intval($conf[1]); $Z = array(); $G = array();
                for($i = 1; $i <= $N; $i ++) $Z[$i] = trim(fgets($handle));
                for($i = 1; $i <= $M; $i ++) { $e = explode(' ', trim(fgets($handle))); $G[$e[0]][$e[1]] = 1; $G[$e[1]][$e[0]] = 1; }
                //if($c != 70) continue;
                $r = $T->solve($N, $M, $Z, $G);
                echo ($r).'<br/>';
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
$i = new Input('../下载/C-small-practice.in','../下载/OUT_3.txt');
$i->process();

echo '<br/>execution time: '.(time() - $t).'<br/>';
echo '<br/>memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-2-1
 * Time: 下午9:43
 */