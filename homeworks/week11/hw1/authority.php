<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  if (empty($_SESSION['username'])) {
    header('Location: index.php?errCode=8');
    die('您尚未登入');
  } else {
    $username = $_SESSION['username'];
    $user = getUserFromUsername($username);
  }
  
  if ($user['role'] !== 'admin') {
    header('Location: index.php?errCode=7');
    die('只有管理員有權限');
  }
?>

<!DOCTYPE html>
<html lang>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>權限管理</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <header class="warning">
      注意！本站為練習用網站，註冊時請勿使用任何真實的帳號或密碼。
    </header>

    <main class="backstage">
      <div class="board_header">
        <h1 class="board_title">Backstage</h1>
        <div class="btn">
          <a href="index.php" class="board_btn">返回</a>
          <a href="handle_logout.php" class="board_btn">登出</a>
        </div>  
      </div>

      <ul class="authority_items">
        <li>使用者</li>
        <li>身分</li>
        <li>權限管理</li>
      </ul>

      <?php
        // select all user
        $sql = 'SELECT * FROM lavier_week11_users AS U';
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute();
        if (empty($result)) {
          die($conn->error);
        }
        $result = $stmt->get_result();
      ?>

      <section class="user_list">
        <?php while ($row = $result->fetch_assoc()) { ?>
          <form class="user_form" method="POST" action="handle_authority.php">
            <section class="user_item">
              <div class="user_info">
                <h3><?php echo escape($row['nickname']) ?></h3>
                <h4>@<?php echo escape($row['username']) ?></h4>
                <input type="hidden" name="id" value="<?php echo ($row['id']) ?>">
              </div>
            
              <div class="user_role">
                <h3><?php echo escape($row['role']) ?></h3>
              </div>

              <div class="user_authority">
                <select name="role">
                  <?php
                    switch ($row['role']) {
                      case 'admin': 
                  ?>
                        <option value="admin" selected>admin</option>
                  <?php 
                        break;
                      case 'editor':
                  ?>    
                        <option value="editor" selected>editor</option>
                        <option value="user">user</option>
                        <option value="banned">banned</option>
                  <?php 
                        break;
                      case 'user':
                  ?>
                        <option value="editor">editor</option>
                        <option value="user" selected>user</option>
                        <option value="banned">banned</option>
                  <?php
                        break;
                      case 'banned':
                  ?>
                        <option value="editor">editor</option>
                        <option value="user">user</option>
                        <option value="banned" selected>banned</option>
                  <?php break; 
                    }
                  ?>
                </select>
              </div>
              <input type="submit" class="save_btn" value="Save" > 
          </form>
        </section>
      <?php } ?>
    </main>
  </body>
</html>