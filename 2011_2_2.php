<?php
ini_set("max_execution_time",  "60");
ini_set("memory_limit","60M");

class Blade {
    private $C, $R, $D, $S;

    function __construct($r, $c, $d, $s) {
        $this->C = $c;
        $this->R = $r;
        $this->D = $d;
        $this->S = $s;
    }

    function blade() {
        $max_size = 3; $possible = false;
        for($i = 0; $i < $this->R - 2; $i ++) {
            for($j = 0; $j < $this->C - 2; $j ++) {
                //计算以这个点为左上角的最大size
                $r = $this->R - $i; $c = $this->C - $j; $m = min($r, $c); //echo $i.','.$j.','.$r.','.$c.','.$m.'<br/>';
                for($k = $m; $k >= $max_size; $k --) {
                    if($this->center($i, $j, $k)) {
                        $possible = true;
                        if($k > $max_size) $max_size = $k;
                        break 1;
                    }
                }
            }
        }
        return $possible ? $max_size : false;
    }

    function center($I, $J, $S) {
        $ci = $I + ($S - 1) / 2; $cj = $J + ($S - 1) / 2; $h = 0; $v = 0;
        //计算横行
        for($i = $I; $i < $I + $S; $i ++) {
            $dr = $i - $ci; if($dr == 0) continue;
            for($j = $J; $j < $J + $S; $j ++) {
                if(($i == $I || $i == $I + $S - 1) && ($j == $J || $j == $J + $S - 1)) continue;
                $h += $dr * ($this->S[$i][$j]);
            }
        }
        //计算纵行
        for($j = $J; $j < $J + $S; $j ++) {
            $dr = $j - $cj; if($dr == 0) continue;
            for($i = $I; $i < $I + $S; $i ++) {
                if(($i == $I || $i == $I + $S - 1) && ($j == $J || $j == $J + $S - 1)) continue;
                $v += $dr * ($this->S[$i][$j]);
            }
        }

        if($h == 0 && $v == 0) return true;
        return false;
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
            for($c = 1; $c <= $cases; $c ++) {
                $conf = trim(fgets($handle));
                $conf = explode(' ', $conf);
                $R = intval($conf[0]); $C = intval($conf[1]); $D = intval($conf[2]); $S = array();
                for($i = 0; $i < $R; $i ++) $S[] = trim(fgets($handle));
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $b = new Blade($R, $C, $D, $S);
                $r = $b->blade(); $r = $r == false ? 'IMPOSSIBLE' : $r; echo $r.'<br/>';
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$i = new Input('../下载/B-small-practice.in','../下载/OUT_3.txt');
$i->process();
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-4-19
 * Time: 下午4:25
 */