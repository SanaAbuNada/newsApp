<?php
require_once __DIR__ . '/../helpers.php';   // فيه session_start() ودالة e()
require_once __DIR__ . '/../config/db.php'; // الاتصال بقاعدة البيانات

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 1) قراءة المدخلات
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';

  // 2) تحقق بسيط
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'البريد غير صالح';
  if ($pass === '')                               $errors[] = 'كلمة المرور مطلوبة';

  // 3) محاولة تسجيل الدخول
  if (!$errors) {
    $q = $conn->prepare("SELECT id, name, password_hash FROM users WHERE email=? LIMIT 1");
    $q->bind_param("s", $email);
    $q->execute();
    $user = $q->get_result()->fetch_assoc();
    $q->close();

    if ($user && password_verify($pass, $user['password_hash'])) {
      $_SESSION['user_id']   = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      header("Location: /web2-practical/news_app/dashboard.php");
      exit;
    } else {
      $errors[] = 'بيانات الدخول غير صحيحة';
    }
  }
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>تسجيل الدخول</title>
  <link rel="stylesheet" href="/web2-practical/news_app/assets/style.css">
</head>
<body>
  <div class="box">
    <h2>تسجيل الدخول</h2>

    <?php if (!empty($_GET['registered'])): ?>
      <div class="ok">تم إنشاء الحساب بنجاح، تفضّل بتسجيل الدخول.</div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="err">
        <?php foreach ($errors as $e): ?>
          <div>• <?= e($e) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="post">
      <label>البريد الإلكتروني</label>
      <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required>

      <label>كلمة المرور</label>
      <input type="password" name="password" required>

      <button type="submit">دخول</button>
    </form>

    <p>ليس لديك حساب؟ <a class="btn btn-outline btn-sm" href="register.php">إنشاء حساب</a></p>
  </div>
</body>
</html>
