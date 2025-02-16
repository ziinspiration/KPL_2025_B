<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div
                class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8 border-b border-gray-200 pb-6">
                <h1 class="text-4xl font-bold text-gray-800">
                    My Dashboard
                </h1>
                <a href="<?= BASEURL; ?>/posts/create"
                    class="px-6 py-3 bg-[#2B6AED] hover:bg-[#5599F7] text-white text-lg font-semibold rounded-xl transition-all transform hover:scale-105">
                    Create New Article
                </a>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 shadow-sm">
                    <?php echo htmlspecialchars($_SESSION['success_message']);
                    unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 shadow-sm">
                    <?php echo htmlspecialchars($_SESSION['error_message']);
                    unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-[#2B6AED] to-[#5599F7] text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Title</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Image</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Created At
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Published At
                            </th>
                            <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <?php foreach ($data['articles'] as $article): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-gray-800 font-medium">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($article['image'])): ?>
                                        <img src="<?= BASEURL . '/uploads/' . htmlspecialchars($article['image']); ?>"
                                            alt="Article image"
                                            class="w-28 h-20 object-cover rounded-lg shadow-sm hover:shadow-md transition-shadow"
                                            onerror="this.onerror=null; this.src='<?= BASEURL ?>/public/img/no-image.jpg';">
                                    <?php else: ?>
                                        <span class="text-gray-400 italic">No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="<?= htmlspecialchars(BASEURL) ?>/posts/updateStatus" method="POST"
                                        enctype="multipart/form-data" class="space-y-2">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($article['id']) ?>">
                                        <select name="status"
                                            class="w-full rounded-lg border-gray-300 focus:border-[#2B6AED] focus:ring focus:ring-[#5599F7] focus:ring-opacity-50">
                                            <option value="draft" <?= ($article['status'] == 'draft') ? 'selected' : '' ?>>
                                                Draft</option>
                                            <option value="published"
                                                <?= ($article['status'] == 'published') ? 'selected' : '' ?>>Published
                                            </option>
                                        </select>
                                        <button type="submit"
                                            class="w-full px-4 py-2 bg-[#5599F7] hover:bg-[#2B6AED] text-white font-medium rounded-lg transition-all transform hover:scale-105">
                                            Update
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <?php echo htmlspecialchars($article['created_at']); ?>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <?php echo $article['published_at'] ? date('F j, Y, g:i a', strtotime($article['published_at'])) : '-'; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-3">
                                        <a href="<?= BASEURL ?>/posts/edit/<?php echo htmlspecialchars($article['slug']); ?>"
                                            class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-all transform hover:scale-105">
                                            Edit
                                        </a>
                                        <a href="<?= BASEURL ?>/posts/revisions/<?php echo htmlspecialchars($article['slug']); ?>"
                                            class="px-5 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-all transform hover:scale-105">
                                            History
                                        </a>
                                        <a href="<?= BASEURL ?>/posts/delete/<?php echo htmlspecialchars($article['slug']); ?>"
                                            onclick="return confirm('Are you sure you want to delete this article?');"
                                            class="px-5 py-2 bg-rose-500 hover:bg-rose-600 text-white font-medium rounded-lg transition-all transform hover:scale-105">
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                <a href="<?= BASEURL ?>/home"
                    class="inline-flex px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-xl transition-all transform hover:scale-105">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>