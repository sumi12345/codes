<?php
class Parenting {
    public function solve($AC, $AJ) {
        // 如果没有活动, 每天也要交接 2 次
        if (empty($AC) && empty($AJ)) return 2;

        // 事件按时间顺序排列, 并计算已经占用的时间, C 的活动里由 J 看管, J 的活动里由 C 看管
        $T = []; $TC = 0; $TJ = 0; $EX = 0;
        foreach ($AC as $a) {
            $T[$a[0]] = 'JS'; $T[$a[1]] = 'JE'; $TJ += $a[1] - $a[0];
        }
        foreach ($AJ as $a) {
            if (isset($T[$a[0]]) && $T[$a[0]] == 'JE') $EX ++;  // 开始踩到对方活动的结束, 交换
            if (isset($T[$a[1]]) && $T[$a[1]] == 'JS') $EX ++;  // 结束踩到对方活动的开始, 交换
            $T[$a[0]] = 'CS'; $T[$a[1]] = 'CE'; $TC += $a[1] - $a[0];
        }
        ksort($T);

        // 把环掰直成链
        foreach ($T as $t => $a) {
            if ($t == 0 && isset($T[1440])) {  // 第一个活动的开始踩到最后一个活动的结束
                if (($T[1440] == 'CE' && $a == 'JS') || ($T[1440] == 'JE' && $a == 'CS')) $EX ++;
            }
            $T[$t + 1440] = $a; break;
        }
        echo 'T: '; print_r($T);

        // 前后家长不同的间隙, 增加交换次数. 前后家长相同的间隙, 把时间长度记录进数组.
        $C = []; $J = []; $pre_t = 0; $pre_a = '.';
        foreach ($T as $t => $a) {
            if (($pre_a == 'CE' && $a == 'JS') || ($pre_a == 'JE' && $a == 'CS')) $EX ++;
            if ($pre_a == 'CE' && $a == 'CS') $C[] = $t - $pre_t;
            if ($pre_a == 'JE' && $a == 'JS') $J[] = $t - $pre_t;
            $pre_t = $t; $pre_a = $a;
        }
        echo 'EX: '.$EX."\n";

        // 所有前后家长相同的可支配时间, 排序, 向前填充到满 720
        sort($C); sort($J); echo 'C: '; print_r($C); echo 'J: '; print_r($J);
        foreach ($C as $t) {
            if ($TC + $t > 720) $EX += 2;
            $TC += $t;
        }
        foreach ($J as $t) {
            if ($TJ + $t > 720) $EX += 2;
            $TJ += $t;
        }

        return $EX;
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
                $AC = []; $AJ = [];
                for ($i = 0; $i < $N[0]; $i ++) {
                    $AC[] = explode(' ', trim(fgets($handle)));
                }
                for ($i = 0; $i < $N[1]; $i ++) {
                    $AJ[] = explode(' ', trim(fgets($handle)));
                }
                $P = new Parenting(); // if ($c != 79) continue;
                $r = $P->solve($AC, $AJ);
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
 * Date: 18-12-18
 * Time: 下午4:11
 */