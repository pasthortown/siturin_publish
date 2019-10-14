<?php

namespace App\Http\Controllers;

use Validator;
use Exception;
use App\User;
use App\AccountRol;
use App\AuthLocation;
use App\AccountRolAssigment;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Crypt;

use SoapClient;

class AuthController extends Controller
{
  function passwordRecoveryRequest(Request $data) {
    $result = $data->json()->all();
    $email = $result['email'];
    $user = User::where('email', $email)->first();
    if(!$user){
      return response()->json('Ocurrió un error',400);
    }
    $enlace = env('API_AUTH').'password_recovery/?r='.$user->api_token;
    $message = "Para cambiar tu contraseña da click en el siguiente enlace: " . $enlace;
    $subject = "Solicitud de Cambio de Contraseña";
    return $this->send_mail($user->email, $user->name, $subject, $message, env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
  }

  function passwordRecovery(Request $data)
  {
    $token = $data['r'];
    $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
    try{
      $new_password = str_random(10);
      DB::beginTransaction();
      $status = User::where('id', $credentials->subject)->update([
        'password'=>Crypt::encrypt($new_password),
      ]);
      if(!$status){
        return response()->json('Ocurrió un error',400);
      }
      DB::commit();
    } catch (Exception $e) {
      return response()->json('Ocurrió un error',400);
    }
    $message = "Tu nueva contraseña es " . $new_password;
    $subject = "Recuperación de Contraseña";
    $user = User::where('id', $credentials->subject)->first();
    return $this->send_mail($user->email, $user->name, $subject, $message, env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
  }

  function passwordChange(Request $data)
  {
    $result = $data->json()->all();
    $id = $data->auth->id;
    $new_password = $result['new_password'];
    try{
      DB::beginTransaction();
      $user = User::find($id)->update([
        'password'=>Crypt::encrypt($new_password),
      ]);
      DB::commit();
    } catch (Exception $e) {
      return response()->json([
        'error' => 'Bad Credentials'
      ], 400);
    }
    return response()->json('Password changed successfully',200);
  }

  function register(Request $data)
  {
    $result = $data->json()->all();
    $userData = $result['user'];
    $email = $userData['email'];
    $new_password = str_random(10);
    try{
      DB::beginTransaction();
      $previewUserEmail = User::where('email', $email)->first();
      if($previewUserEmail) {
        return response()->json(0,200);
      }
      $previewUserRuc = User::where('ruc', $userData['ruc'])->first();
      if($previewUserRuc) {
        return response()->json(0,200);
      }
      $user = new User();
      $lastUser = User::orderBy('id')->get()->last();
      if($lastUser) {
          $user->id = $lastUser->id + 1;
      } else {
          $user->id = 1;
      }
      $user->name = $userData['name'];
      $user->email = $userData['email'];
      $user->identification = $userData['identification'];
      $user->ruc = $userData['ruc'];
      $user->address_map_latitude = -0.2153676;
      $user->address_map_longitude = -78.5036064;
      $user->secondary_phone_number = '0000000000';
      $user->password = Crypt::encrypt($new_password);
      $user->api_token = str_random(64);
      $user->save();
      $accountrolassigment = new AccountRolAssigment();
      $lastAccountRolAssigment = AccountRolAssigment::orderBy('id')->get()->last();
      if($lastAccountRolAssigment) {
         $accountrolassigment->id = $lastAccountRolAssigment->id + 1;
      } else {
         $accountrolassigment->id = 1;
      }
      $accountrolassigment->account_rol_id = 2;
      $accountrolassigment->user_id = $user->id;
      $accountrolassigment->save();
      $authlocation = new AuthLocation();
      $lastAuthLocation = AuthLocation::orderBy('id')->get()->last();
      if($lastAuthLocation) {
          $authlocation->id = $lastAuthLocation->id + 1;
      } else {
          $authlocation->id = 1;
      }
      $authlocation->id_ubication = 2;
      $authlocation->id_user = $user->id;
      $authlocation->save();
      $domain = explode('@', $email);
      if (sizeof($domain) == 2) {
        if ($domain[1] == 'turismo.gob.ec') {
          $new_password = 'la de su correo institucional.';
        }
      }
      $message = "Su nueva contraseña es " . $new_password;
      $subject = "Le damos la bienvenida a " . env('MAIL_FROM_NAME');
      DB::commit();
      return $this->send_mail($email, $user->name, $subject, $message, env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
    } catch (Exception $e) {
      return response()->json($e,400);
    }
  }

  function login(Request $data)
  {
      $result = $data->json()->all();
      $email = $result['email'];
      $password = $result['password'];
      $user = User::where('email', $email)->first();
      if (!$user) {
        return response()->json([
          'error' => 'Bad Credentials'
        ], 400);
      }
      if (substr(Crypt::decrypt($user->password),0,9) == 'BLOQUEADA') {
        return response()->json([
          'error' => 'Cuenta Bloqueada'
        ], 400);
      }
      $domain = explode('@', $email);
      if (sizeof($domain) == 2) {
        if ($domain[1] == 'turismo.gob.ec') {
          if($this->authenticate_ldap($domain[0], $password)){
            return $this->iniciarSesion($user);
          }
        } else {
          if ($password === Crypt::decrypt($user->password)) {
            return $this->iniciarSesion($user);
          }
        }
      }
      return response()->json([
        'error' => 'Bad Credentials'
      ], 400);
  }

  protected function authenticate_ldap($email, $password) {
    $LDAP_HOST = '192.168.20.102';
    $LDAP_BASE_DN = 'ou=people,dc=turismo,dc=gob,dc=ec';
    $LDAP_PORT = 389;
    $ldap_connection = ldap_connect($LDAP_HOST, $LDAP_PORT);
    $username = $email;
    $ldap_dn = 'uid='.$username.','.$LDAP_BASE_DN;
    ldap_set_option($ldap_connection,LDAP_OPT_PROTOCOL_VERSION,3);
    ldap_set_option($ldap_connection,LDAP_OPT_REFERRALS,0);
    $isDisabled = $this->checkIfIsActive($username);
    if (!$isDisabled) {
      $bind = @ldap_bind($ldap_connection, $ldap_dn, $password);
      if ($bind) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
    
    $bind = @ldap_bind($ldap_connection, $ldap_dn, $password);
    if ($bind) {
        return true;
    } else {
        return false;
    }
  }

  protected function checkIfIsActive($username) {
    $LDAP_HOST = '192.168.20.102';
    $LDAP_BASE_DN = 'uid=zimbra,cn=admins,cn=zimbra';
    $LDAP_PASS_ZIMBRA = 'XsWnu53E';
    $LDAP_PORT = 389;
    $ldap_connection = ldap_connect($LDAP_HOST, $LDAP_PORT);
    ldap_set_option($ldap_connection,LDAP_OPT_PROTOCOL_VERSION,3);
    ldap_set_option($ldap_connection,LDAP_OPT_REFERRALS,0);
    $bind = @ldap_bind($ldap_connection, $LDAP_BASE_DN, $LDAP_PASS_ZIMBRA);
    if ($bind) {           
        $filter="(userid=".$username.")";
        $attributes = array('zimbramailstatus');
        $sr=ldap_search($ldap_connection, null, $filter, $attributes);
        $info = ldap_get_entries($ldap_connection, $sr);
        ldap_free_result($sr);
        ldap_unbind($ldap_connection);
        $respuesta = ($info[0]['zimbramailstatus'][0] == 'disabled' ? true : false);
        return $respuesta;
    } else {
        return false;
    }
  }

  protected function iniciarSesion($user) {
    $token = $this->jwt($user);
    $response = User::where('id',$user->id)->update([
      'api_token'=>$token,
    ]);
    $rol_assigments = AccountRolAssigment::where('user_id',$user->id)->get();
    $roles = [];
    foreach($rol_assigments as $rol_assigment) {
      $rol = AccountRol::where('id', $rol_assigment->account_rol_id)->first();
      array_push($roles, $rol);
    }
    return response()->json([
        'token' => $token,
        'name' => $user->name,
        'id' => $user->id,
        'roles' => $roles,
    ], 200);
  }

  protected function jwt(User $user) {
    $payload = [
        'subject' => $user->id,
        'creation_time' => time(),
        'expiration_time' => time() + 60*60
    ];
    return JWT::encode($payload, env('JWT_SECRET'));
  }

  protected function send_mail($to, $toAlias, $subject, $body, $fromMail,$fromAlias) {
    $data = ['name'=>$toAlias, 'body'=>$body, 'appName'=>env('MAIL_FROM_NAME')];
    Mail::send('mail', $data, function($message) use ($to, $toAlias, $subject, $fromMail,$fromAlias) {
      $message->to($to, $toAlias)->subject($subject);
      $message->from($fromMail,$fromAlias);
    });
    return response()->json("Solicitud Procesada. Enviaremos la respuesta a tu correo electrónico en un momento.",200);
  }
}
