<?php

class Login
{
    public function index()
    {
        Flight::render('login');
    }

    /**
     * Attempt to login user based on credentials specified in config file.
     */
    public function loginuser()
    {
        $username_ok = false;
        $password_ok = false;

        $username = $_POST['username'];
        $password = $_POST['password'];

        // get user login details from config file
        $config = Flight::get('config');

        // go through array and match username and password
        foreach ($config as $key => $value) {
            if ($key === 'username') {
                if (is_array($value)) {
                    foreach ($value as $value2) {
                        if ($username === $value2) {
                            $username_ok = true;
                        }
                    }
                } else {
                    if ($username === $value) {
                        $username_ok = true;
                    }
                }
            } elseif ($key === 'password') {
                if (is_array($value)) {
                    foreach ($value as $value2) {
                        if ($password === $value2) {
                            $password_ok = true;
                        }
                    }
                } else {
                    if ($password === $value) {
                        $password_ok = true;
                    }
                }
            }
        }

        if ($username_ok && $password_ok) {
            $_SESSION['logged'] = true;
            Flight::redirect('./home');
        } else {
            setFlashMessage('Error: Invalid username or password!');
            Flight::redirect('./login');
        }
    }

    /**
     * Logout logged user
     */
    public function logout()
    {
        unset($_SESSION['db']);
        unset($_SESSION['logged']);
        unset($_SESSION);
        @session_destroy();
        session_regenerate_id();

        Flight::redirect('./login');
    }
}