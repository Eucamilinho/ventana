var vue_clientes = new Vue({
    el: "#app",
    data: {
        clientes: [],
        registro: {
            id: "",
            nombre1: "",
            telefono: "",
            identificacion: "",
            estado: "",
            correo: "",
        },
        botonMostrar: "",
    },
    methods: {
        vaciarCampos() {
            this.registro.id = "";
            this.registro.nombre1 = "";
            this.registro.identificacion = "";
            this.registro.telefono = "";
            this.registro.correo = "";
            $("#phone").val("");
        },
        Nuevo() {
            this.botonMostrar = "Nuevo";
            this.vaciarCampos();
            $("#exampleModal").modal("show");
            $(".accion").prop("disabled", false);
            setTimeout(() => {
                $("#btn_guardar").show();
            }, 500);
        },

        Crear() {
            var nombre1 = this.registro.nombre1;
            if (nombre1 == "") {
                Swal.fire({
                    icon: "warning",
                    title: "Alerta",
                    text: "Por favor ingrese el primer nombre",
                });
                return false;
            }


            vue_global.ajax_peticion("CrearCliente", this.registro, [
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
            if ($.fn.dataTable.isDataTable("#table_clientes")) {
                var table = $("#table_clientes").DataTable();
                table.destroy();
            }
            vue_global.ajax_peticion("ListarClientes", null, [
                (respuesta) => {
                    this.clientes = respuesta.clientes;
                    setTimeout(() => {
                        $("#table_clientes").css("visibility", "visible");
                        $("#table_clientes").DataTable({
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
            vue_global.ajax_peticion("MostrarCliente", this.registro, [
                (respuesta) => {
                    $("#exampleModal").modal("show");
                    this.registro.id = respuesta.cliente.id;
                    this.registro.nombre1 = respuesta.cliente.nombre1;
                    this.registro.identificacion = respuesta.cliente.identificacion;
                    this.registro.telefono = respuesta.cliente.telefono;
                    this.registro.estado = respuesta.cliente.estado;
                    $("#phone").val(respuesta.cliente.telefono);
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
                        vue_global.ajax_peticion(
                            "EliminarCliente",
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
                        vue_global.ajax_peticion(
                            "ActualizarClientes",
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
                            vue_clientes.Mostrar(id);
                            break;
                        case "btneliminarv":
                            vue_clientes.Eliminar(id);
                            break;
                    }
                }
                return;
            }
        }
    });
});
