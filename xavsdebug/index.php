<?php
$admins = array(3, 5, 7, 8, 9, 10, 11, 23, 26, 27, 30, 33, 21, 41, 52, 59, 67, 61);
$owners = array(3, 41, 66);
session_start();
if (isset($_SESSION['loggedin'])) {
  $_SESSION['admin'] = true;
  echo 'working in progress lol<br/>';
  echo 'debug:<br/>';
  echo 'user id:'.$_SESSION['user']['userid'].'<br/>username: '.$_SESSION['user']['username'].'<br/>email: '.$_SESSION['user']['email']; // im making an api
} // ok

?>