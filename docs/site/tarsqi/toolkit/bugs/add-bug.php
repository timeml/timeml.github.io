<?php
require("database_forms.php");
?>

<html>
<?php require("head.html"); ?>
<body>
<?php require("navigation.html"); ?>


<h1>Create Bug Report</h1>

<form method="POST" action="show-bug.php">
<input type=hidden name=action value=create>

<table cellpadding=10pt>

<tr>
  <td valign="top">Descriptive String:
  <td>
    <textarea name="bugname" rows="2" cols="70"></textarea>
<tr>
  <td>Bug Type: 
  <td>
    <select name="bugtype">
<?php
foreach ($bugtypes as $bugtype) {
       echo "      <option label=\"$bugtype\">$bugtype</option>\n";
}
?>
    </select>

<tr>
  <td>Component:
  <td>
    <select name="component">
<?php
foreach ($components as $component) {
       echo "      <option label=\"$component\">$component</option>\n";
}
?>
    </select>

<tr>
  <td valign="top">Long Description
  <td>
    <textarea name="description" rows="20" cols="70"></textarea>

</table>

<br>

<table cellpadding=10pt>
<tr>
  <td><INPUT type="submit" value="Submit Bug Report">
  <td><INPUT type="Reset">
</table>



</form>


</html>