<?php
class Home_model
{
    private $db;

    public function __construct()
    {
        error_log("Home_model::__construct() - Membuat instance Database");
        $this->db = new Database();
    }

    public function getPublishedPosts()
    {
        error_log("Home_model::getPublishedPosts() - Mengambil semua post yang dipublikasikan");
        $this->db->query("
            SELECT p.*, u.username, u.fullname,
            (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count
            FROM posts p
            JOIN users u ON p.user_id = u.id
            WHERE p.status = 'published'
            ORDER BY p.created_at DESC
        ");
        $result = $this->db->resultSet();
        error_log("Home_model::getPublishedPosts() - Jumlah post: " . count($result));
        return $result;
    }

    public function getPostBySlug($slug)
    {
        error_log("Home_model::getPostBySlug() - Mengambil post dengan slug: " . $slug);
        $this->db->query("
            SELECT p.*, u.username, u.fullname,
            (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count
            FROM posts p
            JOIN users u ON p.user_id = u.id
            WHERE p.slug = :slug
        ");
        $this->db->bind(':slug', $slug);
        $result = $this->db->single();
        error_log("Home_model::getPostBySlug() - Hasil query: " . print_r($result, true));
        return $result;
    }

    public function getCommentsByPostId($post_id)
    {
        error_log("Home_model::getCommentsByPostId() - Mengambil komentar untuk post dengan ID: " . $post_id);
        $this->db->query("
            SELECT c.*, u.username
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_id = :post_id
            ORDER BY c.created_at ASC
        ");
        $this->db->bind(':post_id', $post_id);
        $result = $this->db->resultSet();
        error_log("Home_model::getCommentsByPostId() - Jumlah komentar: " . count($result));
        return $result;
    }

    public function addComment($post_id, $user_id, $comment_text)
    {
        error_log("Home_model::addComment() - Menambahkan komentar baru ke post ID: " . $post_id);

        $comment_id = uniqid('comment_', true);

        $this->db->query("
            INSERT INTO comments (id, post_id, user_id, content, created_at)
            VALUES (:id, :post_id, :user_id, :content, NOW())
        ");
        $this->db->bind(':id', $comment_id);
        $this->db->bind(':post_id', $post_id);
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':content', $comment_text);

        error_log("Sebelum Execute");
        if ($this->db->execute()) {
            error_log("Setelah Execute Berhasil");
            error_log("Home_model::addComment() - Komentar berhasil ditambahkan");
            return true;
        } else {
            error_log("Setelah Execute Gagal");
            error_log("Home_model::addComment() - Gagal menambahkan komentar. Error: " . $this->db->error());
            return false;
        }
    }
}
