<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class Mail {
    private $container;

    public function __construct($container){
        $this->container = $container;
    }

    public function send(array $to, string $template, string $subject, array $payload) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'contatesteslimphp@gmail.com';
            $mail->Password = 'o0i9u8y7t6r5e4w3q21';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->CharSet = 'utf-8';
            $mail->setFrom('contatesteslimphp@gmail.com', 'Alexandre');
            $mail->addAddress($to['email'], $to['name']);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $this->container->view->render(
                $this->container->response,
                'mails/' . $template,
                $payload
            );

            $mail->send();
        } catch (PHPMailerException $e){
            echo 'Houve um erro na tentativa de enviar um email' . $e;
        }
    }
}