<?php
class StableNeighbors {
    public function solve($N, $R, $O, $Y, $G, $B, $V) {
        // 转换为小数据集的问题
        $S = $this->solve_small($R - $G, $Y - $V, $B - $O);
        if ($S === false) return 'IMPOSSIBLE'; echo 'S='.implode('', $S)."\n";

        // 混色的字符串
        $g = ''; $v = ''; $o = '';
        for ($i = 0; $i < $G; $i ++) $g .= 'GR';
        for ($i = 0; $i < $V; $i ++) $v .= 'VY';
        for ($i = 0; $i < $O; $i ++) $o .= 'OB';

        // 如果混色和另一纯色的数量相等, 就不能有其他颜色了
        if ($G > 0 && $G == $R) return $N == $G + $R ? $g : 'IMPOSSIBLE';
        if ($V > 0 && $V == $Y) return $N == $V + $Y ? $v : 'IMPOSSIBLE';
        if ($O > 0 && $O == $B) return $N == $O + $B ? $o : 'IMPOSSIBLE';

        // 替换一个纯色为混色组合
        if ($G > 0) foreach ($S as $k => $color) if ($color == 'R') { echo 'found R!======='."\n";
            $S[$k] = 'R'.$g; break;
        }
        if ($V > 0) foreach ($S as $k => $color) if ($color == 'Y') { echo 'found Y!======='."\n";
            $S[$k] = 'Y'.$v; break;
        }
        if ($O > 0) foreach ($S as $k => $color) if ($color == 'B') { echo 'found B!======='."\n";
            $S[$k] = 'B'.$o; break;
        }

        // 返回
        $s = implode('', $S); if (strlen($s) != $N) exit($s.' not equal==========');
        return $s;
    }

    private function solve_small($R, $Y, $B) { echo 'solve_small(R='.$R.', Y='.$Y.', B='.$B.")\n";
        if ($R < 0 || $Y < 0 || $B < 0) return false;

        $N = $R + $Y + $B;
        $cnt = ['R' => $R, 'Y' => $Y, 'B' => $B];
        arsort($cnt);

        $S = []; $p = 0;
        foreach ($cnt as $color => $n) {
            if ($n > $N / 2) return false;
            for ($i = 0; $i < $n; $i ++) {
                $S[$p] = $color;
                if ($p % 2 == 0 && $p + 1 <= $N - 1) $S[$p + 1] = '.';
                $p += 2;
                if ($p > $N - 1) $p = 1;
            }
        }
        return $S;
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
                $S = new StableNeighbors(); // if ($c != 7) continue;
                $r = $S->solve($N[0], $N[1], $N[2], $N[3], $N[4], $N[5], $N[6]);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
//$i = new Input('../下载/B-small-practice.in','../下载/OUT_2.txt');
$i = new Input('../下载/B-large-practice.in','../下载/OUT_2.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-14
 * Time: 下午1:42
 */