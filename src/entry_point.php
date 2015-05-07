<?PHP

require_once("db/Updater.php");
require_once("routing.php");

Updater::CheckForUpdates();

Routing::AddRoute("/{controller}/{action}/{data}", array("controller" => "Test", "action" => "Action", "data" => 5));

Routing::FollowRoute($_SERVER['REQUEST_URI']);

exit;

?>