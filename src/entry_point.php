<?PHP

$baseUri = "/MVC";

$routingInfo = Array(Array("path" => '/\/Test\/([^\/]+)\/([^\/]+)\/([^\/]+)/', "vars" => Array("action", "data1", "data2"), "controller" => "Test", "action" => "Output", "data" => "Test"),
Array("path" => '/\/([^\/]+)\/([^\/]+)\/([^\/]+)/', "vars" => Array("controller", "action", "data"), "controller" => "Test", "action" => "Output", "data" => "Test"));


$requestUri = substr($_SERVER['REQUEST_URI'], strlen($baseUri));

for($i = 0; $i < count($routingInfo); $i++)
{
	if(preg_match_all($routingInfo[$i]["path"], $requestUri, $matches_out))
	{
		$foundValues = Array();
		for($j = 1; $j < count($matches_out); $j++)
		{
			$foundValues[$routingInfo[$i]["vars"][$j-1]] = $matches_out[$j][0];
		}
		
		$controller = $routingInfo[$i]["controller"];
		if(isset($foundValues["controller"]))
		{
			$controller = $foundValues["controller"];
			array_splice($foundValues, 0, 1);
		}		
		
		$action = $routingInfo[$i]["action"];
		if(isset($foundValues["action"]))
		{
			$action = $foundValues["action"];
			array_splice($foundValues, 0, 1);
		}		
		
		FollowRoute($controller, $action, $foundValues);
		
		$uriParts = explode('/', $requestUri);
		break;
	}
}

function FollowRoute($controller, $action, $data)
{
	$controllerName = $controller . "controller";
	require_once("/controllers/" . $controllerName . ".php");
	
	$controller = new $controllerName;
	
	echo call_user_func_array(array($controller, $action), $data);
}

?>