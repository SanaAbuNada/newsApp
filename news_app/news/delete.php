<?php
require_once __DIR__ . '/../helpers.php';  require_login();
require_once __DIR__ . '/../config/db.php';

$userId = intval($_SESSION['user_id']);
$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
  $stmt = $conn->prepare("UPDATE news SET is_deleted = 1 WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $id, $userId);
  $stmt->execute();
  $stmt->close();
}
header('Location: /web2-practical/news_app/news/list.php');
exit;
