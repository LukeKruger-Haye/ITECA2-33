<?php

include_once("../includes/utils.php");

if (!isset($_SESSION['user_id'])) {
    redirect("../index.php?page=login");
}

include_once("../includes/header/header.php");

$user_id = $_SESSION['user_id'];

$pdo = connect_db();

$stmt = $pdo -> prepare("
    SELECT u.user_id, u.first_name, u.last_name
    FROM users u
    JOIN messages m ON u.user_id = m.sender_id
    WHERE m.receiver_id = ?
    UNION
    SELECT u.user_id, u.first_name, u.last_name
    FROM users u
    JOIN messages m ON u.user_id = m.receiver_id
    WHERE m.sender_id = ?
    GROUP BY user_id
");
$stmt -> execute([$user_id, $user_id]);
$senders = $stmt -> fetchAll(PDO::FETCH_ASSOC);

$chat_user_id = isset($_GET['chat_user']) ? (int)$_GET['chat_user'] : ($senders[0]['user_id'] ?? null);

$chat_history = [];
if ($chat_user_id) {
    $stmt = $pdo -> prepare("
        SELECT m.*, u.first_name, u.last_name
        FROM messages m
        JOIN users u ON u.user_id = m.sender_id
        WHERE (m.sender_id = ? AND m.receiver_id = ?)
           OR (m.sender_id = ? AND m.receiver_id = ?)
        ORDER BY m.date_created ASC
    ");
    $stmt -> execute([$user_id, $chat_user_id, $chat_user_id, $user_id]);
    $chat_history = $stmt -> fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="styles/messages.css">
</head>
<body>
    <br><br><br><br><br>

    <div class="messages-wrapper">
        <div class="messages-users">
            <ul>
                <?php foreach ($senders as $sender): ?>
                    <li class="<?php echo ($chat_user_id == $sender['user_id']) ? 'active' : ''; ?>" onclick="window.location.href='?chat_user=<?php echo $sender['user_id']; ?>'">
                        <a href="?chat_user=<?php echo $sender['user_id']; ?>" style="color:inherit;text-decoration:none;">
                            <?php echo htmlspecialchars($sender['first_name'] . ' ' . $sender['last_name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button id="new-chat-btn">+ New Chat</button>

            <div id="new-chat-modal">
                <div class="modal-content">
                    <button class="close-modal-btn" onclick="close_new_chat()">&times;</button>
                    <h3>Start New Chat</h3>
                    <input type="text" id="user-search" placeholder="Search users...">
                    <ul id="user-list">
                        <?php
                        $stmt = $pdo -> prepare("SELECT user_id, first_name, last_name FROM users WHERE user_id != ?");
                        $stmt -> execute([$user_id]);
                        $all_users = $stmt -> fetchAll(PDO::FETCH_ASSOC);

                        foreach ($all_users as $user): ?>
                            <li onclick="window.location.href='?chat_user=<?php echo $user['user_id']; ?>'">
                                <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="messages-chat">
            <div class="chat-history">
                <?php if ($chat_user_id && count($chat_history) > 0): ?>
                    <?php foreach ($chat_history as $msg): ?>
                        <div class="chat-message <?php echo ($msg['sender_id'] == $user_id) ? 'user' : 'other'; ?>">
                            <div class="chat-meta">
                                <?php echo htmlspecialchars($msg['first_name'] . ' ' . $msg['last_name']); ?> - 
                                <?php echo date('Y-m-d H:i', strtotime($msg['date_created'])); ?>
                            </div>
                            <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                        </div>
                    <?php endforeach; ?>
                <?php elseif ($chat_user_id): ?>
                    <p style="color:#888;">No messages yet.</p>
                <?php else: ?>
                    <p style="color:#888;">Select a conversation.</p>
                <?php endif; ?>
            </div>

            <?php if ($chat_user_id): ?>

            <form class="chat-form" action="../api/messages/send.php" method="POST" autocomplete="off">
                <input type="hidden" name="receiver_id" value="<?php echo $chat_user_id; ?>">
                <input type="text" name="message" placeholder="Type your message..." required>
                <button type="submit">Send</button>
            </form>

            <?php endif; ?>
        </div>
    </div>

    <script src="js/messages.js"></script>
</body>
</html>

<?php

include_once("../includes/footer/footer.php");

?>