<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Password Hash &middot; Talentos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Leonardo A. Merino Loyola">

    <link href="assets/css/bootstrap-metro.css" rel="stylesheet">
</head> 
<body> 
<form method="post" action="hash.php"> 
<textarea rows="10" cols="80" name="textarea" wrap="physical"></textarea><br />
<button type="submit" class="btn btn-large btn-primary">Hash!</button>
</form>
    <pre>
<?php
    $text = @trim($_POST['textarea']);
    $text = explode ("\n", $text);

    foreach ($text as $line) {
       $line = str_replace(array("\n", "\r"), '', $line);  //Le saco la sal! le saco la sal! chao newlines
       //echo hash('sha256', $line)."\n";
       echo password_hash($line, PASSWORD_DEFAULT)."\n";


       //echo "</br>";
    }
?>
</pre>
</body>
</html>