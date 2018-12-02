<?php
class Rebel {

    public function solve($N, $S, $A) {
        return $this->solve_small($N, $S, $A);
    }

    // 小数据集, 行星不移动, 只要找个行星跳
    public function solve_small($N, $S, $A) { //echo 'solve('.$N.', '.$S.', '; print_r($A); exit;
        $C = [];
        for ($i = 0; $i < $N; $i ++) {
            for ($j = $i + 1; $j < $N; $j ++) {
                $d = pow($A[$i][0] - $A[$j][0], 2) + pow($A[$i][1] - $A[$j][1], 2) + pow($A[$i][2] - $A[$j][2], 2);
                $C[$i][$j] = $d;
                $C[$j][$i] = $d;
            }
        }
        //print_r($C); exit;
        // Dijkstra
        $D = $C[0];
        $visited = [0 => 1];
        $pre = 0;
        for ($k = 0; $k < 100; $k ++) {         // 循环直到到达所有结点
            $min_d = 3000000; $new = -1;        // 找前一个结点连接的新最短路径
            for ($v = 0; $v < $N; $v ++) {
                if (isset($visited[$v])) continue;
                if ($C[$pre][$v] <= $min_d) { $min_d = $C[$pre][$v]; $new = $v; }
            }
            for ($v = 1; $v < $N; $v ++) {
                if ($v == $new) continue;
                $D[$v] = min($D[$v], max($D[$new], $C[$new][$v]));
            }
            $visited[$new] = 1;
            $pre = $new;
            //echo 'new: '.$new.', current D[1]: '.$D[1]."\n";
            if (count($visited) == $N) break;
        }

        // 返回最大距离
        return sqrt($D[1]);
    }

}

$R = new Rebel();
echo $R->solve(3, 7, [[0, 0, 0, 0, 0, 0], [1, 2, 2, 0, 0, 0], [1, 1, 1, 0, 0, 0]]);
//exit;

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
                $S = explode(' ', trim(fgets($handle)));
                $N = $S[0]; $S = $S[1]; $A = [];
                for ($i = 0; $i < $N; $i ++) { $A[] = explode(' ', trim(fgets($handle))); }
                $R = new Rebel();
                $r = $R->solve($N, $S, $A);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

$i = new Input('../下载/C-small-practice.in','../下载/OUT_3.txt');
//$i = new Input('../下载/C-large-practice.in','../下载/OUT_3.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-2
 * Time: 下午3:57
 *
 * 你破坏了帝国的邪恶工厂, 帝国武装部队马上就要来抓你了!
 * 工厂在行星0, 你的飞船在行星1, 你们星系有N颗行星
 * 你不能连续 S 秒不移动到下一个星球, 不然部队就会追上你了
 * 长距离的行星间移动很吓人, 所以你想每次跳跃的距离尽可能短
 * 每一颗行星的初始位置是(xi, yi, zi), 移动速度是(vxi, vyi, vzi)
 * 在跳跃距离尽可能短的方案中, 最大跳跃距离是多少
 * 小数据集中, 行星是不动的. 大数据集中, 行星是移动的
 *
 * 小数据集思路: Dijkstra, 每次记录最大跳跃距离.
 * 或者 Prim 算法, 从最小距离开始构造图.
 * 使用 Dijkstra. 不通过, 原因不明
 */