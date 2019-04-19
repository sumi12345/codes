<?php
ini_set("max_execution_time",  "3");
ini_set("memory_limit","3M");

class Walkway {
    private $X, $S, $R, $t, $N, $W;

    function __construct($x, $s, $r, $t, $n, $w) {
        $this->X = $x;
        $this->S = $s;
        $this->R = $r;
        $this->t = $t;
        $this->N = $n;
        $this->W = $w;
    }

    function walkway() {
        $this->map = array(); $walkway_len = 0;
        foreach($this->W as $w) {
            $len = $w[1] - $w[0]; $walkway_len += $len;
            $this->map[$w[2]] = isset($this->map[$w[2]]) ? $this->map[$w[2]] + $len : $len;
        }
        $this->map[0] = $this->X - $walkway_len;
        ksort($this->map);

        $run_time = 0; $walk_time = 0;
        foreach($this->map as $w => $len) {
            if($run_time < $this->t) $run_time += $this->run($w, $len, $run_time);
            else $walk_time += $this->walk($w, $len);
        }
        return $run_time + $walk_time;
    }

    function run($w, $len, $run_time) {
        $t1 = $this->t - $run_time;
        $t2 = $len / ($w + $this->R);
        if($t2 <= $t1) return $t2;
        else {
            $run_d = $t1 * ($w + $this->R);
            return $t1 + (($len - $run_d) / ($w + $this->S));
        }
    }

    function walk($w, $len) {
        return $len / ($w + $this->S);
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
                $x = intval($conf[0]); $s = intval($conf[1]); $r = intval($conf[2]); $t = intval($conf[3]); $n = intval($conf[4]); $w = array();
                for($i = 0; $i < $n; $i ++) $w[] = explode(' ', trim(fgets($handle)));
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $W = new Walkway($x, $s, $r, $t, $n, $w);
                $r = $W->walkway(); echo $r.'<br/>';
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$i = new Input('../下载/A-large-practice.in','../下载/OUT_3.txt');
$i->process();
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-4-19
 * Time: 下午4:11
 */