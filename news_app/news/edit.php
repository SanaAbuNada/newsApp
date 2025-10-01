<?php
require_once __DIR__ . '/../helpers.php';  require_login();
require_once __DIR__ . '/../config/db.php';

$userId = intval($_SESSION['user_id']);
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: /web2-practical/news_app/news/list.php'); exit; }

// بجيب الخبر الخاص بالمستخدم
$stmt = $conn->prepare("SELECT id, title, category_id, details, image_path
                        FROM news
                        WHERE id = ? AND user_id = ? AND is_deleted = 0
                        LIMIT 1");
$stmt->bind_param("ii", $id, $userId);
$stmt->execute();
$news = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$news) { header('Location: /web2-practical/news_app/news/list.php'); exit; }

// الفئات
$cats = [];
if ($r = $conn->query("SELECT id, name FROM categories ORDER BY name")) $cats = $r->fetch_all(MYSQLI_ASSOC);

$errors = []; $ok = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title      = trim($_POST['title'] ?? '');
  $categoryId = intval($_POST['category_id'] ?? 0);
  $details    = trim($_POST['details'] ?? '');
  $imagePath  = $news['image_path'];

  if ($title === '')    $errors[] = 'العنوان مطلوب';
  if ($categoryId <= 0) $errors[] = 'اختاري فئة';
  if ($details === '')  $errors[] = 'التفاصيل مطلوبة';

  if (!empty($_FILES['image']['name'])) {
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
      $errors[] = 'امتداد الصورة غير مسموح';
    } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
      $errors[] = 'فشل رفع الصورة';
    } else {
      $newName = 'img_'.time().'_'.mt_rand(1000,9999).'.'.$ext;
      $dest = __DIR__ . '/../uploads/' . $newName;
      if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        $imagePath = '/web2-practical/news_app/uploads/' . $newName;
      } else {
        $errors[] = 'تعذّر حفظ الصورة';
      }
    }
  }

  if (!$errors) {
    $stmt = $conn->prepare("UPDATE news
                            SET title=?, category_id=?, details=?, image_path=?
                            WHERE id=? AND user_id=?");
    $stmt->bind_param("sissii", $title, $categoryId, $details, $imagePath, $id, $userId);
    if ($stmt->execute()) {
      $ok = 'تم التعديل بنجاح';
      $news['title']=$title; $news['category_id']=$categoryId; $news['details']=$details; $news['image_path']=$imagePath;
    } else {
      $errors[] = 'فشل التعديل';
    }
    $stmt->close();
  }
}
?>
<!doctype html><html lang="ar" dir="rtl"><head>
<meta charset="utf-8"><title>تعديل خبر</title>
<link rel="stylesheet" href="/web2-practical/news_app/assets/style.css">
</head><body>
<div class="box">
  <h2>تعديل خبر</h2>
  <?php if ($ok): ?><div class="ok"><?= e($ok) ?></div><?php endif; ?>
  <?php if ($errors): ?><div class="err"><?php foreach($errors as $e) echo '<div>• '.e($e).'</div>'; ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <label>العنوان</label>
    <input type="text" name="title" value="<?= e($news['title']) ?>">

    <label>الفئة</label>
    <select name="category_id">
      <?php foreach($cats as $c): ?>
        <option value="<?= e($c['id']) ?>" <?= ($news['category_id']==$c['id'])?'selected':'' ?>>
          <?= e($c['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>التفاصيل</label>
    <textarea name="details" rows="6"><?= e($news['details']) ?></textarea>

    <label>صورة (اختياري لاستبدالها)</label>
    <?php if ($news['image_path']): ?>
      <div><img class="thumb" src="<?= e($news['image_path']) ?>" alt=""></div>
    <?php endif; ?>
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp">

    <button type="submit">حفظ التعديلات</button>
  </form>

  <p><a href="/web2-practical/news_app/news/list.php">عودة لقائمة أخباري</a></p>
</div>
</body></html>
