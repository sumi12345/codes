<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Fairland {
	public function solve($c1, $c2, $c3) {
        $N = $c1[0];
        $D = $c1[1];
        $s0 = $c2[0];
        $as = $c2[1];
        $cs = $c2[2];
        $rs = $c2[3];
        $m0 = $c3[0];
        $am = $c3[1];
        $cm = $c3[2];
        $rm = $c3[3];

        // 初始化工资和层级
        $this->S = array();  // 工资
        $this->T = array();  // 层级
        for ($i = 0; $i < $N; $i ++) {
            $this->init($i, $m0, $am, $cm, $rm, $s0, $as, $cs, $rs);
        }
        //return 0;

        // 遍历节点计算范围
        $this->nodes = array();    // 记录边界
        $this->D = $D;

        // 设置有用节点
        $this->queue = array(array(0, 0, max($s0 - $D, 0), $s0));
        while (!empty($this->queue)) $this->range(false);
        ksort($this->nodes);

        // 计算次数
        $this->queue = array(array(0, 0, max($s0 - $D, 0), $s0));
        while (!empty($this->queue)) $this->range(true);

        // 找到 nodes 中数量最大的一个
        $max = 0;
        foreach ($this->nodes as $node => $num) if ($num > $max) $max = $num;
        return $max;
	}

    // 遍历节点计算范围
    private function range($count) {
        $node = array_shift($this->queue);
        $i = $node[0];
        $m = $node[1];
        $ml = $node[2];
        $mr = $node[3];

        // 本节点的左右界限
        $rl = max($this->S[$i] - $this->D, $ml, 0);
        $rr = min($this->S[$i], $mr);
        if ($rl > $rr) {
            unset($this->T[$m][$i]);
            return;
        }

        if ($count) {  // 记录 nodes
            foreach ($this->nodes as $n => $num) {
                if ($n < $rl) continue;
                if ($n > $rr) break;
                $this->nodes[$n] ++;
            }
        } else {
            $this->nodes[$rl] = 0;
            $this->nodes[$rr] = 0;
        }

        // 访问下一层
        if (!empty($this->T[$i])) foreach ($this->T[$i] as $node => $r) {
            $this->queue[] = array($node, $i, $rl, $rr);
        }
    }

    private function init($i, $m0, $am, $cm, $rm, $s0, $as, $cs, $rs) {
        if ($i == 0) {
            $this->m = $m0;
            $this->s = $s0;
            $this->S[$i] = $s0;
        } else {
            $this->m = ($this->m * $am + $cm) % $rm;
            $this->s = ($this->s * $as + $cs) % $rs;
            $this->S[$i] = $this->s;
            $this->T[$this->m % $i][$i] = 1;
        }
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
			$F = new Fairland();
			for($c = 1; $c <= $cases; $c ++) {
				echo 'Case #'.$c.': ';
				file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $c1 = explode(' ', trim(fgets($handle)));
                $c2 = explode(' ', trim(fgets($handle)));
                $c3 = explode(' ', trim(fgets($handle)));
				$r = $F->solve($c1, $c2, $c3);
				echo ($r)."\n";
				file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
			}
			fclose($handle);
		}
	}
}

$t = microtime(true);

//$i = new Input('IN.txt','OUT.txt');
$i = new Input('A-small-practice.in','OUT_1.txt');
//$i = new Input('A-large-practice.in','OUT_1.txt');
$i->process();

echo "\n".'execution time: '.(microtime(true) - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);
