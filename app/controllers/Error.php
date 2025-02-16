<?php
class Error extends Controller
{
    public function index()
    {
        error_log("Error::index() - Dipanggil");
        $data['title'] = '404 Not Found';
        $this->view('template/header', $data);
        $this->view('error/404', $data);
        $this->view('template/footer', $data);
    }
}
