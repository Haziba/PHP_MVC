<?PHP

class Gateway extends SQLite3
{
	function __construct()
	{
		$this->open('db/main.db');
	}
	
	function GetRow($sql)
	{
		$query = $this->query($sql);
		$row = $query->fetchArray();
		return $row;
	}
}

?>