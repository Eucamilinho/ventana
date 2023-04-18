var vue_global = new Vue({
    data: {
        mensaje_carga: "",
        ajax_estado: 2,
    },
    methods: {
        /**
         * Este metodo es para enviar peticiones ajax-axios al cualquier controlador de laravel
         * @param {*} url ##Esta es la ruta o el nombre de la ruta en el web.php
         * @param {*} datos ##Esta va a ser la informacion que voy a enviar desde javascript a el controlador
         * @param {*} callback ##Esto es lo que voy a recibir, o lo que va a pasar despues de que ocurra el resultado de la peticion
         */
        ajax_peticion(url, datos = null, callback = []) {
            this.mensaje_carga = "Cargando resultados por favor espere...";
            this.ajax_estado = 1;
            var formData = new FormData();
            //Pregunto si se estan enviando datos para operaciones
            if (datos != null) {
                var str_datos = JSON.stringify(datos);
                formData.append("data", str_datos);
            }
            axios
                .post(url, formData, {
                    headers: { "Content-type": "multipart/form-data" },
                })
                .then((respuesta) => {
                    if (callback.length > 0) {
                        this.mensaje_carga = "";
                        this.ajax_estado = 0;
                        callback.forEach((element) => {
                            if (element != null) {
                                element(respuesta.data);
                                $("#customizerclose-btn").click();
                            }
                        });
                    }
                });
        },
        ajax_archivo_peticion(
            url,
            Myselector = null,
            registro = {},
            callback = []
        ) {
            this.mensaje_carga = "Cargando resultados por favor espere...";
            this.ajax_estado = 1;
            var formData = new FormData();
            //Pregunto si se estan enviando datos para operaciones
            // if (datos != null) {
            //   var str_datos = JSON.stringify(datos);
            //   formData.append("data", str_datos);
            // }
            var imagefileLogo = document.querySelector("#" + Myselector);
            formData.append("fileDato", imagefileLogo.files[0]);
            var str_datos = JSON.stringify(registro);
            formData.append("data", str_datos);

            axios
                .post(url, formData, {
                    headers: { "Content-type": "multipart/form-data" },
                })
                .then((respuesta) => {
                    if (callback.length > 0) {
                        this.mensaje_carga = "";
                        this.ajax_estado = 0;
                        callback.forEach((element) => {
                            if (element != null) {
                                element(respuesta.data);
                            }
                        });
                    }
                });
        },
    },
});
