<?php
require_once __DIR__ . '/../helpers.php';  require_login(); // جلسة + حماية
require_once __DIR__ . '/../config/db.php';               // اتصال DB

$uid = (int)($_SESSION['user_id']);

// جبنا المحذوفة للمستخدم الحالي
$rows = [];
$q = $conn->prepare("SELECT id, title FROM news WHERE is_deleted=1 AND user_id=? ORDER BY id DESC");
$q->bind_param("i", $uid);
$q->execute();
$res = $q->get_result();
if ($res) $rows = $res->fetch_all(MYSQLI_ASSOC);
$q->close();
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>أخباري المحذوفة</title>
  <link rel="stylesheet" href="/web2-practical/news_app/assets/style.css">
</head>
<body>
<div class="wrap">
  <h2>الأخبار المحذوفة (الخاصة بي)</h2>

  <?php if (!empty($_GET['restored'])): ?>
    <div class="ok">تم الاسترجاع بنجاح.</div>
  <?php endif; ?>

  <p class="actions">
    <a class="btn btn-outline" href="/web2-practical/news_app/news/list.php">عودة لقائمة أخباري</a>
  </p>

  <?php if (!$rows): ?>
    <div class="muted">لا توجد أخبار محذوفة.</div>
  <?php else: ?>
    <table>
      <thead>
        <tr><th>#</th><th>العنوان</th><th>إجراءات</th></tr>
      </thead>
      <tbody>
        <?php foreach($rows as $r): ?>
          <tr>
            <td><?= e($r['id']) ?></td>
            <td><?= e($r['title']) ?></td>
            <td>
              <a class="btn btn-sm" href="/web2-practical/news_app/news/restore.php?id=<?= e($r['id']) ?>"
                 onclick="return confirm('استرجاع هذا الخبر؟')">استرجاع</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
</body>
</html>
