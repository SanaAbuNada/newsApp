<?php
require_once __DIR__ . '/../helpers.php';  require_login();
require_once __DIR__ . '/../config/db.php';

$result = $conn->query("SELECT id, name FROM categories ORDER BY id DESC");
$cats = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>قائمة الفئات</title>
  <link rel="stylesheet" href="/web2-practical/news_app/assets/style.css">
</head>
<body>
  <div class="wrap">
    <h2>قائمة الفئات</h2>

    <p>
      <a href="/web2-practical/news_app/categories/add.php">+ إضافة فئة</a> |
      <a href="/web2-practical/news_app/dashboard.php">لوحة التحكم</a>
    </p>

    <table>
      <thead>
        <tr><th>#</th><th>الاسم</th></tr>
      </thead>
      <tbody>
        <?php foreach($cats as $c): ?>
          <tr>
            <td><?= e($c['id']) ?></td>
            <td><?= e($c['name']) ?></td>
          </tr>
        <?php endforeach; ?>

        <?php if (!$cats): ?>
          <tr><td colspan="2">لا توجد فئات بعد.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
