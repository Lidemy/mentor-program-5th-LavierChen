<?php
  session_start();
  session_destroy();  // 清除所有 session
  header('Location: index.php');
?>