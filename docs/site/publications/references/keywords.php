<?php

require("utils.php");
if ($HTTP_POST_VARS['action'] == 'create') {
  add_keyword($HTTP_POST_VARS['keyword'], $HTTP_POST_VARS['description']);
 }
$keywords = get_keywords_with_descriptions();

?>

<html>
<?php require("head.html"); ?>
<body>
<?php require("navigation.html"); ?>

<h2>Keywords</h2>

<div class="description">

<table cellpading="3pt">
<?php
foreach ($keywords as $keyword) {
  echo "<tr>\n";
  echo "   <td valign=\"top\">$keyword[0]\n";
  echo "   <td>&nbsp;\n";
  echo "   <td>$keyword[1]\n";
}
?>
</table>
</div>

<p>

<form method="POST" action="add-keyword.php">
<input type="hidden" name="action" value="create">
<div class="bordered">
<table cellpadding="5pt">
<tr>
  <td>keyword
  <td><input name="keyword" size="30">
<tr>
  <td>description
  <td><input name="description" size="60">
  <td><input type="submit" value="Add Keyword">
</table>
</div>
</form>

</body>
</html>