<?php

class Article extends Controller
{
    private $home_model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header("Location: " . BASEURL . "/auth/signin");
            exit();
        }

        $this->home_model = $this->model('Home_model');
    }

    public function index($slug = null)
    {
        if ($slug === null) {
            echo "Artikel tidak ditemukan";
            return;
        }

        $data['post'] = $this->home_model->getPostBySlug($slug);

        if (!$data['post']) {
            header("HTTP/1.0 404 Not Found");
            require_once 'app/controllers/Error.php';
            $errorController = new Error();
            $errorController->index();
            return;
        }

        $data['comments'] = $this->home_model->getCommentsByPostId($data['post']['id']);
        $data['title'] = $data['post']['title'];
        $this->view('template/header', $data);
        $this->view('article/index', $data);
        $this->view('template/footer', $data);
    }

    public function addComment($slug)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
            $post = $this->home_model->getPostBySlug($slug);
            error_log("Data Post: " . print_r($post, true));
            if (!$post) {
                $_SESSION['error_message'] = "Artikel tidak ditemukan.";
                header("Location: " . BASEURL . "/home");
                exit();
            }

            $user_id = $_SESSION['user_id'];
            $comment_text = trim($_POST['comment']);

            if (!empty($comment_text)) {
                if ($this->home_model->addComment($post['id'], $user_id, $comment_text)) {
                    header("Location: " . BASEURL . "/article/index/" . $slug);
                    exit();
                } else {
                    $_SESSION['error_message'] = "Gagal menambahkan komentar.";
                }
            } else {
                $_SESSION['error_message'] = "Komentar tidak boleh kosong.";
            }

            header("Location: " . BASEURL . "/article/index/" . $slug);
            exit();
        } else {
            header("Location: " . BASEURL . "/home");
            exit();
        }
    }
}
