<?php
ini_set("max_execution_time","3");

class DataPacking {

    public function solve($N, $X, $F) {
        rsort($F);
        $cnt = 0; $l = 0; $r = $N - 1;
        while($l <= $r) {
            if($F[$l] + $F[$r] <= $X) { $cnt ++; $l ++; $r --; }
            else { $cnt ++; $l ++; }
        }
        return $cnt;
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
            $D = new DataPacking();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $N = $conf[0]; $X = $conf[1]; $F = explode(' ', trim(fgets($handle)));
                $r = $D->solve($N, $X, $F);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
$i = new Input('../下载/A-large-practice.in','../下载/OUT_1.txt');
$i->process();

echo '<br/>execution time: '.(time() - $t).'<br/>';
echo '<br/>memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-1-24
 * Time: 下午2:39
 */