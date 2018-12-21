<?php
class RollerCoasterScheduling {
    public function solve($N, $C, $M, $T) {  // N 个座位, C 位顾客, M 张票
        //echo 'solve(N='.$N.', C='.$C.', M='.$M.')';
        // 找出买票最多的顾客
        $B = []; $max_b = 0;
        for ($i = 1; $i <= $C; $i ++) $B[$i] = 0;
        for ($i = 0; $i < $M; $i ++) $B[$T[$i][1]] ++;
        for ($i = 1; $i <= $C; $i ++) if ($B[$i] > $max_b) $max_b = $B[$i];
        //echo 'B: '; print_r($B);
        // 计算每个座位的票有多少人买
        $S = [];
        for ($i = 1; $i <= $N; $i ++) $S[$i] = 0;
        for ($i = 0; $i < $M; $i ++) $S[$T[$i][0]] ++;
        //echo 'S: '; print_r($S);
        // 计算最少趟数
        $min_ride = $max_b; $sold = 0;
        for ($i = 1; $i <= $N; $i ++) {
            $total = $min_ride * $i;
            if ($total < $sold + $S[$i]) {
                $min_ride = ceil(($sold + $S[$i]) / $i);
            }
            $sold += $S[$i];
        }
        // 计算需要升舱的票数
        $sold = 0; $promote = 0;
        for ($i = 1; $i <= $N; $i ++) {
            if ($S[$i] > $min_ride) $promote += $S[$i] - $min_ride;
            $sold += $S[$i];
        }

        return $min_ride.' '.$promote;
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
                $T = []; for ($i = 0; $i < $N[2]; $i ++) $T[] = explode(' ', trim(fgets($handle)));
                $R = new RollerCoasterScheduling(); //if ($c != 5) continue;
                $r = $R->solve($N[0], $N[1], $N[2], $T);
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
 * Date: 18-12-21
 * Time: 下午3:57
 */