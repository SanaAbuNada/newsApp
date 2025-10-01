<?php
require_once __DIR__ . '/helpers.php';
require_login();
require_once __DIR__ . '/config/db.php';

$userId = intval($_SESSION['user_id']);

// هان عششان نشوف عدد الفئات 
$cat_count = 0;
if ($res = $conn->query("SELECT COUNT(*) AS c FROM categories")) {
  $cat_count = (int)$res->fetch_assoc()['c'];
}

// هان لنعد عدد الاخبار الخاصة لكل يوزر
$news_count = 0;
$stmt = $conn->prepare("SELECT COUNT(*) AS c FROM news WHERE is_deleted = 0 AND user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$r = $stmt->get_result()->fetch_assoc();
$news_count = (int)($r['c'] ?? 0);
$stmt->close();
?>


<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>لوحة التحكم</title>
  <link rel="stylesheet" href="/web2-practical/news_app/assets/style.css">
</head>
<body>

  <div class="nav">
    <a class="btn" href="/web2-practical/news_app/categories/add.php">+ إضافة فئة</a>
    <a class="btn btn-outline" href="/web2-practical/news_app/categories/list.php">عرض الفئات</a>
    <a class="btn" href="/web2-practical/news_app/news/add.php">+ إضافة خبر</a>
    <a class="btn btn-outline" href="/web2-practical/news_app/news/list.php">كل الأخبار</a>
    <a class="btn btn-outline" href="/web2-practical/news_app/news/deleted.php">الأخبار المحذوفة</a>
    <a class="btn btn-danger" href="/web2-practical/news_app/auth/logout.php">تسجيل الخروج</a>
  </div>

  <div class="card">
    <h2>أهلًا <?= e($_SESSION['user_name'] ?? '') ?></h2>
    <p>تم تسجيل الدخول بنجاح.</p>

    <div class="row" style="margin-top:12px">
      <div class="card col">
        <h3>الفئات</h3>
        <p>العدد الحالي: <strong><?= $cat_count ?></strong></p>
        <p><a href="/web2-practical/news_app/categories/add.php">+ إضافة فئة</a> ·
           <a href="/web2-practical/news_app/categories/list.php">عرض الكل</a></p>
      </div>

      <div class="card col">
        <h3>أخباري</h3>
        <p>العدد الحالي: <strong><?= $news_count ?></strong></p>
        <p><a href="/web2-practical/news_app/news/add.php">+ إضافة خبر</a> ·
           <a href="/web2-practical/news_app/news/list.php">عرض أخباري</a></p>
      </div>
    </div>
  </div>

</body>
</html>
