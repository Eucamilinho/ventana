<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use  App\Models\User;
use  App\Models\ups;
use  App\Models\tecnicos;
use  App\Models\clientes;
use  App\Models\reportes;
use Illuminate\Support\Facades\Storage;
// use  Illuminate\Http\File;
use Illuminate\Support\Facades\File;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard.index');
    }
    public function opciones()
    {
        $Tipouser = Auth::user()->roles->first()->name;
        if ($Tipouser=='asesor' || $Tipouser=='admin' || $Tipouser=='observador' ) {
            return view('opcionesAsesores.index');
        }else{
            return view('opciones.index');
        }
    }

    public function reporteHome() 
    {
        $ups = "";
        $tecnicos = "";
        $clientes = "";
        $reportes = "";

        try {

            // $ups=DB::table('ups')
            // ->selectRaw('ups.codigoUps,COUNT(ups.codigoUps) as ConteoUps')
            // ->get();
            // $tecnicos=DB::table('tecnicos')
            // ->selectRaw('tecnicos.id,COUNT(tecnicos.nombre1) as conteoTecnicos')
            // ->get();
            // $clientes=DB::table('clientes')
            // ->selectRaw('clientes.id,COUNT(clientes.nombre1) as conteoClientes')
            // ->get();
            // $reportes=DB::table('reportes')
            // ->selectRaw('reportes.id,COUNT(reportes.idUps) as conteoReportes')
            // ->get();

            // forma mas sencilla
            $tecnicos=DB::table('tecnicos')
            ->count();
            $clientes=DB::table('clientes')
            ->count();
            $reportes=DB::table('reportes')
            ->count();
            $ups=DB::table('ups')
            ->count();
            
            $mensaje=['Titulo' => 'Éxito', 'Respuesta' => 'Se realizó la consulta de manera correcta.', 'Tipo' => 'success','ups'=>$ups, 'tecnicos'=>$tecnicos, 'clientes' => $clientes, 'reportes' => $reportes];
        } catch (\Throwable $th) {
            $mensaje=['Titulo' => 'Error', 'Respuesta' => 'No se realizó la consulta de manera correcta.', 'Tipo' => 'error', 'ups'=>$ups, 'tecnicos'=>$tecnicos, 'clientes' => $clientes, 'reportes' => $reportes];
        }

        return json_encode($mensaje);
    }


    public function perfil() {
        $Tipouser = Auth::user()->roles->first()->name;       
        return view('perfiles.index');       
    }
    public function MostrarInformacionPerfil()
    {
        try {
           $usuario = Auth::user();   
           $mensaje = ["Titulo" => "Éxito", "Respuesta" => "Mostrar Informacion Perfil", "Tipo" => "success","usuario" => $usuario];
        } catch (\Throwable $th) {
            $mensaje = ["Titulo" => "Error", "Respuesta" => "Mostrar Informacion Perfil", "Tipo" => "error"];
        }
        return json_encode($mensaje);       
    }
    public function updatePassword(Request $request)
    {      
        $data = json_decode($_POST['data']);
        if(!\Hash::check($data->old_password, auth()->user()->password)){
            $mensaje = ["Titulo" => "Error", "Respuesta" => "Ha ingresado una contraseña actual incorrecta", "Tipo" => "error"];
        }else{
            $user = User::find(Auth::user()->id);
            $user->password = bcrypt($data->new_password);
            $user->bpass = $data->new_password;
            if ($user->save()) {             
                $mensaje = ["Titulo" => "Éxito", "Respuesta" => "Se cambió la contraseña de manera correcta", "Tipo" => "success"];
            }
        }
        return json_encode($mensaje);
    }
    public function actualizarDatosPersonales()
    {
        try {
            $data = json_decode($_POST['data']);
            $user = User::find(Auth::user()->id);
            $user->name=$data->name;
            $user->celular=$data->celular;
            $user->save();
            $mensaje = ["Titulo" => "Éxito", "Respuesta" => "Se actualizó de manera correcta la información", "Tipo" => "success"];
        } catch (\Throwable $th) {
            $mensaje = ["Titulo" => "Error", "Respuesta" => "No se actualizó de manera correcta la información", "Tipo" => "error"];
        }
        return json_encode($mensaje);
    }
    public function actualizarCorreoFacturacion()
    {
        try {
            $data = json_decode($_POST['data']);
            $user = User::find(Auth::user()->id);
            $user->correoFacturacion=$data->correoFacturacion;
            $user->save();
            $mensaje = ["Titulo" => "Éxito", "Respuesta" => "Se actualizó el correo de facturación de manera correcta", "Tipo" => "success"];
        } catch (\Throwable $th) {
            $mensaje = ["Titulo" => "Error", "Respuesta" => "No se actualizó el correo de facturación de manera correcta", "Tipo" => "error"];
        }
        return json_encode($mensaje);
    }
    public function graficaBarrasContadorXestado()
    {
        $Tipouser = Auth::user()->roles->first()->name;
        $sumaValorTotal="";
        $ordenes="";
       try {
        if ($Tipouser=='admin' || $Tipouser=='observador' || $Tipouser=='aprobador') {
            $ordenes=DB::table('programacionesasesores')->distinct()            
                        ->selectRaw(' count(programacionesasesores.id) as data , programacionesasesores.colortr as name ')          
                        ->whereRaw('YEAR(programacionesasesores.fechaCrea) = YEAR(CURRENT_DATE())')
                        ->groupBy('programacionesasesores.colortr')
                        ->get();
            $sumaValorTotal=DB::table('programacionesasesores')  
                            ->selectRaw(' ROUND(COUNT(programacionesasesores.id),0)  as valor,programacionesasesores.colortr')                        
                            ->whereRaw('YEAR(programacionesasesores.fechaCrea) = YEAR(CURRENT_DATE())')
                            ->groupBy('programacionesasesores.colortr')
                            ->get();
        }else {
            $ordenes=DB::table('programacionesasesores')->distinct()
                        ->selectRaw(' count(programacionesasesores.id) as data , programacionesasesores.colortr as name')         
                        ->whereRaw('programacionesasesores.idUsuarioCrea='.Auth::user()->id.' and YEAR(programacionesasesores.fechaCrea) = YEAR(CURRENT_DATE())')
                        ->groupBy('programacionesasesores.colortr')
                        ->get();
            $sumaValorTotal=DB::table('programacionesasesores')
                        ->selectRaw(' ROUND(COUNT(programacionesasesores.id),0)  as valor,programacionesasesores.colortr')
                        ->whereRaw('programacionesasesores.idUsuarioCrea ='.Auth::user()->id.' and YEAR(programacionesasesores.fechaCrea) = YEAR(CURRENT_DATE())')
                        ->groupBy('programacionesasesores.colortr')
                        ->get();
        }
        //  dd($ordenes);
      $arrayEstado=[];
        for ($i=0; $i < count($ordenes) ; $i++) {
            $contendio='';
            $contendio.='name=>'.$ordenes[$i]->name.',';
            $contendio.='data=>['.$ordenes[$i]->data.']';
            $contendio.='';
            if ($ordenes[$i]->name!="" ||  $ordenes[$i]->name!=null ) {
                $contendio=(object)[
                    'name' => $ordenes[$i]->name,
                    'data' => [$ordenes[$i]->data],
                ];
                array_push($arrayEstado, $contendio);
            }
        }
        //fecha
        $year=date('Y');
        $m=date('m');
        $d=date('d');
        if ($Tipouser=='admin'  || $Tipouser=='aprobador' || $Tipouser=='observador') {
            $ordenesMeses = DB::table('programacionesasesores')->distinct()
                            ->selectRaw('ROUND(COUNT(programacionesasesores.ID),2) as data , DATE_FORMAT(programacionesasesores.fechaCrea, "%m" ) as mes ')
                            ->whereRaw('YEAR(programacionesasesores.fechaCrea) = YEAR(CURRENT_DATE()) and programacionesasesores.colortr LIKE "%table-success%" ')        
                            ->groupByRaw('DATE_FORMAT(programacionesasesores.fechaCrea, "%m-%'.$year.'")')
                            ->get();
         }else {
            $ordenesMeses = DB::table('programacionesasesores')->distinct()                  
                            ->selectRaw('programacionesasesores.idUsuarioCrea ='.Auth::user()->id.' ROUND(COUNT(programacionesasesores.ID),2) as data , DATE_FORMAT(programacionesasesores.fechaCrea, "%m" ) as mes ')
                            ->whereRaw('YEAR(programacionesasesores.fechaCrea) = YEAR(CURRENT_DATE()) and programacionesasesores.colortr LIKE "%table-success%" ')        
                            ->groupByRaw('DATE_FORMAT(programacionesasesores.fechaCrea, "%m-%'.$year.'")')
                            ->get();
         }
        $arrayValorxMes=[];
        $arrayValorxMesPrueba=['0','0','0','0','0','0','0','0','0','0','0','0'];
        $arrayCategorias=[];
        $contendio='';
        for ($i=0; $i < count($ordenesMeses) ; $i++) {
            if ($ordenesMeses[$i]->data!="" ||  $ordenesMeses[$i]->data!=null ) {
            switch ($ordenesMeses[$i]->mes) {
                case '01':
                    $contendio2='Ene';
                    array_push($arrayCategorias, $contendio2);
                    $pos=0;
                break;
                case '02':
                    $contendio2='Feb';
                    array_push($arrayCategorias, $contendio2);
                    $pos=1;
                break;
                case '03':
                    $contendio2='Mar';
                    array_push($arrayCategorias, $contendio2);
                    $pos=2;
                break;
                case '04':
                    $contendio2='Abr';
                    array_push($arrayCategorias, $contendio2);
                    $pos=3;
                break;
                case '05':
                    $contendio2='May';
                    array_push($arrayCategorias, $contendio2);
                    $pos=4;
                break;
                case '06':
                    $contendio2='Junio';
                    array_push($arrayCategorias, $contendio2);
                    $pos=5;
                break;
                case '07':
                    $contendio2='Jul';
                    array_push($arrayCategorias, $contendio2);
                    $pos=6;
                break;
                case '08':
                    $contendio2='Ago';
                    array_push($arrayCategorias, $contendio2);
                    $pos=7;
                break;
                case '09':
                    $contendio2='Sep';
                    array_push($arrayCategorias, $contendio2);
                    $pos=8;
                break;
                case '10':
                    $contendio2='Oct';
                    array_push($arrayCategorias, $contendio2);
                    $pos=9;
                break;
                case '11':
                    $contendio2='Nov';
                    array_push($arrayCategorias, $contendio2);
                    $pos=10;
                break;
                case '12':
                    $contendio2='Dic';
                    array_push($arrayCategorias, $contendio2);
                    $pos=11;
                break;
            }
        }
            if ($ordenesMeses[$i]->data!="" ||  $ordenesMeses[$i]->data!=null ) {
                $valor=$ordenesMeses[$i]->data;
                //  $valor=str_replace(',','.',$valor);
                //  $valor=number_format(floatval($valor), 2, ',', '.');
                $valor=floatval($valor);
                $contendio=(object)[
                    'name' => $contendio2,
                    'data' => [$valor],
                ];
                array_push($arrayValorxMes, $contendio);
                $arrayValorxMesPrueba[$pos]=$valor;
            }
        }
        if ($Tipouser=='admin' || $Tipouser=='observador' || $Tipouser=='aprobador' ) {
            $valorTotal = DB::table('programacionesasesores')->distinct()
                        ->selectRaw('ROUND(COUNT(programacionesasesores.id),0) as total')
                        ->whereRaw('YEAR(programacionesasesores.fechaCrea) = YEAR(CURRENT_DATE()) and programacionesasesores.colortr LIKE "%table-success%"')                       
                        ->get();
        }else {
            $valorTotal = DB::table('repuestos')->distinct()                       
                        ->selectRaw('ROUND(COUNT(programacionesasesores.id),0) as total')
                        ->whereRaw('YEAR(programacionesasesores.fechaCrea) = YEAR(CURRENT_DATE())  and programacionesasesores.idUsuarioCrea ='.Auth::user()->id.' and programacionesasesores.colortr LIKE "%table-success%"')
                        ->get();
        }
        $arrayValorxDias=['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'];
        $from = date($year.'-'.$m.'-01');
        $to = date($year.'-'.$m.'-31');
        // if ($Tipouser=='admin'  || $Tipouser=='aprobador' || $Tipouser=='observador') {
        $ordenesxDias = DB::table('programacionesasesores')                    
                    ->selectRaw('ROUND(count(programacionesasesores.id),0) as data , DATE_FORMAT(programacionesasesores.fechaCrea, "%d" ) as dia  , DATE_FORMAT(programacionesasesores.fechaCrea, "%d-%m" ) as fecha  , DATE_FORMAT(programacionesasesores.fechaCrea, "%m" ) as mes ')
                    ->whereRaw(' programacionesasesores.fechaCrea Between "'.$from.'" and "'.$to .'" and programacionesasesores.colortr LIKE "%table-success%"')
                    ->groupByRaw('DATE_FORMAT(programacionesasesores.fechaCrea, "%d-%m")')
                    ->get();
        //  }else {
        //     $ordenesxDias = DB::table('repuestos')
        //                     ->leftJoin('ordenes', 'programacionesasesores.id', '=', 'repuestos.orden_id')
        //                     ->selectRaw('ROUND(SUM(repuestos.valorTotal),2) as data , DATE_FORMAT(programacionesasesores.fechaCrea, "%d" ) as dia  , DATE_FORMAT(programacionesasesores.fechaCrea, "%d-%m" ) as fecha  , DATE_FORMAT(programacionesasesores.fechaCrea, "%m" ) as mes ')
        //                     ->whereRaw(' programacionesasesores.fechaCrea Between "'.$from.'" and "'.$to .'" and ( programacionesasesores.estado="FACTURADO" or programacionesasesores.estado="TERMINADO") and programacionesasesores.id_usuario ='.Auth::user()->id.' ')
        //                     ->groupByRaw('DATE_FORMAT(programacionesasesores.fechaCrea, "%d-%m")')
        //                     ->get();
        //  }
        //    dd($ordenesxDias);
          for ($i=0; $i < count($ordenesxDias); $i++) { 
            $diaMes=$ordenesxDias[$i]->dia;
            $valor=$ordenesxDias[$i]->data;
            $mes=$ordenesxDias[$i]->mes;
             switch ($diaMes) {
                case '01':
                    $pos=0;
                break;
                 case '02':
                    $pos=1;
                break;
                case '03':
                    $pos=2;
                break;
                case '04':
                    $pos=3;
                break;
                case '05':
                    $pos=4;
                break;
                case '06':
                    $pos=5;
                break;
                case '07':
                    $pos=6;
                break;
                case '08':
                    $pos=7;
                break;
                case '09':
                    $pos=8;
                break;
                case '10':
                    $pos=9;
                break;
                case '11':
                    $pos=10;
                break;
                case '12':
                    $pos=11;
                break;
                case '13':
                    $pos=12;
                break;
                case '14':
                    $pos=13;
                break;
                case '15':
                    $pos=14;
                break;
                case '16':
                    $pos=15;
                break;
                case '17':
                    $pos=16;
                break;
                case '18':
                    $pos=17;
                break;
                case '19':
                    $pos=18;
                break;
                case '20':
                    $pos=19;
                break;
                case '21':
                    $pos=20;
                break;
                case '22':
                    $pos=21;
                break;
                case '23':
                    $pos=22;
                break;
                case '24':
                    $pos=23;
                break;
                case '25':
                    $pos=24;
                break;
                case '26':
                    $pos=25;
                break;
                case '27':
                    $pos=26;
                break;
                case '28':
                    $pos=27;
                break;
                case '29':
                    $pos=28;
                break;
                case '30':
                    $pos=29;
                break;
                case '31':
                    $pos=30;
                break;
                default:
                $pos='NO';
                  break;
             }
             if ($pos!='NO' && $mes==$m) {
                $arrayValorxDias[$pos]=$valor;
             }
          }
        $mensaje = ["Titulo" => "Éxito", "Respuesta" => "la informaci&oacuten satisfatoria", "Tipo" => "success","ordenes"=>$arrayEstado,"ordenesMes"=>$arrayValorxMes,"categoriasMesValor"=>$arrayCategorias,"cantidad"=>$ordenes,"valorTotal"=>$valorTotal,"arrayValorxMesPrueba"=>$arrayValorxMesPrueba,"arrayValorxDias"=>$arrayValorxDias,"sumaValorTotal"=>$sumaValorTotal];
       } catch (\Throwable $th) {
        // dd($th);
        $mensaje = ["Titulo" => "Error", "Respuesta" => "Disculpe, se presentó un problema al listar", "Tipo" => "error"];
       }
       return json_encode($mensaje);
    }

    public function GuardarFilePerfil(Request $request)
    {
        $Tipouser = Auth::user()->roles->first()->name;
        $idUsuario = Auth::user()->id;
        $compPic1="";
            try {
                $string = $request->data;
                $datos = json_decode($string);

                if ($datos->accion=='nuevo') {
                //tomar toda la informacion
                    if ($request->hasFile('filePerfil')) {
                        $nombreCompletoArchvio = $request->file('filePerfil')->getClientOriginalName();
                        $nombreDelArchivo = pathinfo($nombreCompletoArchvio, \PATHINFO_FILENAME);
                        $extension = $request->file('filePerfil')->getClientOriginalExtension();
                        $compPic1 = str_replace(' ', '_', $nombreDelArchivo) . '-' . rand() . '_' . time() . '.' . $extension;
                        $path = $request->file('filePerfil')->storeAs('imagenes', $compPic1);
                    }
                 }

                if ($datos->accion!='nuevo') {
                    // dd($datos);
                    $imageData=$datos->info;
                    if ($imageData!='' && $imageData!=null ) {
                        $compPic1= HomeController::base64ToImage($imageData);
                    }                
                }
              

                $usuarios=User::find($idUsuario);
                if ($compPic1 != "") {
                    $usuarios->imgUser = 'storage/imagenes/' . $compPic1;
                    $usuarios->save();
                }
              
                
                $mensaje = ["Titulo" => "Éxito", "Respuesta" => "Se guardo la imagen de manera correcta", "Tipo" => "success"];
            
            } catch (\Throwable $th) {
                //throw $th;

                 
                $mensaje = ["Titulo" => "Error", "Respuesta" => "no se cambio el estado a facturado de manera correcta", "Tipo" => "error"];
            }
            return json_encode($mensaje);
    }

    public function base64ToImage($imageData){
        // $path=

        // $path = "http://" . $_SERVER["SERVER_NAME"].'storage/imagenes/';
        // $data = 'data:image/png;base64,AAAFBfj42Pj4';
        // list($type, $imageData) = explode(';', $imageData);
        // list(,$extension) = explode('/',$type);
        // list(,$imageData)      = explode(',', $imageData);
        $extension='jpg';
        $base64_str = substr($imageData, strpos($imageData, ",")+1);
        // dd($base64_str);
        $fileName = uniqid().'.'.$extension;
        $imageData = base64_decode($base64_str);
        // dd($imageData);
        // file_put_contents($path."/".$fileName, $imageData);
        // dd(storage_path());
        //  File::put(storage_path(). '/imagenes/' .  $fileName, $imageData);
        // dd($fileName);
         Storage::disk('imagenes')->put($fileName, $imageData);
        return $fileName;
    }

    public function MostrarCalendarioAgendas()   {
     
        $eventosArray=[];     
        $role=Auth::user()->roles()->first()->name;
        $idUsario = Auth::user()->id;    
        $identificacion = Auth::user()->identificacion;   
     
     
        try{        
            if ($role=='admin' ) {
                $idUserLogin=Auth::user()->id;             
                $eventosPedidos = DB::table('agendas')
                            ->selectRaw('agendas.fecha,ups.codigoUps,agendas.hora,agendas.id')  
                            ->leftJoin('reportes', 'reportes.uuid', '=', 'agendas.idNovedad')
                            ->leftJoin('ups', 'ups.id', '=', 'reportes.idUps')
                            ->leftJoin('clientes', 'clientes.id', '=', 'ups.idCliente')
                            ->leftJoin('tecnicos', 'tecnicos.id', '=', 'ups.idTecnico')               
                            ->orderBy('fecha','DESC')
                            ->limit(60)
                            ->get();     
                                
                                // dd($eventosPedidos);
            }else {
                $eventosPedidos = DB::table('agendas')
                                ->selectRaw('agendas.fecha,ups.codigoUps,agendas.hora,agendas.id')
                                ->leftJoin('reportes', 'reportes.uuid', '=', 'agendas.idNovedad')
                                ->leftJoin('ups', 'ups.id', '=', 'reportes.idUps')
                                ->leftJoin('clientes', 'clientes.id', '=', 'ups.idCliente')
                                ->leftJoin('tecnicos', 'tecnicos.id', '=', 'ups.idTecnico') 
                                ->where('tecnicos.identificacion','=',$identificacion)              
                                ->orderBy('fecha','DESC')
                                ->limit(60)
                                ->get();
            }
            foreach ($eventosPedidos as $key) {
                $obj = new \stdClass();
                $obj->title="Cita ".$key->codigoUps." ".$key->hora ;
                $obj->start =$key->fecha."T".$key->hora.":00";
                $obj->allDay ="true";
                // $obj->end =$key->fechaPedido.'T23:59:59';
                $obj->className= "bg-soft-info";
                $obj->id=$key->id;
                array_push($eventosArray,$obj);
            }

             $mensaje = ["Titulo"=>"Éxito","Respuesta"=>"la informaci&oacuten satisfatoria","Tipo"=>"success","eventosArray"=>$eventosArray
            
            ]; 
        }catch(\Exception $e){
           
             $mensaje = ["Titulo"=>"Error","Respuesta"=>"Algo salio mal contacte con al administrador del sistema.","Tipo"=>"error","eventosArray"=>$eventosArray
            ]; 
        }
        return json_encode($mensaje);   
    
    }

    public function mostrarEvento()
    {
        $datos = json_decode($_POST['data']);
        $registro="";
        try {
            $id=$datos->id;
            $registro= DB::table('agendas')
                    ->selectRaw('agendas.fecha,ups.codigoUps,agendas.hora,agendas.id,clientes.nombre1 as nomcli,tecnicos.nombre1 as nomtec ')  
                    ->leftJoin('reportes', 'reportes.uuid', '=', 'agendas.idNovedad')
                    ->leftJoin('ups', 'ups.id', '=', 'reportes.idUps')
                    ->leftJoin('clientes', 'clientes.id', '=', 'ups.idCliente')
                    ->leftJoin('tecnicos', 'tecnicos.id', '=', 'ups.idTecnico')
                    ->where('agendas.id','=',$id)              
                    ->get(); 
            $mensaje = ["Titulo"=>"Éxito","Respuesta"=>"la informaci&oacuten satisfatoria","Tipo"=>"success","registro"=>$registro];
        } catch (\Throwable $th) {
            $mensaje = ["Titulo"=>"Error","Respuesta"=>"Algo salio mal contacte con al administrador del sistema.","Tipo"=>"error","registro"=>$registro];
        }
        return json_encode($mensaje);  
    }
}
