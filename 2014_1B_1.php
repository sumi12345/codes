<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Repeater {

    public function solve($N, $S) {
        $this->N = $N;
        $this->S = $S;

        $this->seq = array();
        $this->map = array();

        foreach($S as $k => $s) {
            $num = $this->seq_num($s);
            if($num === false) return false;
            foreach($num as $i => $vi) {
                $this->map[$i][$vi] = isset($this->map[$i][$vi]) ? $this->map[$i][$vi] + 1 : 1;
            }
        }

        $move = 0;
        foreach($this->map as $k => &$v) $move += $this->min_move($k);
        return $move;
    }

    /**
     * 计算去重序列和序列中每个字母的出现次数
     * str为去重之后的序列 num[i]为去重之后第i个字母出现次数
     */
    private function seq_num($s) {
        $len = strlen($s); $str = ''; $num = array(); $pre = ''; $cnt = -1;
        for($i = 0; $i < $len; $i ++) {
            if($s[$i] != $pre) {
                $cnt ++;
                $str .= $s[$i];
                $num[$cnt] = 1;
                $pre = $s[$i];
            } else {
                $num[$cnt] ++;
            }
        }
        if(!$this->seq) $this->seq[$str] = 1;
        elseif(!isset($this->seq[$str])) return false;
        return $num;
    }

    /**
     * 找第i个序列的最小步数
     * 是一个分段函数 每段是线性函数 所以极值一定在某个点上
     */
    private function min_move($I) {
        ksort($this->map[$I]); $min = 999999;
        //修改到数量i所需的步数
        foreach($this->map[$I] as $i => $vi) {
            $m = 0;
            foreach($this->map[$I] as $j => $vj) $m += abs($i - $j) * $vj;
            if($m < $min) $min = $m;
        }
        return $min;
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
            $R = new Repeater();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $N = trim(fgets($handle)); $S = array();
                for($i = 0; $i < $N; $i ++) $S[] = trim(fgets($handle));
                $r = $R->solve($N, $S);
                echo ($r === false ? 'Fegla Won' : $r).'<br/>';
                file_put_contents($this->out_file, ($r === false ? 'Fegla Won' : $r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
$i = new Input('../下载/A-small-practice.in','../下载/OUT_1.txt');
$i->process();

echo '<br/>execution time: '.(time() - $t).'<br/>';
echo '<br/>memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-2-1
 * Time: 下午9:44
 */