<?php

# connection variables
require("connection-local.php");

$debugging = true;
$debugging = false;

# table names
$MAIN_TABLE = 'refs-main';
$HISTORY_TABLE = 'refs-history';
$NOTES_TABLE = 'refs-notes';
$KEYWORDS_TABLE = 'refs-keywords';



// Connect to host and select database

$db_conn = mysql_connect($hostname, $username, $password);
if (mysql_errno()) {
  echo "<b><font color=#ff0000>There is a problem with the database connection, ";
  echo "try again later.</font></b>";
  echo "<p>Please send an email to marc@cs.brandeis.edu if the problem persists.<hr>";
  echo mysql_errno() . ": " . mysql_error(). "\n<p><hr><p>\n\n";
}
mysql_select_db($db, $db_conn);
if (mysql_errno()) {
  echo mysql_errno() . ": " . mysql_error(). "\n<p><hr><p>\n\n";
}


// function to retrieve and set keywords

function get_keywords()
{
  global $KEYWORDS_TABLE;
  $q = "select keyword, description from `$KEYWORDS_TABLE` order by keyword;";
  $rows = dbSelect($q, 'get_keywords()');
  $keywords = array();
  foreach ($rows as $row) {
    $keywords[] = $row[0];
  }
  return $keywords;
}

function get_keywords_with_descriptions()
{
  global $KEYWORDS_TABLE;
  $q = "select keyword, description from `$KEYWORDS_TABLE` order by keyword;";
  $rows = dbSelect($q, 'get_keywords()');
  return $rows;
}

function add_keyword($keyword, $description)
{
  global $KEYWORDS_TABLE;
  if ($keyword) {
    $q = "insert into `$KEYWORDS_TABLE` values ('$keyword', '$description');";
    dbInsert($q, "add_keyword($keyword, $description)");
  }
}


function add_reference($title)
{
  global $HTTP_POST_VARS;
  global $MAIN_TABLE;
  global $HISTORY_TABLE;
  global $NOTES_TABLE;

  $author = $HTTP_POST_VARS['author'];
  $year = $HTTP_POST_VARS['year'];
  $bibtex = $HTTP_POST_VARS['bibtex'];
  $html = $HTTP_POST_VARS['html'];
  $note = $HTTP_POST_VARS['note'];

  $keywords = array();
  foreach ($HTTP_POST_VARS as $key => $val) {
    if (substr($key, 0,3) == 'kw-') {
      //echo "$key -> $val<br>";
      $keywords[] = $val;
    }
  }
  $keywords_string = implode(' ; ', $keywords);
  
  $q = "INSERT INTO `$MAIN_TABLE` (year, title, author, keywords, html, bibtex) " .
    "VALUES ($year, '$title', '$author', '$keywords_string', '$html', '$bibtex');";
  dbInsert($q, 'add_reference');
  
  $q = "SELECT LAST_INSERT_ID();";
  $rows = dbSelect($q, 'add_reference main insertt');
  $last_id = $rows[0][0];

  $q = "INSERT INTO `$HISTORY_TABLE` (refid, timestamp, year, title, author, keywords, html, bibtex) " .
    "VALUES ($last_id, NOW(), $year, '$title', '$author', '$keywords_string', '$html', '$bibtex');";
  dbInsert($q, 'add_reference history insert');
  
  if ($note) {
    $q = "INSERT INTO `$NOTES_TABLE` (refid, timestamp, note) " .
      "VALUES ($last_id, NOW(), '$note');";
    dbInsert($q, 'add_reference note insert');
  }
  return $last_id;
}


function update_reference($id)
{
  global $HTTP_POST_VARS;
  global $MAIN_TABLE;
  global $HISTORY_TABLE;
  global $NOTES_TABLE;
  
  $year = $HTTP_POST_VARS['year'];
  $title = $HTTP_POST_VARS['title'];
  $author = $HTTP_POST_VARS['author'];
  $html = $HTTP_POST_VARS['html'];
  $bibtex = $HTTP_POST_VARS['bibtex'];
  $note = $HTTP_POST_VARS['note'];

  $keywords = array();
  foreach ($HTTP_POST_VARS as $key => $val) {
    if (substr($key, 0,3) == 'kw-') {
      //echo "$key -> $val<br>";
      $keywords[] = $val;
    }
  }
  $keywords_string = implode(' ; ', $keywords);

  $q = "UPDATE `$MAIN_TABLE` SET year=$year, title='$title', author='$author', keywords='$keywords_string', html='$html', bibtex='$bibtex' WHERE id=$id;";
  dbUpdate($q, 'update_reference');

  $q = "INSERT INTO `$HISTORY_TABLE` (refid, timestamp, year, title, author, keywords, html, bibtex) " .
    "VALUES ($id, NOW(), $year, '$title', '$author', '$keywords_string', '$html', '$bibtex');";
  dbInsert($q, 'add_reference history insert');
  
  if ($note) {
    $q = "INSERT INTO `$NOTES_TABLE` (refid, timestamp, note) VALUES ($id, NOW(), '$note')";
    dbInsert($q, 'update_reference');
  }
  
}



function protect_string($str)
{
  // Massage the strting a bit so it can be displayed as HTML.
  // This may need to be more sophisticated, not sure whether it
  // allways works, what for example if a line starts with three
  // white spaces, will it then be turned into "&nbsp:&nbsp;
  // some_text" and still split at the wrong spot if there are too
  // many whitespaces?
  $str = str_replace("<", "&lt;", $str);
  $str = str_replace("  ", "&nbsp;&nbsp;", $str);
  $str = str_replace("\n", "<br>\n", $str);
  return $str;
}


class Reference
{
  function Reference($id)
  {
    // Creates a Reference from an id, retrieving all data from the database
    
    global $MAIN_TABLE;
    global $NOTES_TABLE;

    $q = "SELECT * FROM `$MAIN_TABLE` WHERE id=$id;";
    $rows = dbSelect($q, 'Reference()');
    $row = $rows[0];
    $this->id = $id;
    $this->year = $row[1];
    $this->title = $row[3];
    $this->author = $row[2];
    $this->keywords = $row[4];
    $this->html = $row[5];
    $this->bibtex = $row[6];

    $q = "SELECT * FROM `$NOTES_TABLE` WHERE refid=$id ORDER BY timestamp;";
    $rows = dbSelect($q, 'Bug()');
    $this->notes = array();
    foreach ($rows as $row) {
      $this->notes[] = new Note($row);
    }
  }

  function get_keywords()
  {
    $keywords_string = $this->keywords;
    $keywords = preg_split("/\s*;\s*/", $keywords_string);
    return $keywords;
  }

  function get_bibtex_string()
  {
    return $this->bibtex;
  }

  function get_bibtex_lines()
  {
    $lines = explode("\n", $this->bibtex);
    $lines = array_map(rtrim, $lines);
    return $lines;
  }
  
  function get_html_string()
  {
    return $this->html;
  }
    
  function write_html()
  {
    // title, authors and year
    echo
      "\n<div class=ref-header>\n\n",
      "<p><b><font size=\"+1\">$this->title</font></b></p>\n",
      "<p>$this->author</p>\n",
      "<p>$this->year</p>\n";
    if ($this->html) {
      print "\n<p>\n";
      echo "$this->html\n\n";
    }
    // keywords
    echo "<p>[ $this->keywords ]</p>\n";
    // bibtex popup and link to file
    $this->write_bibtex_popup_javascript_code();
    echo
      "<p>\n",
      "  [ <a href=\"javascript:void(0)\" onclick=\"show_bibtex()\">bibtex</a> | \n",
      "   file ]\n",
      "</p>\n";
    echo "\n</div>\n\n";
    // inline bibtex
    $this->write_bibtex();
    // print notes
    foreach ($this->notes as $note) {
      print "\n<p>\n";
      $note->write_html();
    }
    // edit form
    print "\n<p>\n\n";
    echo "<form method=GET action=edit-reference.php>\n";
    echo "<input type=hidden name=id value=$this->id>\n";
    echo "<input type=submit value=Edit>\n";
    echo "</form>\n";
  }

  function write_bibtex()
  {
    if ($this->bibtex) {
      print "\n<p>\n";
      echo "\n<div class=description>\n";
      echo "<pre>\n";
      $desc = $this->bibtex;
      echo "$desc\n";
      echo "</pre>\n";
      echo "</div>\n";
    }
  }

  function write_bibtex_popup_javascript_code()
  {
      echo
        "\n<script type=\"text/javascript\">\n",
        "<!--\n\n",
        "function show_bibtex()\n",
        "{\n",
        "  var w = window.open('', 'bibtex', 'width=800,height=300,status=no,resizable=yes');\n",
        "  var d = w.document;\n",
        "  d.writeln('<pre>');\n";
      
      $lines = explode("\r\n", $this->bibtex);
      $lines = $this->get_bibtex_lines();
      foreach ($lines as $line) {
        echo "  d.writeln('$line');\n";
      }
      
      echo
        "  d.writeln('</pre>');\n",
        "  d.close();\n",
        "  w.focus();\n",
        "}\n\n",
        "-->\n",
        "</script>\n\n";
  }

}

class Note
{
  function Note($row)
  {
    $this->date = $row[2];
    $this->note = $row[3];
  }

  function write_html()
  {
    echo "\n<div class=description>\n";
    echo "[$this->date]\n<p/>\n";
    $desc = protect_string($this->note);
    echo "$desc\n</div>\n";
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
  if ($debugging) {
    echo "<hr>\n";
  }
  return $result;
}

?>
