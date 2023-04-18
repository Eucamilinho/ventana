@extends('layouts.velzon.master')
@section('title', 'Usuarios')
@section('content_header')
    Listado Usuarios
@stop
@section('content')
    <div  id="app" style="padding: 1%;">
            <div class="card">
            <div class="btn-group-sm" style=" padding: 1%;">
            <button class="btn btn-success btn-sm" @click="Nuevo()"><i class="fas fa-plus"></i>&nbsp;Nuevo</button>
            </div>
            <div style="padding: 1%;">
                <table class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%; visibility:hidden" id="table_usuarios">
                    <thead class="thead-light">
                        <th>Consecutivo</th>
                        <th>Nombre</th>
                        <th>Identificacion</th>
                        <th>Rol</th>
                        {{-- <th>Fecha Menbresia</th> --}}
                        <th class="all"> Acciones</th>
                    </thead>
                    <tbody>
                    <tr v-for="user in usuarios">
                    <td>@{{user.id}}</td>
                    <td>@{{user.name}}</td>   
                    <td>@{{user.identificacion}}</td>   
                    <td>@{{user.descript}}</td> 
                    {{-- <td><span v-if="user.descript=='Proveedor' || user.descript=='Proveedor Observador' ">@{{user.fechaVencimientoMembresia}}</span> <span v-else> Iliminitada </span></td>                 --}}
                    <td>
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-fill align-middle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="">
                                <li @click="Mostrar(user.id,'ver')"><a href="#!" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> Ver</a></li>
                                <li @click="Mostrar(user.id,'editar')"><a class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Editar</a></li>
                                <li @click="Eliminar(user.id)">
                                    <a class="dropdown-item remove-item-btn">
                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Eliminar
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                    {{-- <button class="btn  btn-sm btn-info btnmostrarv" :id="user.id" @click="Mostrar(user.id,'ver')" title="Editar"><i class="far fa-eye "  style="color:antiquewhite"></i>&nbsp;</button>
                    <button class="btn  btn-sm btn-warning btnmostrarv" :id="user.id" @click="Mostrar(user.id,'editar')" title="Editar"><i class="far fa-edit "  style="color:antiquewhite"></i>&nbsp;</button>
                    <button class="btn  btn-sm btn-danger btneliminarv" :id="user.id" @click="Eliminar(user.id)" title="Eliminar"><i class="fas fa-trash-alt "  ></i>&nbsp;</button>
                    <button class="btn  btn-sm btn-info btnmenbresiav" :id="user.id" @click="ActualizarMembresia(user.id)" title="Actualizar menbresia"  v-if="user.descript=='Proveedor' || user.descript=='Proveedor Observador'"><i class="fas fa-calendar "  ></i>&nbsp;</button> --}}
                    
                    </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            </div> 
            <!-- modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Usuario</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close" @click="CerrarModal()"></button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label class="form-label" for="">Nombre</label>
                                    <input type="text" v-model="registro.name" class="form-control accion">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-3">
                                    <label  class="form-label" for="">Correo Electrónico</label>
                                    <input type="text" v-model="registro.email" class="form-control accion">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-3">
                                    <label class="form-label"  for="">Identificación</label>
                                    <input type="text" v-model="registro.identificacion" class="form-control accion">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-3">
                                    <label class="form-label"  for="">Teléfono</label>
                                    <input type="text" v-model="registro.telefono" class="form-control accion">
                                </div>
                            </div>
                            <div  class="form-group" id="div_sucursal">
                                <div class="mb-3" id="campo_sucursal">
                                    <label class="form-label"  for="">Sucursal</label>
                                    <select id="sel_missucursal" name=""   v-model="registro.sucursal"  class="form-control select2 accion"  >
                                                <option v-for="role in missucursales" :value="role.id">
                                                    @{{ role.descripcion }}
                                                </option>   
                                        </select>
                                </div>   
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div  class="form-group">
                                <div class="mb-3">
                                    <label class="form-label"  for="">Rol</label>
                                    <select id="sel_registro_role" name=""   v-model="registro.role"  class="form-control select2 accion"  style="border: 1px solid #e3ebf6 !important;" >
                                                <option v-for="role in roles" :value="role.id">
                                                    @{{ role.description }}
                                                </option>   
                                        </select>
                                </div>   
                            </div>
                            <div  class="form-group">
                            <div class="mb-3 ">
                                <label for="" class="form-label btnaccion">Departamento</label>
                                <select name="" id="sel_departamento_det" class="form-control select2 btnaccion accion" style="border: 1px solid #e3ebf6 !important;">

                                    <option value="0">Seleccione...</option>
                                    <option v-for="depar in departamentos" v-bind:value="depar.id_departamento">
                                        @{{ depar.departamento }}
                                    </option>
                                </select>
                            </div>
                        </div>
        
                            <div  class="form-group">
                            <div class=" mb-3 ">
                                <label for="" class=" form-label btnaccion">Municipio</label>
                                <select name="" id="sel_municipio_sel" class="form-control select2 btnaccion accion" style="border: 1px solid #e3ebf6 !important;">
                                    <option value="0">Seleccione...</option>
                                    <option v-for="municipiosfiltrado in municipiosfiltrados" v-bind:value="municipiosfiltrado.id_municipio">
                                        @{{ municipiosfiltrado.municipio }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"  for="">Estado</label>
                            <select id="sel_registro_estado" name=""   v-model="registro.estado"  class="form-control select2 accion" style="border: 1px solid #e3ebf6 !important;"  >                                       
                                        <option :value="1">Activo</option>
                                        <option :value="0">Inactivo</option>
                                </select>
                        </div>   
                    </div>
                        </div>
                    </div>
                  
                   

                  
                    <div  class="form-group">
                 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success  btn-sm"  @click="Crear()" v-if="botonMostrar=='Nuevo'">Guardar</button>
                    <button type="button" id="btn_actualizar" class="btn btn-success  btn-sm" @click="Actualizar()" v-else>Actualizar</button>
                    <button type="button" class="btn btn-secondary  btn-sm" data-bs-dismiss="modal" @click="CerrarModal()">Cerrar</button>
                </div>
                </div>
            </div>
            </div>
            <!-- fin modal -->

            <div class="modal fade bd-example-modal-sm" id="modalFechaVencimiento" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title m-0" id="mySmallModalLabel">Cambiar fecha de vencimiento </h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div><!--end modal-header-->
                        <div class="modal-body text-center">
                            <label class="form-label"  for="">Fecha de vencimiento membresía</label>

                           <input type="date" class="form-control" v-model="registro.fechaVencimiento"> 

                        </div><!--end modal-body-->
                        <div class="modal-footer">                                                    
                            <button type="button" class="btn btn-soft-success btn-sm" @click="actualizarFechaVencimiento()">Actualizar</button>
                            <button type="button" class="btn btn-soft-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                        </div><!--end modal-footer-->
                    </div><!--end modal-content-->
                </div><!--end modal-dialog-->
            </div><!--end modal-->



    </div> 
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
     /* .btn-close{
        background-color: red;
        color: white;
    }
    .select2{
        width: 100% !important;
    }     */
    </style>
@stop
@section('js')

<script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
 {{-- librerias extgernas --}}
<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.js" integrity="sha512-otOZr2EcknK9a5aa3BbMR9XOjYKtxxscwyRHN6zmdXuRfJ5uApkHB7cz1laWk2g8RKLzV9qv/fl3RPwfCuoxHQ==" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/js/global.js"></script> 
<script src="/js/usuarios/usuarios.js?v=<?php echo date('YmdHis'); ?>"></script> 


@stop