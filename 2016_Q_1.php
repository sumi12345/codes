<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Sheep {

    private $digits = [];

    public function solve($N) {
        if ($N == 0) return 'INSOMNIA';
        
        for ($i = 1; $i < 100; $i ++) {
            $this->setDigits($i * $N);
            if (count($this->digits) == 10) {
                return $i * $N;
            }
        }

        return 'INSOMNIA';
    }

    private function setDigits($x) {
        //echo $x;
        while ($x > 0) {
            $this->digits[$x % 10] = 1;
            $x = floor($x / 10);
        }
        //print_r($this->digits);
    }
}

//$S = new Sheep();
//echo $S->solve(11);
//exit;

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
                $S = new Sheep();
                $N = trim(fgets($handle));
                $r = $S->solve($N);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

//$i = new Input('in.txt','OUT_1.txt');
//$i = new Input('K:/下载/A-small-practice.in','OUT_1.txt');
$i = new Input('K:/下载/A-large-practice.in','OUT_1.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);