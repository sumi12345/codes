<?php

class Slides {

    public function buildSlides($B, $M) {
        // 不可能
        if ($M > pow(2, $B - 2)) return false;

        // 初始化图
        $C = [];
        for ($i = 0; $i < $B; $i ++) {
            for ($j = 0; $j < $B; $j ++) {
                if ($i > 0 && $i < $j) $C[$i][$j] = 1;
                else $C[$i][$j] = 0;
            }
        }

        if ($M == pow(2, $B - 2)) {
            for ($j = $B - 1; $j > 0; $j --) $C[0][$j] = 1;
        } else {
            for ($j = $B - 2; $j > 0; $j --) {
                $C[0][$j] = $M % 2;
                $M = floor($M / 2);
            }
        }

        return $C;
    }

    public function solve($B, $M) {
        echo 'solve('.$B.', '.$M.'): ';
        $r = $this->buildSlides($B, $M);
        if ($r === false) return 'IMPOSSIBLE';

        $str = "POSSIBLE\n";
        for ($i = 0; $i < $B; $i ++) $str .= implode('', $r[$i])."\n";
        return trim($str);
    }

}

// $S = new Slides();
// echo $S->solve(5, 4);
// exit;

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
                $S = new Slides();
                $N = explode(' ', trim(fgets($handle)));
                $B = $N[0]; $M = $N[1];
                $r = $S->solve($B, $M);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

// $i = new Input('../下载/B-small-practice.in','../下载/OUT_2.txt');
$i = new Input('../下载/B-large-practice.in','../下载/OUT_2.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Date: 18-11-28
 * Time: 下午1:32
 *
 * B 栋楼, 想一个方案, 让从楼1到楼B正好有 M 种走法
 * 图中不能有环, 否则会有无限的走法, 所以每栋楼只能访问一次, 所以每个走法最多只有 B 长度
 * 这是一个有向无环图, 所以可以只考虑从数字小往数字大走的情况
 * 暴力法可以枚举 B x B 的矩阵, 让从高到低的数值为0, 再用DFS寻路, 超过B长度则有循环
 */