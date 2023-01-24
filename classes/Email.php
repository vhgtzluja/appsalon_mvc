<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        //Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '75f63ed8c252cc';
        $mail->Password = '5f3576ecf601df';
        $mail->SMTPSecure = 'tls';

        //Configurar el contenido del email
        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('vhluja@gmail.com', $this->nombre);
        $mail->Subject = 'Confirma tu cuenta';

        //Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ".$this->nombre."</strong>, has creado tu cuenta en AppSalon, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aqui: <a href='http://localhost:3000/confirmar-cuenta?token=".$this->token."'>Confirmar cuenta</a></p>";
        $contenido .= "<p>Si no solictaste esta cuenta, ignora el mensaje</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;
        
        //Enviar el email
        $mail->send();
    }

    public function enviarInstrucciones() {
        //Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '75f63ed8c252cc';
        $mail->Password = '5f3576ecf601df';
        $mail->SMTPSecure = 'tls';

        //Configurar el contenido del email
        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('vhluja@gmail.com', $this->nombre);
        $mail->Subject = 'Restablece tu password';

        //Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ".$this->nombre."</strong>, has solicitado restablecer tu password, haz clic en el siguiente enlace.</p>";
        $contenido .= "<p><a href='http://localhost:3000/recuperar?token=".$this->token."'>Restablecer password</a></p>";
        $contenido .= "<p>Si no solictaste este cambio, ignora el mensaje</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;
        
        //Enviar el email
        $mail->send();    
    }
}