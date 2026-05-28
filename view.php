<?php
session_start();
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO("mysql:host=localhost;dbname=u82460;charset=utf8mb4", 'u82460', '1450175');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}
$pdo = getDB();
$stmt = $pdo->query("SELECT a.*, GROUP_CONCAT(l.name SEPARATOR ', ') AS languages FROM application a LEFT JOIN application_language al ON a.id = al.application_id LEFT JOIN language l ON al.language_id = l.id GROUP BY a.id ORDER BY a.id DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Просмотр анкет — Задание 6</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Сохранённые анкеты</h1>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
        <tr><th>ID</th><th>ФИО</th><th>Email</th><th>Телефон</th><th>Дата рождения</th><th>Пол</th><th>Языки</th><th>Биография</th></tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['birth_date']) ?></td>
            <td><?= $row['gender'] === 'male' ? 'Мужской' : 'Женский' ?></td>
            <td><?= htmlspecialchars($row['languages'] ?? '—') ?></td>
            <td style="max-width:300px;"><?= nl2br(htmlspecialchars($row['biography'])) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <div class="back-link" style="margin-top:30px;">
        <a href="index.php">← Вернуться к форме</a>
        <?php if (isset($_SESSION['application_id'])): ?><a href="index.php?logout=1">Выйти</a><?php else: ?><a href="login.php">Войти для редактирования</a><?php endif; ?>
    </div>
</div>
</body>
</html>