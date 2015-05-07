<?PHP

class Routing
{
	static $Routes = Array();

	public static function AddRoute($path, $defaults)
	{
		$parameterRegex = "@{([^}]+)}@";
		
		if(!preg_match_all($parameterRegex, $path, $matches))
		{
			echo "Misformed route";
			return;
		}
		
		if(count($matches[1]) != count($defaults))
		{
			echo "Parameters and defaults don't match";
			return;
		}
		
		for($i = 0; $i < count($matches[1]); $i++)
		{
			if(!isset($defaults[$matches[1][$i]]))
			{
				echo "Parameters and defaults don't match";
				return;
			}
		}
		
		$pathPattern = "@" . preg_replace($parameterRegex, "([^\/]+)", $path) . "@";
		
		$route = Array("path" => $pathPattern, "vars" => $matches[1], $defaults);
		
		array_push(self::$Routes, $route);
		
		var_dump(self::$Routes);
	}
}

?>