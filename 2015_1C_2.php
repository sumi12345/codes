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

$i = new Input('../下载/B-small-practice.in','../下载/OUT_2.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * 你的出版社决定让随机打字的猴子来写伟大的文学作品.
 * 你管着一只猴子和一个键盘, 键盘有 K 个键, 每个按键标着一个大写英文字母.
 * 猴子随机挑选一个按键, 按下它, 在字符串右边加上一个字母.
 * 重复 S 次, 得到一个长 S 的字符串.
 * 你有一个长 L 的目标词语. 你计划猴子打的字符串中每出现一次目标词语就奖励它一根香蕉.
 * 每次你必须带足够的香蕉, 然后根据猴子实际打的字奖励它. 求你能留下的香蕉的期望值.
 *
 * 思路: 这个问题有两部分, 计算你要带的香蕉的数量, 和计算所需香蕉的期望
 * 比如, X = ABACABA, 要找一个长度为 S 的字符串, 包含最多次 X 的重复.
 * 我们需要计算 X 前后重复的最大长度, 可以一个一个计算, 也可以用 KMP 算法.
 * 要计算重复次数的期望, 我们先计算这个词在某个固定位置出现的概率 P.
 * P 等于每个字母都打对的概率.
 * 所以重复次数的期望是 P 乘以这个词出现的次数, 即 S-L+1.
 * O = max_overlap(target)
 * max_copies = 1.0 + (S-L) / (L-O)
 * min_copies = P * (S-L+1)
 * res = max_copies - min_copies
 */