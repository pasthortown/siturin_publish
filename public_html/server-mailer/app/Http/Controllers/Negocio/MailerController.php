<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailerController extends Controller
{
  public function enviar(Request $data) {
    $result = $data->json()->all();
    $tipoMail = $result['tipoMail'];
    $email = $result['email'];
    $subject = $result['subject'];
    $information = $result['information'];
    return $this->send_mail($email, $information, $subject, env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'), $tipoMail);
  }

  protected function send_mail($to, $information, $subject, $fromMail,$fromAlias, $tipoMail) {
    $data = ['information'=>$information, 'appName'=>env('MAIL_FROM_NAME')];
    Mail::send($tipoMail, $data, function($message) use ($to, $information, $subject, $fromMail,$fromAlias, $tipoMail) {
      $message->to($to, $information['para'])->subject($subject);
      $message->from($fromMail,$fromAlias);
      if ($tipoMail == 'mail' || $tipoMail == 'asignacion') {
        $message->attachData(base64_decode($information['pdfBase64']), 'Solicitud_Tramite.pdf', ['mime' => 'application/pdf']);
      }
    });
    return response()->json("Solicitud Procesada. Enviaremos la respuesta a tu correo electrónico en un momento.",200);
  }

  public function entregar_documentos(Request $data) {
    $result = $data->json()->all();
    $email = $result['email'];
    $subject = $result['subject'];
    $information = $result['information'];
    return $this->send_mail_documentos($email, $information, $subject, env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'), 'entregar_documentos');
  }

  protected function send_mail_documentos($to, $information, $subject, $fromMail,$fromAlias, $tipoMail) {
    $data = ['information'=>$information, 'appName'=>env('MAIL_FROM_NAME')];
    Mail::send($tipoMail, $data, function($message) use ($to, $information, $subject, $fromMail,$fromAlias, $tipoMail) {
      $message->to($to, $information['para'])->subject($subject);
      $message->from($fromMail,$fromAlias);
      $message->attachData(base64_decode($information['pdfBase64_certificado']), 'Certificado_Registro.pdf', ['mime' => 'application/pdf']);
      $message->attachData(base64_decode($information['pdfBase64_tarifario']), 'Tarifario_Rack.pdf', ['mime' => 'application/pdf']);
    });
    return response()->json("Solicitud Procesada. Enviaremos la respuesta a tu correo electrónico en un momento.",200);
  }
}
