<?php

# connection variables
require("connection.php");
require("database_forms.php");

$debugging = true;
$debugging = false;

# table names
$BUGS_TABLE = 'bt-bugs';
$DESCRIPTIONS_TABLE = 'bt-description';



// Connect to host and select database

$db_conn = mysql_connect($hostname, $username, $password);
if (mysql_errno()) {
	echo "<b><font color=#ff0000>There is a problem with the database connection, try again later.</font></b>";
	echo "<p>Please send an email to marc@cs.brandeis.edu if the problem persists.<hr>";
	exit;
    echo mysql_errno() . ": " . mysql_error(). "\n<p><hr><p>\n\n";
}
mysql_select_db($db, $db_conn);
if (mysql_errno()) {
    echo mysql_errno() . ": " . mysql_error(). "\n<p><hr><p>\n\n";
}



function add_bug($bugname)
{
	global $HTTP_POST_VARS;
	global $BUGS_TABLE;
	global $DESCRIPTIONS_TABLE;

	$bugtype = $HTTP_POST_VARS['bugtype'];
	$component = $HTTP_POST_VARS['component'];
	$long_description = $HTTP_POST_VARS['description'];

	$q = "INSERT INTO `$BUGS_TABLE` (`bugName`, `bugType`, `component`) " .
		"VALUES ('$bugname', '$bugtype', '$component');";
	dbInsert($q, 'add_bug');

	$q = "SELECT LAST_INSERT_ID();";
	$rows = dbSelect($q, 'add_bug');
	$last_id = $rows[0][0];

	$q = "INSERT INTO `$DESCRIPTIONS_TABLE` (`bugID`, `status`, `description`) " .
		"VALUES ($last_id, 'open', '$long_description');";
	dbInsert($q, 'add_bug');

	return $last_id;
}


class BugElement
{
	function print_options($name, $label, $choices, $val)
	{
		echo "$name: ";
		echo "<select name=$label>\n";
		foreach ($choices as $choice) {
			if ($val == $choice) {
				echo "  <option label=$choice selected>$choice</option>\n";
			} else {
				echo "  <option label=$choice>$choice</option>\n";
			}
		}
		echo "</select>\n";
	}
}


class Bug extends BugElement
{
	function Bug($id)
	{
		// Creates a Bug from an id, retrieving all data from the database

		global $BUGS_TABLE;
		global $DESCRIPTIONS_TABLE;

		$q = "SELECT * FROM `$BUGS_TABLE` WHERE `bugID`=$id;";
		$rows = dbSelect($q, 'Bug()');
		$row = $rows[0];
		$this->id = $id;
		$this->name = $row[1];
		$this->date = $row[2];
		$this->status = $row[3];
		$this->type = $row[4];
		$this->component = $row[5];

		$q = "SELECT * FROM `$DESCRIPTIONS_TABLE` WHERE `bugID`=$id;";
		$rows = dbSelect($q, 'Bug()');
		$this->descriptions = array();
		foreach ($rows as $row) {
			$this->descriptions[] = new Description($row);
		}

	}

	function print_html()
	{
		echo "<h2>$this->name</h2>\n";
		echo "Created: $this->date";
		echo "<p>\n";
		$this->print_status();
		echo "&nbsp;&nbsp;";
		$this->print_type();
		echo "&nbsp;&nbsp;";
		$this->print_component();
		foreach ($this->descriptions as $desc) {
			$desc->print_html();
		}
	}

	function print_status()
	{
		global $status_choices;
		$this->print_options('Status', 'status', $status_choices, $this->status);
	}

	function print_type()
	{
		global $bugtypes;
		$this->print_options('Type', 'type', $bugtypes, $this->type);
	}

	function print_component()
	{
		global $components;
		$this->print_options('Component', 'component', $components, $this->component);
	}

}


class Description extends BugElement
{
	function Description($row)
	{
		$this->date = $row[2];
		$this->status = $row[3];
		$this->description = $row[4];
	}

	function print_html()
	{
		echo "<p><hr>\n";
		echo "Description at $this->date ";
		echo "with status set to \"$this->status\"";
		echo "<p>\n";
		$desc = str_replace("\n", "<br>\n", $this->description);
		echo "<blockquote>$desc</blockquote>\n";
	}

	function print_status()
	{
		global $status_choices;
		$this->print_options('Status', 'status', $status_choices, $this->status);
	}

}


// DEBUGGING

function printVars()
{
	global $debugging, $HTTP_GET_VARS, $HTTP_POST_VARS;
	foreach ($HTTP_GET_VARS as $var => $value) {
		echo "\$HTTP_GET_VARS['$var'] => '$value'<br>\n"; }
	foreach ($HTTP_POST_VARS as $var => $value) {
		echo "\$HTTP_POST_VARS['$var'] => '$value'<br>\n"; }
}


// DATABASE FUNCTIONS

function dbSelect($query,$caller)
{
	$result = dbQuery($query, $caller);
	if ($result) {
		$rows = array();
		while ($row = mysql_fetch_row($result)) { $rows[] = $row; }
		return $rows;
	} else {
		return $result;
	}	
}

function dbUpdate($query,$caller) 
{ 
	return dbQuery($query, $caller); 
}

function dbInsert($query,$caller) 
{ 
	return dbQuery($query, $caller); 
}

function dbDelete($query,$caller) 
{ 
	return dbQuery($query, $caller); 
}

function dbQuery($query, $caller)
{
	global $db_conn, $debugging;
	if ($debugging) echo $query."<br>\n";
	$result = mysql_query($query, $db_conn);
	if (mysql_errno()) {
    		echo '<p><hr><p>MySQL Error:' . mysql_errno() . ": " . mysql_error(). "\n";
		echo "<p>Query: $query<p>Caller: $caller<p><hr><p>\n\n"; 
	}
	if ($debugging) echo "<hr>\n";
	return $result;
}

?>
