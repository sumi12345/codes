<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Pancakes {
    private $P;

    public function solve($P) {
        rsort($P);
        $this->P = $P;
        $min_time = $P[0];
        for($i = $P[0]; $i > 0; $i --) {
            $need_time = $this->per_plate($i);
            if($need_time < $min_time) $min_time = $need_time;
        }
        return $min_time;
    }

    // 分配到每个盘子有i个月饼以下需要多少分钟
    private function per_plate($i) {
        $need_time = $i;
        foreach($this->P as $num) {
            $need_time += ceil($num / $i) - 1;
        }
        return $need_time;
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
            $P = new Pancakes();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $D = trim(fgets($handle));
                $plates = explode(' ', trim(fgets($handle)));
                $r = $P->solve($plates);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

//$i = new Input('IN.txt','OUT.txt');
//$i = new Input('B-small-practice.in','OUT.txt');
$i = new Input('B-large-practice.in','OUT.txt');
$i->process();

echo '<br/>execution time: '.(time() - $t).'<br/>';
echo '<br/>memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);