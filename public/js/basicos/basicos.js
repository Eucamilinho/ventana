var vue_basicos = new Vue({
    el: "#app",
    data: {
        basicos: [],
        registro: {
            id: "",
            nombre: "",
            logo: "",
            direccion: "",
            redSocial1: "",
            redSocial2: "",
            redSocial3: "",
            telefono1: "",
            telefono2: "",
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
            // var descripcion= this.registro.descripcion;
            // var name= this.registro.name;

            // if (name=="") {
            //   Swal.fire({
            //     icon: 'warning',
            //     title: 'Alerta',
            //     text: 'Por favor ingrese nombre',
            //   })
            //   return false;
            // }
            // if (descripcion=="") {
            //   Swal.fire({
            //     icon: 'warning',
            //     title: 'Alerta',
            //     text: 'Por favor ingrese descripción',
            //   });
            //   return false;
            // }

            vue_global.ajax_peticion("CrearBasico", this.registro, [
                (respuesta) => {
                    Swal.fire({
                        icon: respuesta.Tipo,
                        title: respuesta.Titulo,
                        text: respuesta.Respuesta,
                    });
                    if (respuesta.Tipo == "success") {
                        this.GuardarFile("nuevo", respuesta.ultimoId);
                        this.CerrarModal();
                        this.vaciarCampos();
                        this.Listar();
                    }
                },
            ]);
        },
        Listar() {
            vue_global.ajax_peticion("ListarBasicos", null, [
                (respuesta) => {
                    this.basicos = respuesta.basicos;
                    this.$nextTick(() => {
                        // setTimeout(() => {
                        $("#table_basicos").css("visibility", "visible");
                        $("#table_basicos").DataTable({
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
            vue_global.ajax_peticion("MostrarBasico", this.registro, [
                (respuesta) => {
                    $("#exampleModal").modal("show");
                    // this.registro.id = respuesta.role.id;
                    // this.registro.name = respuesta.role.name;
                    // this.registro.descripcion = respuesta.role.description;

                    this.registro.id = respuesta.basico.id;
                    this.registro.nombre = respuesta.basico.nombre;
                    this.registro.logo = respuesta.basico.logo;
                    this.registro.direccion = respuesta.basico.direccion;
                    this.registro.redSocial1 = respuesta.basico.redSocial1;
                    this.registro.redSocial2 = respuesta.basico.redSocial2;
                    this.registro.redSocial3 = respuesta.basico.redSocial3;
                    this.registro.telefono1 = respuesta.basico.telefono1;
                    this.registro.telefono2 = respuesta.basico.telefono2;

                    var imagenUrl = this.registro.logo;
                    var img = $("#file_imagen").dropify({
                        defaultFile: imagenUrl,
                    });
                    img = img.data("dropify");
                    img.resetPreview();
                    img.clearElement();
                    img.settings.defaultFile = imagenUrl;
                    img.destroy();
                    img.init();

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
                            "EliminarBasico",
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
                            "ActualizarBasico",
                            this.registro,
                            [
                                (respuesta) => {
                                    Swal.fire({
                                        icon: respuesta.Tipo,
                                        title: respuesta.Titulo,
                                        text: respuesta.Respuesta,
                                    }).then((result) => {
                                        this.GuardarFile(
                                            "editar",
                                            this.registro.id
                                        );
                                        this.Listar();
                                        this.CerrarModal();
                                    });
                                },
                            ]
                        );
                    }
                });
        },

        GuardarFile(accion, id) {
            var formData = new FormData();
            if (accion == "nuevo") {
                this.registro.id = id;
                var imagefileLogo = document.querySelector("#file_imagen");
                formData.append("filePerfil", imagefileLogo.files[0]);
            } else {
                var imagefileLogo = document.querySelector("#file_imagen");
                formData.append("filePerfil", imagefileLogo.files[0]);
            }

            var registro = {
                accion: accion,
                id: this.registro.id,
                // info: $("#txt_img_redondeda").attr("src"),
            };

            // console.log(registro);
            // return false;
            var str_datos = JSON.stringify(registro);
            formData.append("data", str_datos);
            axios
                .post("GuardarFileEmpresa", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then(
                    function (response) {
                        // Swal.fire({
                        //   icon: response.data.Tipo,
                        //   title: response.data.Titulo,
                        //   text: response.data.Respuesta,
                        //   showConfirmButton: false,
                        //   timer: 1000,
                        // });
                        // if (response.data.Tipo == "success") {
                        //   // this.imagenUser = "";
                        //   // this.Listar();
                        //   // $("#exampleModalLarge").modal("hide");
                        //   // $("#result").html("");
                        //   // $("#exampleModalSmall").modal("hide");
                        //   // this.Listar();
                        //   // this.registroFacturar.numeroFactura = "";
                        //   // $("#fileFactura").val("");
                        //   // $(".nav-link").removeClass("active");
                        //   // $(".navfacturado").addClass("active");
                        // }
                    }.bind(this)
                );
        },
    },
    /**
     * Mounted es lo PRIMERO que ocurre cuando se carga la pagina
     */
    mounted() {
        /**
         * Cuando se carga la pagina necesito recibir
         */
        $(".dropify").dropify();
        this.Listar();
        // vue_global.ajax_peticion("ListarBasicos", null, [
        //   (respuesta) => {
        //     this.basicos = respuesta.basicos;
        //     setTimeout(() => {
        //       $("#table_basicos").css("visibility", "visible");
        //       $("#table_basicos").DataTable({
        //         destroy: true,
        //         language: {
        //           decimal: "",
        //           emptyTable: "No hay información",
        //           info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        //           infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
        //           infoFiltered: "(Filtrado de _MAX_ total entradas)",
        //           infoPostFix: "",
        //           thousands: ",",
        //           lengthMenu: "Mostrar _MENU_ Entradas",
        //           loadingRecords: "Cargando...",
        //           processing: "Procesando...",
        //           search: "Buscar:",
        //           zeroRecords: "Sin resultados encontrados",
        //           paginate: {
        //             first: "Primero",
        //             last: "Ultimo",
        //             next: "Siguiente",
        //             previous: "Anterior",
        //           },
        //         },
        //       });
        //     }, 500);
        //   },
        // ]);
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
                            vue_basicos.Mostrar(id);
                            break;
                        case "btneliminarv":
                            vue_basicos.Eliminar(id);
                            break;
                    }
                }
                return;
            }
        }
    });
});
