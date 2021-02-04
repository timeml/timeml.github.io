<?php

require("utils.php");

if (false) { 
	printVars();
	echo "<hr><p>\n\n";
}

$action = $HTTP_POST_VARS['action'];
if (! $action) {
 	$action = $HTTP_GET_VARS['action'];
}

if ($action == 'create') { 
	// new bug being added
	// data are received from the add-bug.php
	$bugname = $HTTP_POST_VARS['bugname'];
	$bug_id = add_bug($bugname);
}
else if ($action == 'update') { 
	// bug id received from list-bugs.php
	$bug_id = $HTTP_POST_VARS['id'];
        update_bug($bug_id);
}
else if ($action == 'add-description') { 
	$bug_id = $HTTP_POST_VARS['id'];
	$description = $HTTP_POST_VARS['description'];
	if ($description) {
		add_description($bug_id, $description);
	}
}
else if ($action == 'show') { 
	// bug id received from list-bugs.php
	$bug_id = $HTTP_GET_VARS['id'];
}


$bug = new Bug($bug_id);

?>
<html>
<?php require("head.html"); ?>
<body>
<?php require("navigation.html"); ?>

<h1>Bug Report</h1>

<?php
$bug->print_html();
?>

