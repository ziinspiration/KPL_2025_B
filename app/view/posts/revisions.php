<div class="min-h-screen bg-gradient-to-br from-[#2B6AED]/5 to-[#5599F7]/10 py-12 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Article History</h1>
            <a href="<?= BASEURL ?>/posts/index"
                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-8">
            <div class="bg-gradient-to-r from-[#2B6AED] to-[#5599F7] px-6 py-4">
                <h5 class="text-xl font-semibold text-white">Current Article</h5>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Title</p>
                        <p class="text-gray-800 font-semibold">
                            <?php echo htmlspecialchars($data['article']['title']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Status</p>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#2B6AED]/10 text-[#2B6AED]">
                            <?php echo ucfirst($data['article']['status']); ?>
                        </span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Created At</p>
                        <p class="text-gray-800">
                            <?php echo date('F j, Y, g:i a', strtotime($data['article']['created_at'])); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Published At</p>
                        <p class="text-gray-800">
                            <?php echo $data['article']['published_at'] ? date('F j, Y, g:i a', strtotime($data['article']['published_at'])) : 'Not Published Yet'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Title</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Content</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Date</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Type</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $lastTitle = $data['article']['title'];
                        $lastContent = $data['article']['content'];
                        $lastStatus = $data['article']['status'];

                        $allHistory = array_merge(
                            [[
                                'title' => $data['article']['title'],
                                'content' => $data['article']['content'],
                                'date' => $data['article']['created_at'],
                                'status' => $data['article']['status'],
                                'type' => 'Created'
                            ]],
                            $data['revisions'],
                            $data['status_changes']
                        );

                        usort($allHistory, function ($a, $b) {
                            return strtotime($b['date']) - strtotime($a['date']);
                        });
                        ?>
                        <?php foreach ($allHistory as $entry): ?>
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    <?php
                                    if (isset($entry['title'])) {
                                        $lastTitle = $entry['title'];
                                        echo htmlspecialchars($entry['title']);
                                    } else {
                                        echo htmlspecialchars($lastTitle);
                                    }
                                    ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php
                                    if (isset($entry['content'])) {
                                        $lastContent = $entry['content'];
                                        echo nl2br(htmlspecialchars(substr($entry['content'], 0, 100) . (strlen($entry['content']) > 100 ? '...' : '')));
                                    } else {
                                        echo nl2br(htmlspecialchars(substr($lastContent, 0, 100) . (strlen($lastContent) > 100 ? '...' : '')));
                                    }
                                    ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo isset($entry['date']) ? date('F j, Y, g:i a', strtotime($entry['date'])) : 'No Date'; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    if (isset($entry['status'])) {
                                        $lastStatus = $entry['status'];
                                        echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">';
                                        echo ucfirst($entry['status']);
                                        echo '</span>';
                                    } else {
                                        echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">';
                                        echo ucfirst($lastStatus);
                                        echo '</span>';
                                    }
                                    ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $badgeColor = 'bg-blue-100 text-blue-800';
                                    if (isset($entry['type'])) {
                                        if ($entry['type'] === 'Revision') {
                                            $badgeColor = 'bg-yellow-100 text-yellow-800';
                                        } elseif ($entry['type'] === 'Status Change') {
                                            $badgeColor = 'bg-purple-100 text-purple-800';
                                        }
                                    }
                                    ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $badgeColor; ?>">
                                        <?php echo htmlspecialchars($entry['type'] ?? 'Unknown'); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>