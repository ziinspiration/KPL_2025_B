<?php
class Posts_model
{
    private $db;
    private $table = 'posts';

    public function __construct()
    {
        $this->db = new Database;
    }

    public function createPost($data)
    {
        $query = "INSERT INTO {$this->table} (id, user_id, title, slug, content, image, keywords, status, created_at, published_at)
                 VALUES (:id, :user_id, :title, :slug, :content, :image, :keywords, :status, :created_at, :published_at)";
        $this->db->query($query);
        $this->db->bind(':id', $data['id'], PDO::PARAM_STR);
        $this->db->bind(':user_id', $data['user_id'], PDO::PARAM_STR);
        $this->db->bind(':title', $data['title'], PDO::PARAM_STR);
        $this->db->bind(':slug', $data['slug'], PDO::PARAM_STR);
        $this->db->bind(':content', $data['content'], PDO::PARAM_STR);
        $this->db->bind(':image', $data['image'], PDO::PARAM_STR);
        $this->db->bind(':keywords', $data['keywords'], PDO::PARAM_STR);
        $this->db->bind(':status', $data['status'], PDO::PARAM_STR);
        $this->db->bind(':created_at', $data['created_at'], PDO::PARAM_STR);
        $this->db->bind(':published_at', $data['published_at'], PDO::PARAM_STR);

        try {
            if (!$this->db->execute()) {
                $errorInfo = $this->db->errorInfo();
                $errorMessage = isset($errorInfo[2]) ? $errorInfo[2] : 'Unknown error';
                error_log("Gagal membuat artikel. Error: " .  $errorMessage);
                return false;
            }
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getPostBySlug($slug)
    {
        $query = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $this->db->query($query);
        $this->db->bind(':slug', $slug, PDO::PARAM_STR);
        try {
            return $this->db->single();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getPostBySlugAndUserId($slug, $user_id)
    {
        $query = "SELECT * FROM {$this->table} WHERE slug = :slug AND user_id = :user_id LIMIT 1";
        $this->db->query($query);
        $this->db->bind(':slug', $slug, PDO::PARAM_STR);
        $this->db->bind(':user_id', $user_id, PDO::PARAM_STR);
        try {
            return $this->db->single();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getPostsByUserId($user_id)
    {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC";
        $this->db->query($query);
        $this->db->bind(':user_id', $user_id, PDO::PARAM_STR);
        try {
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updatePostStatus($id, $status, $user_id)
    {
        try {
            $this->db->beginTransaction();

            $query = "UPDATE " . $this->table . "
                      SET status = :status,
                          published_at = (CASE WHEN :status = 'published' THEN NOW() ELSE NULL END)
                      WHERE id = :id AND user_id = :user_id";

            $this->db->query($query);
            $this->db->bind(':status', $status, PDO::PARAM_STR);
            $this->db->bind(':id', $id, PDO::PARAM_STR);
            $this->db->bind(':user_id', $user_id, PDO::PARAM_STR);

            $this->db->debugDumpParams();

            if (!$this->db->execute()) {
                $errorInfo = $this->db->errorInfo();
                $errorMessage = isset($errorInfo[2]) ? $errorInfo[2] : 'Unknown error';
                error_log("Gagal memperbarui status artikel. Error: " .  $errorMessage);
                error_log("SQLSTATE: " . $errorInfo[0] . ", Error Code: " . $errorInfo[1]);
                $this->db->rollBack();
                return false;
            }

            $historyId = uniqid('history_', true);

            $query_insert = "INSERT INTO post_status_history (id, post_id, status, changed_at) VALUES (:history_id, :post_id, :status, NOW())";
            $this->db->query($query_insert);
            $this->db->bind(':history_id', $historyId, PDO::PARAM_STR);  // Bind ID unik
            $this->db->bind(':post_id', $id, PDO::PARAM_STR);
            $this->db->bind(':status', $status, PDO::PARAM_STR);

            $this->db->debugDumpParams();

            if (!$this->db->execute()) {
                $errorInfo = $this->db->errorInfo();
                $errorMessage = isset($errorInfo[2]) ? $errorInfo[2] : 'Unknown error';
                error_log("Gagal memasukkan riwayat status posting. Error: " . $errorMessage);
                error_log("SQLSTATE: " . $errorInfo[0] . ", Error Code: " . $errorInfo[1]);
                $this->db->rollBack();
                return false;
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("PDOException dalam updatePostStatus: " . $e->getMessage());
            return false;
        }
    }

    public function deletePost($id, $user_id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id AND user_id = :user_id";
        $this->db->query($query);
        $this->db->bind(':id', $id, PDO::PARAM_STR);
        $this->db->bind(':user_id', $user_id, PDO::PARAM_STR);

        try {
            return $this->db->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function saveRevision(
        $post_id,
        $user_id,
        $old_title,
        $old_content,
        $old_image,
        $new_title,
        $new_content,
        $new_keywords,
        $new_image,
        $new_status
    ) {
        error_log("saveRevision - Dipanggil dengan data:");
        error_log("saveRevision - post_id: " . $post_id);
        error_log("saveRevision - user_id: " . $user_id);
        error_log("saveRevision - old_title: " . $old_title);
        error_log("saveRevision - old_content: " . strlen($old_content) . " characters");
        error_log("saveRevision - old_image: " . $old_image);
        error_log("saveRevision - new_title: " . $new_title);
        error_log("saveRevision - new_content: " . strlen($new_content) . " characters");
        error_log("saveRevision - new_keywords: " . $new_keywords);
        error_log("saveRevision - new_image: " . $new_image);
        error_log("saveRevision - new_status: " . $new_status);


        $query = "INSERT INTO revisions (id, post_id, user_id, old_title, old_content, old_image, new_title, new_content, new_keywords, new_image, revised_at, type, new_status)
                  VALUES (:id, :post_id, :user_id, :old_title, :old_content, :old_image, :new_title, :new_content, :new_keywords, :new_image, NOW(), 'Content Change', :new_status)";

        $this->db->query($query);

        $this->db->bind(':id', uniqid('rev_', true), PDO::PARAM_STR);
        $this->db->bind(':post_id', $post_id, PDO::PARAM_STR);
        $this->db->bind(':user_id', $user_id, PDO::PARAM_STR);
        $this->db->bind(':old_title', $old_title, PDO::PARAM_STR);
        $this->db->bind(':old_content', $old_content, PDO::PARAM_STR);
        $this->db->bind(':old_image', $old_image, PDO::PARAM_STR);
        $this->db->bind(':new_title', $new_title, PDO::PARAM_STR);
        $this->db->bind(':new_content', $new_content, PDO::PARAM_STR);
        $this->db->bind(':new_keywords', $new_keywords, PDO::PARAM_STR);
        $this->db->bind(':new_image', $new_image, PDO::PARAM_STR);
        $this->db->bind(':new_status', $new_status, PDO::PARAM_STR);

        $this->db->debugDumpParams();

        try {
            $result = $this->db->execute();
            if ($result) {
                error_log("saveRevision - Eksekusi query saveRevision berhasil!");
                return true;
            } else {
                $errorInfo = $this->db->errorInfo();
                $errorMessage = isset($errorInfo[2]) ? $errorInfo[2] : 'Unknown error';
                error_log("saveRevision - Eksekusi query saveRevision gagal. Error: " .  $errorMessage);
                error_log("saveRevision - SQLSTATE: " . $errorInfo[0] . ", Error Code: " . $errorInfo[1]);
                return false;
            }
        } catch (PDOException $e) {
            error_log("saveRevision - PDOException dalam saveRevision: " . $e->getMessage());
            return false;
        }
    }

    public function updatePost($id, $user_id, $title, $content, $keywords, $image)
    {
        $query = "UPDATE {$this->table}
                  SET title = :title,
                      content = :content,
                      keywords = :keywords,
                      image = :image
                  WHERE id = :id AND user_id = :user_id";

        $this->db->query($query);
        $this->db->bind(':title', $title, PDO::PARAM_STR);
        $this->db->bind(':content', $content, PDO::PARAM_STR);
        $this->db->bind(':keywords', $keywords, PDO::PARAM_STR);
        $this->db->bind(':image', $image, PDO::PARAM_STR);
        $this->db->bind(':id', $id, PDO::PARAM_STR);
        $this->db->bind(':user_id', $user_id, PDO::PARAM_STR);

        $this->db->debugDumpParams();

        try {
            if (!$this->db->execute()) {
                $errorInfo = $this->db->errorInfo();
                $errorMessage = isset($errorInfo[2]) ? $errorInfo[2] : 'Unknown error';
                error_log("Gagal update data artikel. Error: " .  $errorMessage);
                return false;
            }
            return $this->db->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getRevisionsByPostId($post_id)
    {
        $query = "SELECT old_title AS title, old_content AS content, revised_at AS date, 'Revision' AS type FROM revisions WHERE post_id = :post_id ORDER BY revised_at DESC";
        $this->db->query($query);
        $this->db->bind(':post_id', $post_id, PDO::PARAM_STR);

        try {
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getStatusChangesByPostId($post_id)
    {
        $query = "SELECT status, changed_at AS date, 'Status Change' AS type FROM post_status_history WHERE post_id = :post_id ORDER BY changed_at DESC";
        $this->db->query($query);
        $this->db->bind(':post_id', $post_id, PDO::PARAM_STR);

        try {
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getPostByIdAndUserId($id, $user_id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id AND user_id = :user_id LIMIT 1";
        $this->db->query($query);
        $this->db->bind(':id', $id, PDO::PARAM_STR);
        $this->db->bind(':user_id', $user_id, PDO::PARAM_STR);
        try {
            return $this->db->single();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
