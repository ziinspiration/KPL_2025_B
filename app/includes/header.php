<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">MyBlog</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
                <form class="d-flex me-3" action="search.php" method="GET">
                    <input class="form-control me-2" type="search" name="q" placeholder="Search posts..." required>
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>

                <!-- Cek apakah user sudah login -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'author'): ?>
                                        <li><a class="dropdown-item" href="/KPL_2025_B/public/dashboard/index.php">Dashboard</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item" href="../app/auth/logout.php">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="../app/auth/login.php" class="btn btn-primary ms-auto">Login</a>
                <?php endif; ?>




            </div>
        </div>
    </nav>