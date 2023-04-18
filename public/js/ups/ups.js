var vue_ups = new Vue({
  el: "#app",
  data: {
    ups: [],
    clientes: [],
    tecnicos: [],
    registro: { 
      id: "",
      codigoUps: "",
      direccion: "",
      idCliente: "",
      idTecnico: "",
    },
    botonMostrar: "",
  },
  methods: {
    vaciarCampos() {
      this.registro.id = "";
      this.registro.codigoUps = "";
      this.registro.direccion = "";
      this.registro.idCliente = "";
      this.registro.idTecnico = "";
    },
    Nuevo() {
      this.botonMostrar = "Nuevo";
      this.vaciarCampos();
      $("#exampleModal").modal("show");
      $(".accion").prop('disabled',false);
      setTimeout(() => {

        $("#btn_guardar").show();
      }, 500);
      $("#sel_id_cliente").val(0).trigger('change')
      $("#sel_id_tecnico").val(0).trigger('change')
    },
    Crear() {

      var direccion= this.registro.direccion;
      var codigoUps= this.registro.codigoUps;
      var idCliente = $("#sel_id_cliente").val();
      var idTecnico = $("#sel_id_tecnico").val();

      if (idTecnico=="" || idTecnico == 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Alerta',
          text: 'Por favor seleccione el Tecnico',
        })
        return false;
      }

      if (idCliente=="" || idCliente == 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Alerta',
          text: 'Por favor seleccione el Cliente',
        })
        return false;
      }

      if (codigoUps=="") {
        Swal.fire({
          icon: 'warning',
          title: 'Alerta',
          text: 'Por favor ingrese el código de la UPS',
        })
        return false;
      }
      if (direccion=="") {
        Swal.fire({
          icon: 'warning',
          title: 'Alerta',
          text: 'Por favor ingrese la dirección',
        });
        return false;
      }
      this.registro.idCliente = idCliente;
      this.registro.idTecnico = idTecnico;

      vue_global.ajax_peticion("CrearUps", this.registro, [
        (respuesta) => {
          Swal.fire({
            icon: respuesta.Tipo,
            title: respuesta.Titulo,
            text: respuesta.Respuesta,
            timer: 1500,
          })
          if(respuesta.Tipo=='success') {
            this.CerrarModal();
            this.vaciarCampos();
            this.Listar();
          };
        },
      ]);
    },
    Listar() {
      if ($.fn.dataTable.isDataTable("#table_ups" )) {
        var table = $("#table_ups" ).DataTable();
        table.destroy();
      }
      vue_global.ajax_peticion("ListarUps", null, [
        (respuesta) => {


          this.ups = respuesta.Ups;
          this.clientes = respuesta.clientes;
          this.tecnicos = respuesta.tecnicos;
          setTimeout(() => {
            $("#table_ups").css("visibility", "visible");
            $("#table_ups").DataTable({
              destroy: true,
              language: {
                decimal: "",
                emptyTable: "No hay información",
                info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
                infoFiltered: "(Filtrado de _MAX_ total entradas)",
                infoPostFix: "",
                thousands: ",",
                lengthMenu: "Mostrar _MENU_ Entradas",
                loadingRecords: "Cargando...",
                processing: "Procesando...",
                search: "Buscar:",
                zeroRecords: "Sin resultados encontrados",
                paginate: {
                  first: "Primero",
                  last: "Ultimo",
                  next: "Siguiente",
                  previous: "Anterior",
                },
              },
            });
          }, 500);
        },
      ]);
    },
    Mostrar(id, accion) {

      this.botonMostrar = "Mostrar";
      this.registro.id = id;
      vue_global.ajax_peticion("MostrarUps", this.registro, [
        (respuesta) => {
          console.log(respuesta);
          $("#exampleModal").modal("show");

          this.registro.id = respuesta.ups.id;
          this.registro.codigoUps = respuesta.ups.codigoUps;
          this.registro.direccion = respuesta.ups.direccion;
          this.registro.idCliente = respuesta.ups.idCliente;
          this.registro.idTecnico = respuesta.ups.idTecnico;

          $("#sel_id_cliente").val(respuesta.ups.idCliente).trigger('change');
          $("#sel_id_tecnico").val(respuesta.ups.idTecnico).trigger('change');

          switch (accion) {
            case "editar":
              $(".accion").prop("disabled", false);
              $("#btn_actualizar").show();
              break;
            case "ver":
              $(".accion").prop("disabled", true);
              $("#btn_actualizar").hide();
              break;
          }
        },
      ]);
    },
    Eliminar(id) {
      this.registro.id = id;
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
      });
      swalWithBootstrapButtons
        .fire({
          title: "¿Está seguro que desea eliminar este registro?",
          text: "El registro se eliminara permanentemente",
          icon: "warning",
          showCancelButton: true,
          showCloseButton: true,
          confirmButtonText: "SI, eliminar!",
          cancelButtonText: "No, cancelar!",
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
            vue_global.ajax_peticion("EliminarUps", this.registro, [
              (respuesta) => {
                Swal.fire({
                  icon: respuesta.Tipo,
                  title: respuesta.Titulo,
                  text: respuesta.Respuesta,
                  timer: 1500,
                })
                if (respuesta.Tipo == "success") {
                  this.Listar();
                }
              },
            ]);
          }
        });
    },
    CerrarModal() {
      $("#exampleModal").modal("hide");
    },
    Actualizar() {

      this.registro.idCliente = $("#sel_id_cliente").val();
      this.registro.idTecnico = $("#sel_id_tecnico").val();

      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
      });
      swalWithBootstrapButtons
        .fire({
          title: "¿Está seguro que desea actualizar este registro?",
          text: "El registro se tomara los cambios",
          icon: "warning",
          showCancelButton: true,
          showCloseButton: true,
          confirmButtonText: "SI, actualizar!",
          cancelButtonText: "No, cancelar!",
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
            vue_global.ajax_peticion("ActualizarUps", this.registro, [
              (respuesta) => {
                Swal.fire({
                  icon: respuesta.Tipo,
                  title: respuesta.Titulo,
                  text: respuesta.Respuesta,
                  timer: 1500,
                });
                if (respuesta.Tipo == "success") {
                  console.log(respuesta);
                  this.CerrarModal();
                  this.Listar();
                }
              },
            ]);
          }
        });
    },
  },
  /**
   * Mounted es lo PRIMERO que ocurre cuando se carga la pagina
   */
  mounted() {
    /**
     * Cuando se carga la pagina necesito recibir
     */

    this.Listar();

    $(".select2").select2({
        dropdownParent: $('#exampleModal')
    })

  },
});

$(function () {
  //cuando la datatable se pone pequeña  no funciona de manera normal el vue
  //ya que la libreria  construye la tabla utilizando child
  $("tbody").on("click", "td", function (e) {
    if (screen.width <= 1920) {
      if ($(e.target).is("button")) {
        // if ($(e.target).is(":not(td)") && $(e.target).is(":not(i)")) {
        //ingresamos a la propiedad del objeto y capturamos la clase para identificar la accion
        var clase = $(e.target)[0].classList[3];
        var id = $(e.target)[0].id;
        if (clase != undefined) {
          switch (clase) {
            case "btnmostrarv":
              vue_ups.Mostrar(id);
              break;
            case "btneliminarv":
              vue_ups.Eliminar(id);
              break;
          }
        }
        return;
      }
    }
  });
});

$("#sel_id_cliente").change(function () {
  var idcli = $("#sel_id_cliente").val();
  vue_ups.registro.idCliente = idcli;
  console.log(idcli);
})
