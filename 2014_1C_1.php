<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class PartElf {

    public function solve($P) {
        $p = explode('/', $P);
        $d = $this->gcd($p[0], $p[1]);
        $a = log($p[0] / $d, 2);
        $b = log($p[1] / $d, 2);
        if($b != intval($b)) return false;
        return intval($b) - intval($a);
    }

    public function gcd($p, $q) {
        if($p == 0) return $q;
        return $this->gcd($q % $p, $p);
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
            $E = new PartElf();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = trim(fgets($handle));
                $r = $E->solve($conf);
                echo ($r === false ? 'impossible' : $r).'<br/>';
                file_put_contents($this->out_file, ($r === false ? 'impossible' : $r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
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
 *  large case: 需要64位的计算
 */

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-2-1
 * Time: 下午9:34
 */