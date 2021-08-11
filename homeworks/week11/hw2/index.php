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

  // category setting
  $category = NULL;
  if (!empty($_GET['category'])) {
    $category = $_GET['category'];
  }

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
              <a href="edit_post.php" class="button">發文</a>
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

    <!-- select category or not -->
    <?php 
      if ($category) {
        $sql = 'SELECT ' . 
                  'P.id AS id, P.username AS username, P.title AS title, ' .  
                  'P.category AS category, P.content AS content, P.create_at AS create_at ' . 
                'FROM lavier_week11_blog_post AS P ' . 
                'LEFT JOIN lavier_week11_blog_admin AS U ' . 
                'ON P.username = U.username ' . 
                'WHERE P.is_deleted IS NULL AND P.category = ? ' . 
                'ORDER BY P.id DESC ' . 
                'LIMIT ? OFFSET ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sii', $category, $limit, $offset);
      } else {
        $sql = 'SELECT ' . 
                  'P.id AS id, P.username AS username, P.title AS title, ' .  
                  'P.category AS category, P.content AS content, P.create_at AS create_at ' . 
                'FROM lavier_week11_blog_post AS P ' . 
                'LEFT JOIN lavier_week11_blog_admin AS U ' . 
                'ON P.username = U.username ' . 
                'WHERE P.is_deleted IS NULL ' . 
                'ORDER BY P.id DESC ' . 
                'LIMIT ? OFFSET ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $limit, $offset);
      }
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
          <p class="post_content"><?php echo escape($row['content']) ?></p>
        </div>
      <?php } ?>
    </section>

    <!-- pagination -->
    <div class="page_info">
      <?php
        if ($category) {
          $sql = 'SELECT COUNT(id) AS count FROM lavier_week11_blog_post ' . 
          'WHERE is_deleted IS NULL AND category = ?';
          $stmt = $conn->prepare($sql);
          $stmt->bind_param('s', $category);
        } else {
          $sql = 'SELECT COUNT(id) AS count FROM lavier_week11_blog_post ' . 
          'WHERE is_deleted IS NULL';
          $stmt = $conn->prepare($sql);
        }
        $result = $stmt->execute();
        if (empty($result)) {
          die ($conn->error);
        }
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $post_count = (int)$row['count'];
        $total_pages = ceil($post_count/$limit);
      ?>
      <span>頁數： <?php echo $page ?> / <?php echo $total_pages ?></span>
    </div>


    <?php if ($category) { ?>
      <div class="paginator">
        <?php if ($page != 1) { ?>
          <a href="index.php?category=<?php echo escape($category) ?>&page=1">首頁</a>
          <a href="index.php?category=<?php echo escape($category) ?>&page=<?php echo $page - 1 ?>">往前</a>
        <?php } ?>
        <?php if ($page != $total_pages) { ?>
          <a href="index.php?category=<?php echo escape($category) ?>&page=<?php echo $page + 1 ?>">往後</a>
          <a href="index.php?category=<?php echo escape($category) ?>&page=<?php echo $total_pages ?>">末頁</a>
        <?php } ?>
      </div>
    <?php } else { ?>  
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
    <?php } ?>
    
    <?php if ($post_count <= $limit || $page == $total_pages) { ?>
        <div class="alert">沒有更多文章了</div>
    <?php } ?>

    <!-- footer -->
    <footer>
      <p>Copyright © 2021 Lidemy Mentor Program 5th By Lavier</p>
    </footer>
  </body>
</html>