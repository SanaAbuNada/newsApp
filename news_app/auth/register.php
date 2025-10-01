<?php
require_once __DIR__ . '/../config/db.php';   // الاتصال بقاعدة البيانات
require_once __DIR__ . '/../helpers.php';     // دالة e() فقط لو بدك

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 1) نجيب القيم من الفورم
  $name     = trim($_POST['name']  ?? '');
  $email    = trim($_POST['email'] ?? '');
  $pass     = $_POST['password']   ?? '';
  $confirm  = $_POST['confirm']    ?? '';

  // 2) تحقق بسيط
  if ($name === '')    $errors[] = 'الاسم مطلوب';
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'البريد غير صالح';
  if ($pass === '' || strlen($pass) < 6)   $errors[] = 'كلمة المرور 6 أحرف على الأقل';
  if ($pass !== $confirm)                  $errors[] = 'تأكيد كلمة المرور غير مطابق';

  // 3) لو ما في أخطاء: نتأكد البريد مش مستخدم
  if (!$errors) {
    $q = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $q->bind_param("s", $email);
    $q->execute();
    $q->store_result();
    if ($q->num_rows > 0) $errors[] = 'البريد مستخدم مسبقًا';
    $q->close();
  }

  // 4) لو لسه ما في أخطاء: نسجل المستخدم
  if (!$errors) {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $q = $conn->prepare("INSERT INTO users(name,email,password_hash) VALUES(?,?,?)");
    $q->bind_param("sss", $name, $email, $hash);
    if ($q->execute()) {
      header("Location: login.php?registered=1");
      exit;
    } else {
      $errors[] = 'تعذر إتمام التسجيل، حاول لاحقًا';
    }
    $q->close();
  }
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>تسجيل حساب</title>
  <link rel="stylesheet" href="/web2-practical/news_app/assets/style.css">
</head>
<body>
<div class="box">
  <h2>تسجيل حساب جديد</h2>

  <?php if ($errors): ?>
    <div class="err">
      <?php foreach ($errors as $e): ?>
        <div>• <?= e($e) ?></div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form method="post">
    <label>الاسم</label>
    <input type="text" name="name" value="<?= e($_POST['name'] ?? '') ?>" required>

    <label>البريد الإلكتروني</label>
    <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required>

    <label>كلمة المرور</label>
    <input type="password" name="password" required>

    <label>تأكيد كلمة المرور</label>
    <input type="password" name="confirm" required>

    <button type="submit">تسجيل</button>
  </form>

  <p>لديك حساب؟ <a class="btn btn-outline btn-sm" href="login.php">تسجيل الدخول</a></p>
</div>
</body>
</html>
