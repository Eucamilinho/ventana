@extends('layouts.velzon.master')
@section('title', 'Perfil')
@section('content_header')
    {{-- Listado de usuarios --}}
@stop
@section('content')
    <div id="app">
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                                      
                <div class="card-body">
                    <div class="dastone-profile">
                        <div class="row">
                            <div class="col-lg-4 align-self-center mb-3 mb-lg-0">
                                <div class="dastone-profile-main">
                                    <div class="dastone-profile-main-pic">
                                        <img src="{{asset('avatar1.png')}}" alt="" height="110" class="rounded-circle">
                                  
                                    </div>
                                    <div class="dastone-profile_user-detail">
                                        <h5 class="dastone-user-name" v-text="usuario.name"></h5>                                                        
                                        <p class="mb-0 dastone-user-name-post">Usuario,Colombia</p>                                                        
                                    </div>
                                </div>                                                
                            </div><!--end col-->
                            <div class="col-lg-4 ms-auto align-self-center">
                                <ul class="list-unstyled personal-detail mb-0">
                                    <li class=""><i class="ti ti-mobile me-2 text-secondary font-16 align-middle"></i> <b> Celular </b> : <span v-text="usuario.celular"></span></li>
                                    <li class="mt-2"><i class="ti ti-email text-secondary font-16 align-middle me-2"></i> <b> Email </b> : <span v-text="usuario.email"></span></li>
                                    {{-- <li class="mt-2"><i class="ti ti-world text-secondary font-16 align-middle me-2"></i> <b> Website </b> : 
                                        <a href="https://mannatthemes.com" class="font-14 text-primary">https://mannatthemes.com</a> 
                                    </li>                                                    --}}
                                </ul>
                            </div><!--end col-->
                            <div class="col-lg-4 align-self-center">
                                <div class="row">
                                </div><!--end row-->                                               
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end f_profile-->                                                                                
                </div><!--end card-body-->          
            </div> <!--end card-->    
        </div><!--end col-->
    </div><!--end row-->
    <div class="pb-4">
        <ul class="nav-border nav nav-pills mb-0" id="pills-tab" role="tablist">           
            <li class="nav-item">
                <a class="nav-link" id="settings_detail_tab" data-bs-toggle="pill" href="#Profile_Settings">Ajustes</a>
            </li>
        </ul>        
    </div><!--end card-body-->
    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">                      
                                    <h4 class="card-title">Imagen  perfil</h4>                      
                                </div><!--end col-->                                                       
                            </div>  <!--end row-->                                  
                        </div><!--end card-header-->
                        <div class="card-body">
                            <input type="file" id="file_imagen" name="file_imagen"
                            class="dropify" data-default-file />
                            <button class="btn btn-outline-success mt-3 btn-sm" @click="GuardarFile('nuevo')"><i class="fas fa-plus"></i>&nbsp;Guardar imagen</button>
                            <button class="btn btn-outline-warning mt-3 btn-sm"  id="btn_mostrar_edit" data-bs-toggle="modal" data-bs-target="#exampleModalLarge"><i class="fas fa-edit"></i>&nbsp;Editar imagen</button>
                        </div>
                    </div>
                    {{-- <img id="image" src="https://concretol.sodeker.com/imagenes/publicidad5.jpeg"> --}}
                  </div>
                <div class="tab-pane fade show active" id="Profile_Settings" role="tabpanel" aria-labelledby="settings_detail_tab">
                    <div class="row">
                        <div class="col-lg-6 col-xl-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">                      
                                            <h4 class="card-title">Información Personal</h4>                      
                                        </div><!--end col-->                                                       
                                    </div>  <!--end row-->                                  
                                </div><!--end card-header-->
                                <div class="card-body">                       
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center" >Nombre</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="text" value="" v-model="usuario.name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Número Celular</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="las la-phone"></i></span>
                                                <input type="text" class="form-control" value="" placeholder="Celular" aria-describedby="basic-addon1" v-model="usuario.celular">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Email</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="las la-at"></i></span>
                                                <input type="text" class="form-control" value="" placeholder="Email" aria-describedby="basic-addon1" v-model="usuario.email" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-9 col-xl-8 offset-lg-3">
                                            <button type="submit" class="btn btn-sm btn-outline-primary" v-if="imagenUser!='' && imagenUser!=null " @click="actualizarDatosPersonales()">Actualizar</button>
                                        </div>
                                    </div>                                                    
                                </div>                                            
                            </div>
                        </div> <!--end col--> 
                        <div class="col-lg-6 col-xl-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Cambiar Contraseña</h4>
                                </div><!--end card-header-->
                                <div class="card-body"> 
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Contraseña Actual</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="password" placeholder="Contraseña actual" v-model="usuario.old_password">
                                            {{-- <a href="#" class="text-primary font-12">Forgot password ?</a> --}}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Nueva Contraseña</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="password" placeholder="Nueva contraseña" v-model="usuario.new_password">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Confirme nueva contraseña</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="password" placeholder="Confirmar nueva contraseña" v-model="usuario.confirm_password">
                                            <span class="form-text text-muted font-12">Nunca comparta su contraseña</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-9 col-xl-8 offset-lg-3">
                                            <button type="submit" class="btn btn-sm btn-outline-primary" @click="actualizarPassword()">Cambiar contraseña</button>
                                            {{-- <button type="button" class="btn btn-sm btn-outline-danger">Cancel</button> --}}
                                        </div>
                                    </div>   
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div> <!-- end col -->    
                    >
                    </div><!--end row-->
                </div><!--end tab-pane-->
            </div><!--end tab-content-->
        </div><!--end col-->
    </div><!--end row-->
    <div class="modal fade bd-example-modal-lg" id="exampleModalLarge" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title m-0" id="myLargeModalLabel">Foto</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><!--end modal-header-->
                <div class="modal-body">
                    <div class="row">
                        <div>
                            <img id="file_imagen_edit" :src="imagenUser">
                        </div>    
                        <button id="btn_recortar" class="btn btn-info btn-sm" style="visibility: hidden"><i class="fa-solid fa-scissors"></i>&nbsp;recortar</button>
                        <div id="result" style="visibility: hidden"></div>
                    </div>                   
                    <!--end row--> 
                </div><!--end modal-body-->
                <div class="modal-footer">
                    <button @click="GuardarFile('')" class="btn btn-success">Actualizar Imagen</button>
                    <button type="button" class="btn btn-soft-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div><!--end modal-footer-->
            </div><!--end modal-content-->
        </div><!--end modal-dialog-->
    </div><!--end modal-->
</div>
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" integrity="sha512-cyzxRvewl+FOKTtpBzYjW6x6IAYUCZy3sGP40hn+DQkqeluGRCax7qztK2ImL64SA+C7kVWdLI6wvdlStawhyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
     /* .btn-close{
        background-color: red;
        color: white;
    }
    .select2{
        width: 100% !important;
    }     */
    .cropper-view-box,
    .cropper-face {
      border-radius: 50%;
    }
    /* The css styles for `outline` do not follow `border-radius` on iOS/Safari (#979). */
    .cropper-view-box {
        outline: 0;
        box-shadow: 0 0 0 1px #39f;
    }
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
{{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js" integrity="sha512-6lplKUSl86rUVprDIjiW8DuOniNX8UDoRATqZSds/7t6zCQZfaCe3e5zcGaQwxa8Kpn5RTM9Fvl3X2lLV4grPQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="/js/global.js"></script> 
<script src="/js/perfiles/perfiles.js?v=<?php echo date('YmdHis'); ?>"></script> 
<script>

    function getRoundedCanvas(sourceCanvas) {
      var canvas = document.createElement('canvas');
      var context = canvas.getContext('2d');
      var width = sourceCanvas.width;
      var height = sourceCanvas.height;
      canvas.width = width;
      canvas.height = height;
      context.imageSmoothingEnabled = true;
      context.drawImage(sourceCanvas, 0, 0, width, height);
      context.globalCompositeOperation = 'destination-in';
      context.beginPath();
      context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI, true);
      context.fill();
      return canvas;
    }
    // window.addEventListener('DOMContentLoaded', function () {
    // });
    var el = document.getElementById("btn_mostrar_edit");
  el.addEventListener("click", function(){
    var image = document.getElementById('file_imagen_edit');
      var button = document.getElementById('btn_recortar');
      var result = document.getElementById('result');
      var croppable = false;
      var cropper = new Cropper(image, {
        aspectRatio: 16 / 9,
        // viewMode: 1,
        ready: function () {
          croppable = true;
        },
      });
      button.onclick = function () {
        var croppedCanvas;
        var roundedCanvas;
        var roundedImage;
        if (!croppable) {
          return;
        }
        // Crop
        croppedCanvas = cropper.getCroppedCanvas();
        // Round
        roundedCanvas = getRoundedCanvas(croppedCanvas);
        // Show
        roundedImage = document.createElement('img');
        roundedImage.src = roundedCanvas.toDataURL()
        roundedImage.id = 'txt_img_redondeda'
        result.innerHTML = '';
        result.appendChild(roundedImage);
      };
  }, false);
</script>
@stop