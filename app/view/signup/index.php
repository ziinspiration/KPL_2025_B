<div class="min-h-screen bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center p-4">
    <div class="flex w-full max-w-5xl">
        <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-lg">
            <h2 class="text-2xl font-bold mb-6">Sign Up</h2>
            <p class="text-gray-500 text-sm mb-8">Start your creativity path with Technovation</p>
            <?php if (isset($data['error'])) : ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline"><?= $data['error']; ?></span>
                </div>
            <?php endif; ?>
            <form action="<?= BASEURL; ?>/auth/processSignUp" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= $data['csrf_token']; ?>">

                <div class="relative">
                    <input type="text" name="fullname" id="fullname" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
                        placeholder="Fullname">
                    <span id="fullnameIcon" class="absolute right-3 top-3.5 text-red-500">❌</span>
                </div>

                <div class="relative">
                    <input type="text" name="username" id="username" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
                        placeholder="Username">
                    <span id="usernameIcon" class="absolute right-3 top-3.5 text-red-500">❌</span>
                </div>

                <div class="relative">
                    <input type="email" name="email" id="email" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
                        placeholder="Email">
                    <span id="emailIcon" class="absolute right-3 top-3.5 text-red-500">❌</span>
                </div>

                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
                        placeholder="Password">
                </div>

                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center gap-2" id="lengthCheck">
                        <span id="lengthIcon">❌</span>
                        <span>Minimum 8 characters length (8-32)</span>
                    </div>
                    <div class="flex items-center gap-2" id="caseCheck">
                        <span id="caseIcon">❌</span>
                        <span>Lower and uppercase letter (A-Z)</span>
                    </div>
                    <div class="flex items-center gap-2" id="numberCheck">
                        <span id="numberIcon">❌</span>
                        <span>Letter mix number (0-9)</span>
                    </div>
                    <div class="flex items-center gap-2" id="symbolCheck">
                        <span id="symbolIcon">❌</span>
                        <span>Mix number (0-9) or symbol</span>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-8">
                    <div class="text-sm text-gray-600">
                        Already have an account ? <a href="<?= BASEURL; ?>/auth/signin"
                            class="text-blue-600 font-medium">Sign in</a>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    Sign Up
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