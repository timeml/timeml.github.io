<?php

require("utils.php");

if (false) { 
	printVars();
	echo "<hr><p>\n\n"; }

$bug_id = $HTTP_GET_VARS['id'];
$bug = new Bug($bug_id);

?>
<html>
<?php require("head.html"); ?>
<body>
<?php require("navigation.html"); ?>

<h1>Add Comment</h1>

<h2><?php echo "#$bug_id $bug->name" ?></h2>


<blockquote>
<form action=show-bug.php method=post>
<input type=hidden name=id value=<?php echo $bug->id ?>>
<input type=hidden name=action value=add-description>
<textarea name=description rows=20 cols=70></textarea>
<p>
<input type=submit value=Submit>
</blockquote>



