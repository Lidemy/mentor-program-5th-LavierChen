<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  // pagination setting
  $page = 1;
  $limit = 5;
  if (!empty($_GET['page'])) {
    $page = (int)$_GET['page'];
  }
  $offset = ($page - 1) * $limit;

  /*
      1. 從 cookie 裡讀取 PHPSESSID (token)
      2. 從 session-id 檔案讀取 session-data
      3. 放到 $_SESSION
  */
  $username = NULL;
  $user = NULL;
  if (!empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $user = getUserFromUsername($username);
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
        <!-- check login or not -->
        <?php if (empty($username)) { ?>
          <div class="btn">
            <a href="login.php" class="board_btn">登入</a>
            <a href="register.php" class="board_btn">註冊</a>
          </div>
        <?php } else { ?>
          <div class="btn">
            <?php if ($user['role'] === 'admin') {?>
              <a href="authority.php" class="board_btn">管理後臺</a>
            <?php } ?>
            <div class="board_btn update_nickname">編輯暱稱</div>
            <a href="handle_logout.php" class="board_btn">登出</a>
          </div>
        <?php } ?>
      </div>

      <!-- check login or not -->
      <?php if (empty($username)) { ?>
        <h3 class="error">請登入後再執行動作</h3>
      <?php } else { ?>
        <h3>Hello, <?php echo escape($user['nickname']); ?></h3>
        <?php 
          if ($user['role'] === 'banned') {
            echo '<h4 class="error">該使用者為停權用戶</h4>';
          }
        ?>
        <form class="board_update-nickname-form hidden" method="POST" action="handle_update_nickname.php">
          <div class="board_info">
            <span>新的暱稱：</span>
            <input type="text" name="nickname">
            <input type="submit" class="board_submit-btn" value="確認">
          </div>
        </form>
        <form class="board_new-comment-form" method="POST" action="handle_add_comment.php">
          <textarea name="content" rows="5" placeholder="請輸入留言..."></textarea>
          <input type="submit" class="board_submit-btn">
        </form>  
      <?php } ?>

      <!-- error code -->
      <?php
        if (!empty($_GET['errCode'])) {
          $code = $_GET['errCode'];
          $msg = 'Error';
          switch ($code) {
            case '1':
              $msg = '請輸入留言';
              break;
            case '2':
              $msg = '請輸入新的暱稱';
              break;
            case '3':
              $msg = '該使用者被禁止編輯暱稱';
              break;
            case '4':
              $msg = '該使用者被禁止新增留言';
              break;
            case '5':
              $msg = '無權編輯他人留言';
              break;
            case '6':
              $msg = '您尚未登入';
              break;
            case '7':
              $msg = '只有管理員有權限';
              break;
          }
          echo '<h3 class="error">錯誤：' . $msg . '</h3>';
        }
      ?>

      <hr>

      <?php
        // select content of comments
        $sql = 'SELECT ' .  
                  'C.id AS id, C.content AS content, C.create_at AS create_at, ' . 
                  'U.nickname AS nickname, U.username AS username, U.role AS role, ' .
                  'U.update_own AS update_own, U.delete_own AS delete_own, ' . 
                  'U.update_other AS update_other, U.delete_other AS delete_other ' .  
                'FROM lavier_week11_comments AS C ' . 
                'LEFT JOIN lavier_week11_users AS U ' . 
                'ON C.username = U.username ' .
                'WHERE C.is_deleted IS NULL ' .  
                'ORDER BY C.id DESC ' . 
                'LIMIT ? OFFSET ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $limit, $offset);
        $result = $stmt->execute();
        if (empty($result)) {
          die($conn->error);
        }
        $result = $stmt->get_result();
      ?>

      <section>
        <!-- check authority of user -->
        <?php while ($row = $result->fetch_assoc()) { 
          $update = NULL;
          $delete = NULL;
          
          if (!empty($username)) {
            // personal
            if ($row['username'] === $username) {
              if ((int)$user['update_own'] === 1) {
                $update = 1;
              }
              if ((int)$user['delete_own'] === 1) {
                $delete = 1;
              }
            } 
            // admin or editor
            if ($user['role'] === 'admin' || $user['role'] === 'editor') {
              if ((int)$user['update_other'] === 1) {
                $update = 1;
              }
              if ((int)$user['delete_other'] === 1) {
                $delete = 1;
              }
            }
          }
        ?>
          
        <div class="card">
          <div class="card_avater"></div>
          <div class="card_body">
            <div class="card_info">
              <span class="card_author">
                <?php echo escape($row['nickname']) ?>
                (@<?php echo escape($row['username']) ?>)
              </span>
              <span class="card_time"><?php echo escape($row['create_at']) ?></span>
              <?php if ($update) { ?>
                <a href="update_comment.php?id=<?php echo $row['id'] ?>" class="update_btn">編輯</a>
              <?php } ?>
              <?php if ($delete) { ?>
                <a href="handle_delete_comment.php?id=<?php echo $row['id'] ?>" class="update_btn">刪除</a>
              <?php } ?>
            </div>
            <p class="card_content"><?php echo escape($row['content']) ?></p>
          </div>
        </div>
        <?php } ?>
      </section>

      <hr>

      <div class="page_info">
        <?php
          $sql = 'SELECT COUNT(id) AS count FROM lavier_week11_comments WHERE is_deleted IS NULL';
          $stmt = $conn->prepare($sql);
          $result = $stmt->execute();
          if (empty($result)) {
            die ($conn->error);
          }
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          $comment_count = (int)$row['count'];
          $total_pages = ceil($comment_count/$limit);
        ?>
        <span>總共有 <?php echo $comment_count ?> 筆留言， </span>
        <span>頁數： <?php echo $page ?> / <?php echo $total_pages ?></span>
      </div>
      <div class="paginator">
        <?php if ($page != 1) { ?>
          <a href="index.php?page=1">首頁</a>
          <a href="index.php?page=<?php echo $page - 1 ?>">往前</a>
        <?php } ?>
        <?php if ($page != $total_pages) { ?>
          <a href="index.php?page=<?php echo $page + 1 ?>">往後</a>
          <a href="index.php?page=<?php echo $total_pages ?>">末頁</a>
        <?php } ?>
      </div>
    </main>
    <script>
      document.querySelector('.update_nickname').addEventListener('click', (e) => {
        const update_nickname_form = document.querySelector('.board_update-nickname-form')
        update_nickname_form.classList.toggle('hidden')
      })
    </script>
  </body>
</html>