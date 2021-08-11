<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  if (empty($_SESSION['username'])) {
    header('Location: index.php?errCode=6');
    die('您尚未登入');
  } else {
    $username = $_SESSION['username'];
    $user = getUserFromUsername($username);
  }

  $id = $_GET['id'];
  $sql = 'SELECT * FROM lavier_week11_comments where id = ? AND is_deleted IS NULL';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $id);
  $result = $stmt->execute();
  if (empty($result)) {
    die('Read failed：' . $conn->error);
  }
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  if ($user['role'] !== 'admin') {
    if ($row['username'] !== $username) {
      header('Location: index.php?errCode=5');
      die('無權編輯他人留言');
    }
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

    <main class="board edit">
      <div class="board_header">
        <h1 class="board_title">Update Comment</h1>
      </div>

      <form class="board_update-comment-form" method="POST" action="handle_update_comment.php">
        <textarea name="content" class="edit_textarea"><?php echo escape($row['content']) ?></textarea>
        <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
        <input type="submit" class="board_submit-btn">
      </form>

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
    </main>
  </body>
</html>