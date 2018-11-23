<?php
class CloseMatch {

    public function findPair($C, $J)
    {
        if (strlen($C) != strlen($J)) return '长度不相等';
        $len = strlen($C);

        $prePossibleC = [0];  // pre 为比当前位少一位的可能结果
        $prePossibleJ = [0];
        for ($i = 0; $i < $len; $i ++) {
            $c = $C[$i];      // 当前位
            $possibleC = [];  // 当前位的可能结果
            if ($c != '?') {  // 如果本位确定, 则把每个上一位的可能结果*10和当前位相加
                foreach($prePossibleC as $pC) $possibleC[] = $pC * 10 + $c;
            } else {          // 不确定
                foreach($prePossibleC as $pC) {
                    for($k = 0; $k <= 9; $k ++) $possibleC[] = $pC * 10 + $k;
                }
            }

            $j = $J[$i];
            $possibleJ = [];
            if ($j != '?') {
                foreach($prePossibleJ as $pJ) $possibleJ[] = $pJ * 10 + $j;
            } else {
                foreach($prePossibleJ as $pJ) {
                    for($k = 0; $k <= 9; $k ++) $possibleJ[] = $pJ * 10 + $k;
                }
            }

            $possibleC = array_flip($possibleC); ksort($possibleC); // 去重并排序, 数值在key
            $possibleJ = array_flip($possibleJ); ksort($possibleJ);

            $tie = [];    // 记录结果, 1. 平局, 0 => [C最小]
            $cwins = [];  // 2. C赢得比赛, 分差 => C从小到大的组合们
            $jwins = [];  // 3. J赢得比赛, 分差 => C从小到大的组合们
            foreach ($possibleC as $c => $v) {
                foreach ($possibleJ as $j => $v) {
                    $diff = abs($c - $j);
                    if ($c == $j) {
                        if (empty($tie)) $tie[] = [$c, $j];
                    } elseif ($c > $j) {
                        if (!isset($cwins[$diff])) $cwins[$diff] = [];
                        $cwins[$diff][] = [$c, $j];
                    } elseif ($c < $j) {
                        if (!isset($jwins[$diff])) $jwins[$diff] = [];
                        $jwins[$diff][] = [$c, $j];
                    }
                }
            }

            $prePossibleC = [];  // 重置当前位的可能结果, 为下一轮做准备
            $prePossibleJ = [];
            $prePairs = [];      // 最后留下的3组结果 分差 => 组合们
            if (!empty($tie)) {   // 平局的
                $prePossibleC[] = $tie[0][0];
                $prePossibleJ[] = $tie[0][1];
                $prePairs[0] = [$tie[0]];        // 留下的3组结果 1. 平局的
            }
            ksort($cwins);        // C赢的
            if (!empty($cwins)) foreach($cwins as $diff => $pairs) {
                $prePossibleC[] = $pairs[0][0];
                $prePossibleJ[] = $pairs[0][1];
                $prePairs[$diff] = [$pairs[0]]; // 留下的3组结果 2. C赢得比赛, 分差最小, C最小
                break;
            }
            ksort($jwins);        // J赢的
            if (!empty($jwins)) foreach($jwins as $diff => $pairs) {
                $prePossibleC[] = $pairs[0][0];
                $prePossibleJ[] = $pairs[0][1];
                if (!isset($prePairs[$diff])) $prePairs[$diff] = [];
                $prePairs[$diff][] = $pairs[0]; // 留下的3组结果 3. J赢得比赛, 分差最小, C最小
                break;
            }
            //echo $i.':';
            //echo 'possibleC:'; print_r($possibleC);
            //echo 'possibleJ:'; print_r($possibleJ);
            //echo 'prePairs:'; print_r($prePairs);
            if ($i == $len - 1) {     // 最后一位, 选出结果返回
                ksort($prePairs);     // 按分差排序
                foreach ($prePairs as $diff => $pairs) {
                    if ($diff == 0 || !isset($pairs[1])) return $pairs[0];   // 平局或最小分差只有一组
                    if ($pairs[0][0] < $pairs[1][0]) return $pairs[0];       // 分差相等比第一位
                    if ($pairs[0][0] > $pairs[1][0]) return $pairs[1];
                    return $pairs[0][1] < $pairs[1][1] ? $pairs[0] : $pairs[1];  // 分差相等, 第一位相等, 比第二位
                }
            }
        }
    }

    public function solve($C, $J)        // 补全位数, 前面补0
    {
        $pair = $this->findPair($C, $J);

        $c = ''.$pair[0];
        $diff = strlen($C) - strlen($c);
        for ($i = 0; $i < $diff; $i ++) $c = '0'.$c;

        $j = ''.$pair[1];
        $diff = strlen($J) - strlen($j);
        for ($i = 0; $i < $diff; $i ++) $j = '0'.$j;

        return $c.' '.$j;
    }
}

//$C = new CloseMatch();
//echo $C->solve('1?', '2?');


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
                $C = new CloseMatch();
                $S = explode(' ', trim(fgets($handle)));
                $r = $C->solve($S[0], $S[1]);
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
