<?php
namespace Core;

class Controller {
    // Load a model
    public function model($model) {
        if (file_exists(ROOT_PATH . '/app/Models/' . $model . '.php')) {
            require_once ROOT_PATH . '/app/Models/' . $model . '.php';
            // Return model instance
            // Note: Models should be in the global namespace or defined namespace
            return new $model();
        } else {
            die("Model does not exist: " . $model);
        }
    }

    // Load a view
    public function view($view, $data = []) {
        // Extract data array to variables for the view
        extract($data);
        
        if (file_exists(ROOT_PATH . '/app/Views/' . $view . '.php')) {
            require_once ROOT_PATH . '/app/Views/' . $view . '.php';
        } else {
            die("View does not exist: " . $view);
        }
    }
}