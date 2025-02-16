<?php
class Controller
{
    public function view($view, $data = [])
    {
        error_log("Controller::view() - Memuat view: " . $view);
        extract($data);
        $viewFile = APPPATH . '/view/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            error_log("Controller::view() - File view tidak ditemukan: " . $viewFile);
            echo "Error: View file tidak ditemukan.";
        }
    }

    public function model($model)
    {
        error_log("Controller::model() - Memuat model: " . $model);
        require_once APPPATH . '/models/' . $model . '.php';
        return new $model;
    }
}
