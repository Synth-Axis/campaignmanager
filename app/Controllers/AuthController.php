<?php

class AuthController
{
    public function login(): void
    {
        $model = new Users();

        $message = "";
        $email = "";

        if (!isset($_SESSION["csrf_token"])) {
            generateCSRFToken();
        }

        if (!isset($_SESSION["user_id"]) && isset($_COOKIE["remember_token"])) {
            $currentUser = $model->getUserByRememberToken($_COOKIE["remember_token"]);
            if ($currentUser) {
                $_SESSION["user_id"] = $currentUser["user_id"];
                $_SESSION["user"] = $currentUser;
                header("Location: /home-center");
                exit;
            }
        }

        if (isset($_POST["send"])) {
            if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
                die("CSRF token validation failed.");
            }

            foreach ($_POST as $key => $value) {
                $_POST[$key] = htmlspecialchars(strip_tags(trim($value)));
            }

            if (
                !empty($_POST["email"]) &&
                !empty($_POST["password"]) &&
                mb_strlen($_POST["password"]) >= 8 &&
                mb_strlen($_POST["password"]) <= 255 &&
                filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)
            ) {

                $currentUser = $model->findUserByEmail($_POST["email"]);

                if (!empty($currentUser)) {
                    $userPassword = $currentUser["password"];
                    if (password_verify($_POST["password"], $userPassword)) {
                        $_SESSION["user_id"] = $currentUser["user_id"];
                        $_SESSION["user"] = $currentUser;

                        if (!empty($_POST["remember"]) && $_POST["remember"] === "on") {
                            $token = bin2hex(random_bytes(32));
                            $tokenHash = hash('sha256', $token);
                            $expiresAt = date("Y-m-d H:i:s", time() + 60 * 60 * 24 * 30);
                            $model->storeRememberToken($currentUser["user_id"], $tokenHash, $expiresAt);
                            setcookie("remember_token", $token, time() + 60 * 60 * 24 * 30, "/", "", false, true);
                        }

                        header("Location: /home-center");
                        exit;
                    } else {
                        $message = "A password não é válida";
                        $email = retainFormData($_POST["email"]);
                    }
                } else {
                    $message = "O email não está registado";
                }
            } else {
                $message = "Por favor preencha todos os campos!";
                $email = retainFormData($_POST["email"]);
            }
            generateCSRFToken();
        }

        require view_path('auth/login.php');
    }

    public function register(): void
    {
        $message = "";
        $code = "";
        $email = "";
        $modelUsers = new Users();

        if (!isset($_SESSION["csrf_token"])) {
            generateCSRFToken();
        }

        if (isset($_POST["send"])){
            if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
                die("CSRF token validation failed.");
            }

            foreach($_POST as $key => $value){
                $_POST[ $key ] = htmlspecialchars(strip_tags(trim($value)));
            }

            $nome = $_POST["nome"] ?? "";
            $email = $_POST["email"] ?? "";

            if (
                !empty($_POST["nome"]) &&
                !empty($_POST["email"]) &&
                !empty($_POST["password"]) &&
                !empty($_POST["passwordCheck"]) &&
                mb_strlen($_POST["nome"]) >= 2 &&
                mb_strlen($_POST["password"]) >= 8 &&
                mb_strlen($_POST["password"]) <= 255 &&
                mb_strlen($_POST["passwordCheck"]) >= 8 &&
                mb_strlen($_POST["passwordCheck"]) <= 255 &&
                filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) &&
                $_POST["password"] === $_POST["passwordCheck"]
            ){
                $userEmail = $modelUsers->findUserByEmail($_POST["email"]);

                if( empty( $userEmail )){
                    $_POST["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);
                    $modelUsers->RegisterUser( $_POST );
                    header("Location: /login");
                    exit;
                }
                $message = "O email já se encontra registado";

            }
            else if($_POST["password"] !== $_POST["passwordCheck"]){
                $nome = retainFormData($_POST["nome"]);
                $message = "As passwords não são iguais";
                $email = retainFormData($_POST["email"]);
            }
            else {
                $message = "Todos os campos são obrigatórios";
                $nome = retainFormData($_POST["nome"]);
                $email = retainFormData($_POST["email"]);
            }

            generateCSRFToken();
        }

        require view_path('auth/register.php');
    }

    public function logout(): void
    {
        session_destroy();
        header("Location: /");
        exit;
    }

    public function recuperarPassword(): void
    {
        $userModel = new Users();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $csrf = $_POST['csrf_token'];

            if ($csrf !== $_SESSION['csrf_token']) {
                $message = "Token CSRF inválido.";
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = "Email inválido.";
                return;
            }

            $user = $userModel->findUserByEmail($email);

            if (!$user) {
                $message = "Email não encontrado.";
                return;
            }

            $token = bin2hex(random_bytes(32));
            $userModel->storeResetToken($user['user_id'], $token);

            $resetLink = ENV["ADDRESS"] . "/redefinir_password?token=$token";
            $subject = "Recuperação de Palavra-passe";
            $body = "Clique neste link para redefinir a sua palavra-passe:<br><a href='$resetLink'>$resetLink</a>";

            send_email($email, $subject, $body);

            $message = "Foi enviado um e-mail com o link de recuperação.";
        }

        require view_path('auth/recuperar_password.php');
    }

    public function redefinirPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $csrf = $_POST['csrf_token'] ?? '';

            if ($csrf !== $_SESSION['csrf_token']) {
                die("CSRF inválido.");
            }

            if (strlen($password) < 8) {
                die("Password demasiado curta.");
            }

            $model = new Users();
            $user = $model->findByToken($token);

            if (!$user) {
                die("Token inválido ou expirado.");
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $model->updatePassword($user['user_id'], $hash);

            header("Location: /login?redefinido=1");
            exit;
        }

        require view_path('auth/redefinir_password.php');
    }
}
