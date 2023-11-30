<style>
    table {
        width: 100%; /* Задаем ширину таблицы */
        table-layout: fixed; /* Фиксируем ширину колонок */
        border-collapse: collapse; /* Убираем двойные границы */
    }
    th, td {
        border: 1px solid #ddd; /* Добавляем границы для ячеек */
        padding: 8px; /* Добавляем отступы в ячейках */
        text-align: left; /* Выравниваем текст по левому краю */
        word-wrap: break-word; /* Переносим слова на новую строку, если они не помещаются */
    }
    th {
        background-color: #f2f2f2; /* Цвет фона для заголовков */
    }

</style>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr class="user-row">
                <td class="userid"><?= htmlspecialchars($user['id']); ?></td>
                <td class="username"><?= htmlspecialchars($user['username']); ?></td>
                <td class="email"><?= htmlspecialchars($user['email']); ?></td>
                <td class="role" data-role-id="<?= htmlspecialchars($user['id_role']) ?>"><?= htmlspecialchars($user['role']); ?></td>
                <td>
                    <button class="editButton" data-user-id="<?= htmlspecialchars($user['id']); ?>">Edit Role</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

