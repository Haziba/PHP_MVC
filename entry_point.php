<?PHP

$baseUri = "/MVC/";


$requestUri = substr($_SERVER['REQUEST_URI'], strlen($baseUri));

$uriParts = explode('/', $requestUri);

$controllerName = $uriParts[0] . "controller";
$actionName = $uriParts[1];
$data = $uriParts[2];

require_once("/controllers/" . $controllerName . ".php");

$controller = new $controllerName;

echo $controller->{$actionName}($data);

?>