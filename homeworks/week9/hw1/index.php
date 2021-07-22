<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  /*
      1. 從 cookie 裡讀取 PHPSESSID (token)
      2. 從 session-id 檔案讀取 session-data
      3. 放到 $_SESSION
  */
  $username = NULL;
  if (!empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
  }

  // 讀取 comments 內的留言
  $sql = sprintf('SELECT * FROM lavier_week9_comments ORDER BY id DESC');
  $result = $conn->query($sql);
  if (empty($result)) {
    die('Read failed：' . $conn->error);
  }
?>

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
        <h1 class="board_title">Comments</h1>
        <!-- 判斷是否為 Login 狀態 -->
        <?php if (empty($username)) { ?>
          <div class="btn">
            <a href="login.php" class="board_btn">Login</a>
            <a href="register.php" class="board_btn">Register</a>
          </div>
        <?php } else { ?>
          <a href="logout.php" class="board_btn">Logout</a>
        <?php } ?>
      </div>

      <!-- 判斷是否為 Login 狀態 -->
      <?php if (empty($username)) { ?>
        <h3 class="error">請登入後再發佈留言</h3>
      <?php } else { ?>
        <!-- 若為 Login 即可看到留言表單 -->
        <h3>Hello, <?php echo $username; ?></h3>
        <form class="board_new-comment-form" method="POST" action="handle_add_comment.php">
          <textarea name="content" rows="5" placeholder="請輸入留言..."></textarea>
          <input type="submit" class="board_submit-btn">
        </form>  
      <?php } ?>

      <!-- 後端驗證 -->
      <?php
        if (!empty($_GET['errCode'])) {
          $code = $_GET['errCode'];
          $msg = 'error';
          if ($code === '1') {
            $msg = '請輸入留言';
          }
          echo '<h3 class="error">錯誤：' . $msg . '</h3>';
        }
      ?>

      <hr>

      <section>
        <?php while ($row = $result->fetch_assoc()) { ?>
          <div class="card">
            <div class="card_avater"></div>
            <div class="card_body">
              <div class="card_info">
                <span class="card_author"><?php echo $row['nickname'] ?></span>
                <span class="card_time"><?php echo $row['create_at'] ?></span>
              </div>
              <p class="card_content"><?php echo $row['content'] ?></p>
            </div>
          </div>
        <?php } ?>
      </section>
    </main>
  </body>
</html>