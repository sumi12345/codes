<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Monkey {

    /**
     * 解决方法
     * @param $K int 按键数量
     * @param $L int 目标单词的长度
     * @param $S int 打印出的单词的长度
     * @param $keyboard string 键盘
     * @param $word string 目标单词
     */
    public function solve($K, $L, $S, $keyboard, $word) {
        // 打印出目标单词的概率
        $P = $this->probability($word, $keyboard);
        if ($P == 0) return 0;

        // 最多可能打出多少目标单词
        $O = $this->max_overlap($word, $L);
        $max_copies = floor(($S - $O) / ($L - $O));

        // 猴子打印出目标单词个数的期望 (linarity of expectation)
        $min_copies = $P * ($S - $L + 1);

        return $max_copies - $min_copies;
    }

    // 计算最长的前缀和后缀重合度
    private function max_overlap($word, $len) {
        for ($i = $len - 1; $i >= 1; $i --) {
            if (substr($word, 0, $i) == substr($word, $len - $i)) return $i;
        }
        return 0;
    }

    // 计算打印出目标单词的概率
    private function probability($word, $keyboard) {
        $L = strlen($word);
        $K = strlen($keyboard);
        $kb = array();
        for ($i = 0; $i < $K; $i ++) {
            if (!isset($kb[$keyboard[$i]])) $kb[$keyboard[$i]] = 1;
            else $kb[$keyboard[$i]] ++;
        }

        $P = 1;
        for ($i = 0; $i < $L; $i ++) {
            if (!isset($kb[$word[$i]])) return 0;
            $P *= $kb[$word[$i]] / $K;
        }

        return $P;
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
            $M = new Monkey();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $keyboard = trim(fgets($handle));
                $word = trim(fgets($handle));
                $r = $M->solve($conf[0], $conf[1], $conf[2], $keyboard, $word);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

$i = new Input('IN.txt','OUT.txt');
//$i = new Input('A-small-practice.in','OUT_1.txt');
//$i = new Input('A-large-practice.in','OUT_1.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
