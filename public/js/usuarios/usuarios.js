var vm = new Vue({
    el: "#app",
    data: {
        usuarios: [],
        roles: [],
        zonas: [],
        sucursales: [],
        departamentos: [],
        municipiosfiltrados: [],
        municipios: [],
        missucursales: [],
        registro: {
            id: "",
            name: "",
            email: "",
            role: "",
            identificacion: "",
            idZona: "",
            idSucursal: "",
            estado: "",
            fechaVencimiento: "",
            telefono: "",
            idDepartamento: "",
            idMunicipio: "",
        },
        botonMostrar: "",
    },
    methods: {
        vaciarCampos() {
            this.registro.id = "";
            this.registro.name = "";
            this.registro.email = "";
            this.registro.role = "";
            this.registro.identificacion = "";
            this.registro.idZona = "";
            this.registro.idSucursal = "";
            $("#sel_registro_role").val("").trigger("change");
            $("#sel_registro_zona").val("").trigger("change");
            $("#sel_registro_sucursal").val("").trigger("change");
            $("#sel_registro_estado").val(1).trigger("change");
            $(".select2")
                .select2({ dropdownParent: $("#exampleModal") })
                .trigger("change");

            $(".accion").prop("disabled", false);
        },
        Nuevo() {
            this.botonMostrar = "Nuevo";
            this.vaciarCampos();
            $("#exampleModal").modal("show");
        },
        Crear() {
            var sel = $("#sel_registro_role").select2("val");
            var selMunicipio = $("#sel_municipio_sel").select2("val");

            this.registro.idMunicipio = selMunicipio;

            this.registro.role = sel;
            if (
                this.registro.identificacion == null ||
                this.registro.identificacion == undefined
            ) {
                Swal.fire({
                    icon: "warning",
                    title: "Alerta",
                    text: "Por favor ingrese identificación.",
                });
                return false;
            }
            this.registro.idMiSucursal = $("#sel_missucursal").val();
            vue_global.ajax_peticion("CrearUsuario", this.registro, [
                (respuesta) => {
                    Swal.fire({
                        icon: respuesta.Tipo,
                        title: respuesta.Titulo,
                        text: respuesta.Respuesta,
                        timer: 2000,
                    });

                    if (respuesta.Tipo == "success") {
                        var email = respuesta.email;
                        var user = respuesta.user;
                        var pass = respuesta.pass;
                        this.enviarMailCreacionUsuario(email, user, pass);
                        this.CerrarModal();
                        this.vaciarCampos();
                        this.Listar();
                    }
                },
            ]);
        },
        Listar() {
            this.usuarios = [];
            if ($.fn.dataTable.isDataTable("#table_usuarios")) {
                var table = $("#table_usuarios").DataTable();
                table.destroy();
            }
            vue_global.ajax_peticion("ListarUsuarios", null, [
                (respuesta) => {
                    console.log(respuesta.usuarios);
                    this.usuarios = respuesta.usuarios;
                    this.roles = respuesta.roles;
                    this.zonas = respuesta.zonas;
                    this.sucursales = respuesta.sucursales;
                    this.municipios = respuesta.municipios;

                    this.departamentos = respuesta.departamentos;
                    this.missucursales = respuesta.missucursales;

                    $("#table_usuarios").css("visibility", "visible");
                    this.$nextTick(() => {
                        // setTimeout(() => {
                        $("#table_usuarios").dataTable({
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

                        //   $(".select2d").select2({
                        //     dropdownParent: $("#exampleModal"),
                        //   });
                        // }, 500);
                    });
                },
            ]);
        },
        Mostrar(id, accion) {
            this.botonMostrar = "Mostrar";
            this.registro.id = id;
            vue_global.ajax_peticion("MostrarUsuario", this.registro, [
                (respuesta) => {
                    $("#exampleModal").modal("show");
                    this.registro.id = respuesta.usuario.id;
                    this.registro.name = respuesta.usuario.name;
                    this.registro.email = respuesta.usuario.email;
                    this.registro.identificacion =
                        respuesta.usuario.identificacion;
                    this.registro.idZona = respuesta.usuario.id_zona;
                    this.registro.idSucursal = respuesta.usuario.id_sucursal;
                    this.registro.estado = respuesta.usuario.estado;
                    this.registro.telefono = respuesta.usuario.telefono;
                    this.registro.idDepartamento =
                        respuesta.usuario.idDepartamento;
                    this.registro.idMunicipio = respuesta.usuario.idMunicipio;
                    this.registro.idSucursal = respuesta.usuario.idSucursal;
                    $idRole = respuesta.role[0].role_id;
                    $("#sel_registro_role").val($idRole).trigger("change");
                    $("#sel_missucursal")
                        .val(respuesta.usuario.idSucursal)
                        .trigger("change");

                    $("#sel_registro_zona")
                        .val(this.registro.idZona)
                        .trigger("change");
                    $("#sel_departamento_det")
                        .val(this.registro.idDepartamento)
                        .trigger("change");

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

                    setTimeout(() => {
                        $("#sel_registro_sucursal")
                            .val(this.registro.idSucursal)
                            .trigger("change");
                        $("#sel_municipio_sel")
                            .val(this.registro.idMunicipio)
                            .trigger("change");
                        // $("#sel_registro_sucursal").select2('val',this.registro.idSucursa).trigger('change');
                        $("#exampleModal").modal("show");
                    }, 1000);
                    $("#sel_registro_estado")
                        .val(this.registro.estado)
                        .trigger("change");
                    // $(".select2").select2({dropdownParent: $('#exampleModal')}).trigger('change');
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
                        console.log(this.registro);
                        vue_global.ajax_peticion(
                            "EliminarUsuario",
                            this.registro,
                            [
                                (respuesta) => {
                                    Swal.fire({
                                        icon: respuesta.Tipo,
                                        title: respuesta.Titulo,
                                        text: respuesta.Respuesta,
                                    });
                                    if (respuesta.Tipo == "success") {
                                        if (
                                            $.fn.dataTable.isDataTable(
                                                "#table_usuarios" +
                                                    self.idDespachoKey
                                            )
                                        ) {
                                            var table = $(
                                                "#table_usuarios" +
                                                    self.idDespachoKey
                                            ).DataTable();
                                            table.destroy();
                                        }
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
            var selMunicipio = $("#sel_municipio_sel").select2("val");
            var selSucursal = $("#sel_missucursal").val();

            this.registro.idMunicipio = selMunicipio;
            this.registro.idSucursal = selSucursal;

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
                        var sel = $("#sel_registro_role").select2("val");
                        var sucursalPdf;
                        this.registro.role = sel;

                        vue_global.ajax_peticion(
                            "ActualizarUsuario",
                            this.registro,
                            [
                                (respuesta) => {
                                    Swal.fire({
                                        icon: respuesta.Tipo,
                                        title: respuesta.Titulo,
                                        text: respuesta.Respuesta,
                                    });
                                    if (respuesta.Tipo == "success") {
                                        this.Listar();
                                        this.CerrarModal();
                                    }
                                },
                            ]
                        );
                    }
                });
        },
        obtenerSucursales(id) {
            vue_global.ajax_peticion("obtenerSucursales", id, [
                (respuesta) => {
                    this.sucursales = respuesta.sucursales;

                    // Swal.fire({
                    //     icon: respuesta.Tipo,
                    //     title: respuesta.Titulo,
                    //     text: respuesta.Respuesta
                    // }).then((result) => {
                    //     this.Listar();
                    //     this.CerrarModal();
                    // });
                },
            ]);
        },
        ActualizarMembresia(id) {
            this.registro.id = id;
            console.log(this.registro.id);
            $("#modalFechaVencimiento").modal("show");
        },
        actualizarFechaVencimiento() {
            if (
                this.registro.fechaVencimiento == "" ||
                this.registro.fechaVencimiento == null ||
                this.registro.fechaVencimiento == undefined
            ) {
                Swal.fire({
                    icon: "warning",
                    title: "Alerta",
                    text: "Por favor ingrese una fecha de vencimiento de membresia",
                });

                return false;
            }
            vue_global.ajax_peticion(
                "actualizarFechaVencimiento",
                this.registro,
                [
                    (respuesta) => {
                        Swal.fire({
                            icon: respuesta.Tipo,
                            title: respuesta.Titulo,
                            text: respuesta.Respuesta,
                        });
                    },
                ]
            );
        },
        filtrarMunicipiosArray(id) {
            // this.municipiosfiltrados=[];
            // var array=this.municipios;
            var filtered = "";
            $("#sel_municipio_sel").select2("destroy");
            const array = this.municipios;
            if (array.length > 0) {
                filtered = array.filter(function (element) {
                    return element.departamento_id == parseInt(id);
                });
            }

            // var newarray= array.filter(function(elemento) {
            //     return elemento.departamento_id = id;
            // });
            this.municipiosfiltrados = filtered;

            $("#sel_municipio_sel").select2({
                dropdownParent: $("#exampleModal"),
            });
        },
        enviarMailCreacionUsuario(email, user, pass) {
            var esmail = this.ValidateEmail(email);

            if (esmail === false) {
                return false;
            }
            var registro = {
                email: email,
                user: user,
                pass: pass,
            };

            console.log(registro);
            vue_global.ajax_peticion("enviarMailCreacionUsuario", registro, [
                (respuesta) => {},
            ]);
        },

        ValidateEmail(input) {
            var validRegex =
                /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

            if (input.match(validRegex)) {
                // alert("Valid email address!");

                // document.form1.text1.focus();

                return true;
            } else {
                // alert("Invalid email address!");

                // document.form1.text1.focus();

                return false;
            }
        },
    },
    /**
     * Mounted es lo PRIMERO que ocurre cuando se carga la pagina
     */
    mounted() {
        /**
         * Cuando se carga la pagina necesito recibir las habitaciones que voy a mostrar
         */
        this.Listar();
        $(".select2").select2({
            dropdownParent: $("#exampleModal"),
        });
    },
});
$("#sel_registro_zona").on("change", function () {
    var idZona = $("#sel_registro_zona").val();
    vm.registro.idZona = idZona;
    vm.obtenerSucursales(idZona);
});
$("#sel_registro_sucursal").on("change", function () {
    var idSucursal = $("#sel_registro_sucursal").val();
    vm.registro.idSucursal = idSucursal;
});
$("#sel_registro_role").on("change", function () {
    var idRole = $("#sel_registro_role").val();
    vm.registro.role = idRole;
});
$("#sel_registro_estado").on("change", function () {
    var estado = $("#sel_registro_estado").val();
    vm.registro.estado = estado;
});
$("#sel_departamento_det").change(function () {
    var id = $("#sel_departamento_det").val();
    vm.registro.idDepartamento = id;
    vm.filtrarMunicipiosArray(id);
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
                            vm.Mostrar(id);
                            break;
                        case "btneliminarv":
                            vm.Eliminar(id);
                            break;

                        case "btnmenbresiav":
                            vm.ActualizarMembresia(id);
                            break;
                    }
                }
                return;
            }
        }
    });
});

$("#sel_registro_role").change(function () {
    var id = $("#sel_registro_role").val();
    if (id != 3) {
        $("#div_sucursal").hide();
    } else {
        $("#div_sucursal").show();
    }
});
