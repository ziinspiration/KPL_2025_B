<?php
// app/posts/view.php
session_start();
require_once '../app/config/config.php';
require_once '../app/core/database.php';
require_once '../app/core/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Post not found!');
}

$conn = getConnection();
$post_id = $_GET['id'];

// Fetch post details
$stmt = $conn->prepare("SELECT p.*, u.username, u.fullname FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die('Post not found!');
}

// Fetch comments
$comment_stmt = $conn->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at ASC");
$comment_stmt->execute([$post_id]);
$comments = $comment_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI']; // Simpan halaman tujuan sebelum login
        header('Location: ' . BASE_URL . 'auth/login.php');
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $comment_text = trim($_POST['comment']);

    if (!empty($comment_text)) {
        $insert_stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
        if ($insert_stmt->execute([$post_id, $user_id, $comment_text])) {
            header("Location: view.php?id=$post_id");
            exit;
        } else {
            echo "Error: Unable to add comment.";
        }
    } else {
        echo "Error: Comment cannot be empty.";
    }
}

include '../app/includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1><?php echo htmlspecialchars($post['title']); ?></h1>
            <p class="text-muted">By <?php echo htmlspecialchars($post['fullname']); ?> | <?php echo date('F j, Y', strtotime($post['created_at'])); ?></p>
            <img src="<?php echo BASE_URL . 'uploads/' . htmlspecialchars($post['image']); ?>" class="img-fluid mb-4" alt="<?php echo htmlspecialchars($post['title']); ?>">
            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>

            <!-- Comments Section -->
            <h3 class="mt-5">Comments</h3>
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="border p-3 mb-3">
                        <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                        <p><?php echo nl2br(htmlspecialchars($comment['content'] ?? 'No content available')); ?></p>
                        <small class="text-muted">Posted on <?php echo date('F j, Y, g:i a', strtotime($comment['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No comments yet.</p>
            <?php endif; ?>


            <!-- Comment Form -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="view.php?id=<?php echo $post_id; ?>" method="POST">
                    <div class="mb-3">
                        <label for="comment" class="form-label">Leave a Comment:</label>
                        <textarea class="form-control" name="comment" id="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            <?php else: ?>
                <p><a href="../app/auth/login.php">Login</a> to post a comment.</p>
            <?php endif; ?>


            <!-- Withdraw Article Button (Only for Author) -->
            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'author' && $_SESSION['user_id'] == $post['user_id']): ?>
                <form action="withdraw_article.php" method="POST">
                    <input type="hidden" name="article_id" value="<?php echo $post['id']; ?>">
                    <button type="submit" class="btn btn-warning">Withdraw Article</button>
                </form>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include '../app/includes/footer.php'; ?>