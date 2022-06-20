<?php

/**
 * Controller per la gestione di /logout
 */
class LogoutController extends Controller
{

    public function main(){
        $_SESSION = array();

        // Destroy the session.
        session_destroy();

        self::redirect("login");
        exit;
    }
}