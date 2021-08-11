<?php 
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  // default role is user
  $create_own = 1;
  $update_own = 1;
  $delete_own = 1;
  $update_other = 0;
  $delete_other = 0;

  $id = $_POST['id'];
  $role = $_POST['role'];

  switch ($role) {
    case 'admin':
      $update_other = 1;
      $delete_other = 1;
      break;
    case 'editor':
      $delete_other = 1;
      break;
    case 'banned':
      $create_own = 0;
      break;
  }

  $sql = 'UPDATE lavier_week11_users ' . 
         'SET role = ?, create_own = ?, update_own = ?, ' . 
         'delete_own = ?, update_other = ?, delete_other = ? ' . 
         'WHERE id = ?';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("siiiiii", $role, $create_own, $update_own, $delete_own, $update_other, $delete_other, $id);
  $result = $stmt->execute();
  if (!$result) {
    die ($conn->error);
  }

  header("Location: authority.php");
?>