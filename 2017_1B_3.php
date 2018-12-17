<?php
class PonyExpress {
    public function solve($N, $Q, $E, $S, $D, $pair) {
        echo 'D: '; print_r($D);
        // Floyd 算法, 找到城市 i, j 之间的最短距离 D
        for ($k = 0; $k < $N; $k ++)
        for ($i = 0; $i < $N; $i ++)
            for ($j = 0; $j < $N; $j ++) {
                if ($i == $j) continue;
                $d = $D[$i][$k] != -1 && $D[$k][$j] != -1 ? $D[$i][$k] + $D[$k][$j] : -1;
                if ($d != -1) $D[$i][$j] = $D[$i][$j] != -1 ? min($D[$i][$j], $d) : $d;
            }
        echo 'D\': '; print_r($D);
        // 城市 i, j 之间不换马可以跑到的最短时间
        $G = [];
        for ($i = 0; $i < $N; $i ++) {
            for ($j = 0; $j < $N; $j++) {
                if ($i == $j || $D[$i][$j] == -1 || $E[$i] < $D[$i][$j]) continue;
                $G[$i][$j] = $D[$i][$j] / $S[$i];
            }
        }
        echo 'G: '; print_r($G);
        // Floyd 算法, 找到城市 i, j 之间的最短时间
        for ($k = 0; $k < $N; $k ++)
        for ($i = 0; $i < $N; $i ++)
            for ($j = 0; $j < $N; $j ++) {
                $g = isset($G[$i][$k]) && isset($G[$k][$j]) ? $G[$i][$k] + $G[$k][$j] : -1;
                if($g != -1) $G[$i][$j] = isset($G[$i][$j]) ? min($G[$i][$j], $g) : $g;
            }
        echo 'G\''; print_r($G);
        // 返回
        $o = [];
        for ($i = 0; $i < $Q; $i ++) {
            $u = $pair[$i][0] - 1; $v = $pair[$i][1] - 1; if (!isset($G[$u][$v])) exit;
            $o[] = $G[$u][$v];
        }
        return implode(' ', $o);
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
                $E = []; $S = [];
                for ($i = 0; $i < $N[0]; $i ++) {
                    $row = explode(' ', trim(fgets($handle)));
                    $E[] = $row[0]; $S[] = $row[1];
                }
                $D = [];
                for ($i = 0; $i < $N[0]; $i ++) {
                    $D[] = explode(' ', trim(fgets($handle)));
                }
                $pair = [];
                for ($i = 0; $i < $N[1]; $i ++) {
                    $pair[] = explode(' ', trim(fgets($handle)));
                }
                $P = new PonyExpress(); // if ($c != 21) continue;
                $r = $P->solve($N[0], $N[1], $E, $S, $D, $pair);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

//$i = new Input('../下载/C-small-practice.in','../下载/OUT_3.txt');
$i = new Input('../下载/C-large-practice.in','../下载/OUT_3.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-17
 * Time: 下午2:15
 */