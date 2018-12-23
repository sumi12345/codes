<?php
class Beaming {
    public function solve($R, $C, $M) {
        // echo "solve: \n".implode("\n", $M)."\n";
        // 把所有的发射器都设为不固定
        for ($i = 0; $i < $R; $i ++) {
            for ($j = 0; $j < $C; $j ++) {
                if ($M[$i][$j] == '-' || $M[$i][$j] == '|') $M[$i][$j] = '*';
            }
        }
        // 检查有没有不管怎么转都会摧毁其他发射器的发射器
        for ($i = 0; $i < $R; $i ++) {
            for ($j = 0; $j < $C; $j ++) {
                if ($M[$i][$j] != '*') continue;
                $c = $this->check_shooter($R, $C, $M, $i, $j);
                if ($c === false) return 'IMPOSSIBLE';
            }
        }
        echo "after checking shooter: \n".implode("\n", $M)."\n";
        // 格子如果只有一个发射器能照到, 则固定这个发射器
        for ($i = 0; $i < $R; $i ++) {
            for ($j = 0; $j < $C; $j ++) {
                if ($M[$i][$j] != '.') continue;
                $f = $this->fix_shooter($R, $C, $M, $i, $j);
                if ($f === false) return 'IMPOSSIBLE';
            }
        }
        echo "after fixing shooter 1: \n".implode("\n", $M)."\n";
        // 检查第二次
        for ($i = 0; $i < $R; $i ++) {
            for ($j = 0; $j < $C; $j ++) {
                if ($M[$i][$j] != '.') continue;
                $f = $this->fix_shooter($R, $C, $M, $i, $j);
                if ($f === false) { echo 'failed at second check'; return 'IMPOSSIBLE'; }
            }
        }
        echo "after fixing shooter 2: \n".implode("\n", $M)."\n";
        // 检查第三次
        for ($i = 0; $i < $R; $i ++) {
            for ($j = 0; $j < $C; $j ++) {
                if ($M[$i][$j] != '.') continue;
                $f = $this->fix_shooter($R, $C, $M, $i, $j);
                if ($f === false) { echo 'failed at third check'; return 'IMPOSSIBLE'; }
            }
        }
        echo "after fixing shooter 3: \n".implode("\n", $M)."\n";
        // 对剩下的格子, 将所有未固定发射器转向同一方向
        for ($i = 0; $i < $R; $i ++) {
            for ($j = 0; $j < $C; $j ++) {
                if ($M[$i][$j] == '*') $M[$i][$j] = '|';
            }
        }
        // 返回
        return "POSSIBLE\n".implode("\n", $M);
    }

    private function check_shooter($R, $C, &$M, $I, $J) {
        $h = false; $v = false;
        for ($j = $J - 1; $j >= 0; $j --) {  // 向左
            $m = $M[$I][$j];
            if ($m == '#') break;
            if ($m == '-' || $m == '|' || $m == '*') { $h = true; break; }
        }
        if ($h == false) for ($j = $J + 1; $j < $C; $j ++) {  // 向右
            $m = $M[$I][$j];
            if ($m == '#') break;
            if ($m == '-' || $m == '|' || $m == '*') { $h = true; break; }
        }
        for ($i = $I - 1; $i >= 0; $i --) {  // 向上
            $m = $M[$i][$J];
            if ($m == '#') break;
            if ($m == '-' || $m == '|' || $m == '*') { $v = true; break; }
        }
        if ($v == false) for ($i = $I + 1; $i < $R; $i ++) {  // 向下
            $m = $M[$i][$J];
            if ($m == '#') break;
            if ($m == '-' || $m == '|' || $m == '*') { $v = true; break; }
        }
        if ($v == true && $h == true) return false;
        if ($v == true) $M[$I][$J] = '-';
        if ($h == true) $M[$I][$J] = '|';
    }

    private function fix_shooter($R, $C, &$M, $I, $J) {
        $h = false; $v = false; $hs = false; $vs = false;
        for ($j = $J - 1; $j >= 0; $j --) {  // 向左
            $m = $M[$I][$j];
            if ($m == '#' || $m == '|') break;
            if ($m == '-' || $m == '*') { $h = true; $hs = [$I, $j]; break; }
        }
        if ($h == false) for ($j = $J + 1; $j < $C; $j ++) {  // 向右
            $m = $M[$I][$j];
            if ($m == '#' || $m == '|') break;
            if ($m == '-' || $m == '*') { $h = true; $hs = [$I, $j]; break; }
        }
        for ($i = $I - 1; $i >= 0; $i --) {  // 向上
            $m = $M[$i][$J];
            if ($m == '#' || $m == '-') break;
            if ($m == '|' || $m == '*') { $v = true; $vs = [$i, $J]; break; }
        }
        if ($v == false) for ($i = $I + 1; $i < $R; $i ++) {  // 向下
            $m = $M[$i][$J];
            if ($m == '#' || $m == '-') break;
            if ($m == '|' || $m == '*') { $v = true; $vs = [$i, $J]; break; }
        }
        // echo 'fix_shooter('.$I.', '.$J.')='.($h ? '1' : '0').($v ? '1' : '0')."\n";
        if ($h == false && $v == false) return false;
        if ($h == true && $v == true) return true;
        if ($h == true) {
            $M[$hs[0]][$hs[1]] = '-';
            //$M[$I][$J] = '_';
        }
        if ($v == true) {
            $M[$vs[0]][$vs[1]] = '|';
            //$M[$I][$J] = '1';
        }
    }
}
$B = new Beaming();
echo $B->solve(1, 3, ['-.-']);
echo $B->solve(3, 4, ['#.##', '#--#', '####']);
echo $B->solve(2, 2, ['-.', '#|']);
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
                $N = explode(' ', trim(fgets($handle)));
                $M = [];
                for ($i = 0; $i < $N[0]; $i ++) $M[] = trim(fgets($handle));
                $B = new Beaming(); // if ($c != 2) continue;
                $r = $B->solve($N[0], $N[1], $M);
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
 * Date: 18-12-23
 * Time: 下午3:27
 */