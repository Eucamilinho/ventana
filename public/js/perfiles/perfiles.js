var vm = new Vue({
  el: "#app",
  data: {
    usuario: {
      name: "",
      celular: "",
      email: "",
      old_password: "",
      new_password: "",
      confirm_password: "",
      correoFacturacion: "",
      fechaVencimiento: "",
    },
    imagenUser: "",
  },
  methods: {
    actualizarPassword() {
      var nueva = this.usuario.new_password;
      var confirmar = this.usuario.confirm_password;
      var actual = this.usuario.old_password;
      if (nueva != confirmar) {
        Swal.fire({
          icon: "warning",
          title: "Alerta",
          text: "la nueva contraseña no coincide con la confirmación",
        });
        return false;
      }
      if (actual == "" || actual == undefined || actual == null) {
        Swal.fire({
          icon: "warning",
          title: "Alerta",
          text: "por favor ingrese el campo contraseña actual",
        });
        return false;
      }
      if (nueva == "" || nueva == undefined || nueva == null) {
        Swal.fire({
          icon: "warning",
          title: "Alerta",
          text: "por favor  ingrese el campo nueva contraseña",
        });
        return false;
      }
      if (nueva == "" || nueva == undefined || nueva == null) {
        Swal.fire({
          icon: "warning",
          title: "Alerta",
          text: "por favor  ingrese el campo nueva contraseña",
        });
        return false;
      }
      if (confirmar == "" || confirmar == undefined || confirmar == null) {
        Swal.fire({
          icon: "warning",
          title: "Alerta",
          text: "por favor el campo ingrese confirmación de nueva contraseña",
        });
        return false;
      }
      vue_global.ajax_peticion("updatePassword", this.usuario, [
        (respuesta) => {
          if (respuesta.Tipo == "errores") {
            Swal.fire({
              icon: "error",
              title: "Alerta",
              text:
                respuesta.errors.new_password[0] +
                "," +
                respuesta.errors.old_password[0],
            });
          } else {
            Swal.fire({
              icon: respuesta.Tipo,
              title: respuesta.Titulo,
              text: respuesta.Respuesta,
            });
            if (respuesta.Tipo == "success") {
              this.vaciarCamposPass();
            }
          }
        },
      ]);
    },
    actualizarDatosPersonales() {
      vue_global.ajax_peticion("actualizarDatosPersonales", this.usuario, [
        (respuesta) => {
          Swal.fire({
            icon: respuesta.Tipo,
            title: respuesta.Titulo,
            text: respuesta.Respuesta,
          });
          if (respuesta.Tipo == "success") {
            this.Listar();
          }
        },
      ]);
    },
    actualizarCorreoFacturacion() {
      if (
        this.usuario.correoFacturacion == "" ||
        this.usuario.correoFacturacion == undefined ||
        this.usuario.correoFacturacion == null
      ) {
        Swal.fire({
          icon: "warning",
          title: "Alerta",
          text: "Ingrese el campo email facturación ",
        });
        return false;
      }

      if (!this.isValidEmailAddress(this.usuario.correoFacturacion)) {
        Swal.fire({
          icon: "warning",
          title: "Alerta",
          text: "El correo no tiene una estructura valida",
        });
        return false;
      } else {
        vue_global.ajax_peticion("actualizarCorreoFacturacion", this.usuario, [
          (respuesta) => {
            Swal.fire({
              icon: respuesta.Tipo,
              title: respuesta.Titulo,
              text: respuesta.Respuesta,
            });
            if (respuesta.Tipo == "success") {
              this.Listar();
            }
          },
        ]);
      }
    },
    Listar() {
      vue_global.ajax_peticion("MostrarInformacionPerfil", null, [
        (respuesta) => {
          this.usuario.name = respuesta.usuario.name;
          this.usuario.celular = respuesta.usuario.celular;
          this.usuario.email = respuesta.usuario.email;
          this.usuario.passActual = respuesta.usuario.bpass;
          this.usuario.correoFacturacion = respuesta.usuario.correoFacturacion;
          this.usuario.fechaVencimiento =
            respuesta.usuario.fechaVencimientoMembresia;

          if (respuesta.usuario.imgUser != null) {
            this.imagenUser = respuesta.usuario.imgUser;
            var imagenUrl = respuesta.usuario.imgUser;
            console.log(imagenUrl);
            var img = $("#file_imagen").dropify({
              defaultFile: imagenUrl,
            });
            img = img.data("dropify");
            img.resetPreview();
            img.clearElement();
            img.settings.defaultFile = imagenUrl;
            img.destroy();
            img.init();
          }
        },
      ]);
    },
    vaciarCamposPass() {
      this.usuario.old_password = "";
      this.usuario.confirm_password = "";
      this.usuario.new_password = "";
    },
    isValidEmailAddress(emailAddress) {
      var pattern =
        /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
      return pattern.test(emailAddress);
    },

    GuardarFile(accion) {
      $("#btn_recortar").click();
      setTimeout(() => {
        var formData = new FormData();
        if (accion == "nuevo") {
          var imagefileLogo = document.querySelector("#file_imagen");
          formData.append("filePerfil", imagefileLogo.files[0]);
        } else {
          var imagefileLogo = document.querySelector("#txt_img_redondeda");
        }

        var registro = {
          accion: accion,
          info: $("#txt_img_redondeda").attr("src"),
        };

        // console.log(registro);
        // return false;
        var str_datos = JSON.stringify(registro);
        formData.append("data", str_datos);
        axios
          .post("GuardarFilePerfil", formData, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          })
          .then(
            function (response) {
              Swal.fire({
                icon: response.data.Tipo,
                title: response.data.Titulo,
                text: response.data.Respuesta,
                showConfirmButton: false,
                timer: 1000,
              });
              if (response.data.Tipo == "success") {
                this.imagenUser = "";
                this.Listar();
                $("#exampleModalLarge").modal("hide");
                $("#result").html("");
                // $("#exampleModalSmall").modal("hide");
                // this.Listar();
                // this.registroFacturar.numeroFactura = "";
                // $("#fileFactura").val("");
                // $(".nav-link").removeClass("active");
                // $(".navfacturado").addClass("active");
              }
            }.bind(this)
          );
      }, 200);
    },
    // MostrarImagen() {
    //   setTimeout(() => {
    //     console.log("MostrarImagen");
    //     var button = document.getElementById("btn_recortar");
    //     var result = document.getElementById("result");
    //     const image = document.getElementById("file_imagen_edit");
    //     var croppable = false;
    //     const cropper = new Cropper(image, {
    //       aspectRatio: 16 / 9,
    //       ready: function () {
    //         croppable = true;
    //       },
    //       crop(event) {
    //         console.log(event.detail.x);
    //         console.log(event.detail.y);
    //         console.log(event.detail.width);
    //         console.log(event.detail.height);
    //         console.log(event.detail.rotate);
    //         console.log(event.detail.scaleX);
    //         console.log(event.detail.scaleY);
    //       },
    //     });
    //     this.redondear(cropper);
    //   }, 1000);
    // },

    // redondear(cropper) {
    //   var croppedCanvas;
    //   var roundedCanvas;
    //   var roundedImage;

    //   // if (!croppable) {
    //   //   return;
    //   // }
    //   // Crop
    //   croppedCanvas = cropper.getCroppedCanvas();

    //   // Round
    //   roundedCanvas = getRoundedCanvas(croppedCanvas);

    //   // Show
    //   roundedImage = document.createElement("img");
    //   roundedImage.src = roundedCanvas.toDataURL();
    //   result.innerHTML = "";
    //   result.appendChild(roundedImage);
    // },

    // getRoundedCanvas(sourceCanvas) {
    //   var canvas = document.createElement("canvas");
    //   var context = canvas.getContext("2d");
    //   var width = sourceCanvas.width;
    //   var height = sourceCanvas.height;

    //   canvas.width = width;
    //   canvas.height = height;
    //   context.imageSmoothingEnabled = true;
    //   context.drawImage(sourceCanvas, 0, 0, width, height);
    //   context.globalCompositeOperation = "destination-in";
    //   context.beginPath();
    //   context.arc(
    //     width / 2,
    //     height / 2,
    //     Math.min(width, height) / 2,
    //     0,
    //     2 * Math.PI,
    //     true
    //   );
    //   context.fill();
    //   return canvas;
    // },
  },
  /**
   * Mounted es lo PRIMERO que ocurre cuando se carga la pagina
   */
  mounted() {
    /**
     * Cuando se carga la pagina necesito recibir las habitaciones que voy a mostrar
     */
    vue_global.ajax_peticion("MostrarInformacionPerfil", null, [
      (respuesta) => {
        console.log(respuesta);

        $(".dropify").dropify();
        this.usuario.name = respuesta.usuario.name;
        this.usuario.celular = respuesta.usuario.celular;
        this.usuario.email = respuesta.usuario.email;
        this.usuario.passActual = respuesta.usuario.bpass;
        this.usuario.correoFacturacion = respuesta.usuario.correoFacturacion;
        this.usuario.fechaVencimiento =
          respuesta.usuario.fechaVencimientoMembresia;

        //colocar la imagen

        if (respuesta.usuario.imgUser != null) {
          this.imagenUser = respuesta.usuario.imgUser;
          var imagenUrl = respuesta.usuario.imgUser;
          console.log(imagenUrl);
          var img = $("#file_imagen").dropify({
            defaultFile: imagenUrl,
          });
          img = img.data("dropify");
          img.resetPreview();
          img.clearElement();
          img.settings.defaultFile = imagenUrl;
          img.destroy();
          img.init();
        }
      },
    ]);
  },
});
