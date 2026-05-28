<?php
session_start();
if (isset($_SESSION['application_id'])) {
    header('Location: index.php');
    exit();
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
$errors = [];
$login_input = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = trim($_POST['login'] ?? '');
    $password_input = $_POST['password'] ?? '';
    if (empty($login_input) || empty($password_input)) {
        $errors[] = 'Введите логин и пароль';
    } else {
        function getDB() {
            static $pdo = null;
            if ($pdo === null) {
                $pdo = new PDO("mysql:host=localhost;dbname=u82460;charset=utf8mb4", 'u82460', '1450175');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $pdo;
        }
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT id, password_hash FROM application WHERE login = ?");
        $stmt->execute([$login_input]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password_input, $user['password_hash'])) {
            $_SESSION['application_id'] = $user['id'];
            header('Location: index.php');
            exit();
        } else {
            $errors[] = 'Неверный логин или пароль';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход — Задание 6</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Вход в систему</h1>
    <p class="subtitle">Введите логин и пароль, которые были выданы при первой отправке формы</p>
    <?php if (!empty($errors)): ?><div class="messages"><?php foreach ($errors as $err) echo '<div class="error-message">' . htmlspecialchars($err) . '</div>'; ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group"><label>Логин</label><input type="text" name="login" value="<?= htmlspecialchars($login_input) ?>" required></div>
        <div class="form-group"><label>Пароль</label><input type="password" name="password" required></div>
        <button type="submit">Войти</button>
    </form>
    <div class="back-link"><a href="index.php">← Вернуться к форме</a><a href="view.php">📊 Просмотреть сохранённые анкеты</a></div>
    <p class="auth-hint">Нет аккаунта?<br>Заполните форму на главной странице — логин и пароль будут сгенерированы автоматически.</p>
</div>
</body>
</html>