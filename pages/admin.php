<?php

include_once("../includes/utils.php");
include_once("../includes/header/header.php");

if (!isset($_SESSION['user_id']) || $_SESSION['privileges'] !== 'admin') {
    redirect("../index.php?page=home");
    exit();
}

$pdo = connect_db();

$tables = $pdo -> query("SHOW TABLES") -> fetchAll(PDO::FETCH_COLUMN);
$selected_table = $_GET['table'] ?? $tables[0] ?? null;
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $selected_table) {
    if (isset($_POST['add'])) {
        $fields = array_keys($_POST['fields']);
        $placeholders = array_map(fn($f) => "?", $fields);
        $values = array_values($_POST['fields']);

        $sql = "INSERT INTO `$selected_table` (`" . implode('`,`', $fields) . "`) VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $pdo  ->  prepare($sql);
        $stmt  ->  execute($values);

        $message = "Row added!";
    }

    if (isset($_POST['edit'])) {
        $fields = array_keys($_POST['fields']);
        $values = array_values($_POST['fields']);

        $set = implode(', ', array_map(fn($f) => "`$f` = ?", $fields));

        $pk = $_POST['pk'];
        $pk_val = $_POST['pk_val'];

        $sql = "UPDATE `$selected_table` SET $set WHERE `$pk` = ?";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute([...$values, $pk_val]);

        $message = "Row updated!";
    }

    if (isset($_POST['delete'])) {
        $pk = $_POST['pk'];
        $pk_val = $_POST['pk_val'];

        $sql = "DELETE FROM `$selected_table` WHERE `$pk` = ?";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute([$pk_val]);

        $message = "Row deleted!";
    }
}

$columns = [];
$rows = [];
$primary_key = null;

if ($selected_table) {
    $columns = $pdo -> query("DESCRIBE `$selected_table`") -> fetchAll(PDO::FETCH_ASSOC);

    foreach ($columns as $col) {
        if ($col['Key'] === 'PRI') {
            $primary_key = $col['Field'];
        }
    }

    $rows = $pdo -> query("SELECT * FROM `$selected_table`") -> fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <br><br><br><br>

    <div class="admin-container">
        <h1>Database Management</h1>
        <?php if ($message): ?>
            <div class="admin-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="get" class="table-select-form">
            <label for="table">Select Table:</label>
            <select name="table" id="table" onchange="this.form.submit()">
                <?php foreach ($tables as $table): ?>
                    <option value="<?php echo htmlspecialchars($table); ?>" <?php if ($table == $selected_table) echo "selected"; ?>>
                        <?php echo htmlspecialchars($table); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if ($selected_table): ?>
            <h2>Table: <?php echo htmlspecialchars($selected_table); ?></h2>
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <?php foreach ($columns as $col): ?>
                                <th><?php echo htmlspecialchars($col['Field']); ?></th>
                            <?php endforeach; ?>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                        <form method="post">
                            <?php foreach ($columns as $col): ?>
                                <td>
                                    <input type="text" name="fields[<?php echo htmlspecialchars($col['Field']); ?>]"
                                        value="<?php echo htmlspecialchars($row[$col['Field']]); ?>"
                                        <?php if ($col['Field'] === $primary_key) echo 'readonly'; ?>>
                                </td>
                            <?php endforeach; ?>

                            <td>
                                <input type="hidden" name="pk" value="<?php echo htmlspecialchars($primary_key); ?>">
                                <input type="hidden" name="pk_val" value="<?php echo htmlspecialchars($row[$primary_key]); ?>">
                                <button type="submit" name="edit" class="admin-btn edit">Edit</button>
                                <button type="submit" name="delete" class="admin-btn delete" onclick="return confirm('Delete this row?')">Delete</button>
                            </td>
                        </form>
                        </tr>
                    <?php endforeach; ?>
                        <tr>
                        <form method="post">
                            <?php foreach ($columns as $col): ?>
                                <td>
                                    <input type="text" name="fields[<?php echo htmlspecialchars($col['Field']); ?>]" value="">
                                </td>
                            <?php endforeach; ?>

                            <td>
                                <button type="submit" name="add" class="admin-btn add">Add</button>
                            </td>
                        </form>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <br><br><br><br>
</body>
</html>

<?php 

include_once("../includes/footer/footer.php"); 

?>