<?php
require_once __DIR__ . '/../helpers.php';  require_login();
require_once __DIR__ . '/../config/db.php';

$userId = intval($_SESSION['user_id']);

$sql = "SELECT n.id, n.title, n.image_path,
               c.name AS category_name,
               u.name AS user_name
        FROM news n
        JOIN categories c ON c.id = n.category_id
        JOIN users u ON u.id = n.user_id
        WHERE n.is_deleted = 0 AND n.user_id = ?
        ORDER BY n.id DESC";

$rows = [];
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
$rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
$stmt->close();
?>
<!doctype html><html lang="ar" dir="rtl"><head>
<meta charset="utf-8"><title>أخباري</title>
<link rel="stylesheet" href="/web2-practical/news_app/assets/style.css">
</head><body>
<div class="wrap">
  <h2>كل الأخبار (الخاصة بي)</h2>
  <p>
    <a class="btn" href="/web2-practical/news_app/news/add.php">+ إضافة خبر</a>
    <a class="btn btn-outline" href="/web2-practical/news_app/news/deleted.php">الأخبار المحذوفة</a>
    <a class="btn btn-outline" href="/web2-practical/news_app/dashboard.php">لوحة التحكم</a>
  </p>

  <?php

?>

<div class="cards">
  <?php foreach ($rows as $r): ?>
    <article class="news-card">
      <?php if (!empty($r['image_path'])): ?>
        <img src="<?= e($r['image_path']) ?>" alt="<?= e($r['title']) ?>">
      <?php else: ?>
        <div class="news-card__noimg">لا توجد صورة</div>
      <?php endif; ?>

      <div class="news-card__body">
        <h3 class="news-card__title"><?= e($r['title']) ?></h3>

        <div class="news-card__meta">
          <span class="badge"><?= e($r['category_name']) ?></span>
          <span class="muted">• بواسطة <?= e($r['user_name']) ?></span>
        </div>

        <?php if (!empty($r['details'])): ?>
          <p class="news-card__excerpt">
            <?= e(mb_strimwidth(strip_tags($r['details']), 0, 140, '…', 'UTF-8')) ?>
          </p>
        <?php endif; ?>

        <div class="news-card__actions">
          <a class="btn btn-outline btn-sm" href="/web2-practical/news_app/news/edit.php?id=<?= e($r['id']) ?>">تعديل</a>
          <a class="btn btn-danger btn-sm"
             href="/web2-practical/news_app/news/delete.php?id=<?= e($r['id']) ?>"
             onclick="return confirm('حذف منطقي؟')">حذف</a>
        </div>
      </div>
    </article>
  <?php endforeach; ?>

  <?php if (!$rows): ?>
    <div class="muted">لا توجد أخبار بعد.</div>
  <?php endif; ?>
</div>

</div>
</body></html>
