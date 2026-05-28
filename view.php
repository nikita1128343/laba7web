<?php
session_start();

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $db_host = 'localhost';
        $db_user = 'u82460';
        $db_pass = '1450175';
        $db_name = 'u82460';
        try {
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            die("Внутренняя ошибка сервера. Пожалуйста, попробуйте позже.");
        }
    }
    return $pdo;
}

$pdo = getDB();
$stmt = $pdo->query("
    SELECT a.*, GROUP_CONCAT(l.name SEPARATOR ', ') AS languages 
    FROM application a 
    LEFT JOIN application_language al ON a.id = al.application_id 
    LEFT JOIN language l ON al.language_id = l.id 
    GROUP BY a.id 
    ORDER BY a.id DESC
");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Просмотр анкет — Задание 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Сохранённые анкеты</h1>
    <table>
        <thead><tr><th>ID</th><th>ФИО</th><th>Email</th><th>Телефон</th><th>Дата рождения</th><th>Пол</th><th>Языки</th><th>Биография</th></tr></thead>
        <tbody>
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
        </tbody>
    </table>

    <div class="back-link" style="margin-top:30px;">
        <a href="index.php">← Вернуться к форме</a>
        <?php if (isset($_SESSION['application_id'])): ?><a href="index.php?logout=1">Выйти</a><?php else: ?><a href="login.php">Войти для редактирования</a><?php endif; ?>
    </div>
</div>
</body>
</html>