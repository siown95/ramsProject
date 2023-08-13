<?php
class Controller
{
	var $db;

	function __construct()
	{
		global $db;
		$this->db = $db;
	}

	public function getMsgException($e)
	{
		$return_data = array();
		$return_data['error'] = $e->getCode();
		$return_data['msg'] = $e->getMessage();
		return $return_data;
	}

	public function getMonthArray($date)
	{
		$start_date = date("Y-m" . "-01", strtotime($date));
		$start_time = strtotime($start_date);

		$end_time = strtotime("+1 month", $start_time);

		for ($i = $start_time; $i < $end_time; $i += 86400) {
			$list[]['date'] = date('Y-m-d', $i);
		}

		return $list;
	}
}