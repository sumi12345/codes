<?php
class TeachingAssistant {
    public function solve($S) {
        $len = strlen($S);
        $stack = [];
        $p = -1;
        for ($i = 0; $i < $len; $i ++) {
            if ($p == -1 || $stack[$p] != $S[$i]) {
                $stack[] = $S[$i];
                $p ++;
            } else {
                array_pop($stack);
                $p --;
            }
        }

        $not_match = count($stack);
        return ($len - $not_match) / 2 * 10 + $not_match / 2 * 5;
    }
}

$T = new TeachingAssistant();
echo $T->solve('CJCJ');
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
                $N = trim(fgets($handle));
                $T = new TeachingAssistant();
                $r = $T->solve($N);
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

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-2
 * Time: 下午3:26
 * NoteTime: 下午3:41
 *
 * 你在参加一次计算机编程课. 课程有偶数天.
 * 每一天, 你可以申请一个作业C, 或申请一个作业J, 或提交最近申请的作业
 * 但是负责布置作业和批改作业的AI每天的喜好不一样, 某一天可能喜欢C或J的任何一个
 * 每一份作业最多有10分, 但是你在不对的时间申请作业或提交作业都会扣5分
 * 求你最多能得多少分
 */