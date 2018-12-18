<?php
class Syrup {
    public function solve($N, $K, $R, $H) {
        // 按照侧面面积 R x H 从大到小排序
        $side = [];
        for ($i = 0; $i < $N; $i ++) $side[$i] = $R[$i] * $H[$i];
        arsort($side);

        // 按照底面面积 + 侧面面积从大到小排序
        $bottom = [];
        for ($i = 0; $i < $N; $i ++) $bottom[$i] = $R[$i] * (0.5 * $R[$i] + $H[$i]);
        asort($bottom);

        // 选择一个底面, 和 K - 1 个侧面
        $max_area = -1;
        foreach ($bottom as $b => $ba) {
            $k = 0; $total = $ba;
            if ($total > $max_area) $max_area = $total;  // K = 1 的情况
            foreach ($side as $s => $sa) {
                if ($R[$s] > $R[$b] || $s == $b) continue;
                $total += $sa; $k ++;
                if ($k == $K - 1) {
                    if ($total > $max_area) $max_area = $total;
                    break;
                }
            }
        }

        return 2 * pi() * $max_area;
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