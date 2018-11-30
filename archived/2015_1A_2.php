<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Barbershop {

    public function solve($B, $N, $M) {
        $T = array();
        foreach ($M as $k => $v) $T[$k + 1] = $v;

        $this->B = $B;
        $this->T = $T;

        rsort($M);
        $min = 0;
        $max = $M[0] * $N;

        while ($min < $max - 1) {
            $mid = floor(($min + $max) / 2);
            $served = $this->served_customers_at($mid);

            if ($served < $N) $min = $mid;
            else $max = $mid;
        }

        $served = $this->served_customers_at($min);
        $to_be_served = $N - $served;

        for ($i = 1; $i <= $B; $i ++) {
            if ($min % $T[$i] == 0) $to_be_served --;
            if ($to_be_served == 0) return $i;
        }
    }

    private function served_customers_at($t) {
        $num = 0;
        foreach ($this->T as $v) $num += ceil($t / $v);
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
            $B = new Barbershop();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $b = $conf[0];
                $n = $conf[1];
                $m = explode(' ', trim(fgets($handle)));
                $r = $B->solve($b, $n, $m);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

//$i = new Input('in.txt','OUT_2.txt');
$i = new Input('B-small-practice.in','OUT_2.txt');
//$i = new Input('B-large-practice.in','OUT_2.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
