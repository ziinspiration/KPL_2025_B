<div class="container mt-5">
    <?php if (isset($data['post'])) : ?>
        <?php $post = $data['post']; ?>
        <div class="card">
            <div class="card-header">
                <?php echo htmlspecialchars($post['title']); ?>
            </div>
            <div class="card-body">
                <?php if ($post['image']) : ?>
                    <img src="<?php echo htmlspecialchars(BASEURL . '/uploads/' . $post['image']); ?>" class="img-fluid mb-3"
                        alt="<?php echo htmlspecialchars($post['title']); ?>">
                <?php endif; ?>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                <p class="card-text">
                    <small class="text-muted">
                        By <?php echo htmlspecialchars($post['fullname']); ?> |
                        <?php echo htmlspecialchars(date('M j, Y', strtotime($post['created_at']))); ?> |
                        <?php echo htmlspecialchars($post['comment_count']); ?> comments
                    </small>
                </p>

                <h3 class="mt-5">Comments</h3>
                <?php if (isset($data['comments']) && !empty($data['comments'])): ?>
                    <?php foreach ($data['comments'] as $comment): ?>
                        <div class="border p-3 mb-3">
                            <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                            <p><?php echo nl2br(htmlspecialchars($comment['content'] ?? 'No content available')); ?></p>
                            <small class="text-muted">Posted on
                                <?php echo date('F j, Y, g:i a', strtotime($comment['created_at'])); ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No comments yet.</p>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <form action="<?php echo htmlspecialchars(BASEURL . '/article/addComment/' . $post['slug']); ?>"
                        method="POST">
                        <div class="mb-3">
                            <label for="comment" class="form-label">Leave a Comment:</label>
                            <textarea class="form-control" name="comment" id="comment" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                <?php else: ?>
                    <p><a href="<?php echo htmlspecialchars(BASEURL . '/auth/signin'); ?>">Login</a> to post a comment.</p>
                <?php endif; ?>

                <a href="<?php echo htmlspecialchars(BASEURL); ?>/home" class="btn btn-secondary mt-3">Back to Home</a>
            </div>
        </div>
    <?php else : ?>
        <p>Post not found.</p>
    <?php endif; ?>
</div>