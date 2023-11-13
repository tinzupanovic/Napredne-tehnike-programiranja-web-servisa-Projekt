<?php
	echo '<ul>
		<li><a href="index.php?menu=1">Home</a></li>
		<li><a href="index.php?menu=2">Papers</a></li>
		<li><a href="index.php?menu=10">arXiv</a></li>';

	if (!isset($_SESSION['username'])) { 
		echo '
		<li><a href="index.php?menu=5">Register</a></li>
		<li><a href="index.php?menu=6">Sign In</a></li>';
	} else { 
		echo '
		<li><a href="index.php?menu=8">New Article</a></li>';
		if ($_SESSION['role'] == 'admin') { 
			echo '
			<li><a href="index.php?menu=7">Admin</a></li>';
		}
		
		echo '
		<li><a href="signout.php">Sign Out</a></li>';
	}

	echo '</ul>';
?>




