<?php

require("utils.php");

$year = $HTTP_GET_VARS['year'];
$author = $HTTP_GET_VARS['author'];
$title = $HTTP_GET_VARS['title'];
$keyword = $HTTP_GET_VARS['keyword'];

$WHERE = '';
$restrictions = array();
if ($year) {
	$restrictions[] = "year=$year"; } 
if ($title) {
	$restrictions[] = "title like '%$title%'"; } 
if ($author) {
	$restrictions[] = "author like '%$author%'"; } 
if ($keyword) {
	$restrictions[] = "keywords like '%$keyword%'"; } 
if ($restrictions) {
	$WHERE = 'WHERE ' . implode(" AND ", $restrictions); }

$query = "SELECT * FROM `$MAIN_TABLE` $WHERE ORDER BY year DESC;";
$rows = dbSelect($query, 'list-references.php');

?>
<html>
<?php require("head.html"); ?>
<body>
<?php require("navigation.html"); ?>

<h2>List of References</h2>

<div class=search>
<form action="list-references.php" method=GET>
<b>year:</b> 
<input type="text" name="year" size="10" value="<?php echo $year ?>">&nbsp;&nbsp;
<b>author:</b> 
<input type="text" name="author" size="10" value="<?php echo $author ?>">&nbsp;&nbsp;
<b>title:</b> 
<input type="text" name="title" size="10" value="<?php echo $title ?>">&nbsp;&nbsp;
<b>keyword:</b> 
<input type="text" name="keyword" size="10" value="<?php echo $keyword ?>">&nbsp;&nbsp;
<input type="submit" value="Restrict Search">
</form>
</div>

<p>

<table cellpadding="7" cellspacing="0" class="listings">

<?php

foreach ($rows as $row) 
{
  $id = $row[0];
  $year = $row[1];
  $author = $row[2];
  $title = $row[3];
  $keywords = $row[4];
  echo "<tr>\n";
  echo "  <td valign=\"top\">$year\n";
  echo "  <td valign=\"top\">\n";
  echo "     <font color=blue>$title</font>&nbsp;&nbsp;";
  echo "     [<a href=show-reference.php?action=show&id=$id>view</a>]";
  echo "     [<a href=edit-reference.php?id=$id>edit</a>]<br>\n";
  echo "     $author.<br>\n";
  echo "     [$keywords]\n";
}

?>

</table>

</bode>
</html>














