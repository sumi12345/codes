<?php
class Prize {
    function solve($N, $P) {
        echo 'N: '.$N.' P:'.$P.'<br/>';
        $r = array();
        //计算无条件
        $arr = $this->to_binary($P - 1); print_r($arr); echo '<br/>';
        $len = count($arr);
        if($N > $len) $r[0] = 0;
        else {
            for($i = 0; $i < $len; $i ++) if($arr[$i] == 0) break;
            $r[0] = $i < $len ? pow(2, $i + 1) - 2 : pow(2, $N) - 1;
        }
        //计算有条件
        /*
        $max_lose = 0;
        for($i = 0; $i < $P; $i ++) {
            $m = $this->count_lose($i);
            if($m > $max_lose) $max_lose = $m;
        } */
        $arr = $this->to_binary($P);
        $len = count($arr);
        $max_lose = $len - 1;
        $min_win = $N - $max_lose;
        $r[1] = pow(2, $N) - pow(2, $min_win);
        return implode(' ', $r);
    }

    function to_binary($p) {
        $arr = array();
        while($p >= 1) {
            $arr[] = $p % 2;
            $p = intval($p / 2);
        }
        return array_reverse($arr);
    }

    function count_lose($N) {
        $cnt = 0; $n = $N;
        while($n >= 1) {
            if($n % 2 == 1) $cnt ++;
            $n = intval($n / 2);
        }
        //echo 'count_lose: '.$N.' = '.$cnt.'<br/>';
        return $cnt;
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
            $P = new Prize();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $r = $P->solve($conf[0], $conf[1]);
                echo ($r).'<br/>';
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

//$p = new Prize();
//echo $p->solve(6, 64); exit;

$t = time();
$i = new Input('../下载/B-large-practice.in','../下载/OUT_3.txt');
$i->process();

/*
	1. 有条件的最大值
	计算从0到P-1的数字中 分解为2进制后 可以出现的最多的1 即将P分解为2进制后的位数-1
	对某个P最多可以输多少场 反推至少要赢多少场
	假设必须赢得n场 因为在进入第i场比赛之前 对手也已经赢得了 i - 1 场
	类推 n = 1 w = 1 / n = 2 w = 3 / n = 3 w = 7
	结果序号： pow(2, N) - 必须赢得的比赛数量 - 1
	2. 无条件最大值
	将P - 1分解为二进制 遍历每个数
	如果遇到0 表示这一场必须赢 得到序列 LL...LW
	按照L的数量n
	n = 0 W 即全胜 只有0符合
	n = 1 LW 可以输一场 但第二场必须赢 1(01) 2(10)满足条件 而3(11)可能是LL(N = 2)
	n = 2 LLW 考虑N = 3 此时7一定会出现在第三场且失败 而其他进入这一轮的数字一定会赢 所以最大是6
	特殊情况 P - 1分解后都是1 即LLL 则全都有奖 +1
*/
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-3-14
 * Time: 下午4:54
 */