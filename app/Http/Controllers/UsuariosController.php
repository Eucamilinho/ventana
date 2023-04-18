<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\User;
use  App\Models\Role;
//use App\Models\sucursales;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('usuarios.index');
    }
    public function Listar()
    {
        $usuarios = "";
        $roles = "";
        $zonas = "";
        $sucursales = "";
        $departamentos = "";
        $municipios = "";
        $missucursales = "";
        try {
            //$missucursales = sucursales::get();
            // $roles =  Role::get();
            $roles =  DB::table('roles')
                ->select('roles.*')
                ->where('roles.id', '<>', '8')
                ->get();
            // $usuarios =  User::get();
            $usuarios =  DB::table('users')
                ->select('users.*')
                ->selectRaw('roles.description as descript')
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->get();
            // $zonas=DB::table('maestra_zonas')->get();
            // $sucursales=DB::table('maestra_sucursales')->get();

            $departamentos = DB::table('departamentos')
                ->get();
            $municipios = DB::table('municipios')
                ->get();
            $mensaje = [
                "Titulo" => "Éxito", "Respuesta" => "la informaci&oacuten satisfatoria", "Tipo" => "success", "usuarios" => $usuarios, "roles" => $roles, "zonas" => $zonas, "sucursales" => $sucursales, "departamentos" => $departamentos, "municipios" => $municipios, "missucursales" => $missucursales
            ];
        } catch (\Exception $e) {
            $mensaje = [
                "Titulo" => "Error", "Respuesta" => "Algo salio mal contacte con al administrador del sistema.", "Tipo" => "error", "usuarios" => $usuarios, "roles" => $roles, "zonas" => $zonas, "sucursales" => $sucursales, "departamentos" => $departamentos, "municipios" => $municipios, "missucursales" => $missucursales
            ];
        }
        return json_encode($mensaje);
    }
    public function Eliminar()
    {
        $datos = json_decode($_POST['data']);
        try {
            $flight = User::find($datos->id);
            $flight->delete();
            $mensaje = ["Titulo" => "Éxito", "Respuesta" => "Se eliminó el registro de manera correcta", "Tipo" => "success"];
        } catch (\Exception $e) {
            $mensaje = ["Titulo" => "Error", "Respuesta" => "Algo salio mal contacte con al administrador del sistema.", "Tipo" => "error"];
        }
        return json_encode($mensaje);
    }
    public function Crear()
    {
        $datos = json_decode($_POST['data']);
        try {



            if ($datos->role == 2) {
                $datos->email = $datos->identificacion;
            }
            if ($datos->telefono == "" || $datos->telefono == "undefined") {
                $datos->telefono = null;
            }
            if ($datos->idDepartamento == "" || $datos->idDepartamento == "undefined") {
                $datos->idDepartamento = null;
            }

            if ($datos->idMunicipio == "" || $datos->idMunicipio == "undefined") {
                $datos->idMunicipio = null;
            }

            if ($datos->idMiSucursal == "" || $datos->idMiSucursal == "undefined") {
                $datos->idMiSucursal = null;
            }


            if ($datos->idMiSucursal == "" || $datos->idMiSucursal == "undefined") {
                $datos->idMiSucursal = null;
            }
            $password =UsuariosController::generatePassword(8);

            $user = User::create([
                'name' => $datos->name,
                'email' => $datos->email,
                'password' => bcrypt($password),
                'identificacion' => $datos->identificacion,
                //'id_zona' => $datos->idZona,
                //'id_sucursal' => $datos->idSucursal,
                'estado' => 1,
                'bpass' => $datos->identificacion,
                'telefono' => $datos->telefono,
                'idDepartamento' => $datos->idDepartamento,
                'idMunicipio' => $datos->idMunicipio,
                //'idSucursal' => $datos->idMiSucursal,
                'bpass' =>$password,
            ]);
            $user
                ->roles()
                ->attach(Role::where('id', $datos->role)->first());
            $mensaje = ["Titulo" => "Éxito", "Respuesta" => "Se creó el registro de manera correcta", "Tipo" => "success", "email" => $datos->email, "user" => $datos->email, "pass" => $password];
        } catch (\Exception $e) {

            $mensaje = ["Titulo" => "Error", "Respuesta" => "No se creó el registro de manera correcta, el correo ya existe en el sistema", "Tipo" => "error"];
        }
        return json_encode($mensaje);
    }
    public function Mostrar()
    {
        $datos = json_decode($_POST['data']);
        $usuario = "";
        $role = "";
        $missucursales = "";
        try {
            $usuario = User::find($datos->id);
            $role = DB::table('role_user')
                ->select('role_id')
                ->where('user_id', $datos->id)
                ->get();
            //    dd($role);
            $mensaje = ["Titulo" => "Éxito", "Respuesta" => "Se creó el registro de manera correcta", "Tipo" => "success", "usuario" => $usuario, 'role' => $role,'sucursal' => $missucursales];
        } catch (\Exception $e) {
            $mensaje = ["Titulo" => "Error", "Respuesta" => "No se creó el registro de manera correcta", "Tipo" => "error", "usuario" => $usuario, 'role' => $role, 'sucursal' => $missucursales];
        }
        return json_encode($mensaje);
    }
    public function Actualizar()
    {
        $datos = json_decode($_POST['data']);
        try {

            if ($datos->telefono == "" || $datos->telefono == "undefined") {
                $datos->telefono = null;
            }

            if ($datos->idDepartamento == "" || $datos->idDepartamento == "undefined") {
                $datos->idDepartamento = null;
            }

            if ($datos->idMunicipio == "" || $datos->idMunicipio == "undefined") {
                $datos->idMunicipio = null;
            }
            $usuario = User::find($datos->id);
            $usuario->name = $datos->name;
            $usuario->email = $datos->email;
            $usuario->identificacion = $datos->identificacion;
            //$usuario->id_zona = $datos->idZona;
            //$usuario->id_sucursal = $datos->idSucursal;
            $usuario->estado = $datos->estado;
            $usuario->telefono = $datos->telefono;
            $usuario->idDepartamento = $datos->idDepartamento;
            $usuario->idMunicipio = $datos->idMunicipio;
           // $usuario->idSucursal = $datos->idSucursal;
            $usuario->save();
            //para actualizar el rol se debe quitar primero y luego crear
            // $usuario->roles()->detach($datos->role);
            //crear nuevamente el rol
            // $usuario->roles()->attach(Role::where('id', $datos->role)->first());
            $role = DB::table('role_user')->where('user_id', $datos->id)
                ->update(['role_id' => $datos->role]);
            $mensaje = ["Titulo" => "Éxito", "Respuesta" => "Se actualizó el registro de manera correcta", "Tipo" => "success"];
        } catch (\Exception $e) {
            $mensaje = ["Titulo" => "Error", "Respuesta" => "No se actualizó el registro de manera correcta", "Tipo" => "error"];
            dd($e);
        }
        return json_encode($mensaje);
        //para imprimir de en la consola en network
    }
    public function obtenerSucursales()
    {
        $idZona = json_decode($_POST['data']);
        $sucursales = "";
        try {
            $sucursales = DB::table('maestra_sucursales')
                ->where('id_zona', $idZona)
                ->get();
            $mensaje = ["Titulo" => "Éxito", "Respuesta" => "Se listo de manera correcta", "Tipo" => "success", "sucursales" => $sucursales];
        } catch (\Exception $e) {
            $mensaje = ["Titulo" => "Error", "Respuesta" => "Error,se presento un problema al momento de obtener sucursales", "Tipo" => "error", "sucursales" => $sucursales];
        }
        return json_encode($mensaje);
    }
    public function sincronizarProveedores($name, $email, $identificacion)
    {
        try {


            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($identificacion),
                'identificacion' => $identificacion,
                'estado' => 1
            ]);
            $user
                ->roles()
                ->attach(Role::where('id', 3)->first());
            $mensaje = 'inserto';
        } catch (\Exception $e) {
            $mensaje = 'no inserto';
        }
        echo $mensaje;
    }
    public function actualizarFechaVencimiento()
    {
        $datos = json_decode($_POST['data']);
        $id = $datos->id;
        $fechaVencimiento = $datos->fechaVencimiento;
        try {
            $user = User::find($id);
            $user->fechaVencimientoMembresia = $fechaVencimiento;
            $user->save();
            // $idUser=Auth::user()->id;
            DB::table('role_user')
                ->where('user_id', $id)
                ->update(['role_id' => 3]);
            $mensaje = ["Titulo" => "Éxito", "Respuesta" => "Se actualizó la fecha de membresia de manera correcta", "Tipo" => "success"];
        } catch (\Throwable $th) {
            $mensaje = ["Titulo" => "Error", "Respuesta" => "Error,se presentó un problema al momento de actualizar fecha de vencimiento membresia", "Tipo" => "error"];
        }
        return json_encode($mensaje);
    }

    public function enviarMailCreacion()
    {

        $datos = json_decode($_POST['data']);
        $email = $datos->email;
        $usuario = $datos->user;
        $pass = $datos->pass;
        // require base_path("vendor/autoload.php");
        // require '../../../vendor/autoload.php';
        // require base_path("public/PHPMailer/src/Exception.php");
        // require base_path("public/PHPMailer/src/PHPMailer.php");
        // require base_path("public/PHPMailer/src/SMTP.php");
        //Load Composer's autoloader
        // require 'vendor/autoload.php';
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        // try {

        // $numeroCotizacion=$orden->consecutivo;
        $mail->Host       = 'mail.sodeker.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'logistica@sodeker.com';                     //SMTP username
        $mail->Password   = 'S0D@2016*_K3R';                               //SMTP password
        // $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        //Recipients
        $mail->setFrom('logistica@sodeker.com', 'FDC');
        $mail->addAddress('heyner.becerrasdk@gmail.com');               //Name is optional
        // foreach ($users as $key ) {
        //     $mailAprobador=$key->correo;
        //     if ($email!="") {
        $mail->addAddress($email);               //Name is optional
        //     }
        // }
        //Content
        $mensaje = "";

        // $mail->AddEmbeddedImage($sImagen, 'imagen');
        // $url='https://sai.sodeker.com/dastone-v2.0/HTML/assets/images/logo-sm-sai.png';
        // $image = file_get_contents($url);
        // $imagenComoBase64 = base64_encode($image);

        // <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAJUlEQVR42u3NQQEAAAQEsJNcdFLw2gqsMukcK4lEIpFIJBLJS7KG6yVo40DbTgAAAABJRU5ErkJggg==">

        $mensaje .= utf8_decode('<div class="card" style="margin-bottom: 16px;
            background-color: #fff;
            border: 1px solid #e3ebf6;text-align:center;">');
        $mensaje .= utf8_decode('<div class="card-body p-0 auth-header-box" style=" background-color: #0c213a;">');
        $mensaje .= utf8_decode('<div class="text-center p-3">');
        // $mensaje.=utf8_decode('<a href="index.html" class="logo logo-admin">');
        $mensaje .= utf8_decode('<img src="https://concretol.sodeker.com/logo%20concretol.jpg" height="50" alt="logo" class="auth-logo">');
        // $mensaje.=utf8_decode('</a>');
        // $mensaje.=utf8_decode('<h1 class="mt-3 mb-1 fw-semibold text-white font-18" style="color:white;">SAi</h1>  ');
        // $mensaje.=utf8_decode('<p class="text-muted  mb-0" style="color:white;">SAi</p>');
        $mensaje .= utf8_decode('</div>');
        $mensaje .= utf8_decode('</div>');
        $mensaje .= utf8_decode('<div class="card-body">');
        $mensaje .= utf8_decode('<h1>Gracias por registrarse</h1>');
        // $mensaje.=utf8_decode('Usuario :'.$usuario.'<br>');
        // $mensaje.=utf8_decode('Contraseña :'.$pass.'<br>');
        $mensaje .= utf8_decode('<p>Te damos la bienvenida al increible mundo de Sai. somos pioneros en soluciones de despachos web
            y lo vas a ver enseguida.</p>');
        $mensaje .= utf8_decode('<p>todo lo que tienes que hacer es ingresar a tu navegador,a la siguiente url https://concretol.sodeker.com/
            y digitar tu usuario y contraseña.</p><br>');
        $mensaje .= utf8_decode('<p>Usuario : ' . $usuario . '<p>');
        $mensaje .= utf8_decode('<p>Contraseña : ' . $pass . '</p>');
        $mensaje .= utf8_decode('<p>Gracias por unirte a nosotros</p>');
        $mensaje .= utf8_decode('</div>');
        $mensaje .= utf8_decode('<div class="card-body bg-light-alt text-center">');
        // $mensaje.=utf8_decode('<span class="text-muted d-none d-sm-inline-block">Mannatthemes © <script> ');
        $mensaje .= utf8_decode('</div>');
        $mensaje .= utf8_decode('</div>');

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = utf8_decode('Concretol >> Credenciales');
        $mail->Body    = $mensaje;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->send();
        // }
        // } catch (Exception $e) {

        //     dd($e);
        //     // echo {$mail->ErrorInfo};
        // }
        $mensaje = ["Titulo" => "Éxito", "Respuesta" => "enviarMailCreacion", "Tipo" => "success"];

        return json_encode($mensaje);
    }
    public function generatePassword($length= 8)
    {
        $key = "";
        $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
        $max = strlen($pattern)-1;
        for($i = 0; $i < $length; $i++){
            $key .= substr($pattern, mt_rand(0,$max), 1);
        }
        return $key;
    }

    public function insertarNovedad(Request $request)
    {


        $dato=$request->all();
        // dd($request);

        $dato=request()->get('id');

        // return  $request;
        // $datos=json_decode($_POST['a']);

        // dd($datos);
        // $dato= $request->a;

       try {
        //code...
        $mensaje = ["Titulo" => "Éxito", "Respuesta" => "enviarMailCreacion", "Tipo" => "success","request"=>$dato];
       } catch (\Throwable $th) {
        $mensaje = ["Titulo" => "Error", "Respuesta" => "enviarMailCreacion", "Tipo" => "success"];

    }


       return json_encode($mensaje);
    }

}
