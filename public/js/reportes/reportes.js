var vue_reportes = new Vue({
    el: "#app",
    data: {
        reportes: [],
        registro: {
            id: "",
            idUps: "",
            estado: "Creado",
            descripcion: "",
            descripcionFin: "",
        },
        ups: [],
        botonMostrar: "",
    },
    methods: {
        vaciarCampos() {
            this.registro.id = "";
            this.registro.descripcion = "";
            this.registro.idUps = "";
            this.registro.descripcionFin = "";
        },

        Nuevo() {
            this.botonMostrar = "Nuevo";
            this.vaciarCampos();
            $("#exampleModal").modal("show");
            $(".accion").prop("disabled", false);
            setTimeout(() => {
                $("#btn_guardar").show();
            }, 500);

            $("#sel_id_ups").val(0).trigger("change");
        },

        Crear() {
            var descripcion = this.registro.descripcion;
            var idUps = $("#sel_id_ups").val();

            if (descripcion == "") {
                Swal.fire({
                    icon: "warning",
                    title: "Alerta",
                    text: "Por favor ingrese la descripción",
                });
                return false;
            }

            if (idUps == "" || idUps == 0) {
                Swal.fire({
                    icon: "warning",
                    title: "Alerta",
                    text: "Por favor seleccione la Ups",
                });
                return false;
            }

            this.registro.idUps = idUps;

            vue_global.ajax_peticion("CrearReporte", this.registro, [
                (respuesta) => {
                    Swal.fire({
                        icon: respuesta.Tipo,
                        title: respuesta.Titulo,
                        text: respuesta.Respuesta,
                        timer: 1500,
                    });
                    if (respuesta.Tipo == "success") {
                        this.CerrarModal();
                        this.vaciarCampos();
                        this.Listar();
                    }
                },
            ]);
        },

        Listar() {
            if ($.fn.dataTable.isDataTable("#table_reportes")) {
                var table = $("#table_reportes").DataTable();
                table.destroy();
            }
            vue_global.ajax_peticion("ListarReportes", null, [
                (respuesta) => {
                    this.reportes = respuesta.reportes;
                    this.ups = respuesta.ups;
                    setTimeout(() => {
                        $("#table_reportes").css("visibility", "visible");
                        $("#table_reportes").DataTable({
                            destroy: true,
                            language: {
                                decimal: "",
                                emptyTable: "No hay información",
                                info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                                infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
                                infoFiltered:
                                    "(Filtrado de _MAX_ total entradas)",
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
            vue_global.ajax_peticion("MostrarReporte", this.registro, [
                (respuesta) => {
                    $("#exampleModal").modal("show");
                    this.registro.id = respuesta.reporte.id;
                    this.registro.descripcion = respuesta.reporte.descripcion;
                    this.registro.descripcionFin = respuesta.reporte.descripcionFin;
                    var id__ups = parseInt(respuesta.reporte.idUps);
                    $("#sel_id_ups").val(id__ups).trigger("change");
                    switch (accion) {
                        case "editar":
                            $(".accion").prop("disabled", true);
                            $("#btn_actualizar").show();
                            $(".cualquiera").prop("disabled", false);
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
                        vue_global.ajax_peticion(
                            "EliminarReporte",
                            this.registro,
                            [
                                (respuesta) => {
                                    Swal.fire({
                                        icon: respuesta.Tipo,
                                        title: respuesta.Titulo,
                                        text: respuesta.Respuesta,
                                        timer: 1500,
                                    });
                                    if (respuesta.Tipo == "success") {
                                        this.Listar();
                                    }
                                },
                            ]
                        );
                    }
                });
        },
        CerrarModal() {
            $("#exampleModal").modal("hide");
        },

        Actualizar() {
            var descripcionFin = this.registro.descripcionFin;
            this.registro.idUps = $("#sel_id_ups").val();
            // this.registro.estado = "Finalizado"; 

            if (descripcionFin == "") {
                Swal.fire({
                    icon: "warning",
                    title: "Alerta",
                    text: "Por favor ingrese una descripción para poder finalizar la novedad",
                });
                return false;
            }        

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });
            swalWithBootstrapButtons
                .fire({
                    title: "¿Está seguro que desea finalizar este registro?",
                    text: "El registro se tomara los cambios",
                    icon: "warning",
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: "Si",
                    cancelButtonText: "No",
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        vue_global.ajax_peticion(
                            "ActualizarReportes",
                            this.registro,
                            [
                                (respuesta) => {
                                    Swal.fire({
                                        icon: respuesta.Tipo,
                                        title: respuesta.Titulo,
                                        text: respuesta.Respuesta,
                                        timer: 1500,
                                    });
                                    if (respuesta.Tipo == "success") {
                                        this.CerrarModal();
                                        this.Listar();
                                    }
                                },
                            ]
                        );
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

        $("#sel_id_ups").select2({
            dropdownParent: $("#exampleModal"),
        });
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
                            vue_reportes.Mostrar(id);
                            break;
                        case "btneliminarv":
                            vue_reportes.Eliminar(id);
                            break;
                    }
                }
                return;
            }
        }
    });
});
