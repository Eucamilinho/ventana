<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\basicos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

class BasicosController extends Controller
{
    public function index()
    {
        return view('basicos.index');
    }
    public function Listar() {
        $basicos="";
        try{
            $basicos =  basicos::get();
            $mensaje = ["Titulo"=>"Éxito","Respuesta"=>"la informaci&oacuten satisfatoria","Tipo"=>"success","basicos"=>$basicos]; 
        }catch(\Exception $e){
            $mensaje = ["Titulo"=>"Error","Respuesta"=>"Algo salio mal contacte con al administrador del sistema.","Tipo"=>"error","basicos"=>$basicos]; 
        }
        return json_encode($mensaje);       
    }


    public function Eliminar(){
        $datos=json_decode($_POST['data']);
        try{
            $flight = basicos::find($datos->id);
            $flight->delete();
            
               $mensaje = ["Titulo"=>"Éxito","Respuesta"=>"Se eliminó el registro de manera correcta","Tipo"=>"success"]; 
           }catch(\Exception $e){
               $mensaje = ["Titulo"=>"Error","Respuesta"=>"Algo salio mal contacte con al administrador del sistema.","Tipo"=>"error"]; 
           }
           return json_encode($mensaje);
    }


    public function Crear(){
        $datos=json_decode($_POST['data']);
        $ultimoId="";
        try{         
            $role = basicos::create([              
                'nombre' => $datos->nombre,
                'logo' => $datos->logo,
                'direccion' => $datos->direccion,
                'redSocial1' => $datos->redSocial1,
                'redSocial2' => $datos->redSocial2,
                'redSocial3' => $datos->redSocial3,
                'telefono1' => $datos->telefono1,
                'telefono2' => $datos->telefono2,
            ]);               
                       
            $data = basicos::latest('id')->first();
             $ultimoId=$data->id;
             $mensaje = ["Titulo"=>"Éxito","Respuesta"=>"Se creó el registro de manera correcta","Tipo"=>"success","ultimoId"=>$ultimoId]; 
           }catch(\Exception $e){
               $mensaje = ["Titulo"=>"Error","Respuesta"=>"No se creó el registro de manera correcta","Tipo"=>"error","ultimoId"=>$ultimoId]; 
           }
        return json_encode($mensaje);
    }

    
    public function Mostrar(){
        $datos=json_decode($_POST['data']);
        $basico="";
        try{
               $basico=basicos::find($datos->id);                      
               $mensaje = ["Titulo"=>"Éxito","Respuesta"=>"Se creó el registro de manera correcta","Tipo"=>"success","basico"=>$basico]; 
           }catch(\Exception $e){
               $mensaje = ["Titulo"=>"Error","Respuesta"=>"No se creó el registro de manera correcta","Tipo"=>"error","basico"=>$basico]; 
           }
        return json_encode($mensaje);
    }

    public function Actualizar(){
        $datos=json_decode($_POST['data']);
        try{
                $role=basicos::find($datos->id); 
                $role->nombre = $datos->nombre;                
                $role->direccion = $datos->direccion;
                $role->redSocial1 = $datos->redSocial1;
                $role->redSocial2 = $datos->redSocial2;
                $role->redSocial3 = $datos->redSocial3;
                $role->telefono1 = $datos->telefono1;
                $role->telefono2 = $datos->telefono2;
                $role->save();             
               $mensaje = ["Titulo"=>"Éxito","Respuesta"=>"Se actualizó el registro de manera correcta","Tipo"=>"success"]; 
           }catch(\Exception $e){

               $mensaje = ["Titulo"=>"Error","Respuesta"=>"No se actualizó el registro de manera correcta","Tipo"=>"error"]; 
           }
        return json_encode($mensaje);
        //para imprimir de en la consola en network
        //dd($datos); 
    }

    public function GuardarFileEmpresa(Request $request)
    {
        $datos=json_decode($_POST['data']);
        $Tipouser = Auth::user()->roles->first()->name;
        $idUsuario = Auth::user()->id;
        $id=$datos->id;
        $compPic1="";
            try {
                $string = $request->data;
                $datos = json_decode($string);

                // if ($datos->accion=='nuevo') {
                //tomar toda la informacion
                if ($request->hasFile('filePerfil')) {
                    $nombreCompletoArchvio = $request->file('filePerfil')->getClientOriginalName();
                    $nombreDelArchivo = pathinfo($nombreCompletoArchvio, \PATHINFO_FILENAME);
                    $extension = $request->file('filePerfil')->getClientOriginalExtension();
                    $compPic1 = str_replace(' ', '_', $nombreDelArchivo) . '-' . rand() . '_' . time() . '.' . $extension;
                    $path = $request->file('filePerfil')->storeAs('imagenes', $compPic1);

                    // if ($datos->accion!='nuevo') {
                    //     // dd($datos);
                    //     $imageData=$datos->info;
    
                    //     if ($imageData!='' && $imageData!=null ) {
                    //         $compPic1= HomeController::base64ToImage($imageData);
                    //     }
                    
                    // }
                  
    
                    $usuarios=basicos::find($id);
                    if ($compPic1 != "") {
                        $usuarios->logo = 'storage/imagenes/' . $compPic1;
                        $usuarios->save();

                        $affected = DB::table('users')             
                       ->update(['imgLogoEmpresa' => 'storage/imagenes/' . $compPic1]);
                    }
                }
                //  }


               
              
                
                $mensaje = ["Titulo" => "Éxito", "Respuesta" => "Se guardo la imagen de manera correcta", "Tipo" => "success"];
            
            } catch (\Throwable $th) {
                //throw $th;

                // dd($th);
                $mensaje = ["Titulo" => "Error", "Respuesta" => "no se cambio el estado a facturado de manera correcta", "Tipo" => "error"];
            }
            return json_encode($mensaje);
    }
}
