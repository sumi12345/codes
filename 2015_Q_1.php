<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Standing {

    public function solve($shy_max, $shy) {
        $has_stand = 0;
        $friend = 0;
        for($i = 0; $i <= $shy_max; $i ++) {
            if($has_stand >= $i) {
                $has_stand += $shy[$i];
                continue;
            }
            $friend += $i - $has_stand;
            $has_stand = $i + $shy[$i];
        }
        return $friend;
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
            $S = new Standing();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $shy_max = $conf[0]; $shy = $conf[1];
                $r = $S->solve($shy_max, $shy);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

//$i = new Input('in.txt','OUT_1.txt');
//$i = new Input('A-small-practice.in','OUT_1.txt');
$i = new Input('A-large-practice.in','OUT_1.txt');
$i->process();

echo '<br/>execution time: '.(time() - $t).'<br/>';
echo '<br/>memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
