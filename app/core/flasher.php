<?php
class Flasher {
    public static function setFlash($message, $title = 'Info', $type = 'info') {
        $_SESSION['flash'] = [
            'message' => $message,
            'title' => $title,
            'type' => $type
        ];
    }

    public static function flash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            echo "<script>Swal.fire({icon: '" . $flash['type'] . "', title: '" . $flash['title'] . "', text: '" . $flash['message'] . "'});</script>";
            unset($_SESSION['flash']);
        }
    }
}

