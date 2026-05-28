<?php
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    // ВОТ ЗДЕСЬ ТВОИ ДАННЫЕ: ЛОГИН admin, ПАРОЛЬ admin123
    if ($login === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit();
    } else {
        $error = 'Неверный логин или пароль';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход в админ-панель</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container" style="max-width: 500px; margin-top: 50px;">
    <h1 style="text-align: center;">Вход в админ-панель</h1>
    <?php if ($error): ?>
        <div class="error-message" style="text-align: center;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label for="login">Логин</label>
            <input type="text" id="login" name="login" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Войти</button>
    </form>
</div>
</body>
</html>