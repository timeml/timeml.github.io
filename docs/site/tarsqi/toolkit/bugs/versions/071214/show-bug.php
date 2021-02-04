<?php

require("utils.php");

if ($debugging) { 
	printVars();
	echo "<hr><p>\n\n";
}

$bugname = $HTTP_POST_VARS['bugname'];

if ($bugname) { 
	// new bug being added
	// data are received from the add-bug.php
	$bug_id = add_bug($bugname); 
} else {
	// bug id received from list-bugs.php
	$bug_id = $HTTP_GET_VARS['id'];
}

$bug = new Bug($bug_id);

?>
<html>
<?php require("head.html"); ?>
<body>
<?php require("navigation.html"); ?>

<h1>Bug #<?php echo $bug_id ?></h1>

<?php
$bug->print_html();
?>

