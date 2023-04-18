@extends('layouts.velzon.master')
@section('title', 'Dashboard')
@section('content_header')
    Dashboard
@stop
@section('content')
    <div id="app" >
       
        <h3>Inicio</h3>
        
        
  </div>
    @stop
    @section('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.2.19/css/lightgallery.min.css" />
        <link rel="stylesheet" type="text/css" href="{{asset('Slicebox-master/css/demo.css')}}" />
		<link rel="stylesheet" type="text/css" href="{{asset('Slicebox-master/css/slicebox.css')}}" />
		<link rel="stylesheet" type="text/css" href="{{asset('Slicebox-master/css/custom.css')}}" />
		<script type="text/javascript" src="{{asset('Slicebox-master/js/modernizr.custom.46884.js')}}"></script>

        <link href="{{ asset('dastone-v2.0/plugins/fullcalendar/packages/core/main.css') }}" rel="stylesheet" />
        <link href="{{ asset('dastone-v2.0/plugins/fullcalendar/packages/daygrid/main.css') }}" rel="stylesheet" />
        <link href="{{ asset('dastone-v2.0/plugins/fullcalendar/packages/bootstrap/main.css') }}" rel="stylesheet" />
        <link href="{{ asset('dastone-v2.0/plugins/fullcalendar/packages/timegrid/main.css') }}" rel="stylesheet" />
        <link href="{{ asset('dastone-v2.0/plugins/fullcalendar/packages/list/main.css') }}" rel="stylesheet" /> 
<style>
    .fc-center > h2{
        color:black !important;
    }
</style>
    @stop

    @section('js')
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/list.pagination.js/list.pagination.min.js') }}"></script>
    
    <!-- listjs init -->
    <script src="{{ URL::asset('build/js/pages/listjs.init.js') }}"></script>
    
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <script>

        $(function() {
            $("#customizerclose-btn").click();
        });

      
             
    </script>
    @stop
 