<?php
  require_once('conn.php');
  header('Content-Type: application/json; charset=utf-8');
  header('Access-Control-Allow-Origin: *');

  $todos = $_POST['todos'];

  if (empty($_POST['id'])) {
    $id = generateID();
    $sql = 'INSERT INTO lavier_week12_todos(id, todos) VALUES (?, ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $id, $todos);
    $result = $stmt->execute();

    if (empty($result)) {
      $json = array(
        'ok' => false, 
        'message' => $conn->error
      );
      $response = json_encode($json);
      echo $response;
      die();
    }

    $json = array(
      'ok' => true, 
      'id' => $id,
    );
    $response = json_encode($json);
    echo $response;
  } else {
    $id = $_POST['id'];
    $sql = 'UPDATE lavier_week12_todos SET todos = ? WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $todos, $id);
    $result = $stmt->execute();
  
    if (empty($result)) {
      $json = array(
        'ok' => false, 
        'message' => $conn->error
      );
      $response = json_encode($json);
      echo $response;
      die();
    }

    $json = array(
      'ok' => true, 
      'id' => $id,
      'has_exist' => true
    );
    $response = json_encode($json);
    echo $response;
  }

  function generateID() {
    $id = '';
    for ($i = 0; $i < 10; $i++) {
      $id .= chr(rand(65, 90));
    }
    return $id;
  }
?>