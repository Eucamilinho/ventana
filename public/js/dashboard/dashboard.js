var vue_dashboard = new Vue({
  el: "#app",
  data: {
    registro: {
      id: "",
      codigoUps: "",
    },
    botonMostrar: "",
    clientesCount: "",
    reportesCount: "",
    upsCount: "",
    tecnicosCount: "",
  },
  methods: {
    reporte() {
      vue_global.ajax_peticion("reporteHome", null, [
        (respuesta) => {
          this.clientesCount = respuesta.clientes;
          this.reportesCount = respuesta.reportes;
          this.upsCount = respuesta.ups;
          this.tecnicosCount = respuesta.tecnicos;

          this.listarCalendario();

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

    listarCalendario() {
      $("#calendar").html("");
      var self = this;
      vue_global.ajax_peticion("MostrarCalendarioAgendas", null, [
        (respuesta) => {
          this.arrayEventosCalendario = respuesta.eventosArray;
          var arrayJstringifyEvent = JSON.stringify(
            this.arrayEventosCalendario
          );
          var Parseado = JSON.parse(arrayJstringifyEvent);

          var e = document.getElementById("calendar"),
            n = new FullCalendar.Calendar(e, {
              plugins: ["interaction", "dayGrid", "timeGrid"],
              header: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth",
              },
              buttonText: {
                //Here I make the button show French date instead of a text.
                today: "Hoy",
                month: "Mes",
              },
              locales: "es",
              // defaultDate: "2020-06-12",
              // navLinks: !0,
              // editable: false,
              selectable: !0,
              selectMirror: !0,
              navLinks: 1,
              navLinkDayClick: function (date, jsEvent) {},
              select: function (e) {},
              editable: 0,
              eventLimit: !0,
              events: Parseado,
              eventClick: function (info) {
                console.log(info.event.id);
                vue_dashboard.mostrarEvento(info.event.id);
                // alert(info);
              },

              // eventClick: function (info) {
              //   info.jsEvent.preventDefault(); // don't let the browser navigate
              //   if (info.event.url) {
              //     // window.open(info.event.url);

              //     var datoFecha = self.formatDate(
              //       info.event.start.toISOString()
              //     );

              //     vue_agendas.MostrarDetallesProgramacion(
              //       datoFecha
              //     );
              //   }
              // },
              // [{
              //     title: "Business Lunch",
              //     start: "2020-06-03T13:00:00",
              //     constraint: "businessHours",
              //     className: "bg-soft-warning"
              // }, {
              //     title: "Meeting",
              //     start: "2020-06-13T11:00:00",
              //     constraint: "availableForMeeting",
              //     className: "bg-soft-purple",
              //     textColor: "white"
              // }, {
              //     title: "Conference",
              //     start: "2020-06-27",
              //     end: "2020-06-29",
              //     className: "bg-soft-primary"
              // }, {
              //     groupId: "availableForMeeting",
              //     start: "2020-06-11T10:00:00",
              //     end: "2020-06-11T16:00:00",
              //     title: "Repeating Event",
              //     className: "bg-soft-purple"
              // }, {
              //     groupId: "availableForMeeting",
              //     start: "2020-06-15T10:00:00",
              //     end: "2020-06-15T16:00:00",
              //     title: "holiday",
              //     className: "bg-soft-success"
              // }, {
              //     start: "2020-06-06",
              //     end: "2020-06-08",
              //     overlap: !1,
              //     title: "New Event",
              //     className: "bg-soft-pink"
              // }],
              // eventClick: function(e) {
              //     confirm("delete event?") && e.event.remove()
              // }
            });
          n.render();
          n.today();
        },
      ]);
    },

    mostrarEvento(id) {
      var registro = {
        id: id,
      };
      vue_global.ajax_peticion("mostrarEvento", registro, [
        (respuesta) => {
          console.log(respuesta.registro);
          $("#txt_ups").val(respuesta.registro.codigoUps);
          $("#txt_cliente").val(respuesta.registro.nomcli);
          $("#txt_tecnico").val(respuesta.registro.nomtec);
          $("#ModalInfoEvent").modal("show");
        },
      ]);
    },
  },
  /**
   * Mounted es lo PRIMERO que ocurre cuando se carga la pagina
   */
  mounted() {
    /**
     * Cuando se carga la pagina necesito recibir
     */

    this.reporte();
  },
});
