<?php
class Evacuation {

    public function solve($P) {
        $nameOfParty = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 1);
        arsort($P);                 // 按人数从多到少排序 政党编号 => 人数
        $parties = array_keys($P);  // 0 => 人数最多的政党编号, 1 => 人数第二多的政党编号
        $plan = [];                // 撤离顺序
        $N = count($P);            // 政党数

        for ($k = $P[$parties[0]]; $k > 0; $k --) {  // 每一轮把每个政党人数减少到k以下
            for ($i = 0; $i < $N; $i ++) {
                if ($P[$parties[$i]] < $k) break;
                $plan[] = $nameOfParty[$parties[$i]];
                $P[$parties[$i]] --;
            }
        }

        $num = count($plan);      // 对于只有两个政党的情况, 留下奇数个议员都会出现绝对多数
        $str = '';
        if ($num % 2 == 1) $str = array_shift($plan).' ';
        for ($i = 0; $i < $num - 1; $i += 2) $str .= $plan[$i].$plan[$i + 1].' ';

        return $str;
    }

}

//$E = new Evacuation();
//echo $E->solve([2, 2]);
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
                $E = new Evacuation();
                $N = trim(fgets($handle));
                $P = explode(' ', trim(fgets($handle)));
                $r = $E->solve($P);
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
