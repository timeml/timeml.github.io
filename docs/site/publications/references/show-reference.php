<?php

require("utils.php");

if ($debugging) { 
  printVars();
  echo "<hr><p>\n\n";
}

$action = $HTTP_POST_VARS['action'];
if (! $action) {
 	$action = $HTTP_GET_VARS['action'];
}

if ($action == 'create') { 
  // creating a new reference, data received from add-reference.php
  $title = $HTTP_POST_VARS['title'];
  $ref_id = add_reference($title);
}
else if ($action == 'update') { 
  // changing e reference, the id received from edit-reference.php
  $ref_id = $HTTP_POST_VARS['id'];
  update_reference($ref_id);
}
else if ($action == 'show') { 
  // reference id received from list-references.php
  $ref_id = $HTTP_GET_VARS['id'];
}

$reference = new Reference($ref_id);

?>
<html>
<?php require("head.html"); ?>

<body>

<?php
require("navigation.html");
$reference->write_html();
?>

</body>
</html>
