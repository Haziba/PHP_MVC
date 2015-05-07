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
		
		$route = Array("path" => $pathPattern, "vars" => $matches[1], "defaults" => $defaults);
		
		array_push(self::$Routes, $route);
	}
	
	public static function FollowRoute($url)
	{
		$baseUri = "/MVC";
		
		$requestUri = substr($url, strlen($baseUri));
	
		for($i = 0; $i < count(self::$Routes); $i++)
		{
			if(preg_match_all(self::$Routes[$i]["path"], $requestUri, $matches))
			{
				$foundValues = Array();
				for($j = 1; $j < count($matches); $j++)
				{
					$foundValues[self::$Routes[$i]["vars"][$j-1]] = $matches[$j][0];
				}
				
				$controller = self::$Routes[$i]["defaults"]["controller"];
				if(isset($foundValues["controller"]))
				{
					$controller = $foundValues["controller"];
					array_splice($foundValues, 0, 1);
				}		
				
				$action = self::$Routes[$i]["defaults"]["action"];
				if(isset($foundValues["action"]))
				{
					$action = $foundValues["action"];
					array_splice($foundValues, 0, 1);
				}
				
				$controllerName = $controller . "controller";
				require_once("/controllers/" . $controllerName . ".php");
				
				$controller = new $controllerName;
				
				echo call_user_func_array(array($controller, $action), $foundValues);
				break;
			}
		}
	}
}

?>