<?php

class Auth extends Controller
{
    private $user_model;

    public function __construct()
    {
        $this->user_model = $this->model('User_model');
    }

    public function index()
    {
        $this->signIn();
    }

    public function signIn()
    {
        $data['title'] = 'Sign In';
        $data['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $data['csrf_token'];
        $this->view('template/header', $data);
        $this->view('signin/index', $data);
        $this->view('template/footer', $data);
    }

    public function processSignIn()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                error_log("CSRF token invalid.");
                die("CSRF token invalid.");
            }

            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $username = htmlspecialchars($username);

            $user = $this->user_model->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                session_regenerate_id(true);

                $redirect_url = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : BASEURL;
                unset($_SESSION['redirect_after_login']);

                header("Location: " . $redirect_url);
                exit();
            } else {
                $data['error'] = 'Invalid username or password.';
                $data['title'] = 'Sign In';
                $data['csrf_token'] = bin2hex(random_bytes(32));
                $_SESSION['csrf_token'] = $data['csrf_token'];
                $this->view('template/header', $data);
                $this->view('signin/index', $data);
                $this->view('template/footer', $data);
            }
        } else {
            header("Location: " . BASEURL . '/auth/signin');
            exit();
        }
    }

    public function signUp()
    {
        $data['title'] = 'Sign Up';
        $data['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $data['csrf_token'];
        $this->view('template/header', $data);
        $this->view('signup/index', $data);
        $this->view('template/footer', $data);
    }

    public function processSignUp()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                error_log("CSRF token invalid.");
                die("CSRF token invalid.");
            }

            $fullname = trim($_POST['fullname']);
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $fullname = htmlspecialchars($fullname);
            $username = htmlspecialchars($username);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = "Invalid email format.";
                $data['title'] = 'Sign Up';
                $data['csrf_token'] = bin2hex(random_bytes(32));
                $_SESSION['csrf_token'] = $data['csrf_token'];
                $this->view('template/header', $data);
                $this->view('signup/index', $data);
                $this->view('template/footer', $data);
                return;
            }
            $email = htmlspecialchars($email);

            $password_validation = $this->validatePassword($password);
            if ($password_validation !== true) {
                $data['error'] = $password_validation;
                $data['title'] = 'Sign Up';
                $data['csrf_token'] = bin2hex(random_bytes(32));
                $_SESSION['csrf_token'] = $data['csrf_token'];
                $this->view('template/header', $data);
                $this->view('signup/index', $data);
                $this->view('template/footer', $data);
                return;
            }

            if (!empty($fullname) && !empty($username) && !empty($email) && !empty($password)) {
                $existingUser = $this->user_model->getUserByUsername($username);
                if ($existingUser) {
                    $data['error'] = "Username already exists.";
                    $data['title'] = 'Sign Up';
                    $data['csrf_token'] = bin2hex(random_bytes(32));
                    $_SESSION['csrf_token'] = $data['csrf_token'];
                    $this->view('template/header', $data);
                    $this->view('signup/index', $data);
                    $this->view('template/footer', $data);
                    return;
                }

                $id = uniqid('user_', true);

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $data = [
                    'id' => $id,
                    'fullname' => $fullname,
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashed_password,
                    'role' => 'reader'
                ];

                if ($this->user_model->createUser($data)) {
                    $_SESSION['success'] = "Registration successful! You can now sign in.";
                    header("Location: " . BASEURL . "/auth/signin");
                    exit();
                } else {
                    $data['error'] = "Registration failed!";
                    $data['title'] = 'Sign Up';
                    $data['csrf_token'] = bin2hex(random_bytes(32));
                    $_SESSION['csrf_token'] = $data['csrf_token'];
                    $this->view('template/header', $data);
                    $this->view('signup/index', $data);
                    $this->view('template/footer', $data);
                }
            } else {
                $data['error'] = "All fields are required!";
                $data['title'] = 'Sign Up';
                $data['csrf_token'] = bin2hex(random_bytes(32));
                $_SESSION['csrf_token'] = $data['csrf_token'];
                $this->view('template/header', $data);
                $this->view('signup/index', $data);
                $this->view('template/footer', $data);
            }
        } else {
            header("Location: " . BASEURL . "/auth/signup");
            exit();
        }
    }

    private function validatePassword($password)
    {
        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long.";
        }
        if (!preg_match('/[a-z]/', $password) || !preg_match('/[A-Z]/', $password)) {
            return "Password must contain at least one lowercase and one uppercase letter.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            return "Password must contain at least one number.";
        }
        if (!preg_match('/[^a-zA-Z0-9\s]/', $password)) {
            return "Password must contain at least one symbol.";
        }
        return true;
    }


    public function signOut()
    {
        session_unset();
        session_destroy();
        header("Location: " . BASEURL);
        exit();
    }
}
