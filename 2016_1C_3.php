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
            $s = ($P == $S && $K == 1) ? ($j + $p) % $S + 1 : $i % $S + 1;
            $C[$i] = [$j, $p, $s];
        }

        $str = $num."\n";
        for ($i = 0; $i < $num; $i ++) $str .= implode(' ', $C[$i])."\n";
        return trim($str);
    }

}

$F = new FashionPolice();
echo $F->solve(2, 2, 2, 1);
exit;

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

//$i = new Input('../下载/C-small-practice.in','../下载/OUT_3.txt');
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
 * 首先, 最多可以有 J * P * min(S, K) 种组合, 所以 J * P 都要用上, 每组重复 min(S, K) 次
 * 第三位原来想就123123循环, 但发现 (2, 2, 2, 1) 的 case, P 和 S 会同步循环
 * 修正了 P = S 且 K = 1 的情况后还是不能通过, 原因不明
 */