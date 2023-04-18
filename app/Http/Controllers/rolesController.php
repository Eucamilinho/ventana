<?php

namespace App\Http\Controllers;
use App\Models\Role;
use Illuminate\Http\Request;

class rolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('roles.index');
    }


    
    public function Listar() {
        $roles="";
        try{
            $roles =  Role::get();
            $mensaje = ["Titulo"=>"Éxito","Respuesta"=>"la informaci&oacuten satisfatoria","Tipo"=>"success","roles"=>$roles]; 
        }catch(\Exception $e){
            $mensaje = ["Titulo"=>"Error","Respuesta"=>"Algo salio mal contacte con al administrador del sistema.","Tipo"=>"error","roles"=>$roles]; 
        }
        return json_encode($mensaje);       
    }


    public function Eliminar(){
        $datos=json_decode($_POST['data']);
        try{
            $flight = Role::find($datos->id);
            $flight->delete();
            
               $mensaje = ["Titulo"=>"Éxito","Respuesta"=>"Se eliminó el registro de manera correcta","Tipo"=>"success"]; 
           }catch(\Exception $e){
               $mensaje = ["Titulo"=>"Error","Respuesta"=>"Algo salio mal contacte con al administrador del sistema.","Tipo"=>"error"]; 
           }
           return json_encode($mensaje);
    }


    public function Crear(){
        $datos=json_decode($_POST['data']);
        try{         
            $role = Role::create([
                'name' => $datos->name,
                'description'=>$datos->descripcion                
            ]);               
                       

             $mensaje = ["Titulo"=>"Éxito","Respuesta"=>"Se creó el registro de manera correcta","Tipo"=>"success"]; 
           }catch(\Exception $e){
               $mensaje = ["Titulo"=>"Error","Respuesta"=>"No se creó el registro de manera correcta","Tipo"=>"error"]; 
           }
        return json_encode($mensaje);
    }

    
    public function Mostrar(){
        $datos=json_decode($_POST['data']);
        $role="";
        try{
               $role=Role::find($datos->id);                      
               $mensaje = ["Titulo"=>"Éxito","Respuesta"=>"Se creó el registro de manera correcta","Tipo"=>"success","role"=>$role]; 
           }catch(\Exception $e){
               $mensaje = ["Titulo"=>"Error","Respuesta"=>"No se creó el registro de manera correcta","Tipo"=>"error","role"=>$role]; 
           }
        return json_encode($mensaje);
    }

    public function Actualizar(){
        $datos=json_decode($_POST['data']);
        try{
                $role=Role::find($datos->id);  
                $role->name=$datos->name; 
                $role->description=$datos->descripcion;                            
                $role->save();             
               $mensaje = ["Titulo"=>"Éxito","Respuesta"=>"Se actualizó el registro de manera correcta","Tipo"=>"success"]; 
           }catch(\Exception $e){
               $mensaje = ["Titulo"=>"Error","Respuesta"=>"No se actualizó el registro de manera correcta","Tipo"=>"error"]; 
           }
        return json_encode($mensaje);
        //para imprimir de en la consola en network
        //dd($datos); 
    }
}
