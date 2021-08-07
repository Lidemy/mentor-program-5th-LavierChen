<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  // user setting
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
    <title>下班一小時</title>
    <link rel="stylesheet" href="./normalize.css">
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <!-- navbar -->
    <nav class="navbar">
      <div class="nav_left">
        <div class="nav_logo">
          <img class="nav_img" src="asset/lifestyle.png" width="50px" color="white">
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
        <?php if (empty($username)) { ?>
          <div class="btn">
            <a href="register.php" class="button">註冊</a>
            <a href="login.php" class="button">登入</a>
          </div>
        <?php } else { ?>
          <div class="btn">
            <?php if ($user['role'] === 'admin') {?>
              <a href="create_post.php" class="button">發文</a>
              <a href="admin.php" class="button">管理後臺</a>
            <?php } ?>
            <a href="handle_logout.php" class="button">登出</a>
          </div>
        <?php } ?>
      </div>
    </nav>

    <!-- banner -->
    <div class="banner">
      <div class="banner_text">做自己的心靈導師</div>
    </div>

    <!-- article -->
    <?php 
      $sql = 'SELECT ' . 
                'P.id AS id, P.username AS username, P.title AS title, ' .  
                'P.category AS category, P.content AS content, P.create_at AS create_at ' . 
              'FROM lavier_week11_blog_post AS P ' . 
              'LEFT JOIN lavier_week11_blog_admin AS U ' . 
              'ON P.username = U.username ' . 
              'WHERE P.is_deleted IS NULL ' . 
              'ORDER BY P.id DESC';
      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
      if (empty($result)) {
        die($conn->error);
      }
      $result = $stmt->get_result();
    ?>
    
    <section class="post">
      <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="post_items">
          <div class="post_info">
            <div class="post_left">
              <a class="post_category check" href="index.php?category=<?php echo $row['category']; ?>">
                <?php echo escape(getCategory($row['category'])); ?>
              </a>
              <a class="post_title" href="read_post.php?id=<?php echo $row['id']; ?>">
                <?php echo escape($row['title']); ?>
              </a>
            </div>
            <div class="post_right">
              <div class="post_time"><?php echo escape(substr($row["create_at"], 5, 11)); ?></div>
            </div>
          </div>
        </div>
      <?php } ?>
    </section>

    <!-- footer -->
    <footer>
      <p>Copyright © 2021 Lidemy Mentor Program 5th By Lavier</p>
    </footer>
  </body>
</html>