<?php
class Controller {
    protected function view($path, $data = []) {
        extract($data);

        ob_start();
        require BASE_PATH . '/app/views/' . $path . '.php';
        $content = ob_get_clean();

        require BASE_PATH . '/app/views/layouts/main.php';
    }
}
?>
