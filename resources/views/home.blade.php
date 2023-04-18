@extends('layouts.dastone')

@section('css')
<style>


/* body {
  padding-top: 20px;
  padding-bottom: 20px;
} */

#draw-canvas {
  border: 2px dotted #CCCCCC;
  border-radius: 5px;
  cursor: crosshair;
}

#draw-dataUrl {
  width: 100%;
}
h3 {
    margin: 10px 15px;
}
/* 
header {
    background: #273B47;
    height: 100%;
    width: 100%;
    padding: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
} */
/* 
section{
    flex:1;
}

h1 {
    margin: 10px 15px;
}
header {
    color: white;
    font-weight: 500;
    padding-left: 15px;
}


.button {
    background: #3071a9;
    box-shadow: inset 0 -3px 0 rgba(0,0,0,.3);
    font-size: 14px;
    padding: 5px 10px;
    border-radius: 5px;
    margin: 0 15px;
    text-decoration: none;
    color: white;
} */

.button:active {
    transform: scale(0.9);
}

.contenedor {
    width: 100%
    margin: 5px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.instrucciones {
    width: 90%;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items:center;
    margin-bottom: 10px;
}

label {
    margin: 0 15px;
}

/* footer {
    background: #273B47;
    color: white;
    height: 100%;
    width: 100%;
    margin-top: 10px;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
} */


input[type=range] {
  -webkit-appearance: none;
  margin: 18px 0;

}
input[type=range]:focus {
  outline: none;
}
input[type=range]::-webkit-slider-runnable-track {
  width: 100%;
  height: 8.4px;
  cursor: pointer;
  animate: 0.2s;
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
  background: #3071a9;
  border-radius: 1.3px;
  border: 0.2px solid #010101;
}
input[type=range]::-webkit-slider-thumb {
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
  border: 1px solid #000000;
  height: 36px;
  width: 16px;
  border-radius: 3px;
  background: #ffffff;
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: -14px;
}
input[type=range]:focus::-webkit-slider-runnable-track {
  background: #367ebd;
}
input[type=range]::-moz-range-track {
  width: 100%;
  height: 8.4px;
  cursor: pointer;
  animate: 0.2s;
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
  background: #3071a9;
  border-radius: 1.3px;
  border: 0.2px solid #010101;
}
input[type=range]::-moz-range-thumb {
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
  border: 1px solid #000000;
  height: 36px;
  width: 16px;
  border-radius: 3px;
  background: #ffffff;
  cursor: pointer;
}
input[type=range]::-ms-track {
  width: 100%;
  height: 8.4px;
  cursor: pointer;
  animate: 0.2s;
  background: transparent;
  border-color: transparent;
  border-width: 16px 0;
  color: transparent;
}
input[type=range]::-ms-fill-lower {
  background: #2a6495;
  border: 0.2px solid #010101;
  border-radius: 2.6px;
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
}
input[type=range]::-ms-fill-upper {
  background: #3071a9;
  border: 0.2px solid #010101;
  border-radius: 2.6px;
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
}
input[type=range]::-ms-thumb {
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
  border: 1px solid #000000;
  height: 36px;
  width: 16px;
  border-radius: 3px;
  background: #ffffff;
  cursor: pointer;
}
input[type=range]:focus::-ms-fill-lower {
  background: #3071a9;
}
input[type=range]:focus::-ms-fill-upper {
  background: #367ebd;
}
</style>
@endsection
@section('content')



<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>

                <div class="contenedor">

                    <div class="row">
                        <div class="col-md-12">
                            <canvas id="draw-canvas" width="620" height="360">
                                No tienes un buen navegador.
                            </canvas>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="button" class="button" id="draw-submitBtn" value="Crear Imagen"></input>
                            <input type="button" class="button" id="draw-clearBtn" value="Borrar Canvas"></input>

                            <label>Color</label>
                            <input type="color" id="color">
                            <label>Tamaño Puntero</label>
                            <input type="range" id="puntero" min="1" default="1" max="5" width="10%">


                        </div>

                    </div>

                    <br />
                    <div class="row">
                        <div class="col-md-12">
                            <textarea id="draw-dataUrl" class="form-control"
                                rows="5">Para los que saben que es esto:</textarea>
                        </div>
                    </div>
                    <br />
                    <div class="contenedor">
                        <div class="col-md-12">
                            <img id="draw-image" src="" alt="Tu Imagen aparecera Aqui!" />
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="/js/firma.js"></script>
@endsection