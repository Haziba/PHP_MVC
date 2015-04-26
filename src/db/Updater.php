<?PHP

require_once("Gateway.php");

class Updater
{
	public static function CheckForUpdates()
	{
		self::CreateMainIfNeeded();
	
		$gateway = new Gateway();
		
		$currentVersion = $gateway->GetRow("SELECT number FROM version")["number"];
		
		$files = scandir("./db/updates");
		$updateRegex = '/([0-9]+)_.+/';
		
		for($i = 0; $i < count($files); $i++)
		{
			if(preg_match_all($updateRegex, $files[$i], $matches_out))
			{
				$updateNum = intval($matches_out[1][0]);
				
				if($updateNum > $currentVersion)
				{
					$updateText = file_get_contents("./db/updates/" . $files[$i]);
					
					if($gateway->exec($updateText))
					{
						$gateway->exec("UPDATE version SET number=" . $updateNum);
						//todo: Log
					}
					else
					{
						//todo: Log
						return false;
					}
				}
			}
		}

		$gateway->close();
	}
	
	public static function CreateMainIfNeeded()
	{
		if(file_exists("db/main.db"))
			return true;
		
		if(copy("db/initial.db", "db/main.db"))
			return true;
		else
			//todo: Log
			return false;
	}
}

?>