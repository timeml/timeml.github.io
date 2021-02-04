<?php

require("utils.php");
$id = $HTTP_GET_VARS['id'];
$reference = new Reference($id);
$bibtex = $reference->get_bibtex_string();
$html = $reference->get_html_string();
$keywords = get_keywords();
$selected_keywords = $reference->get_keywords();

?>
<html>
<?php require("head.html"); ?>
<body>
<?php require("navigation.html"); ?>


<h2>Edit Reference</h2>

<form method="POST" action="show-reference.php">
<input type="hidden" name="action" value="update">
<input type="hidden" name="id" value="<?php echo $id ?>">

<table cellpadding="3pt">
<tr>
  <td>Year:
  <td><input name="year" size="5" value="<?php echo $reference->year ?>">
<tr>
  <td>Title:
  <td><input name="title" size="100" value="<?php echo $reference->title ?>">
<tr>
  <td>Author:
  <td><input name="author" size="100" value="<?php echo $reference->author ?>">
</table>

<p>

<table cellpadding="5pt">
<tr>
  <td valign="top">
    Keywords</br>
    <div class="bordered">
    <?php
    $count = 0;
    foreach ($keywords as $kw) {
      if (in_array($kw, $selected_keywords)) {
        echo "   <input type=\"checkbox\" name=\"kw-$kw\" value=\"$kw\" checked>$kw<br>\n";
      }
      else {
        echo "   <input type=\"checkbox\" name=\"kw-$kw\" value=\"$kw\">$kw<br>\n";
      }
    }
    ?>
    </div>
  <td>
    <p>bibtex<br><textarea name="bibtex" rows="6" cols="90"><?php echo $bibtex; ?></textarea></p>
    <p>html<br><textarea name="html" rows="3" cols="90"><?php echo $html; ?></textarea></p>
    <p>add note<br><textarea name="note" rows="8" cols="90"></textarea></p>
</table>

<hr>
<table cellpadding="5pt">
<tr>
  <td><INPUT type="submit" value="Save Changes">
  <td><INPUT type="Reset" value="Revert">
</table>


</form>


</html>