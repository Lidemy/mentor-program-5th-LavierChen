<!DOCTYPE html>
<html lang>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>留言板</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <header class="warning">
      注意！本站為練習用網站，註冊時請勿使用任何真實的帳號或密碼。
    </header>

    <main class="board">
      <div class="board_header">
        <h1 class="board_title">Login</h1>
        <div class="btn">
          <a href="register.php" class="board_btn">註冊</a>
          <a href="index.php" class="board_btn">返回</a>
        </div>
      </div>

      <form class="board_new-comment-form" method="POST" action="handle_login.php">
        <div class="board_info">
          <span>帳號：</span>
          <input type="text" name="username">
        </div>
        <div class="board_info">
          <span>密碼：</span>
          <input type="password" name="password">
        </div>
        <input type="submit" class="board_submit-btn">
      </form>

      <!-- 後端驗證 -->
      <?php
        if (!empty($_GET['errCode'])) {
          $code = $_GET['errCode'];
          $msg = 'error';
          if ($code === '1') {
            $msg = '資料尚有缺漏，請重新輸入';
          } elseif ($code === '2') {
            $msg = '密碼錯誤，請重新輸入';
          } elseif ($code === '3') {
            $msg = '查無帳號，請重新輸入';
          }
          echo '<h3 class="error">錯誤：' . $msg . '</h3>';
        }
      ?>
    </main>
  </body>
</html>