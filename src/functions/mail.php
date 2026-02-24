<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
    private static ?MailService $instance = null;
    private PHPMailer $mailer;
    private string $fromEmail;
    private string $fromName;
    private string $lastError = '';

    private function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->CharSet = 'UTF-8';
        $this->fromEmail = $_ENV['MAIL_FROM_ADDRESS'] ?? 'no-reply@viva.local';
        $this->fromName = $_ENV['MAIL_FROM_NAME'] ?? 'VIVA Ecommerce';
        $this->configureTransport();
    }

    public static function getInstance(): MailService
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }

    public function sendWelcomeEmail(string $toEmail, string $toName): bool
    {
        $safeName = htmlspecialchars($toName, ENT_QUOTES, 'UTF-8');
        $subject = 'Bienvenido(a) a VIVA Ecommerce';
        $html = "
            <div style=\"font-family: Arial, sans-serif; line-height:1.5;\">
                <h2>Hola {$safeName},</h2>
                <p>Tu cuenta en <strong>VIVA Ecommerce</strong> fue creada correctamente.</p>
                <p>Ya puedes iniciar sesión y explorar el catálogo artesanal.</p>
                <hr>
                <small>Este correo fue generado automáticamente.</small>
            </div>
        ";
        return $this->sendEmail($toEmail, $toName, $subject, $html);
    }

    public function sendPasswordRecoveryEmail(string $toEmail, string $toName, string $token): bool
    {
        $safeName = htmlspecialchars($toName, ENT_QUOTES, 'UTF-8');
        $safeToken = htmlspecialchars($token, ENT_QUOTES, 'UTF-8');
        $minutes = (int)($_ENV['RESET_TOKEN_EXP_MINUTES'] ?? 15);
        $subject = 'Recuperación de contraseña - VIVA Ecommerce';
        $html = "
            <div style=\"font-family: Arial, sans-serif; line-height:1.5;\">
                <h2>Hola {$safeName},</h2>
                <p>Recibimos una solicitud para recuperar tu contraseña.</p>
                <p>Tu código de verificación es:</p>
                <p style=\"font-size: 28px; font-weight: bold; letter-spacing: 4px;\">{$safeToken}</p>
                <p>Este código expira en {$minutes} minutos.</p>
                <p>Si no solicitaste este cambio, ignora este correo.</p>
                <hr>
                <small>Este correo fue generado automáticamente.</small>
            </div>
        ";
        return $this->sendEmail($toEmail, $toName, $subject, $html);
    }

    private function sendEmail(string $toEmail, string $toName, string $subject, string $html): bool
    {
        try {
            $this->mailer->clearAllRecipients();
            $this->mailer->clearAttachments();
            $this->mailer->setFrom($this->fromEmail, $this->fromName);
            $this->mailer->addAddress($toEmail, $toName);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $html;
            $this->mailer->send();
            $this->lastError = '';
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    private function configureTransport(): void
    {
        $smtpHost = $_ENV['SMTP_HOST'] ?? '';

        if ($smtpHost === '') {
            return;
        }

        $this->mailer->isSMTP();
        $this->mailer->Host = $smtpHost;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $_ENV['SMTP_USERNAME'] ?? '';
        $this->mailer->Password = $_ENV['SMTP_PASSWORD'] ?? '';
        $this->mailer->Port = (int)($_ENV['SMTP_PORT'] ?? 587);

        $secure = strtolower($_ENV['SMTP_SECURE'] ?? 'tls');
        if ($secure === 'ssl') {
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
    }
}
