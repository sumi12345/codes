<?php
class LastWord {

    public function solve($S) {
        $len = strlen($S);
        $new = [$S[0]];
        for($i = 1; $i < $len; $i ++) {
            if ($S[$i] >= $new[0]) array_unshift($new, $S[$i]);
            else array_push($new, $S[$i]);
        }

        return implode('', $new);
    }
    
}

$L = new LastWord();
echo $L->solve('ABCABCABC');

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
                $L = new LastWord();
                $N = trim(fgets($handle));
                $r = $L->solve($N);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

//$i = new Input('in.txt','OUT_1.txt');
//$i = new Input('k:/下载/A-small-practice (1).in','OUT_1.txt');
$i = new Input('k:/下载/A-large-practice (1).in','OUT_1.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);