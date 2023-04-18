function AgendarCita() {
    //   var dato = uuid;
    var fecha = $("#fecha").val();
    var horaInicio = $("#horaInicio").val();

    if (fecha == "" || fecha == undefined) {
        Swal.fire({
            icon: "warning",
            title: "Alerta",
            text: "Por favor seleccione fecha",
        });
        return false;
    }

    if (horaInicio == "" || horaInicio == undefined) {
        Swal.fire({
            icon: "warning",
            title: "Alerta",
            text: "Por favor seleccione hora inicio",
        });
        return false;
    }
    var registro = {
        uuid: dato,
        fecha: fecha,
        horaInicio: horaInicio,
    };

    vue_global.ajax_peticion("/AgendarCita", registro, [
        (respuesta) => {
            Swal.fire({
                icon: respuesta.Tipo,
                title: respuesta.Titulo,
                text: respuesta.Respuesta,
                timer: 1500,
            });
            if (respuesta.Tipo == "success") {
                $("#fecha").val("");
                $("#horaInicio").val("");
                setTimeout(() => {
                    var win = window.open("about:blank", "_self");
                    win.close();
                }, 2000);
            }
        },
    ]);
}
