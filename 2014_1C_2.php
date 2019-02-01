<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class TrainCars {

    public function solve($N, $S) {
        $this->N = $N;
        $this->S = $S;

        $r = $this->process_cars();
        if($r === false) return 0;

        return $this->count_order();
    }

    private function process_cars() {
        $this->start = array(); $this->end = array(); $this->mid = array(); $this->all = array();
        foreach($this->S as $k => $car) {
            //字符串去重
            $len = strlen($car); $unq_str = $car[0];
            for($i = 1; $i < $len; $i ++) if($car[$i] != $car[$i - 1]) $unq_str .= $car[$i];
            echo 'uni_str: '.$unq_str.'<br/>';
            //echo 'start: '; print_r($this->start); echo '<br/>';
            //echo 'end: '; print_r($this->end); echo '<br/>';
            //echo 'mid: '; print_r($this->mid); echo '<br/>';
            $car = $unq_str; $len = strlen($unq_str);
            //如果整个字符串是同一个字母 放入all的计数
            if($len == 1) { $this->all[$car[0]] = isset($this->all[$car[0]]) ? $this->all[$car[0]] + 1 : 1; continue; }
            //起始和末尾
            $s = $car[0]; $e = $car[$len - 1];
            if(isset($this->mid[$s]) || isset($this->start[$s])) return false;
            if(isset($this->mid[$e]) || isset($this->end[$e])) return false;
            $this->start[$s] = $k; $this->end[$e] = $k;
            //中间
            for($i = 1; $i < $len - 1; $i ++) {
                $m = $car[$i];
                if(isset($this->mid[$m]) || isset($this->start[$m]) || isset($this->end[$m])) return false;
                $this->mid[$m] = $k;
            }
        }
    }

    private function count_order() {
        $start = array_keys($this->start);
        $end = array_keys($this->end);
        $S = array_diff($start, $end);
        echo 'start: '; print_r($S); echo '<br/>';
        //形成一个环
        if(!$S && $start) return 0;
        $len = count($S); $d = array();
        //处理整个字符串相同的情况
        $this->arr = [];
        foreach($this->all as $k => $v) {
            if(!isset($this->start[$k]) && !isset($this->end[$k])) $len ++;
            $d[$k] = $this->order($v);
        }
        echo 'len: '.$len.'<br/>';
        $r = $this->order($len);
        foreach($d as $k => $v) { $r *= $v; $r %= 1000000007; }
        return $r;
    }

    private function order($n) {
        if(!isset($this->arr[$n])) {
            $this->arr[$n] = $n == 0 ? 1 : $n * $this->order($n - 1);
            $this->arr[$n] %= 1000000007;
        }
        return $this->arr[$n];
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
            $T = new TrainCars();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $N = intval(trim(fgets($handle)));
                $S = explode(' ', trim(fgets($handle)));
                $r = $T->solve($N, $S);
                echo ($r)."\n";
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
 *	某个坑: 当所有字符串都是单字符串时 start为空数组 并不能判断为存在环
 *  large: $r %= 1000000007; 溢出
 */
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-2-1
 * Time: 下午9:33
 */