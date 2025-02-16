<?php

class Posts extends Controller
{
    private $posts_model;

    public function __construct()
    {
        $this->posts_model = $this->model('Posts_model');
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->requireAuthorRole();
    }

    private function requireAuthorRole()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
            $_SESSION['error_message'] = "Anda tidak memiliki izin untuk mengakses halaman ini.";
            header("Location: " . BASEURL . "/home");
            exit();
        }
    }

    public function index()
    {
        $this->requireAuthorRole();
        $user_id = $_SESSION['user_id'];
        $articles = $this->posts_model->getPostsByUserId($user_id);
        $data['title'] = "Dashboard - Articles";
        $data['articles'] = $articles;

        $this->view('template/header', $data);
        $this->view('posts/index', $data);
        $this->view('template/footer', $data);
    }

    public function create()
    {
        $this->requireAuthorRole();
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $data['title'] = "Create Article";
        $data['csrf_token'] = $_SESSION['csrf_token'];

        $this->view('template/header', $data);
        $this->view('posts/create', $data);
        $this->view('template/footer', $data);
    }

    private function generateSlug(string $title, string $separator = '-'): string
    {
        $title = trim($title);
        if (empty($title)) {
            return '';
        }

        $title = iconv('UTF-8', 'ASCII//TRANSLIT', $title);
        $title = preg_replace('/[^a-zA-Z0-9\s-]/', '', $title);
        $title = preg_replace('/\s+/', $separator, $title);
        $title = preg_replace('/-+/', $separator, $title);
        $title = strtolower($title);

        return trim($title, $separator);
    }

    public function processCreate()
    {
        $this->requireAuthorRole();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (
                !isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                $_SESSION['error_message'] = "Invalid security token. Please try again.";
                header("Location: " . BASEURL . "/posts/create");
                exit();
            }

            $title = $this->sanitizeInput($_POST['title']);
            $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
            $keywords = $this->sanitizeInput($_POST['keywords']);

            if (empty($title) || empty($content)) {
                $_SESSION['error_message'] = "Title and content are required.";
                header("Location: " . BASEURL . "/posts/create");
                exit();
            }

            if (strlen($title) > 255) {
                $_SESSION['error_message'] = "Title too long (max 255 characters).";
                header("Location: " . BASEURL . "/posts/create");
                exit();
            }

            $keywordsArray = array_map([$this, 'sanitizeInput'], explode(" ", $keywords));
            $keywordsJson = json_encode(array_filter($keywordsArray));

            $image = $this->uploadImage($_FILES['image']);
            if ($image === false) {
                $_SESSION['error_message'] = 'Gagal upload gambar atau format tidak sesuai.';
                header("Location: " . BASEURL . "/posts/create");
                exit();
            }

            $slug = $this->generateSlug($title);
            $existingSlug = $this->posts_model->getPostBySlug($slug);
            $originalSlug = $slug;
            $i = 1;

            while ($existingSlug) {
                $slug = $originalSlug . '-' . $i;
                $existingSlug = $this->posts_model->getPostBySlug($slug);
                $i++;
                if ($i > 100) {
                    $slug = $originalSlug . '-' . uniqid();
                    break;
                }
            }

            $data = [
                'id' => uniqid('post_', true),
                'user_id' => $_SESSION['user_id'],
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'image' => $image,
                'keywords' => $keywordsJson,
                'status' => 'draft',
                'created_at' => date('Y-m-d H:i:s'),
                'published_at' => null
            ];

            if ($this->posts_model->createPost($data)) {
                $_SESSION['success_message'] = "Artikel berhasil dibuat!";
                header("Location: " . BASEURL . "/posts/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Terjadi kesalahan saat menyimpan artikel.";
                header("Location: " . BASEURL . "/posts/create");
                exit();
            }
        } else {
            header("Location: " . BASEURL . "/posts/create");
            exit();
        }
    }

    private function uploadImage($file)
    {
        $this->requireAuthorRole();

        $target_dir = BASEPATH . "/public/uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $max_size = 5242880;

        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return false;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($mime_type, $allowed_mimes)) {
            error_log("Invalid mime type: " . $mime_type);
            return false;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowed_types)) {
            error_log("Invalid extension: " . $extension);
            return false;
        }

        if ($file['size'] > $max_size) {
            error_log("File too large: " . $file['size']);
            return false;
        }

        $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $target_file = $target_dir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $target_file)) {
            error_log("Failed to move uploaded file to: " . $target_file);
            return false;
        }

        return $filename;
    }

    private function sanitizeInput($data)
    {
        $data = trim($data);
        $data = strip_tags($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

    public function updateStatus()
    {
        $this->requireAuthorRole();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
            $id = $this->sanitizeInput($_POST['id']);
            $status = $this->sanitizeInput($_POST['status']);
            $user_id = $_SESSION['user_id'];

            if (!in_array($status, ['draft', 'published'])) {
                $_SESSION['error_message'] = "Status tidak valid.";
                header("Location: " . BASEURL . "/posts/index");
                exit();
            }

            error_log("updateStatus - id: " . $id . ", status: " . $status . ", user_id: " . $user_id);

            $article = $this->posts_model->getPostByIdAndUserId($id, $user_id);
            if (!$article) {
                $_SESSION['error_message'] = "Artikel tidak ditemukan atau Anda tidak memiliki izin.";
                header("Location: " . BASEURL . "/posts/index");
                exit();
            }

            $result = $this->posts_model->updatePostStatus($id, $status, $user_id);

            if (isset($article['id'], $article['title'], $article['content'], $article['image'])) {

                $this->posts_model->saveRevision(
                    $article['id'],
                    $user_id,
                    $article['title'],
                    $article['content'],
                    $article['image'],
                    $article['title'],
                    $article['content'],
                    $article['keywords'],
                    $article['image'],
                    $status
                );
            } else {
                error_log("Data artikel tidak lengkap untuk menyimpan revisi.");
            }

            if ($result) {
                $_SESSION['success_message'] = "Status artikel berhasil diperbarui.";
            } else {
                $_SESSION['error_message'] = "Gagal memperbarui status artikel.";
            }
        } else {
            $_SESSION['error_message'] = "Permintaan tidak valid.";
        }

        header("Location: " . BASEURL . "/posts/index");
        exit();
    }

    public function delete($slug)
    {
        $this->requireAuthorRole();
        $user_id = $_SESSION['user_id'];

        $article = $this->posts_model->getPostBySlug($slug);
        if (!$article) {
            $_SESSION['error_message'] = "Artikel tidak ditemukan.";
            header("Location: " . BASEURL . "/posts/index");
            exit();
        }

        $result = $this->posts_model->deletePost($article['id'], $user_id);

        if ($result) {
            $_SESSION['success_message'] = "Artikel berhasil dihapus.";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus artikel.";
        }

        header("Location: " . BASEURL . "/posts/index");
        exit();
    }

    public function edit($slug)
    {
        $this->requireAuthorRole();
        if (!isset($slug)) {
            header("Location: " . BASEURL . "/posts/index");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $article = $this->posts_model->getPostBySlugAndUserId($slug, $user_id);

        if (!$article) {
            $_SESSION['error_message'] = "Artikel tidak ditemukan atau Anda tidak memiliki izin.";
            header("Location: " . BASEURL . "/posts/index");
            exit();
        }
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $keywordsArray = json_decode($article['keywords'], true);
        $article['keywords_string'] = implode(" ", $keywordsArray);


        $data['article'] = $article;
        $data['title'] = "Edit Article";
        $data['csrf_token'] = $_SESSION['csrf_token'];

        $this->view('template/header', $data);
        $this->view('posts/edit', $data);
        $this->view('template/footer', $data);
    }

    public function processEdit()
    {
        $this->requireAuthorRole();

        error_log("processEdit - Awal fungsi");

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (
                !isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                $_SESSION['error_message'] = "Invalid security token. Please try again.";
                header("Location: " . BASEURL . "/posts/index");
                exit();
            }

            $id = $this->sanitizeInput($_POST['id']);
            $user_id = $_SESSION['user_id'];

            error_log("processEdit - ID Artikel: " . $id);
            error_log("processEdit - User ID: " . $user_id);

            $article = $this->posts_model->getPostByIdAndUserId($id, $user_id);

            if (!$article) {
                $_SESSION['error_message'] = "Artikel tidak ditemukan atau Anda tidak memiliki izin.";
                header("Location: " . BASEURL . "/posts/index");
                exit();
            }

            error_log("processEdit - Data artikel berhasil diambil dari database");

            $newTitle = $this->sanitizeInput($_POST['title']);
            $newContent = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
            $newKeywords = $this->sanitizeInput($_POST['keywords']);

            $newKeywordsArray = array_map([$this, 'sanitizeInput'], explode(" ", $newKeywords));
            $newKeywordsJson = json_encode(array_filter($newKeywordsArray));

            $newImage = $article['image'];
            if (!empty($_FILES['image']['name'])) {
                $uploadResult = $this->uploadImage($_FILES['image']);
                if ($uploadResult) {
                    $newImage = $uploadResult;
                } else {
                    header("Location: " . BASEURL . "/posts/edit/" . $article['slug']);
                    exit();
                }
            }

            error_log("processEdit - Perubahan:");
            error_log("processEdit - Judul baru: " . $newTitle);
            error_log("processEdit - Konten baru: " . substr($newContent, 0, 100) . "...");
            error_log("processEdit - Keywords baru: " . $newKeywordsJson);
            error_log("processEdit - Image baru: " . $newImage);

            error_log("processEdit - Memanggil saveRevision()");
            $revision_saved = $this->posts_model->saveRevision(
                $article['id'],
                $user_id,
                $article['title'],
                $article['content'],
                $article['image'],
                $newTitle,
                $newContent,
                $newKeywordsJson,
                $newImage,
                $article['status']
            );
            error_log("processEdit - saveRevision() selesai dieksekusi, Hasil: " . ($revision_saved ? 'Berhasil' : 'Gagal'));

            try {
                error_log("processEdit - Memanggil updatePost()");
                $updated = $this->posts_model->updatePost(
                    $article['id'],
                    $user_id,
                    $newTitle,
                    $newContent,
                    $newKeywordsJson,
                    $newImage
                );
                error_log("processEdit - updatePost() selesai dieksekusi, Hasil: " . ($updated ? 'Berhasil' : 'Gagal'));

                if ($updated) {
                    $_SESSION['success_message'] = "Artikel berhasil diperbarui!";
                    header("Location: " . BASEURL . "/posts/index");
                    exit();
                } else {
                    throw new Exception("Failed to update post");
                }
            } catch (Exception $e) {
                error_log("Error in processEdit: " . $e->getMessage());
                $_SESSION['error_message'] = "Terjadi kesalahan saat menyimpan artikel.";
                header("Location: " . BASEURL . "/posts/edit/" . $article['slug']);
                exit();
            }
        }
        error_log("processEdit - Selesai (bukan POST)");
        header("Location: " . BASEURL . "/posts/index");
        exit();
    }

    public function revisions($slug)
    {
        $this->requireAuthorRole();

        if (!isset($slug)) {
            header("Location: " . BASEURL . "/posts/index");
            exit();
        }

        $data['article'] = $this->posts_model->getPostBySlug($slug);
        if (!$data['article']) {
            $_SESSION['error_message'] = "Artikel tidak ditemukan.";
            header("Location: " . BASEURL . "/posts/index");
            exit();
        }

        $data['revisions'] = $this->posts_model->getRevisionsByPostId($data['article']['id']);
        $data['status_changes'] = $this->posts_model->getStatusChangesByPostId($data['article']['id']);
        $data['title'] = "Article Revisions";

        $this->view('template/header', $data);
        $this->view('posts/revisions', $data);
        $this->view('template/footer', $data);
    }
}
