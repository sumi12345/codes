<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Couter {

	public function solve($N) {
        $this->tree = array(1 => 1);  // 叶子节点
        $this->archived = array();    // 非叶子节点
        while (!isset($this->archived[$N]) && !isset($this->tree[$N])) $this->bfs($N);

        if (isset($this->archived[$N])) return $this->archived[$N];
        return $this->tree[$N];
	}

    private function bfs($N) {
        foreach ($this->tree as $num => $cnt) {
            $r = $num + 1;
            if (!isset($this->tree[$r])) {
                $this->tree[$r] = $this->tree[$num] + 1;
            }
            if ($r == $N) return;

            $r = $this->reverse($num);
            if (!isset($this->tree[$r])) {
                $this->tree[$r] = $this->tree[$num] + 1;
            }
            if ($r == $N) return;

            $this->archived[$num] = $this->tree[$num];
            unset($this->tree[$num]);
        }
    }

    private function reverse($num) {
        $r = 0;
        while($num > 0) {
            $n = $num % 10;
            $r = $r * 10 + $n;
            $num = floor($num / 10);
        }
        return $r;
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
			$C = new Couter();
			for($c = 1; $c <= $cases; $c ++) {
				echo 'Case #'.$c.': ';
				file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $n = intval(trim(fgets($handle)));
				$r = $C->solve($n);
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

?>
