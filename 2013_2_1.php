<?php
class TicketSwap {
    private $N, $M, $T;

    function solve($N, $M, &$T) {
        $this->M = $M;
        $this->T = $T;
        $this->map = array();

        //T的格式 in out passengers
        $ori = 0;
        foreach($this->T as $k => $t) {
            if(!isset($this->map[$t[0]])) $this->map[$t[0]] = array(0, 0);
            if(!isset($this->map[$t[1]])) $this->map[$t[1]] = array(0, 0);
            $this->map[$t[0]][0] += $t[2];
            $this->map[$t[1]][1] += $t[2];
            $ori += $t[2] * $this->cost($t[1], $t[0]);
            $ori %= 1000002013;
        }
        ksort($this->map);
        //echo 'ori: '.$ori.'<br/>';
        //echo 'map: '; print_r($this->map); echo '<br/>';
        $this->stack = array(); $cnt = 0;
        foreach($this->map as $station => $action) {
            //进站
            if($action[0] > 0) {
                if(!isset($this->stack[$station])) $this->stack[$station] = 0;
                $this->stack[$station] += $action[0];
            }
            //出站
            if($action[1] > 0) {
                $p = $action[1]; krsort($this->stack);
                //echo 'pre: '; print_r($this->stack); echo '<br/>';
                foreach($this->stack as $s => $n) {
                    $swap = min($p, $n);
                    $p -= $swap; $this->stack[$s] -= $swap;
                    $cnt += $swap * $this->cost($station, $s);
                    $cnt %= 1000002013;
                    if($p == 0) break 1;
                }
                //echo 'cost: '.($this->cost($station, $s)).'<br/>';
                //echo 'after: '; print_r($this->stack); echo '<br/>';
            }

        }
        $r = $cnt - $ori;
        return ($r + 1000002013) % 1000002013;
    }

    function cost($i, $j) {
        $r = ($i - $j) * ($i - $j - 1) / 2;
        return $r % 1000002013;
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
            $T = new TicketSwap();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle))); $A = array();
                for($i = 0; $i < $conf[1]; $i ++) $A[] = explode(' ', trim(fgets($handle)));
                $r = $T->solve($conf[0], $conf[1], $A);
                echo ($r).'<br/>';
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
$i = new Input('../下载/A-large-practice.in','../下载/OUT_3.txt');
$i->process();

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-3-14
 * Time: 下午4:56
 */