<div class="min-h-screen bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center p-4">
    <div class="flex w-full max-w-5xl">
        <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-lg">
            <h2 class="text-2xl font-bold mb-6">Sign In</h2>
            <p class="text-gray-500 text-sm mb-8">Sign in to continue with Technovation</p>
            <?php if (isset($data['error'])) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"><?= $data['error']; ?></span>
            </div>
            <?php endif; ?>
            <form action="<?= BASEURL; ?>/auth/processSignIn" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= $data['csrf_token']; ?>">
                <div class="relative">
                    <input type="text" name="username" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
                        placeholder="Username">
                </div>

                <div class="relative">
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
                        placeholder="Password">

                </div>

                <div class="flex justify-between items-center mb-8">
                    <div class="text-sm text-gray-600">
                        Don't have an account ? <a href="<?= BASEURL; ?>/auth/signup"
                            class="text-blue-600 font-medium">Sign up</a>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    Sign In
                </button>
            </form>
        </div>

        <div class="hidden lg:flex flex-1 ml-8 items-center justify-center">
            <div class="space-y-8 text-white">
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 w-64">
                    <div class="text-2xl font-bold">176,18</div>
                    <div class="mt-4 flex justify-between items-center">
                        <div class="w-32 h-8 bg-white/20 rounded-full"></div>
                        <div class="text-lg">+8</div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 w-64 text-gray-800">
                    <div class="space-y-3">
                        <div class="w-full h-2 bg-gray-100 rounded-full"></div>
                        <div class="w-3/4 h-2 bg-gray-100 rounded-full"></div>
                        <div class="w-1/2 h-2 bg-gray-100 rounded-full"></div>
                    </div>
                    <div class="mt-4 flex items-start gap-3">
                        <div>
                            <div class="font-medium">Your data, your rules</div>
                            <div class="text-sm text-gray-500">Your data belongs to you, and our encryption ensures
                                that.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>