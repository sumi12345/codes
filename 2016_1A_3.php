<?php
class BFF {
    public function solve($N, $F) {
        // 图
        array_unshift($F, 0); unset($F[0]); echo 'solve: '; print_r($F);
        // 计算入度
        $in = [];
        for ($v = 1; $v <= $N; $v ++) $in[$v] = 0;
        for ($u = 1; $u <= $N; $u ++) $in[$F[$u]] ++;
        asort($in);
        // 寻找连通分量
        $visited = [];
        $circle_start_at = [];
        $circle_len = [];
        $sets = [];
        // 从入度为0的结点开始寻路, 直到遇到环
        foreach ($in as $u => $cnt) {
            if (isset($visited[$u])) continue;

            $connected = [$u];
            $visited[$u] = 1;
            for ($k = 0; $k < 9999; $k ++) {     // 循环直到遇到环
                $v = $F[$u];
                if (in_array($v, $connected)) {   // 遇到环
                    echo 'circle found: '; print_r($connected);
                    $connected = array_flip($connected);
                    $circle_start_at[] = $v;
                    $circle_len[] = $connected[$u] - $connected[$v] + 1;
                    $sets[] = $connected;
                    break;
                }
                $connected[] = $v;
                $visited[$v] = 1;
                $u = $v;
            }
        }
        // 以同一个2结点环中的结点为结束的路径
        $candidates = [];
        $ends_at_circle = [];
        foreach ($circle_len as $k => $len) {
            if ($len > 2) { $candidates[] = $len; continue; }

            $u = $circle_start_at[$k];
            if (!isset($ends_at_circle[$u])) $ends_at_circle[$u] = [];
            $ends_at_circle[$u][] = count($sets[$k]);
        }
        echo 'ends_at_circle: '; print_r($ends_at_circle);
        // 找到以本结点为结束的最长的路径
        $ends_at_circle_len = [];
        foreach ($ends_at_circle as $name => $lens) {
            rsort($lens);
            foreach ($lens as $len) {
                $ends_at_circle_len[$name] = $len;
                if (isset ($ends_at_circle_len[$F[$name]]) ) $ends_at_circle_len[$name] -= 2;
                break;
            }
        }
        echo 'ends_at_circle_len: '; print_r($ends_at_circle_len);
        // 相加以成为一个结果候选
        $l = 0;
        foreach ($ends_at_circle_len as $name => $len) $l += $len;
        $candidates[] = $l;
        echo 'candidates: '; print_r($candidates);
        // 排序返回最大的
        rsort($candidates);
        foreach ($candidates as $c) return $c;
    }
}

$B = new BFF();
echo $B->solve(10, [7, 8, 10, 10, 9, 2, 9, 6, 3, 3]);
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
                $F = explode(' ', trim(fgets($handle)));
                $B = new BFF();
                $r = $B->solve($N, $F);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

//$i = new Input('../下载/C-small-practice.in','../下载/OUT_3.txt');
$i = new Input('../下载/C-large-practice.in','../下载/OUT_3.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * 你是一个幼儿园老师. 你的班里有 N 个孩子, 每个孩子都有ID, 从 1 到 N.
 * 每个孩子都有一个BFF. 你明天的课需要孩子们坐成一圈, 每个孩子都和自己的BFF坐在一起.
 * 求最多能安排多少孩子坐一圈
 */