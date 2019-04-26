<?php
class GPP {

    public function solve($N, $L, $G, $B) { echo 'solve(L='.$L.', B='.$B.')'."\n";
        foreach ($G as $g) if ($g === $B) return 'IMPOSSIBLE';

        // * length = 1, the second program will be empty using following way
        if ($L == 1) return (1 - $B[0]).'? '.(1 - $B[0]);

        $P1 = '';
        for ($i = 0; $i < $L; $i ++) $P1 .= (1 - $B[$i]).'?';
        $P2 = '';
        for ($i = 0; $i < $L - 1; $i ++) $P2 .= (1 - $B[$i]).$B[$i];

        return $P1.' '.$P2;
    }

}

$G = new GPP();
echo $G->solve(2, 2, ['10', '00'], '11');
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
                $L = explode(' ', trim(fgets($handle)));
                $N = $L[0]; $L = $L[1];
                $G = explode(' ', trim(fgets($handle)));
                $B = trim(fgets($handle));
                $Go = new GPP();
                $r = $Go->solve($N, $L, $G, $B);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

//$i = new Input('../下载/D-small-practice.in','../下载/OUT_3.txt');
$i = new Input('../下载/D-large-practice.in','../下载/OUT_3.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-3
 * Time: 下午6:26
 *
 * Go++语言使用 1 个寄存器. 有 3 种指令, 0 将寄存器设为 0, 1 设为 1, ? 打印当前值
 * 允许两个程序并发执行.
 * 我们会给你一个 bad string B, 长度为 L. 和一组 good string G, 有 N 个, 长度都是 L.
 * 你的任务是写出两个程序, 它们并发执行可以打印出所有的 good string, 但不能有 bad.
 *
 * 思路: 如果 G 包含 B, 返回 IMPOSSIBLE
 * 程序1: 每一个数字取B的反值, 并在后面加上?. 比如 101 -> 0?1?0?
 *        这样可以打印出B的反值. 程序2的任务是覆写?前的值, 但是不能覆写全部?.
 * 程序2: 小数据集, B都由1组成, 所以只需要提供 L-1 个 1 就行.
 *        大数据集, 把B的前L-1个数字依次替换, 0->10, 1->01.
 *                这样要打印 L-1 长度的结果, 每位在每个 01 和 10 之间选一个
 *                要打印B, 就会因为程序2的序列无法生成B而失败
 */