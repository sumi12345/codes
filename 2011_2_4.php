<?php
class AIWar {
    private $P, $W, $wormhole = array();

    function __construct($p, $w, $wormhole) {
        $this->P = $p;
        $this->W = $w;
        $ws = explode(' ', $wormhole);
        foreach($ws as $w) {
            $w = explode(',', $w);
            $this->wormhole[$w[0]][$w[1]] = 1;
            $this->wormhole[$w[1]][$w[0]] = 1;
        }
        //print_r($this->wormhole);
    }

    function aiwar() {
        //广度优先遍历 算出最短路径
        $len = array(0); $queue = array(0);
        $this->broad($len, $queue); //print_r($len);
        $length = $len[1];
        //找出所有最短路径 根据这个路径查看可以威胁几个星球 返回可以威胁的最多星球
        $way = array();
        $threat = $this->find_way(0, $length, $way);
        return ($length - 1).' '.$threat;
    }

    function broad(&$len, &$queue) {
        if(!$queue) return;
        $p = array_shift($queue); $l = $len[$p]; if($p == 1) return;
        foreach($this->wormhole[$p] as $planet => $wormhole) {
            if(!isset($len[$planet]) && $wormhole > 0) {
                $len[$planet] = $l + 1;
                array_push($queue, $planet);
            }
        }
        //echo 'queue: '.$p.' '; print_r($queue); echo '<br/>';
        $this->broad($len, $queue);
    }

    function find_way($p, $len, $way) {
        if($len == 0 && $p != 1) return -1;
        elseif($len == 0 && $p == 1) return $this->threat($way);
        array_push($way, $p);
        $max = -1;
        foreach($this->wormhole[$p] as $planet => $wormhole) {
            if(!in_array($planet, $way) && $wormhole > 0) {
                $threat = $this->find_way($planet, $len - 1, $way);
                if($threat > $max) $max = $threat;
            }
        }
        return $max;
    }

    function threat($way) {
        //echo 'way: '; print_r($way); echo '<br/>';
        $threat = 0; $planets = array();
        foreach($way as $w) {
            foreach($this->wormhole[$w] as $p => $v) $planets[$p] = 1;
        }
        foreach($planets as $p => $v) if(!in_array($p, $way)) $threat ++;
        return $threat;
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
                $conf = trim(fgets($handle, 32));
                $conf = explode(' ', $conf);
                $p = intval($conf[0]); $w = intval($conf[1]);
                $wormhole = trim(fgets($handle));
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $a = new AIWar($p, $w, $wormhole);
                $r = $a->aiwar(); echo $r.'<br/>';
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$i = new Input('../下载/D-small-practice.in','../下载/OUT_3.txt');
$i->process();
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-4-19
 * Time: 下午4:30
 */