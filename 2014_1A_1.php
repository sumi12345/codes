<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class ChargingChaos {
    private $N, $L, $I, $D;

    function solve($N, $L, $I, $D) {
        $this->N = $N; $this->L = $L; $this->I = $I;

        $F = array(); $S = array();
        for($i = 0; $i < $this->N; $i ++) {
            //让第i个outlet匹配第一个device
            $m = $this->match($I[$i], $D[0]);
            $F[] = $m['f']; $S[] = $m['s'];
        }

        asort($S);
        $this->D = array(); foreach($D as $d) $this->D[$d] = isset($this->D[$d]) ? $this->D[$d] + 1 : 1;

        foreach($S as $k => $v) if($this->check($F[$k])) return $v;
        return false;
    }

    /**
     * 要让原始序列I匹配目标设备D所需的开关序列
     */
    function match($I, $D) {
        $f = ''; $s = 0;
        for($i = 0; $i < $this->L; $i ++) {
            if($I[$i] == $D[$i]) {
                $f .= 0;
            } else {
                $f .= 1; $s ++;
            }
        }
        return array('f' => $f, 's' => $s);
    }

    /**
     * 检查某个开关序列是否合格
     */
    function check($F) {
        $D = $this->D;
        foreach($this->I as $I) {
            $c = $this->change($I, $F);
            if(isset($D[$c]) && $D[$c] > 0) $D[$c] --;
            else return false;
        }
        return true;
    }

    /**
     * 某个outlet I经过开关序列F之后的flow
     */
    function change($I, $F) {
        for($i = 0; $i < $this->L; $i ++) $I[$i] = $F[$i] == 1 ? 1 - $I[$i] : $I[$i];
        return $I;
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
            $C = new ChargingChaos();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $N = $conf[0]; $L = $conf[1];
                $I = explode(' ', trim(fgets($handle))); $D = explode(' ', trim(fgets($handle)));
                $r = $C->solve($N, $L, $I, $D);
                echo ($r === false ? 'NOT POSSIBLE' : $r).'<br/>';
                file_put_contents($this->out_file, ($r === false ? 'NOT POSSIBLE' : $r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
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
 *  基本思想: 拿第一个device和每一个outlet匹配 生成一个开关序列
 *  再检查这个开关序列是否符合要求
 */
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-2-1
 * Time: 下午9:45
 */