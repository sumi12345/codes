<?php
class Syrup {
    public function solve($N, $K, $R, $H) {
        // 按照侧面面积 R x H 从大到小排序
        $side = [];
        for ($i = 0; $i < $N; $i ++) $side[$i] = $R[$i] * $H[$i];
        arsort($side);

        // 按照底面面积 + 侧面面积从大到小排序
        $bottom = []; $max_b = 0;
        for ($i = 0; $i < $N; $i ++) $bottom[$i] = $R[$i] * (0.5 * $R[$i] + $H[$i]);
        arsort($bottom);

        // K = 1 的情况
        if ($K == 1) {
            foreach ($bottom as $b => $ba) { $max_b = $ba; break; }
            return 2 * pi() * $max_b;
        }

        // 选择侧面积最大的前 K - 1 个
        $total = 0; $max_r = 0; $ka = 0; $k = 0;  // 总侧面积, 最大半径, 半径在 K - 1 范围内的下一个
        foreach ($side as $s => $sa) {
            if ($k < $K - 1) {
                $total += $sa;
                if ($R[$s] > $max_r) $max_r = $R[$s];
            } elseif ($R[$s] <= $max_r) {
                $ka = $sa; break;
            }
            $k ++;
        }
        $opt_1 = 0.5 * $max_r * $max_r + $ka + $total;

        $new_b = 0;
        foreach ($bottom as $b => $ba) {
            if ($R[$b] > $max_r) { $new_b = $ba; break; }
        }
        $opt_2 = $new_b + $total;

        return 2 * pi() * max($opt_1, $opt_2);
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
                $R = []; $H = [];
                for ($i = 0; $i < $N[0]; $i ++) {
                    $row = explode(' ', trim(fgets($handle)));
                    $R[] = $row[0]; $H[] = $row[1];
                }
                $S = new Syrup();
                $r = $S->solve($N[0], $N[1], $R, $H);
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
 * Date: 18-12-18
 * Time: 上午9:40
 */