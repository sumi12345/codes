<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class UpAndDown {

    public function solve($N, $S) {
        $cnt = 0;
        $pos = array_flip($S);     //当前某个数字的位置
        $seq = $S; sort($seq);    //排序
        $L = []; $R = [];
        foreach($seq as $k) {   // k 从小到大
            $v = $pos[$k];      // k 的位置
            $tl = count($L);          // 左边的最终位置
            $tr = $N - 1 - count($R); // 右边的最终位置
            $ml = $v - $tl;     // 到左边最终位置的距离
            $mr = $tr - $v;     // 到右边最终位置的距离
            //echo 'k: '.$k.' v: '.$v.' ml: '.$ml.' mr: '.$mr.'<br/>';
            if($ml <= $mr) { //左移 将目标位置(含)到自己之间的数字右移一位
                $L[$k] = 1; $cnt += $ml;
                for($i = $v - 1; $i >= $tl; $i --) { $pos[$S[$i]] ++; $S[$i + 1] = $S[$i]; }
            } else {         //右移 将目标位置(含)到自己之间的数字左移一位
                $R[$k] = 1; $cnt += $mr;
                for($i = $v + 1; $i <= $tr; $i ++) { $pos[$S[$i]] --; $S[$i - 1] = $S[$i]; }
            }
        }
        //echo '<br/>L: '; print_r($L); echo '<br/>';
        //echo 'R: '; print_r($R); echo '<br/>';
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
            $U = new UpAndDown();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $N = intval(trim(fgets($handle)));
                $S = explode(' ', trim(fgets($handle)));
                $r = $U->solve($N, $S);
                echo ($r).'<br/>';
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();
$i = new Input('../下载/B-large-practice.in','../下载/OUT_2.txt');
$i->process();

echo '<br/>execution time: '.(time() - $t).'<br/>';
echo '<br/>memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-1-24
 * Time: 下午2:39
 */