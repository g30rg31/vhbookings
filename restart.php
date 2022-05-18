<?php
	session_start();

        if (!($_SESSION["loggedon"] == "yes")  || $_SESSION["username"] == "" || $_SESSION["role"] != "admin")
	{
                echo '<script>';
                echo 'alert("You are not authorised to use this page.");';
                echo 'location.href="calendar.php"';
                echo '</script>';
                exit();
        } else {

	        echo '<script>';
                echo 'alert("Warning: Forced restart may take some time.");';
                echo '</script>';

		$psAr =  shell_exec("ps -C devicesvr");
		$numlines = explode("\n",$psAr);
		for ($i = 0; $i < sizeof($numlines); $i++ )
		{
			if (strpos($numlines[$i], "devicesvr") != false  )
			{
				$devline = explode(" ",trim($numlines[$i]));
				exec("sudo kill " . $devline[0]);
			}
		}
		$psAr =  shell_exec("ps -C readwgld");
		$numlines = explode("\n",$psAr);
		for ($i = 0; $i < sizeof($numlines); $i++ )
		{
			if (strpos($numlines[$i], "readwgld") != false  )
			{
				$devline = explode(" ",trim($numlines[$i]));
				exec("sudo kill " . $devline[0]);
			}
		}
	}
	echo '<script>';
        echo 'alert("The system will attempt a restart shortly.");';
        echo 'location.href="calendar.php"';
        echo '</script>';
?>
