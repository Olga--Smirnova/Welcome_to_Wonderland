<?php
/**
  * logout.php
  * Author: Olga Smirnova
  * November 2014
  * 1 -cleans $_SESSION['logged'] and $_SESSION['current_id']
  * 2 -redirects to index pade
*/

session_start();
unset($_SESSION['logged']);
unset($_SESSION['current_id']);
header('Location: index.php');

// EOF logout.php