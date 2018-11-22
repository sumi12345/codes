<?php

class Pancake {

    public function solve($P) {
        $R = [];
        
        $len = strlen($P);
        $prev = '.';

        for($i = 0; $i < $len; $i ++) {
            if ($P[$i] != $prev) {
                $R[] = $P[$i];
                $prev = $P[$i];
            }
        }

        return $R[count($R) - 1] == '+' ? count($R) - 1 : count($R);
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
                $P = new Pancake();
                $N = trim(fgets($handle));
                $r = $P->solve($N);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

//$i = new Input('in.txt','OUT_1.txt');
//$i = new Input('K:/下载/B-small-practice.in','OUT_1.txt');
$i = new Input('K:/下载/B-large-practice.in','OUT_1.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);