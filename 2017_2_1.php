<?php
class FreshChocolate {

    public function solve($N, $P, $G) {
        $cnt = [];     // G % P 人数的组的数量
        for ($i = 0; $i < $P; $i ++) $cnt[$i] = 0;
        for ($i = 0; $i < $N; $i ++) $cnt[$G[$i] % $P] ++;

        $r = $cnt[0];  // G % P = 0 的组都能获得新开的
        if ($P == 2) {
            $r += ceil($cnt[1] / 2);  // G % P = 1 的组两两配对
        } elseif ($P == 3) {
            $g = min($cnt[1], $cnt[2]);     // 1 组和 2 组两两配对, 剩下的三个一组
            $r += $g + ceil(($cnt[1] - $g) / 3) + ceil(($cnt[2] - $g) / 3);
        } elseif ($P == 4) {
            print_r($cnt);
            $g = min($cnt[1], $cnt[3]);      // 1 组和 3 组两两配对
            $g2 = floor($cnt[2] / 2); // 2 组和 2 组两两配对
            $r += $g + $g2;

            $l1 = $cnt[1] - $g; $l3 = $cnt[3] - $g; $l2 = $cnt[2] % 2;
            echo 'l1='.$l1.', l3='.$l3.', l2='.$l2."\n";
            if ($l1 > 0) {
                if ($l2 == 0) $r += ceil($l1 / 4);
                else $r += ($l1 <= 2 ? 0 : ceil(($l1 - 2) / 4)) + 1;
            } elseif ($l3 > 0) {
                $r += ceil(($l3 + $l2) / 4);
            } elseif ($l2 > 0) {
                $r += 1;
            }
        }

        return $r;
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
                $G = explode(' ', trim(fgets($handle)));
                $F = new FreshChocolate(); // if ($c != 79) continue;
                $r = $F->solve($N[0], $N[1], $G);
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
 * Date: 18-12-21
 * Time: 下午2:23
 */