<?php
class FashionPolice {

    public function solve($J, $P, $S, $K) {
        echo "solve($J, $P, $S, $K)\n";
        $C = [];
        $num = $J * $P * min($S, $K);
        $numP = min($S, $K);  // p 可以重复几次
        for ($i = 0; $i < $num; $i ++) {
            $j = floor($i / $numP / $P) % $J + 1; // numP * P 个 J
            $p = floor($i / $numP) % $P + 1;      // min(K, J * S)个1, 2, 3
            $s = $i % $S + 1;
            if ($K < $S && $P == $S && $K == 1) $s = ($j + $p) % $S + 1;  // 2, 2, 2, 1
            if ($K < $S && $P == $S && $K == 2) $s = ($j + $p + $i * 2) % $S + 1;  // 3, 3, 3, 2
            $C[$i] = [$j, $p, $s];
        }

        $str = '';
        for ($i = 0; $i < $num; $i ++) $str .= implode(' ', $C[$i])."\n";
        $str = trim($str);
        $r = $this->check($str, $K);
        return $num."\n".$str;
    }

    private function check($str, $K) {
        // 检查全套组合重复
        $set = [];
        $three = explode("\n", $str);
        foreach ($three as $t) {
            if (isset($set[$t])) { echo $t; return -1; }
            $set[$t] = 1;
        }
        // 检查 JS 组合重复 K 次以上
        $set = [];
        $three = explode("\n", $str);
        foreach ($three as $t) {
            $row = explode(' ', $t);
            $js = $row[0].' '.$row[2];
            if (!isset($set[$js])) $set[$js] = 0;
            $set[$js] ++;
            if ($set[$js] > $K) { echo $js; return -2; }
        }
        // 检查 PS 组合重复 K 次以上
        $set = [];
        $three = explode("\n", $str);
        foreach ($three as $t) {
            $row = explode(' ', $t);
            $ps = $row[1].' '.$row[2];
            if (!isset($set[$ps])) $set[$ps] = 0;
            $set[$ps] ++;
            if ($set[$ps] > $K) { echo $ps; return -3; }
        }

        return 1;
    }

}

$F = new FashionPolice();
echo $F->solve(2, 2, 2, 1);
echo $F->solve(3, 3, 3, 2);
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
                $F = new FashionPolice();
                $N = explode(' ', trim(fgets($handle)));
                $r = $F->solve($N[0], $N[1], $N[2], $N[3]);
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
 * Date: 18-11-28
 * Time: 下午4:42
 * NoteTime: 下午7:53
 *
 * 你带了J件夹克, P条裤子, S件衬衫, 三个单品可以组成一套套装, J <= P <= S
 * 如果你两天穿同一套套装, 或者其中两个单品的组合穿了超过K天, 就会被抓去坐牢!
 * 求你带的衣服可以撑多少天不被抓走, 并给出组合
 *
 * 最多有 J * P * min(S, K) 种组合, 所以 J * P 都要用上, 每组重复 min(S, K) 次
 * 特殊处理 (2, 2, 2, 1) 和 (3, 3, 3, 2)
 */