<div class="min-h-screen bg-gradient-to-br from-[#2B6AED]/5 to-[#5599F7]/10 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-[#2B6AED] to-[#5599F7] opacity-90"></div>
                <div class="relative p-8">
                    <h2 class="text-3xl font-bold text-center text-white">Edit Article</h2>
                    <p class="mt-2 text-center text-white/80">Update your article content and media</p>
                </div>
                <div
                    class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQ0MCIgaGVpZ2h0PSI2NDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PGxpbmVhckdyYWRpZW50IHgxPSIwIiB5MT0iMCIgeDI9IjEiIHkyPSIxIiBpZD0iZyI+PHN0b3Agc3RvcC1jb2xvcj0iI2ZmZiIgc3RvcC1vcGFjaXR5PSIwLjEiIG9mZnNldD0iMCUiLz48c3RvcCBzdG9wLWNvbG9yPSIjZmZmIiBzdG9wLW9wYWNpdHk9IjAiIG9mZnNldD0iMTAwJSIvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjxwYXRoIGZpbGw9InVybCgjZykiIGQ9Ik0wIDBoMTQ0MHY2NEwwIDY0MHoiLz48L3N2Zz4=')] opacity-10">
                </div>
            </div>

            <form action="<?= htmlspecialchars(BASEURL) ?>/posts/processEdit" method="POST"
                enctype="multipart/form-data" class="p-8">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($data['csrf_token']) ?>">
                <input type="hidden" name="id" value="<?= htmlspecialchars($data['article']['id']) ?>">

                <div class="space-y-6">
                    <div class="group">
                        <label
                            class="inline-block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-[#2B6AED]">Title</label>
                        <input type="text" name="title" required
                            value="<?php echo htmlspecialchars($data['article']['title']); ?>"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#5599F7] focus:border-transparent outline-none transition duration-200">
                    </div>

                    <div class="group">
                        <label
                            class="inline-block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-[#2B6AED]">Content</label>
                        <textarea name="content" required rows="8"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#5599F7] focus:border-transparent outline-none transition duration-200 resize-y"><?php echo htmlspecialchars($data['article']['content']); ?></textarea>
                    </div>

                    <div class="group">
                        <label
                            class="inline-block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-[#2B6AED]">Keywords</label>
                        <input type="text" name="keywords"
                            value="<?php echo htmlspecialchars($data['article']['keywords_string']); ?>"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#5599F7] focus:border-transparent outline-none transition duration-200">
                    </div>

                    <div class="space-y-4">
                        <label class="inline-block text-sm font-semibold text-gray-700">Current Image</label>
                        <div class="flex justify-center">
                            <?php if (!empty($data['article']['image'])): ?>
                                <div class="relative group">
                                    <img src="<?= BASEURL . '/uploads/' . htmlspecialchars($data['article']['image']); ?>"
                                        alt="Current article image"
                                        class="w-auto max-h-48 rounded-lg shadow-lg group-hover:shadow-xl transition duration-200"
                                        onerror="this.onerror=null; this.src='<?= BASEURL ?>/public/img/no-image.jpg';">
                                </div>
                            <?php else: ?>
                                <div
                                    class="w-full max-w-xs p-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                                    <p class="text-gray-500 text-center">No Image Available</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="group">
                            <label class="inline-block text-sm font-semibold text-gray-700 mb-2">Change Image</label>
                            <div class="relative group cursor-pointer">
                                <input type="file" name="image" accept="image/*" class="hidden" id="image-upload">
                                <label for="image-upload" class="block">
                                    <div
                                        class="p-4 border-2 border-dashed border-gray-300 rounded-xl group-hover:border-[#5599F7] transition-colors duration-200">
                                        <div class="max-w-xs mx-auto flex flex-col items-center gap-3 py-4">
                                            <div
                                                class="w-12 h-12 rounded-full bg-[#2B6AED]/10 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-[#2B6AED]" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-sm font-medium text-[#2B6AED]">Click to change image</p>
                                                <p class="text-xs text-gray-500 mt-1">SVG, PNG, JPG or GIF</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-6">
                        <button type="submit"
                            class="flex-1 bg-gradient-to-r from-[#2B6AED] to-[#5599F7] text-white px-8 py-3 rounded-xl hover:shadow-lg hover:from-[#2B6AED] hover:to-[#2B6AED] transition duration-200 font-semibold text-center">
                            Save Changes
                        </button>
                        <a href="<?= BASEURL ?>/posts/index"
                            class="flex-1 bg-gray-100 text-gray-700 px-8 py-3 rounded-xl hover:bg-gray-200 transition duration-200 font-semibold text-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>