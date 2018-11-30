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

$i = new Input('IN.txt','OUT.txt');
//$i = new Input('A-small-practice.in','OUT_1.txt');
//$i = new Input('A-large-practice.in','OUT_1.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
