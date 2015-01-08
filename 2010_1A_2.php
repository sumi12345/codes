<?php
	ini_set("max_execution_time", "3");
	ini_set("memory_limit", "3M");
	
	class Smooth {
		private $D;
		private $I;
		private $M;
		private $N;
		private $MAX;
		private $px;
		private $map = array();		//前一
		private $map2 = array();	//当前
		private $add = array();
		
		function __construct($conf, $p, $max = 256) {
			list($this->D, $this->I, $this->M, $this->N) = $conf;
			$this->px = $p; $this->MAX = $max;
		}
		
		public function smooth() {
			for($i = 0; $i < $this->N; $i ++) $this->process_one($i);
			$min = 10000;
			foreach($this->map as $k => $v) if($v < $min) $min = $v;
			return $min;
		}
		
		//计算将最后一个数字删除或更改 得到尾数为i 的最小开销
		private function process_one($idx) {
			$item = $this->px[$idx];
			if($idx == 0) {
				$this->map[-1] = $this->D;
				for($i = 0; $i < $this -> MAX; $i ++) $this->map[$i] = abs($item - $i);
			} else {
				$this->map2[-1] = $this->map[-1] + $this->D;
				for($i = 0; $i < $this -> MAX; $i ++) $this->map2[$i] = $this->min_cost($i, $item);
				for($i = -1; $i < $this -> MAX; $i ++) $this->map[$i] = $this->map2[$i];
			}
			//$this->print_map();
		}
		
		//使 item 修改后尾数为 b 的最小开销
		private function min_cost($b, $item) {
			//删除开销 之前尾数为b的最小开销 + 删除开销
			$best = $this->D + $this->map[$b]; $flag = 'D';
			//修改开销 计算从i修改到b的最小开销
			for($i = -1; $i < $this -> MAX; $i ++) {
				$cost_change = abs($item - $b);
				$num_add = abs($i - $b) > $this->M && $i != -1 ? floor((abs($i - $b) - 1)/($this->M)) : 0;
				$cost = $this->map[$i] + $cost_change + $num_add * $this->I;
				if($cost < $best) { 
					$best = $cost; 
					//$flag = 'C-('.$item.','.$b.')-A-('.$i.','.$b.','.$num_add.')-COST-'.$cost_change.'+'.$num_add.'*'.$this->I.'+'.$this->map[$i].'='.$cost; 
				}
			}
			//echo 'min_cost'.$item.'-'.$b.': '.$flag.'<br/>';
			return $best;
		}
		
		private function print_map() {
			foreach($this->map as $m) echo $m.' '; echo '<br/><br/>';
		}
	}
	
	$s = new Smooth(array(100, 1, 5, 3), array(1, 50, 7)); echo $s->smooth();
	/*
	$handle = fopen("2010_1A_2.txt", "r");
	if ($handle) {
		$cases = intval(fgets($handle, 32));
		for($c = 1; $c <= $cases; $c ++) {
			$conf = fgets($handle);
			$conf = explode(' ', $conf);
			$row = fgets($handle);
			$px = explode(' ',trim($row));
			$s = new Smooth($conf, $px);
			echo 'Case #'.$c.': '.$s->smooth().'</br>';
		}
		fclose($handle); 
	}
	*/
?>
