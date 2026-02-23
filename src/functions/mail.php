<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Mantenlo activo para ver el resultado
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'lybayxd10@gmail.com';
    $mail->Password   = 'qwneugtudqzedykr'; // REEMPLAZA AQUÍ
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Esto es CRÍTICO para que funcione en tu Apache local
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $mail->setFrom('lybayxd10@gmail.com', 'VIVA Marketplace');
    $mail->addAddress('geraza2427@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Prueba final Proyecto VIVA';
    $mail->Body    = 'Si recibes esto, el sistema de notificaciones está listo.';

    $mail->send();
    echo "¡ÉXITO! Mensaje enviado.";
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}