<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  if (empty($_POST['username']) || empty($_POST['password'])) {
    header('Location: login.php?errCode=1');
    die('資料尚有缺漏，請重新輸入');
  }

  $username = $_POST['username'];
  $password = $_POST['password'];
  $sql = sprintf('SELECT * FROM lavier_week9_users WHERE username = "%s" AND password = "%s"', $username, $password);
  $result = $conn->query($sql);
  if (empty($result)) {
    die($conn->error);
  }

  // 若資料對比正確，nom_rows 回傳值會是 1
  if ($result->num_rows) {
    // 登入成功，會進行下列三件事：
    /* 
      1. 產生 session-id (token) 
      2. 把 username 寫入 session-id 檔案
      3. set-cookie: session-id
    */
    $_SESSION['username'] = $username;
    header('Location: index.php');
  } else {
    header('Location: login.php?errCode=2');
    die('帳號或密碼輸入錯誤，請重新輸入');
  }
?>