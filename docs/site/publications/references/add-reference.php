<?php

require("utils.php");
$keywords = get_keywords();

?>
<html>
<?php require("head.html"); ?>
<body>
<?php require("navigation.html"); ?>


<h2>Create Reference</h2>

<form method="POST" action="show-reference.php">
<input type="hidden" name="action" value="create">

<table cellpadding="3pt">
<tr>
  <td>Year:
  <td><input name="year" size="5">
<tr>
  <td>Title:
  <td><input name="title" size="100">
<tr>
  <td>Author: 
   <td><input name="author" size="100">
</table>

<p>
   
<table cellpadding="5pt">
<tr>
  <td valign="top">
  Keywords<br>
  <div class="bordered">
<?php
foreach ($keywords as $kw) {
  echo "   <input type=\"checkbox\" name=\"kw-$kw\" value=\"$kw\">$kw<br>\n";
}
?>
  </div>
  <td valign="top">
    <p>bibtex<br><textarea name="bibtex" rows="6" cols="90"></textarea></p>
    <p>html<br><textarea name="html" rows="3" cols="90"></textarea></p>
    <p>note<br><textarea name="note" rows="8" cols="90"></textarea></p>

</table>


<hr>
<table cellpadding="5pt">
<tr>
  <td><INPUT type="submit" value="Add reference">
  <td><INPUT type="Reset">
</table>

</form>


</body>
</html>