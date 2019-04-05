<?php
ini_set("max_execution_time",  "30");
ini_set("memory_limit","30M");
ini_set("xdebug.max_nesting_level", 1024);

class MountainView {
	private $N, $H, $L, $R;

	function __construct($n, $h) {
		$this->N = $n;
		$this->H = $h;
		$this->flag = true;
		for($i = 1; $i <= $n; $i ++) $this->L[$i] = array(0, 1000);
		print_r($this->H); echo '<br/>';
	}

	function solve() {
		$grp = $this->get_group(0, $this->N);
		//echo 'group: '; print_r($grp); echo '<br/>';
		foreach($grp as $g) $this->set($g, $this->H[$g], 0);
		ksort($this->R); //$this->R = array_map('intval', $this->R);
		print_r($this->R); echo '<br/>';
		print_r($this->L); echo '<br/>';
		if($this->flag !== false) foreach($this->L as $k => $l) {
			if($l[1] <= $l[0]) { echo 'failed1: k: '.$k; print_r($l); echo '<br/>'; }
			if($l[0] >= $this->R[$k]) { echo 'failed2: k: '.$k; print_r($l); echo '<br/>'; }
			if($l[1] <= $this->R[$k]) { echo 'failed3: k: '.$k; print_r($l); echo '<br/>'; }
		}
		//echo $this->flag ? implode(' ', $this->R) : 'Impossible'; echo '<br/>';
		return $this->flag ? $this->R : false;
	}

	function set($a, $b, $p) {
		echo 'set('.$a.', '.$b.', '.$p.')<br/>';

		if(!isset($this->R[$a])) {
			$this->R[$a] = $this->L[$a][0] + min(10, ($this->L[$a][1] - $this->L[$a][0]) / 10);
			$this->R[$a] = intval($this->R[$a]);
		}

		if(!isset($this->R[$b])) {
			if($p != 0 && $this->H[$p] != $b) {
				$this->L[$b][0] = $this->get_height($b, $a, $this->H[$p]);
			}
			$this->R[$b] = $this->L[$b][1] - min(10, ($this->L[$b][1] - $this->L[$b][0]) / 10);
			$this->R[$b] = intval($this->R[$b]);
		}

		for($i = $a + 1; $i <= $this->N; $i ++) {
			if($i == $b) continue;
			$this->L[$i][1] = min($this->L[$i][1], $this->get_height($i, $a, $b));
		}

		if($b - $a > 1) {
			$grp = $this->get_group($a, $b);
			if($grp) foreach($grp as $g) $this->set($g, $this->H[$g], $a);
		}
	}

	function get_height($i, $a, $b) {
		$h = $this->R[$a] + ($this->R[$b] - $this->R[$a]) / ($b - $a) * ($i - $a);
		return $h;
	}

	function get_group($a, $b) {
		$arr = array(); $i = $a + 1;
		while($i < $b) {
			$arr[] = $i; $i = $this->H[$i];
			if($i > $b) { $this->flag = false; return false; }
		}
		return $arr;
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
				$conf = intval(fgets($handle));
				$H = explode(' ', '1 '.trim(fgets($handle)));
				$m = new MountainView($conf, $H);
				$ret = $m->solve();
				$r = $ret === false ? 'Impossible' : implode(' ', $ret);
				echo $r.'<br/>';
				file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
			}
			fclose($handle);
		}
	}
}

$t = time();
$i = new Input('../下载/C-small-practice.in','../下载/OUT_3.txt');
$i->process();

/*
$m = new MountainView(6, array(1, 2, 3, 4, 5, 6));
$m->solve();
$m = new MountainView(4, array(1, 4, 4, 4));
$m->solve();
$m = new MountainView(4, array(1, 3, 4, 4));
$m->solve();
$m = new MountainView(4, array(1, 4, 3, 4));
$m->solve();
*/

/*
	通过！
	记得处理输入之前要trim
	处理所有数据之前要先分一次组
	a和b相差1的时候不用分组
	问题在于 要保证一定的斜率 让上限不能<0 想办法让上限和下限之间不能<2
	关键还是值的确定
	large估计过不去...
*/
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-3-21
 * Time: 下午9:47
 */