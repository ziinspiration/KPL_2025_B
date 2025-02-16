<div class="min-h-screen bg-gradient-to-br from-[#2B6AED]/5 to-[#5599F7]/10 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-[#2B6AED] to-[#5599F7] opacity-90"></div>
                <div class="relative p-8">
                    <h2 class="text-3xl font-bold text-center text-white">Create New Article</h2>
                    <p class="mt-2 text-center text-white/80">Share your insights with the Technovation community</p>
                </div>
                <div
                    class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQ0MCIgaGVpZ2h0PSI2NDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PGxpbmVhckdyYWRpZW50IHgxPSIwIiB5MT0iMCIgeDI9IjEiIHkyPSIxIiBpZD0iZyI+PHN0b3Agc3RvcC1jb2xvcj0iI2ZmZiIgc3RvcC1vcGFjaXR5PSIwLjEiIG9mZnNldD0iMCUiLz48c3RvcCBzdG9wLWNvbG9yPSIjZmZmIiBzdG9wLW9wYWNpdHk9IjAiIG9mZnNldD0iMTAwJSIvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjxwYXRoIGZpbGw9InVybCgjZykiIGQ9Ik0wIDBoMTQ0MHY2NEwwIDY0MHoiLz48L3N2Zz4=')] opacity-10">
                </div>
            </div>

            <?php if (isset($data['error_message'])) : ?>
                <div class="mx-8 mt-6 p-4 bg-red-50 rounded-xl border-l-4 border-red-500 flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-700"><?php echo htmlspecialchars($data['error_message']); ?></p>
                </div>
            <?php endif; ?>

            <form action="<?= htmlspecialchars(BASEURL) ?>/posts/processCreate" method="POST"
                enctype="multipart/form-data" class="p-8">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <div class="space-y-6">
                    <div class="group">
                        <label
                            class="inline-block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-[#2B6AED]">Title</label>
                        <input type="text" name="title" required maxlength="255"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#5599F7] focus:border-transparent outline-none transition duration-200"
                            placeholder="What's your article about?">
                    </div>

                    <div class="group">
                        <label
                            class="inline-block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-[#2B6AED]">Content</label>
                        <textarea name="content" required rows="8"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#5599F7] focus:border-transparent outline-none transition duration-200 resize-y"
                            placeholder="Start writing your amazing article..."></textarea>
                    </div>

                    <div class="group">
                        <label
                            class="inline-block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-[#2B6AED]">Keywords</label>
                        <input type="text" name="keywords"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#5599F7] focus:border-transparent outline-none transition duration-200"
                            placeholder="Add keywords to help readers find your article">
                    </div>

                    <div class="group">
                        <label class="inline-block text-sm font-semibold text-gray-700 mb-2">Cover Image</label>
                        <div class="relative group cursor-pointer">
                            <input type="file" name="image" accept="image/*" onchange="previewImage(event)"
                                class="hidden" id="image-upload">
                            <label for="image-upload" class="block">
                                <div
                                    class="p-4 border-2 border-dashed border-gray-300 rounded-xl group-hover:border-[#5599F7] transition-colors duration-200">
                                    <div class="max-w-xs mx-auto flex flex-col items-center gap-3 py-4">
                                        <div
                                            class="w-16 h-16 rounded-full bg-[#2B6AED]/10 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-[#2B6AED]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm font-medium text-[#2B6AED]">Click to upload image</p>
                                            <p class="text-xs text-gray-500 mt-1">SVG, PNG, JPG or GIF</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <div class="mt-4">
                                <img id="imagePreview" src="#" class="hidden max-h-48 mx-auto rounded-lg shadow-lg"
                                    alt="Preview">
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-6">
                        <button type="submit"
                            class="flex-1 bg-gradient-to-r from-[#2B6AED] to-[#5599F7] text-white px-8 py-3 rounded-xl hover:shadow-lg hover:from-[#2B6AED] hover:to-[#2B6AED] transition duration-200 font-semibold text-center">
                            Publish Article
                        </button>
                        <a href="<?= htmlspecialchars(BASEURL) ?>/home"
                            class="flex-1 bg-gray-100 text-gray-700 px-8 py-3 rounded-xl hover:bg-gray-200 transition duration-200 font-semibold text-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const preview = document.getElementById('imagePreview');
        const file = event.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        } else {
            preview.src = '#';
            preview.classList.add('hidden');
        }
    }
</script>