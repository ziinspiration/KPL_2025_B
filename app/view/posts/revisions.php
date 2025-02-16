<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <h1>Riwayat Artikel</h1>

    <!-- ✅ Info Artikel Utama -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Artikel Saat Ini</h5>
            <p><strong>Judul:</strong> <?php echo htmlspecialchars($data['article']['title']); ?></p>
            <p><strong>Dibuat Pada:</strong>
                <?php echo date('F j, Y, g:i a', strtotime($data['article']['created_at'])); ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($data['article']['status']); ?></p>
            <p><strong>Diterbitkan Pada:</strong>
                <?php echo $data['article']['published_at'] ? date('F j, Y, g:i a', strtotime($data['article']['published_at'])) : 'Belum Diterbitkan'; ?>
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
            <?php
            $lastTitle = $data['article']['title'];
            $lastContent = $data['article']['content'];
            $lastStatus = $data['article']['status'];

            $allHistory = array_merge(
                [
                    [
                        'title' => $data['article']['title'],
                        'content' => $data['article']['content'],
                        'date' => $data['article']['created_at'],
                        'status' => $data['article']['status'],
                        'type' => 'Created'
                    ]
                ],
                $data['revisions'],
                $data['status_changes']
            );

            usort($allHistory, function ($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
            ?>
            <?php foreach ($allHistory as $entry): ?>
                <tr>
                    <td>
                        <?php
                        if (isset($entry['title'])) {
                            $lastTitle = $entry['title'];
                            echo htmlspecialchars($entry['title']);
                        } else {
                            echo htmlspecialchars($lastTitle);
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if (isset($entry['content'])) {
                            $lastContent = $entry['content'];
                            echo nl2br(htmlspecialchars($entry['content']));
                        } else {
                            echo nl2br(htmlspecialchars($lastContent));
                        }
                        ?>
                    </td>
                    <td><?php echo isset($entry['date']) ? date('F j, Y, g:i a', strtotime($entry['date'])) : 'No Date'; ?>
                    </td>
                    <td>
                        <?php
                        if (isset($entry['status'])) {
                            $lastStatus = $entry['status'];
                            echo ucfirst($entry['status']);
                        } else {
                            echo ucfirst($lastStatus);
                        }
                        ?>
                    </td>
                    <td>
                        <span
                            class="badge bg-<?php
                                            echo isset($entry['type']) && $entry['type'] === 'Revision' ? 'warning' : ($entry['type'] === 'Status Change' ? 'primary' : 'info'); ?>">
                            <?php echo htmlspecialchars($entry['type'] ?? 'Unknown'); ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?= BASEURL ?>/posts/index" class="btn btn-secondary">Kembali ke Dashboard</a>
</div>