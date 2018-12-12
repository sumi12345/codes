<?php
class Cake {

    public function solve($R, $C, $cake) {  // 行数, 列数, 图
        $this->fill($cake, 0, 0, $R - 1, $C - 1);  // 填充左上坐标, 右下坐标
        return "\n".implode("\n", $cake);
    }

    private function fill(&$cake, $x1, $y1, $x2, $y2) { echo 'fill('.$x1.', '.$y1.', '.$x2.', '.$y2.')'."\n";
        $found = 0; $x = -1; $y = -1; $c = '.';
        for ($i = $x1; $i <= $x2; $i ++) {
            for ($j = $y1; $j <= $y2; $j ++) {
                if ($cake[$i][$j] == '?') continue;  // 找到字母
                $found ++;                           // 范围内有多少字母
                if ($c == '.') { $c = $cake[$i][$j]; $x = $i; $y = $j; } // 记录遇到的第一个字母
            }
        }
        echo 'found: '.$found.', i='.$x.', j='.$y."\n"; if ($found == 0) exit;
        if ($found == 1) {    // 只有一个字母, 填充整个范围
            for ($i = $x1; $i <= $x2; $i ++) for ($j = $y1; $j <= $y2; $j ++) $cake[$i][$j] = $c;
            return 1;
        }
        for ($j = $y + 1; $j <= $y2; $j ++) if ($cake[$x][$j] != '?') {  // 同一行有其他字母, 竖切
            $this->fill($cake, $x1, $y1, $x2, $y);
            $this->fill($cake, $x1, $y + 1, $x2, $y2);
            return true;
        }
        $this->fill($cake, $x1, $y1, $x, $y2);         // 横切
        $this->fill($cake, $x + 1, $y1, $x2, $y2);
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
                $cake = []; for ($i = 0; $i < $N[0]; $i ++) $cake[] = trim(fgets($handle));
                $C = new Cake();
                $r = $C->solve($N[0], $N[1], $cake);
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
 * Date: 18-12-12
 * Time: 下午2:52
 */