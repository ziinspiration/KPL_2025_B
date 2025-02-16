<?php

class Home extends Controller
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

    public function index()
    {
        $data['title'] = "Home";
        $data['posts'] = $this->home_model->getPublishedPosts();

        $this->view('template/header', $data);
        $this->view('home/index', $data);
        $this->view('template/footer', $data);
    }
}
