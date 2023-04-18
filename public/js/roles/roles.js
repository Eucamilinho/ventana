var vue_roles = new Vue({
    el: "#app",
    data: {
        roles: [],
        registro: {
            id: "",
            name: "",
            descripcion: "",
        },
        botonMostrar: "",
    },
    methods: {
        vaciarCampos() {
            this.registro.id = "";
            this.registro.name = "";
            this.registro.descripcion = "";
            $(".accion").prop("disabled", false);
        },
        Nuevo() {
            this.botonMostrar = "Nuevo";
            this.vaciarCampos();
            $("#exampleModal").modal("show");
        },
        Crear() {
            var descripcion = this.registro.descripcion;
            var name = this.registro.name;

            if (name == "") {
                Swal.fire({
                    icon: "warning",
                    title: "Alerta",
                    text: "Por favor ingrese nombre",
                });
                return false;
            }
            if (descripcion == "") {
                Swal.fire({
                    icon: "warning",
                    title: "Alerta",
                    text: "Por favor ingrese descripción",
                });
                return false;
            }

            vue_global.ajax_peticion("CrearRole", this.registro, [
                (respuesta) => {
                    Swal.fire({
                        icon: respuesta.Tipo,
                        title: respuesta.Titulo,
                        text: respuesta.Respuesta,
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
            if ($.fn.dataTable.isDataTable("#table_roles")) {
                var table = $("#table_roles").DataTable();
                table.destroy();
            }
            vue_global.ajax_peticion("ListarRoles", null, [
                (respuesta) => {
                    this.roles = respuesta.roles;
                    $("#table_roles").css("visibility", "visible");
                    this.$nextTick(() => {
                        // setTimeout(() => {
                        $("#table_roles").DataTable({
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
                        // }, 500);
                    });
                },
            ]);
        },
        Mostrar(id, accion) {
            this.botonMostrar = "Mostrar";
            this.registro.id = id;
            vue_global.ajax_peticion("MostrarRole", this.registro, [
                (respuesta) => {
                    $("#exampleModal").modal("show");
                    this.registro.id = respuesta.role.id;
                    this.registro.name = respuesta.role.name;
                    this.registro.descripcion = respuesta.role.description;

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
                            "EliminarRole",
                            this.registro,
                            [
                                (respuesta) => {
                                    Swal.fire({
                                        icon: respuesta.Tipo,
                                        title: respuesta.Titulo,
                                        text: respuesta.Respuesta,
                                    }).then((result) => {
                                        this.Listar();
                                    });
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
                            "ActualizarRole",
                            this.registro,
                            [
                                (respuesta) => {
                                    Swal.fire({
                                        icon: respuesta.Tipo,
                                        title: respuesta.Titulo,
                                        text: respuesta.Respuesta,
                                    }).then((result) => {
                                        this.Listar();
                                        this.CerrarModal();
                                    });
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

// $(function () {
//   //cuando la datatable se pone pequeña  no funciona de manera normal el vue
//   //ya que la libreria  construye la tabla utilizando child
//   $("tbody").on("click", "td", function (e) {
//     if (screen.width <= 1920) {
//       if ($(e.target).is("button")) {
//         // if ($(e.target).is(":not(td)") && $(e.target).is(":not(i)")) {
//         //ingresamos a la propiedad del objeto y capturamos la clase para identificar la accion
//         var clase = $(e.target)[0].classList[3];
//         var id = $(e.target)[0].id;
//         if (clase != undefined) {
//           switch (clase) {
//             case "btnmostrarv":
//               vue_roles.Mostrar(id);
//               break;
//             case "btneliminarv":
//               vue_roles.Eliminar(id);
//               break;
//           }
//         }
//         return;
//       }
//     }
//   });
// });
