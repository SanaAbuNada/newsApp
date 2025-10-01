<?php
require_once __DIR__ . '/../helpers.php';  require_login(); // جلسة + حماية
require_once __DIR__ . '/../config/db.php';               // اتصال DB

$errors = []; $ok = '';

//بنجيب هان الفئات
$cats = [];
if ($res = $conn->query("SELECT id, name FROM categories ORDER BY name")) {
  $cats = $res->fetch_all(MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title  = trim($_POST['title'] ?? '');
  $catId  = (int)($_POST['category_id'] ?? 0);
  $details= trim($_POST['details'] ?? '');
  $userId = (int)($_SESSION['user_id']);
  $imgPath = null;

  // بنتحقق هان
  if ($title === '')      $errors[] = 'العنوان مطلوب';
  if ($catId <= 0)        $errors[] = 'اختاري فئة';
  if ($details === '')    $errors[] = 'التفاصيل مطلوبة';

//نرفع الصورة
  if (!empty($_FILES['image']['name'])) {
    $okExt   = ['jpg','jpeg','png','gif','webp'];
    $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $okExt))          $errors[] = 'امتداد الصورة غير مسموح';
    elseif ($_FILES['image']['error'])    $errors[] = 'فشل رفع الصورة';
    else {
      $new = 'img_'.time().'_'.mt_rand(1000,9999).'.'.$ext;
      $to  = __DIR__ . '/../uploads/' . $new;
      if (move_uploaded_file($_FILES['image']['tmp_name'], $to)) {
        $imgPath = '/web2-practical/news_app/uploads/' . $new;
      } else {
        $errors[] = 'تعذّر حفظ الصورة';
      }
    }
  }

  // بندخل على الداتا بيز
  if (!$errors) {
    $q = $conn->prepare("INSERT INTO news (title, category_id, details, image_path, user_id) VALUES (?,?,?,?,?)");
    $q->bind_param("sissi", $title, $catId, $details, $imgPath, $userId);
    if ($q->execute()) { $ok='تمت إضافة الخبر بنجاح'; $_POST=[]; } else { $errors[]='حدث خطأ أثناء الإضافة'; }
    $q->close();
  }
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>إضافة خبر</title>
  <link rel="stylesheet" href="/web2-practical/news_app/assets/style.css">
</head>
<body>
<div class="box">
  <h2>إضافة خبر</h2>

  <?php if ($ok): ?>
    <div class="ok"><?= e($ok) ?></div>
  <?php endif; ?>

  <?php if ($errors): ?>
    <div class="err"><?php foreach($errors as $e) echo '<div>• '.e($e).'</div>'; ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <label>العنوان</label>
    <input type="text" name="title" value="<?= e($_POST['title'] ?? '') ?>" required>

    <label>الفئة</label>
    <select name="category_id" required>
      <option value="">-- اختاري --</option>
      <?php foreach($cats as $c): ?>
        <option value="<?= e($c['id']) ?>" <?= (!empty($_POST['category_id']) && $_POST['category_id']==$c['id'])?'selected':'' ?>>
          <?= e($c['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>التفاصيل</label>
    <textarea name="details" rows="6" required><?= e($_POST['details'] ?? '') ?></textarea>

    <label>صورة الخبر (اختياري)</label>
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp">

    <button type="submit">حفظ الخبر</button>
  </form>

  <p>
    <a class="btn btn-outline btn-sm" href="/web2-practical/news_app/news/list.php">عرض أخباري</a>
    <a class="btn btn-ghost btn-sm" href="/web2-practical/news_app/dashboard.php">لوحة التحكم</a>
  </p>
</div>
</body>
</html>
