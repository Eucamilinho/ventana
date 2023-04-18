<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Webklex\IMAP\Client;
use Webklex\IMAP\Facades\Client as ClientImap;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Client as ClientOther;
use Webklex\PHPIMAP\Query\WhereQuery ;
use App\Models\reportes;
use App\Models\ups;
use App\Models\reportes_lines;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class getMailUpsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getMailUps:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'obtener los mensaje de una cuenta que esta en el host';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $client = ClientImap::account('default');

            //  dd($client);

            //Connect to the IMAP Server
            $client->connect();

            //  dd($client);

            //Get all Mailboxes
            /** @var \Webklex\PHPIMAP\Support\FolderCollection $folders */
            $folders = $client->getFolders();

            //Loop through every Mailbox
            /** @var \Webklex\PHPIMAP\Folder $folder */
            // $query=new WhereQuery();
            foreach($folders as $folder){

                // dd( $folder->query()->since('15.03.2018')->get());

                //Get all Messages of the current Mailbox $folder
                /** @var \Webklex\PHPIMAP\Support\MessageCollection $messages */
                // $messages = $folder->messages()->all()->get();

                // $messages = $folder->query()->since('15.03.2018')->get();

                $messages = $folder->query()->since(\Carbon\Carbon::now()->subDays(1))->get();

// $messages = $query->since(\Carbon\Carbon::now()->subDays(5))->get();

                /** @var \Webklex\PHPIMAP\Message $message */
                foreach($messages as $message){

                    //   dd($message->getUid());
                    // echo 'soy el codigo del mensaje tal :'.$message->uid.'<br />';
                    // echo $message->getSubject().'<br />';
                    $descripciondb=strval( $message->getSubject());
                    $asuntoCompleto=strval( $message->getSubject());
                    $asuntoCompleto=str_replace('RV: ','',$asuntoCompleto);
                    $asuntoCompleto=str_replace('RE: ','',$asuntoCompleto);
                    $arrayAsunto=explode(':', $asuntoCompleto);
                    $nombreUps=str_replace('RV: ','',$arrayAsunto[0]);
                    $codigoCorreo=str_replace('RV: ','',$nombreUps);
                    $nombreUps=trim($nombreUps);

                    //SABER SI TIENE LA PALABRA UPS
                    if (strlen(stristr($nombreUps,'UPS'))>0) {
                        if (count($arrayAsunto) > 1) {
                            $asuntoCorreoUps=$arrayAsunto[1];
                        }else{
                            $asuntoCorreoUps=null;
                        }

                        $existeUps=DB::table('ups')
                                    ->whereRaw('codigoUps = "'.$nombreUps.'"')
                                    ->get();
                        if (count($existeUps)> 0) {
                            # code...
                        }else{
                            ups::create([
                                'codigoUps'=>$nombreUps,
                                'idCliente' => 18,
                                'idTecnico' => 8
                            ]);
                        }
                        $existeUps=DB::table('ups')
                                ->where('codigoUps','=',$nombreUps)
                                ->get();
                        if (count($existeUps)> 0) {
                            $idUps=$existeUps[0]->id;
                            $telefonoTec=$existeUps[0]->idTecnico;
                        }else {
                            $idUps=null;

                        }
                        $fechaCrea=date('Y-m-d');

                        $existe=DB::table('reportes')
                                ->where('nombreUps','=',$nombreUps)
                                ->where('fechaCrea','=',$fechaCrea)
                                ->get();

                        if (count($existe) > 0) {
                            # code...
                        }else{
                            $uuid = Str::uuid()->toString();

                            reportes::create([
                            'descripcion'=>$descripciondb,
                            'idCorreo'=>$message->uid,
                            'nombreUps'=>$nombreUps,
                            'asuntoCorreoUps'=>$asuntoCorreoUps,
                            'idUps'=>$idUps,
                            'fechaCrea'=> $fechaCrea,
                            'estado'=>'Pendiente',
                            'uuid' => $uuid,
                            'telefono' => '573154968999'
                            ]);

                            $Ups=DB::table('ups')
                            ->selectRaw('clientes.telefono as telefonocli, tecnicos.telefono as telefonotec,
                            ups.codigoUps as codigoUps, tecnicos.nombre1 as nombretec, tecnicos.apellido1 as apellidotec')
                            ->leftJoin('clientes', 'clientes.id', '=', 'ups.idCliente')
                            ->leftJoin('tecnicos', 'tecnicos.id', '=', 'ups.idTecnico')
                            ->where('ups.id', '=', $idUps)
                            ->get();


                            $telefonoTec=$Ups[0]->telefonotec;
                            $telefonoCli=$Ups[0]->telefonocli;
                            $nombreUps=$Ups[0]->codigoUps;
                            $nombreTec=$Ups[0]->nombretec;
                            $apellidoTec=$Ups[0]->apellidotec;

                            $message="UPS: *".$nombreUps."*\nTécnico: *".$nombreTec." ".$apellidoTec.
                            "* 🧑‍🔧\nDescripción: _".$asuntoCorreoUps."_\n\n¿Es necesario agendar una visita para una revisión o reparación de la UPS?";

                            $response = Http::post('http://localhost:3001/lead', [
                                'message' => $message,
                                'phone' => $telefonoTec,
                                'idP' => $uuid,
                                'Tipo' => 'Tecnico',
                                'idA' =>  $idUps,
                            ]);

                            $data = reportes::latest('id')->first();

                            $reporte=reportes::find($data->id);
                                $reporte->telefonoTec=$telefonoTec;
                                $reporte->telefonoCli=$telefonoCli;
                            $reporte->save();
                        }


                        $existeLines=DB::table('reportes_lines')
                            ->where('nombreUps','=',$nombreUps)
                            ->where('fechaCrea','=',$fechaCrea)
                            ->where('asuntoCorreoUps','=',$asuntoCorreoUps)
                            ->get();

                        if (count( $existeLines) > 0) {
                            # code...
                        }else{

                            reportes_lines::create([
                                'nombreUps'=>$nombreUps,
                                'asuntoCorreoUps'=>$asuntoCorreoUps,
                                'idUps'=>$idUps,
                                'fechaCrea'=> $fechaCrea,
                                'getuid'=>$message->getUid(),
                                'estado' => "Pendiente",
                                'uuid' => $uuid
                            ]);

                        }

                        // echo 'Attachments: '.$message->getAttachments()->count().'<br />';
                        // echo $message->getHTMLBody();

                        //Move the current Message to 'INBOX.read'
                        // if($message->move('INBOX.read') == true){
                        //     echo 'Message has ben moved';
                        // }else{
                        //     // echo 'Message could not be moved';
                        // }
                     }
                }
            }
        } catch (\Throwable $th) {
             dd($th);
            //throw $th;
        }
    }
}
