<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Mushroom {

    public function solve($N, $M) {
        $instant = $this->instant($N, $M);
        $constant = $this->constant($N, $M);

        return $instant.' '.$constant;
    }

    private function instant($N, $M) {
        $num = 0;
        for ($i = 1; $i < $N; $i ++) {
            $num += $M[$i - 1] - $M[$i] > 0 ? $M[$i - 1] - $M[$i] : 0;
        }

        return $num;
    }

    private function constant($N, $M) {
        $max = 0;
        for ($i = 1; $i < $N; $i ++) {
            $max = $M[$i - 1] - $M[$i] > $max ? $M[$i - 1] - $M[$i] : $max;
        }
        if ($max <= 0) return 0;

        $num = 0;
        for ($i = 1; $i < $N; $i ++) {
            $num += $M[$i - 1] > $max ? $max : $M[$i - 1];
        }
        return $num;
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
            $M = new Mushroom();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $n = intval(trim(fgets($handle)));
                $m = explode(' ', trim(fgets($handle)));
                $r = $M->solve($n, $m);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

$i = new Input('in.txt','OUT_1.txt');
//$i = new Input('A-small-practice.in','OUT_1.txt');
//$i = new Input('A-large-practice.in','OUT_1.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
