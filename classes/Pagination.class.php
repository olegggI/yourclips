<?php

class Pagination {
	
	const THREE = 3;
	const SIX = 6;


	private $start;
	private $end;
	private $pagination = array();

	public function __construct($curr_page, $col_pages) {
		$this->start = $curr_page - self::THREE;
		$this->end = $curr_page + self::THREE;

		if ($this->start > 0 && $this->end < $col_pages) {
			for ($i = $this->start; $i <= $this->end; $i++) {
				$this->pagination[] = $i;
			}
		} elseif ($this->start > 0 && $this->end >= $col_pages) {
			for ($i = $col_pages - self::SIX; $i <= $col_pages; $i++) {
				$this->pagination[] = $i;
			}
		} elseif ($this->start < 0 && $this->end > $col_pages) {
			for ($i = $this->start; $i <= $this->start + self::SIX; $i++) {
				$this->pagination[] = $i;
			}
		} elseif ($this->start <= 0 && $this->end < $col_pages) {
			for ($i = 0; $i <= self::SIX; $i++) {
				$this->pagination[] = $i;
			}
		}
	 
		foreach ($this->pagination as $key => $value) {
			$this->pagination[$key] = array(0 => $value, 1 => $value);
		}
		
		
	}

	public function getPagi() {
		
		return $this->pagination;
	}

}