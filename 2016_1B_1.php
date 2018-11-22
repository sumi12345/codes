<?php
class Digits {

    public function solve($S) {
        $digitsInUpperCase = [
            'ZERO', 'ONE', 'TWO', 'THREE', 'FOUR', 'FIVE', 'SIX', 'SEVEN', 'EIGHT', 'NINE'
        ];

        $countCases = [];
        $len = strlen($S);
        for ($i = 0; $i < $len; $i ++) {
            $c = $S[$i];
            if (! isset($countCases[$c])) $countCases[$c] = 1;
            else $countCases[$c] ++;
        }

        $digitsIncluded = [];
        $uniqueCase = [
            'G' => 8, 'U' => 4, 'W' => 2, 'X' => 6, 'Z' => 0,
            'F' => 5, 'R' => 3, 'V' => 7, 'I' => 9, 'O' => 1
        ];
        foreach ($uniqueCase as $c => $d) {
            if (isset($countCases[$c]) && $countCases[$c] > 0) {
                $count = $countCases[$c];
                $word = $digitsInUpperCase[$d];
                $len = strlen($word);
                for ($i = 0; $i < $len; $i ++) $countCases[$word[$i]] -= $count;
                for ($i = 0; $i < $count; $i ++) $digitsIncluded[] = $d;
            }
        }

        sort($digitsIncluded);

        return implode('', $digitsIncluded);
    }

}

//$D = new Digits();
//echo $D->solve('WEIGHFOXTOURIST');

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
                $D = new Digits();
                $S = trim(fgets($handle));
                $r = $D->solve($S);
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
