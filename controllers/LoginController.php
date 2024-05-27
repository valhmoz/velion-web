<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../models/LoginModel.php';
require '../vendor/autoload.php';

class LoginController {
    private $loginModel;

    public function __construct() {
        $this->loginModel = new LoginModel();
    }

    public function iniciarSesion($email, $pass) {
        $usuarios = $this->loginModel->read('usuarios', "email = '$email'");
        if (!empty($usuarios)) {
            $usuario = $usuarios[0];
            if (password_verify($pass, $usuario['pass'])) {
                $this->startSession($usuario);
            } else {
                $this->redirectWithMessage("Contraseña incorrecta.", 'warning');
            }
        } else {
            $this->redirectWithMessage("Usuario no encontrado.", 'warning');
        }
    }
    

    private function startSession($usuario) {
        session_start();
        $_SESSION = array_merge($_SESSION, $usuario);
        $redirectUrl = ($usuario['rol'] == 'Administrador' || $usuario['rol'] == 'Fisioterapeuta') ? '../pages/start.php' : '../pages/start-patients.php';
        header('Location: ' . $redirectUrl);
        exit();
    }

    private function redirectWithMessage($message, $alertType) {
        header("Location: ../index.php?alert=$alertType&message=$message");
        exit();
    }

    public function generatePasswordResetToken($email) {
        $token = bin2hex(random_bytes(32));
        $expires = date("U") + 1800;
        $conn = $this->loginModel->getConnection();
        $this->deleteExistingTokens($conn, $email);
        $this->insertNewToken($conn, $email, $token, $expires);
        $resetLink = 'https://tu-dominio.com/reset_password.php?token=' . $token;
        $this->sendPasswordResetEmail($email, $resetLink);
        $conn->close();
    }

    private function deleteExistingTokens($conn, $email) {
        $sql = "DELETE FROM password_resets WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();
    }

    private function insertNewToken($conn, $email, $token, $expires) {
        $sql = "INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $email, $hashedToken, $expires);
        $stmt->execute();
        $stmt->close();
    }

    private function sendPasswordResetEmail($email, $resetLink) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sergiofrubio@gmail.com';
            $mail->Password = 'tu-contraseña';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('tu-correo@gmail.com', 'Tu Nombre');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña';
            $mail->Body = 'Haz clic en el siguiente enlace para recuperar tu contraseña: <a href="' . $resetLink . '">Recuperar Contraseña</a>';
            $mail->send();
        } catch (Exception $e) {
            echo "No se pudo enviar el mensaje. Error de Mailer: {$mail->ErrorInfo}";
        }
    }
}

?>
