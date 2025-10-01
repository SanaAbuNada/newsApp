<?php
require_once __DIR__ . '/../helpers.php';  require_login();
require_once __DIR__ . '/../config/db.php';

$errors = [];
$ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');

  if ($name === '') {
    $errors[] = 'اسم الفئة مطلوب';
  }

  // بنشوف اذا فيه ىكرار او لا
  if (!$errors) {
    $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ? LIMIT 1");
    $stmt->bind_param("s", $name);
    $stmt->execute(); $stmt->store_result();
    if ($stmt->num_rows > 0) {
      $errors[] = 'الفئة موجودة مسبقًا';
    }
    $stmt->close();
  }

  // إدخال
  if (!$errors) {
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    if ($stmt->execute()) {
      $ok = 'تمت إضافة الفئة بنجاح';
      $_POST['name'] = ''; //هنا بنفرغ التسكت فيلد 
    } else {
      $errors[] = 'حدث خطأ أثناء الإضافة';
    }
    $stmt->close();
  }
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>إضافة فئة</title>
  <link rel="stylesheet" href="/web2-practical/news_app/assets/style.css">
</head>
<body>
  <div class="box">
    <h2>إضافة فئة</h2>

    <?php if ($ok): ?><div class="ok"><?= e($ok) ?></div><?php endif; ?>
    <?php if ($errors): ?>
      <div class="err"><?php foreach($errors as $e) echo '<div>• '.e($e).'</div>'; ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <label>اسم الفئة</label>
      <input type="text" name="name" value="<?= e($_POST['name'] ?? '') ?>" required>
      <button type="submit">حفظ</button>
    </form>

    <p>
      <a href="/web2-practical/news_app/categories/list.php">عرض كل الفئات</a> |
      <a href="/web2-practical/news_app/dashboard.php">لوحة التحكم</a>
    </p>
  </div>
</body>
</html>
