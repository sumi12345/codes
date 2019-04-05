<?php
ini_set("max_execution_time",  "180");
ini_set("memory_limit","30M");

class Swing {
	private $N, $R, $D;
	private $map;

	function __construct($n, $r, $d) {
		$this->N = $n;
		$this->R = $r;
		$this->D = $d;
		$this->R[] = array($d, 0);
	}

	function swing_wide() {
		echo '<br/>';
		$this->map[0] = $this->R[0][0];
		for($i = 0; $i < $this->N; $i ++) {
			if(isset($this->map[$i])) $this->sw($i);
			if(isset($this->map[$this->N])) return true;
		}
		return false;
	}

	function sw($r) {
		//echo 'sw: r='.$r; echo '<br/>'; //print_r($this->map);
		$d = $this->map[$r]; $m = $this->R[$r][0] + $d;
		for($i = $r + 1; $i <= $this->N; $i ++) {
			if($this->R[$i][0] > $m) break;
			$len = min($this->R[$i][0] - $this->R[$r][0], $this->R[$i][1]);
			if(!isset($this->map[$i]) || $this->map[$i] < $len) $this->map[$i] = $len;
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
			for($c = 1; $c <= $cases; $c ++) {
				echo 'Case #'.$c.': ';
				file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
				$conf = intval(trim(fgets($handle))); $R = array();
				for($i = 0; $i < $conf; $i ++) $R[] = explode(' ', trim(fgets($handle)));
				$d = intval(trim(fgets($handle)));
				$s = new Swing($conf, $R, $d); $r = $s->swing_wide();
				echo ($r ? 'YES' : 'NO').'<br/>';
				file_put_contents($this->out_file, ($r ? 'YES' : 'NO').($c == $cases ? "" : "\r\n"), FILE_APPEND);
			}
			fclose($handle);
		}
	}
}

$t = time();
$i = new Input('../下载/A-large-practice.in','../下载/OUT_3.txt');
$i->process();
echo '<br/>execution time: '.(time() - $t).'<br/>';
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-3-21
 * Time: 下午9:59
 */