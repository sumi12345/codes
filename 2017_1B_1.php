<?php
class Steed {
    public function solve($D, $N, $K, $S) { echo 'solve: D='.$D.', N='.$N."\nK="; echo implode(' ', $K)."\nS=".implode(' ', $S)."\n";
        $max_t = 0;
        for ($i = 0; $i < $N; $i ++) {
            $t = ($D - $K[$i]) / $S[$i];
            if ($t > $max_t) $max_t = $t;
        }
        return $D / $max_t;
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
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $N = explode(' ', trim(fgets($handle)));
                $K = []; $S = [];
                for ($i = 0; $i < $N[1]; $i ++) {
                    $row = explode(' ', trim(fgets($handle)));
                    $K[] = $row[0]; $S[] = $row[1];
                }
                $St = new Steed();
                $r = $St->solve($N[0], $N[1], $K, $S);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
//$i = new Input('../下载/A-small-practice.in','../下载/OUT_1.txt');
$i = new Input('../下载/A-large-practice.in','../下载/OUT_1.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-13
 * Time: 下午3:36
 */