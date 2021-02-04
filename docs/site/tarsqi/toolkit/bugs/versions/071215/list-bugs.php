<?php

require("utils.php");

$query = "SELECT * FROM `$BUGS_TABLE` ORDER BY `date`;";
$rows = dbSelect($query, 'list-bugs.php');

?>
<html>
<?php require("head.html"); ?>
<body>
<?php require("navigation.html"); ?>

<h1>Bug List</h1>

<table cellpadding=5 cellspacing=5>
<tr align=left>
  <th bgcolor=lightblue>id
  <th bgcolor=lightblue>date
  <th bgcolor=lightblue>type
  <th bgcolor=lightblue>component
  <th bgcolor=lightblue>status
  <th bgcolor=lightblue>name


<?php

foreach ($rows as $row) 
{
	$id = $row[0];
	$name = $row[1];
	$date = $row[2];
	$status = $row[3];
	$type = $row[4];
	$component = $row[5];

	echo "<tr>\n";
	echo "  <td class=listing>$id\n";
	echo "  <td class=listing>$date\n";
	echo "  <td class=listing>$type\n";
	echo "  <td class=listing>$component\n";
	echo "  <td class=listing>$status\n";
	echo "  <td class=listing>$name\n";
	echo "  <td class=listing><a href=show-bug.php?action=show&id=$id>view</a>\n";
}

?>

</table>
















