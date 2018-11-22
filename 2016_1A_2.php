<?php

class RankFile {

    public function solve($N, $arr) {
        //print_r($arr);
        $cnt = [];
        
        for ($i = 0; $i < 2 * $N - 1; $i ++) {
            for ($j = 0; $j < $N; $j ++) {
                $n = $arr[$i][$j];
                if (isset($cnt[$n])) $cnt[$n] ++;
                else $cnt[$n] = 1;
            }
        }

        //print_r($cnt);
        $single = [];        
        foreach ($cnt as $k => $v) if ($v % 2 == 1) $single[] = $k;
        sort($single);

        //print_r($single);
        return implode(' ', $single);
    }

}

$R = new RankFile();
print_r($R->solve(3, [[1, 2, 3], [2, 3, 5], [3, 5, 6], [2, 3, 4], [1, 2, 3]]));

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
                $R = new RankFile();
                $N = trim(fgets($handle));
                $arr = [];
                for ($i = 0; $i < 2 * $N - 1; $i ++) {
                    $arr[] = explode(' ', trim(fgets($handle)));
                }
                $r = $R->solve($N, $arr);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

//$i = new Input('in.txt','OUT_1.txt');
//$i = new Input('k:/下载/B-small-practice (1).in','OUT_1.txt');
$i = new Input('k:/下载/B-large-practice (1).in','OUT_1.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);