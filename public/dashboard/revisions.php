<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../app/config/config.php';
require_once '../../app/core/database.php';

$conn = getConnection();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Ambil artikel utama
$stmt = $conn->prepare("SELECT title, content, created_at, status, published_at FROM posts WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header("Location: index.php");
    exit();
}

// Ambil data revisi artikel
$stmt = $conn->prepare("SELECT old_title AS title, old_content AS content, revised_at AS date, 'Revision' AS type FROM revisions WHERE post_id = ? ORDER BY revised_at DESC");
$stmt->execute([$id]);
$revisions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil riwayat perubahan status
$stmt = $conn->prepare("SELECT status, changed_at AS date, 'Status Change' AS type FROM post_status_history WHERE post_id = ? ORDER BY changed_at DESC");
$stmt->execute([$id]);
$status_changes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil riwayat pembuatan dan publikasi
$history = [
    [
        'title' => $article['title'],
        'content' => $article['content'],
        'date' => $article['created_at'],
        'status' => $article['status'],
        'type' => 'Created'
    ]
];

if ($article['published_at']) {
    $history[] = [
        'title' => $article['title'],
        'content' => $article['content'],
        'date' => $article['published_at'],
        'status' => 'Published',
        'type' => 'Published'
    ];
}

// Gabungkan semua riwayat
$all_history = array_merge($history, $revisions, $status_changes);

// Urutkan berdasarkan tanggal terbaru
usort($all_history, function ($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

// 🔥 Simpan nilai terakhir untuk mengisi perubahan yang tidak ada
$lastTitle = $article['title'];
$lastContent = $article['content'];
$lastStatus = $article['status'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Artikel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Riwayat Artikel</h1>

        <!-- ✅ Info Artikel Utama -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Artikel Saat Ini</h5>
                <p><strong>Judul:</strong> <?php echo htmlspecialchars($article['title']); ?></p>
                <p><strong>Dibuat Pada:</strong> <?php echo date('F j, Y, g:i a', strtotime($article['created_at'])); ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst($article['status']); ?></p>
                <p><strong>Diterbitkan Pada:</strong>
                    <?php echo $article['published_at'] ? date('F j, Y, g:i a', strtotime($article['published_at'])) : 'Belum Diterbitkan'; ?>
                </p>
            </div>
        </div>

        <!-- ✅ Tabel Riwayat -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Konten</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Tipe</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_history as $entry): ?>
                    <tr>
                        <td>
                            <?php
                            if (!empty($entry['title'])) {
                                $lastTitle = $entry['title']; // Perbarui judul terakhir yang diketahui
                            }
                            echo htmlspecialchars($lastTitle, ENT_QUOTES, 'UTF-8');
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($entry['content'])) {
                                $lastContent = $entry['content']; // Perbarui konten terakhir yang diketahui
                            }
                            echo nl2br(htmlspecialchars($lastContent, ENT_QUOTES, 'UTF-8'));
                            ?>
                        </td>
                        <td><?php echo isset($entry['date']) ? date('F j, Y, g:i a', strtotime($entry['date'])) : 'No Date'; ?></td>
                        <td>
                            <?php
                            if (!empty($entry['status'])) {
                                $lastStatus = $entry['status']; // Perbarui status terakhir yang diketahui
                            }
                            echo ucfirst($lastStatus);
                            ?>
                        </td>
                        <td>
                            <span class="badge bg-<?php
                                                    echo isset($entry['type']) && $entry['type'] === 'Revision' ? 'warning' : ($entry['type'] === 'Published' ? 'success' : ($entry['type'] === 'Status Change' ? 'primary' : 'info')); ?>">
                                <?php echo htmlspecialchars($entry['type'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="index.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
</body>

</html>