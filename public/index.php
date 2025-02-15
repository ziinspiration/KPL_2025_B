<?php
session_start();
require_once '../app/config/config.php';
require_once '../app/core/database.php';
require_once '../app/core/functions.php';

$conn = getConnection();
$stmt = $conn->query("
    SELECT p.*, u.username, u.fullname,
    (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count 
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    WHERE p.status = 'published' 
    ORDER BY p.created_at DESC
");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../app/includes/header.php';


if (!isset($_SESSION['user_id'])) {
    echo "Session tidak terbaca! Login ulang.";
} else {
    echo "User ID: " . $_SESSION['user_id'];
}

?>

<!-- Hero Section -->
<div class="hero-section py-5 bg-primary text-white mb-5">
    <div class="container">

        <h1>Selamat datang di Website</h1>
        <?php if (isset($_SESSION['username'])): ?>
            <p>Halo, <?php echo $_SESSION['username']; ?>!</p>
        <?php endif; ?>
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4">Welcome to Our Blog</h1>
                <p class="lead">Discover amazing articles about programming, web development, and technology.</p>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_URL; ?>auth/login.php" class="btn btn-light btn-lg">Login to Write</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>posts/create.php" class="btn btn-light btn-lg">Write Article</a>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <img src="<?php echo BASE_URL; ?>assets/images/hero-image.svg" alt="Blog Hero" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Featured Post -->
    <?php if (!empty($posts)): $featured = $posts[0]; ?>
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-4">Featured Post</h2>
                <div class="card">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <?php if ($featured['image']): ?>
                                <img src="<?php echo BASE_URL . 'uploads/' . $featured['image']; ?>"
                                    class="img-fluid rounded-start" alt="<?php echo $featured['title']; ?>"
                                    style="height: 400px; object-fit: cover;">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/800x400"
                                    class="img-fluid rounded-start" alt="Featured Post">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <h2 class="card-title"><?php echo $featured['title']; ?></h2>
                                <p class="card-text">
                                    <?php echo substr(strip_tags($featured['content']), 0, 300); ?>...
                                </p>
                                <div class="mb-3">
                                    <?php foreach (explode(',', $featured['keywords']) as $keyword): ?>
                                        <span class="badge bg-secondary"><?php echo trim($keyword); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <p class="text-muted">
                                    By <?php echo $featured['fullname']; ?> |
                                    <?php echo date('F j, Y', strtotime($featured['created_at'])); ?> |
                                    <?php echo $featured['comment_count']; ?> comments
                                </p>
                                <a href="<?php echo BASE_URL; ?>app/posts/view.php?id=<?php echo $post['id']; ?>"
                                    class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Latest Posts Grid -->
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Latest Posts</h2>
        </div>
        <?php
        $recent_posts = array_slice($posts, 1);
        foreach ($recent_posts as $post):
        ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if ($post['image']): ?>
                        <img src="<?php echo BASE_URL . 'uploads/' . $post['image']; ?>"
                            class="card-img-top" alt="<?php echo $post['title']; ?>"
                            style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/400x200"
                            class="card-img-top" alt="Post Image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $post['title']; ?></h5>
                        <p class="card-text">
                            <?php echo substr(strip_tags($post['content']), 0, 150); ?>...
                        </p>
                        <div class="mb-3">
                            <?php foreach (explode(',', $post['keywords']) as $keyword): ?>
                                <span class="badge bg-secondary"><?php echo trim($keyword); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                By <?php echo $post['fullname']; ?> |
                                <?php echo date('M j, Y', strtotime($post['created_at'])); ?> |
                                <?php echo $post['comment_count']; ?> comments
                            </small>
                            <a href="view.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-primary">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="row mt-4 mb-5">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page"></li>


                    <li class=" page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>About MyBlog</h5>
                <p>A platform for sharing knowledge and experiences in programming, web development, and technology.</p>
            </div>
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo BASE_URL; ?>about.php" class="text-white">About Us</a></li>
                    <li><a href="<?php echo BASE_URL; ?>contact.php" class="text-white">Contact</a></li>
                    <li><a href="<?php echo BASE_URL; ?>privacy.php" class="text-white">Privacy Policy</a></li>
                    <li><a href="<?php echo BASE_URL; ?>terms.php" class="text-white">Terms of Service</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Connect With Us</h5>
                <div class="social-links">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-12 text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> MyBlog. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
</body>

</html>