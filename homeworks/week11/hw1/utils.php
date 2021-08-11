<?php
  require_once('conn.php');

  function getUserFromUsername($username) {
    // 要在 function 內使用 $conn 要先進行宣告
    global $conn;
    $sql = sprintf('SELECT * FROM lavier_week11_users WHERE username = "%s"', $username);
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row;  // id, nickname, username, password, create_at, role
  }

  // 過濾所有 Client 端提供的內容，並轉譯成純文字
  function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }
?>