<div class="relative bg-gradient-to-br from-[#2B6AED] via-[#5599F7] to-[#2B6AED] overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,...')] opacity-10"></div>
    </div>
    <div class="relative container mx-auto px-4 py-20">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="md:w-1/2 mb-8 md:mb-0 space-y-6">
                <span class="px-4 py-2 bg-white/10 text-white rounded-full text-sm font-medium">Welcome to
                    Technovation</span>
                <h1 class="text-5xl md:text-6xl font-bold text-white leading-tight">
                    Where Innovation Meets Technology
                </h1>
                <p class="text-xl text-gray-100 leading-relaxed">
                    Dive into the future of tech with cutting-edge insights, expert perspectives, and breakthrough
                    innovations.
                </p>

                <?php if (!isset($_SESSION['user_id'])) : ?>
                <div class="flex space-x-4">
                    <a href="<?php echo BASEURL; ?>/auth/signin"
                        class="bg-white text-[#2B6AED] px-8 py-4 rounded-xl font-bold hover:bg-gray-100 transition duration-300 transform hover:-translate-y-1">
                        Join the Community
                    </a>
                    <a href="#latest-posts"
                        class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-xl font-bold hover:bg-white/10 transition duration-300">
                        Explore Articles
                    </a>
                </div>
                <?php else : ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'author') : ?>
                <div class="flex space-x-4">
                    <a href="<?php echo BASEURL; ?>/posts/create"
                        class="bg-white text-[#2B6AED] px-6 py-3 rounded-xl font-bold hover:bg-gray-100 transition duration-300">
                        Share Your Insights
                    </a>
                    <a href="<?php echo BASEURL; ?>/posts/index"
                        class="bg-[#2B6AED] text-white px-6 py-3 rounded-xl font-bold border-2 border-white hover:bg-[#5599F7] transition duration-300">
                        Your Dashboard
                    </a>
                    <a href="<?php echo BASEURL; ?>/auth/SignOut"
                        class="bg-danger text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-100 border-2 border-white transition duration-300">
                        Logout
                    </a>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Trending in Tech</h2>
            <div class="flex space-x-2">
                <span class="px-4 py-2 bg-blue-100 text-[#2B6AED] rounded-full text-sm font-medium">#AI</span>
                <span class="px-4 py-2 bg-blue-100 text-[#2B6AED] rounded-full text-sm font-medium">#Web3</span>
                <span
                    class="px-4 py-2 bg-blue-100 text-[#2B6AED] rounded-full text-sm font-medium">#CloudComputing</span>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($posts)) :
    $featured = $posts[0]; ?>
<div class="container mx-auto px-4 py-16">
    <div class="flex items-center space-x-2 mb-8">
        <div class="w-10 h-1 bg-[#2B6AED] rounded"></div>
        <h2 class="text-3xl font-bold text-gray-800">Featured Insight</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-[1.02] transition duration-300">
        <div class="flex flex-col md:flex-row">
            <div class="md:w-1/2">
                <?php if ($featured['image']) : ?>
                <img src="<?php echo BASEURL . '/uploads/' . $featured['image']; ?>"
                    class="w-full h-[500px] object-cover" alt="<?php echo $featured['title']; ?>">
                <?php else : ?>
                <img src="https://via.placeholder.com/800x500" class="w-full h-[500px] object-cover"
                    alt="Featured Tech Post">
                <?php endif; ?>
            </div>
            <div class="md:w-1/2 p-10 flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-4 mb-6">
                        <span
                            class="px-4 py-1 bg-blue-100 text-[#2B6AED] rounded-full text-sm font-medium">Featured</span>
                        <span
                            class="text-gray-500"><?php echo htmlspecialchars(date('F j, Y', strtotime($featured['created_at']))); ?></span>
                    </div>

                    <h2 class="text-3xl font-bold mb-6 text-gray-800 leading-tight">
                        <?php echo htmlspecialchars($featured['title']); ?>
                    </h2>

                    <p class="text-gray-600 leading-relaxed mb-6">
                        <?php echo substr(strip_tags($featured['content']), 0, 300); ?>...
                    </p>

                    <div class="flex flex-wrap gap-2 mb-6">
                        <?php
                            $keywords = json_decode($featured['keywords'], true);
                            if (is_array($keywords)) :
                                foreach ($keywords as $keyword) :
                            ?>
                        <span class="px-4 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                            <?php echo htmlspecialchars(trim($keyword)); ?>
                        </span>
                        <?php
                                endforeach;
                            endif;
                            ?>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-12 h-12 bg-[#2B6AED] rounded-full flex items-center justify-center text-white font-bold">
                            <?php echo strtoupper(substr($featured['fullname'], 0, 1)); ?>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">
                                <?php echo htmlspecialchars($featured['fullname']); ?></p>
                        </div>
                    </div>

                    <a href="<?php echo BASEURL; ?>/article/index/<?php echo $featured['slug']; ?>"
                        class="inline-flex items-center space-x-2 text-[#2B6AED] font-semibold hover:text-[#5599F7] transition duration-300">
                        <span>Read Article</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="container mx-auto px-4 pb-16">
    <div class="flex items-center space-x-2 mb-8">
        <div class="w-10 h-1 bg-[#2B6AED] rounded"></div>
        <h2 class="text-3xl font-bold text-gray-800">Latest Insights</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php
        $recent_posts = array_slice($posts, 1);
        foreach ($recent_posts as $post) :
        ?>
        <article
            class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-[1.02] transition duration-300">
            <?php if ($post['image']) : ?>
            <img src="<?php echo BASEURL . '/uploads/' . $post['image']; ?>" class="w-full h-56 object-cover"
                alt="<?php echo htmlspecialchars($post['title']); ?>">
            <?php else : ?>
            <img src="https://via.placeholder.com/400x300" class="w-full h-56 object-cover" alt="Tech Post">
            <?php endif; ?>

            <div class="p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <span class="text-sm text-gray-500">
                        <?php echo htmlspecialchars(date('M j, Y', strtotime($post['created_at']))); ?>
                    </span>
                </div>

                <h3 class="text-xl font-bold mb-4 text-gray-800 leading-tight">
                    <?php echo htmlspecialchars($post['title']); ?>
                </h3>

                <p class="text-gray-600 mb-6 line-clamp-3">
                    <?php echo substr(strip_tags($post['content']), 0, 150); ?>...
                </p>

                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 bg-[#2B6AED] rounded-full flex items-center justify-center text-white text-sm font-bold">
                            <?php echo strtoupper(substr($post['fullname'], 0, 1)); ?>

                        </div>
                        <span class="text-sm font-medium text-gray-700">
                            <?php echo htmlspecialchars($post['fullname']); ?>
                        </span>
                    </div>

                    <a href="<?php echo BASEURL; ?>/article/index/<?php echo $post['slug']; ?>"
                        class="flex items-center space-x-1 text-[#2B6AED] font-medium hover:text-[#5599F7] transition duration-300">
                        <span>Read More</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</div>

<div class="bg-gradient-to-r from-[#2B6AED] to-[#5599F7] py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Stay Updated with Tech Trends</h2>
        <p class="text-gray-100 mb-8 max-w-2xl mx-auto">
            Join our community of tech enthusiasts and get the latest insights delivered to your inbox.
        </p>
        <form class="max-w-md mx-auto flex gap-4">
            <input type="email" placeholder="Enter your email"
                class="flex-1 px-6 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-white">
            <button type="submit"
                class="bg-white text-[#2B6AED] px-6 py-3 rounded-xl font-bold hover:bg-gray-100 transition duration-300">
                Subscribe
            </button>
        </form>
    </div>
</div>

<div class="container mx-auto px-4 py-16">
    <div class="flex justify-center">
        <nav class="inline-flex rounded-xl shadow-sm overflow-hidden">
            <a href="#" class="px-4 py-2 bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 font-medium">
                Previous
            </a>
            <a href="#" class="px-4 py-2 bg-[#2B6AED] text-white border border-[#2B6AED] font-medium">
                1
            </a>
            <a href="#" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 font-medium">
                2
            </a>
            <a href="#" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 font-medium">
                3
            </a>
            <a href="#" class="px-4 py-2 bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 font-medium">
                Next
            </a>
        </nav>
    </div>
</div>