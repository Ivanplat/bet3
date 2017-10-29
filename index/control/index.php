<?php
	$s_notif = $db->query("SELECT * FROM history WHERE whose_id = ?i AND view = 0", $_SESSION['userId']);
