<?php
//Comprovem sel csrf_token
session_start();
$accionsPermeses = array('save');
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$csrf_token = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_STRING);
$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
if ($_SESSION["csrf_token"]!=$csrf_token) {
  header("HTTP/1.0 403 Forbidden");
  echo 'error: "csrf_token incorrecte"';
  die();
}
if (!in_array($action,$accionsPermeses)) {
  header("HTTP/1.0 404 Not Found");
  echo 'error: "Acció no trobada"';
  die();

}
if ($action='save') {
  copy ('file.txt.old3','file.txt.old4');
  copy ('file.txt.old2','file.txt.old3');
  copy ('file.txt.old1','file.txt.old2');
  copy ('file.txt.old','file.txt.old1');
  copy ('file.txt','file.txt.old');
  echo 'result: '.(file_put_contents ( 'file.txt' , $content ));
}
