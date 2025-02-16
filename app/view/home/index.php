<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<?php $posts = $data['posts']; ?>

<div class="hero-section py-5 bg-primary text-white mb-5">
    <div class="container">
        <h1>Selamat datang di Website</h1>
        <?php if (isset($_SESSION['username'])) : ?>
            <p>Halo, <?php echo $_SESSION['username']; ?>!</p>
        <?php endif; ?>
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4">Welcome to Our Blog</h1>
                <p class="lead">Discover amazing articles about programming, web development, and technology.</p>
                <?php if (!isset($_SESSION['user_id'])) : ?>
                    <a href="<?php echo BASEURL; ?>/auth/signin" class="btn btn-light btn-lg">Login to Write</a>
                <?php else : ?>
                    <?php if (isset($_SESSION['role'])) : ?>
                        <?php if ($_SESSION['role'] == 'author') : ?>

                            <a href="<?php echo BASEURL; ?>/posts/create" class="btn btn-light btn-lg">Write Article</a>
                            <a href="<?php echo BASEURL; ?>/posts/index" class="btn btn-dark btn-lg">Go To Dashboard</a>
                            <a href="<?php echo BASEURL; ?>/auth/signout" class="btn btn-danger btn-lg">Logout</a>

                        <?php elseif ($_SESSION['role'] == 'reader') : ?>
                            <a href="<?php echo BASEURL; ?>/auth/signout" class="btn btn-danger btn-lg">Logout</a>
                        <?php else : ?>
                            <p>Unknown user role.</p>
                        <?php endif; ?>
                    <?php else : ?>
                        <p>Role is not set in session.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <img src="<?php echo BASEURL; ?>/assets/images/hero-image.svg" alt="Blog Hero" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<div class="container">
    <a href="<?= BASEURL; ?>/auth/SignOut"></a>
    <?php if (!empty($posts)) :
        $featured = $posts[0]; ?>
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-4">Featured Post</h2>
                <div class="card">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <?php if ($featured['image']) : ?>
                                <img src="<?php echo BASEURL . '/uploads/' . $featured['image']; ?>"
                                    class="img-fluid rounded-start" alt="<?php echo $featured['title']; ?>"
                                    style="height: 400px; object-fit: cover;">
                            <?php else : ?>
                                <img src="https://via.placeholder.com/800x400" class="img-fluid rounded-start"
                                    alt="Featured Post">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <h2 class="card-title"><?php echo htmlspecialchars($featured['title']); ?></h2>
                                <p class="card-text">
                                    <?php echo substr(strip_tags($featured['content']), 0, 300); ?>...
                                </p>
                                <div class="mb-3">
                                    <?php
                                    $keywords = json_decode($featured['keywords'], true);
                                    if (is_array($keywords)) :
                                        foreach ($keywords as $keyword) :
                                    ?>
                                            <span class="badge bg-secondary"><?php echo htmlspecialchars(trim($keyword)); ?></span>
                                    <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </div>
                                <p class="text-muted">
                                    By <?php echo htmlspecialchars($featured['fullname']); ?> |
                                    <?php echo htmlspecialchars(date('F j, Y', strtotime($featured['created_at']))); ?> |
                                    <?php echo htmlspecialchars($featured['comment_count']); ?> comments
                                </p>
                                <a href="<?php echo BASEURL; ?>/article/index/<?php echo $featured['slug']; ?>"
                                    class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Latest Posts</h2>
        </div>
        <?php
        $recent_posts = $posts; // Initialize to all posts
        if (count($posts) > 1) {
            $recent_posts = array_slice($posts, 1);
        }


        foreach ($recent_posts as $post) :
        ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if ($post['image']) : ?>
                        <img src="<?php echo BASEURL . '/uploads/' . $post['image']; ?>" class="card-img-top"
                            alt="<?php echo htmlspecialchars($post['title']); ?>" style="height: 200px; object-fit: cover;">
                    <?php else : ?>
                        <img src="https://via.placeholder.com/400x200" class="card-img-top" alt="Post Image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                        <p class="card-text">
                            <?php echo substr(strip_tags($post['content']), 0, 150); ?>...
                        </p>
                        <div class="mb-3">
                            <?php
                            $keywords = json_decode($post['keywords'], true);
                            if (is_array($keywords)) :
                                foreach ($keywords as $keyword) :
                            ?>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars(trim($keyword)); ?></span>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                By <?php echo htmlspecialchars($post['fullname']); ?> |
                                <?php echo htmlspecialchars(date('M j, Y', strtotime($post['created_at']))); ?> |
                                <?php echo htmlspecialchars($post['comment_count']); ?> comments
                            </small>
                            <a href="<?php echo BASEURL; ?>/article/index/<?php echo $post['slug']; ?>"
                                class="btn btn-sm btn-outline-primary">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row mt-4 mb-5">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>

                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>