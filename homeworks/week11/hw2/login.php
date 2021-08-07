<!DOCTYPE html>
<html lang>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>下班一小時</title>
    <link rel="stylesheet" href="./normalize.css">
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <!-- navbar -->
    <nav class="navbar">
      <div class="nav_left">
        <div class="nav_logo">
          <img class="nav_img" src="asset/lifestyle.png" width="50px">
          <h2 class="nav_title">下班一小時</h2>
        </div>
        <div class="nav_list">
          <a href="index.php" class="button">首頁</a>
          <a href="post_list.php" class="button">文章列表</a>
          <a href="index.php?category=food" class="button">食譜</a>
          <a href="index.php?category=movie" class="button">電影</a>
          <a href="index.php?category=feel" class="button">心情</a>
        </div>
      </div>
      <div class="nav_right">
        <div class="btn">
          <a href="index.php" class="button">返回</a>
          <a href="register.php" class="button">註冊</a>
        </div>
      </div>
    </nav>

    <!-- login form -->
    <div class="form_section">
      <form class="fill_in_form" method="POST" action="handle_login.php">
        <h2>登入</h2>
        <div class="form_info">
          <span>帳號：</span>
          <input type="text" name="username">
        </div>
        <div class="form_info">
          <span>密碼：</span>
          <input type="password" name="password">
        </div>
        <input type="submit" class="board_submit-btn">
      </form>

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
    </div>
  </body>
</html>