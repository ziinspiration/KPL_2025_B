<div class="bg-gradient-to-br from-[#2B6AED] via-[#5599F7] to-[#2B6AED]">
    <div class="container mx-auto px-4 py-16">
        <?php if (isset($data['post'])) : ?>
            <?php $post = $data['post']; ?>
            <div class="max-w-4xl mx-auto">
                <a href="<?php echo BASEURL; ?>/home"
                    class="inline-flex items-center text-white mb-8 hover:opacity-80 transition duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Insights
                </a>
                <div class="space-y-6 text-white">
                    <div class="flex items-center space-x-4">
                        <span class="px-4 py-1 bg-white/10 rounded-full text-sm font-medium">Tech Article</span>
                        <span class="text-gray-100">
                            <?php echo htmlspecialchars(date('F j, Y', strtotime($post['created_at']))); ?>
                        </span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </h1>

                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center">
                            <span class="text-xl font-bold">
                                <?php echo strtoupper(substr($post['fullname'], 0, 1)); ?>
                            </span>
                        </div>
                        <div>
                            <p class="font-semibold"><?php echo htmlspecialchars($post['fullname']); ?></p>
                            <p class="text-sm text-gray-100">Tech Innovator ·
                                <?php echo htmlspecialchars($post['comment_count']); ?> comments</p>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<div class="bg-gray-50 min-h-screen pb-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <?php if ($post['image']) : ?>
                <img src="<?php echo htmlspecialchars(BASEURL . '/uploads/' . $post['image']); ?>"
                    class="w-full h-[500px] object-cover rounded-2xl -mt-12 shadow-xl mb-12"
                    alt="<?php echo htmlspecialchars($post['title']); ?>">
            <?php endif; ?>

            <article class="bg-white rounded-2xl shadow-lg p-8 md:p-12 prose prose-lg max-w-none">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>

                <div class="flex flex-wrap gap-2 mt-8 pt-8 border-t">
                    <?php
                    $keywords = json_decode($post['keywords'], true);
                    if (is_array($keywords)) :
                        foreach ($keywords as $keyword) :
                    ?>
                            <span class="px-4 py-1 bg-blue-50 text-[#2B6AED] rounded-full text-sm font-medium">
                                <?php echo htmlspecialchars(trim($keyword)); ?>
                            </span>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </article>

            <section class="mt-16">
                <h2 class="text-2xl font-bold text-gray-800 mb-8">Discussion
                    (<?php echo htmlspecialchars($post['comment_count']); ?>)</h2>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <form action="<?php echo htmlspecialchars(BASEURL . '/article/addComment/' . $post['slug']); ?>"
                        method="POST" class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Add to the discussion</h3>
                        <textarea name="comment"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#2B6AED] focus:border-transparent"
                            rows="4" placeholder="Share your thoughts..." required></textarea>
                        <button type="submit"
                            class="mt-4 bg-[#2B6AED] text-white px-6 py-3 rounded-xl font-semibold hover:bg-[#5599F7] transition duration-300">
                            Post Comment
                        </button>
                    </form>
                <?php else: ?>
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 text-center">
                        <p class="text-gray-600 mb-4">Join the discussion by signing in to your account</p>
                        <a href="<?php echo htmlspecialchars(BASEURL . '/auth/signin'); ?>"
                            class="inline-block bg-[#2B6AED] text-white px-6 py-3 rounded-xl font-semibold hover:bg-[#5599F7] transition duration-300">
                            Sign In to Comment
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (isset($data['comments']) && !empty($data['comments'])): ?>
                    <div class="space-y-6">
                        <?php foreach ($data['comments'] as $comment): ?>
                            <div class="bg-white rounded-2xl shadow-lg p-6">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="w-10 h-10 bg-[#2B6AED] rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                        <?php echo strtoupper(substr($comment['username'], 0, 1)); ?>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-semibold text-gray-800">
                                                <?php echo htmlspecialchars($comment['username']); ?>
                                            </h4>
                                            <span class="text-sm text-gray-500">
                                                <?php echo date('F j, Y', strtotime($comment['created_at'])); ?>
                                            </span>
                                        </div>
                                        <p class="text-gray-700">
                                            <?php echo nl2br(htmlspecialchars($comment['content'] ?? 'No content available')); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
                        <p class="text-gray-600">No comments yet. Be the first to share your thoughts!</p>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</div>
<?php else : ?>
    <div class="container mx-auto px-4 py-16">
        <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Article Not Found</h2>
            <p class="text-gray-600 mb-6">The article you're looking for might have been moved or deleted.</p>
            <a href="<?php echo BASEURL; ?>/home"
                class="inline-block bg-[#2B6AED] text-white px-6 py-3 rounded-xl font-semibold hover:bg-[#5599F7] transition duration-300">
                Return to Home
            </a>
        </div>
    </div>
<?php endif; ?>