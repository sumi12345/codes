<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Pegman {

    public function solve($R, $C, $map) {
        $this->R = $R;
        $this->C = $C;
        $this->map = $map;

        $wrong = array();

        // 检查第一行 如果所在列遇到的第一个箭头是 ^ 则它是错的
        for ($j = 0; $j < $C; $j ++) {
            $first_wrong = $this->check_arrow(0, $j, 0, 1, '^');
            if (!empty($first_wrong)) $wrong[] = $first_wrong;
        }

        for ($j = 0; $j < $C; $j ++) {
            $first_wrong = $this->check_arrow($R - 1, $j, 0, -1, 'v');
            if (!empty($first_wrong)) $wrong[] = $first_wrong;
        }

        for ($i = 0; $i < $R; $i ++) {
            $first_wrong = $this->check_arrow($i, 0, 1, 0, '<');
            if (!empty($first_wrong)) $wrong[] = $first_wrong;
        }

        for ($i = 0; $i < $R; $i ++) {
            $first_wrong = $this->check_arrow($i, $C - 1, -1, 0, '>');
            if (!empty($first_wrong)) $wrong[] = $first_wrong;
        }

        if (empty($wrong)) return 0;

        foreach ($wrong as $pos) {
            $can_fix = $this->change_direction($pos['i'], $pos['j']);
            if (!$can_fix) return 'IMPOSSIBLE';
        }

        return count($wrong);
    }

    // 给一个起始位置和方向 检查遇到的第一个箭头 如果箭头是 arrow 查看能否指向另一个箭头
    private function check_arrow($i, $j, $dir_x, $dir_y, $arrow) {
        while ($i >= 0 && $j >= 0 && $i < $this->R && $j < $this->C) {
            $a = $this->map[$i][$j];
            if ($a == '.') {
                $i += $dir_y; $j += $dir_x;
                continue;
            }
            if ($a == $arrow) return array('i' => $i, 'j' => $j);
            return false;
        }
        return false;
    }

    // 检查这个箭头是否能指向另一个箭头
    private function change_direction($r, $c) {
        // 所在行
        for ($j = 0; $j < $this->C; $j ++) if ($this->map[$r][$j] != '.' && $j != $c) return true;
        // 所在列
        for ($i = 0; $i < $this->R; $i ++) if ($this->map[$i][$c] != '.' && $i != $r) return true;

        return false;
    }

    private function print_map() {
        echo "\n";
        foreach ($this->map as $row) echo $row."\n";
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
            $P = new Pegman();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $R = $conf[0]; $C = $conf[1];
                $map = array();
                for ($i = 0; $i < $R; $i ++) $map[] = trim(fgets($handle));
                $r = $P->solve($R, $C, $map);
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

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
/**
 * 在使用谷歌街景时, 你可以操作角色 Pegman.
 * 今天, 一个恶作剧的用户将 Pegman 放在一个 R 行 C 列的矩阵中.
 * 矩阵中的每一格可能是, 空白, 向上箭头, 向下箭头, 向右箭头, 向左箭头.
 * Pegman 被放置在某个格子上, 如果格子为空白, 它就不动.
 * 如果有箭头, 就沿着箭头走, 直到遇到另一个箭头, 改变方向继续走.
 * 但是 Pegman 有可能走出矩阵的边缘! 你可以改变箭头的方向来防止这种情况发生.
 * 箭头只能改变方向, 不能添加或删除. 求需要更改的箭头数量有多少.
 * 思路:
 * 如果 Pegman 走出边界, 那一定有一个箭头指向矩阵的边缘, 而没有其他箭头改变这一情况.
 * 所以我们必须找到一个这样的箭头把它指向另一方向.
 * 只要计算这样的箭头有多少. 如果他们中有任何一个没办法被改变方向, 返回 IMPOSSIBLE.
 */