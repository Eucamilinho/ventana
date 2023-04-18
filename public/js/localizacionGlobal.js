const funcionInit = () => {
  if (!"geolocation" in navigator) {
    return alert(
      "Tu navegador no soporta el acceso a la ubicación. Intenta con otro"
    );
  }

  const RUTA_API = "obtenerCordenadas";

  let idWatcher = null;

  const $latitud = document.querySelector("#latitud_cor"),
    $longitud = document.querySelector("#longitud_cor"),
    $btnIniciar = document.querySelector("#btnIniciar_cor"),
    $btnDetener = document.querySelector("#btnDetener_cor"),
    $log = document.querySelector("#log_cor");

  const onUbicacionConcedida = (ubicacion) => {
    const coordenadas = ubicacion.coords;
    $latitud.innerText = coordenadas.latitude;
    $longitud.innerText = coordenadas.longitude;
    loguear(
      `${ubicacion.timestamp}: ${coordenadas.latitude},${coordenadas.longitude}`
    );
    enviarAServidor(ubicacion);
  };

  const enviarAServidor = (ubicacion) => {
    // Debemos crear otro objeto porque el que nos mandan no se puede codificar
    localStorage.setItem("latitud", ubicacion.coords.latitude);
    localStorage.setItem("longitud", ubicacion.coords.longitude);
    const otraUbicacion = {
      coordenadas: {
        latitud: ubicacion.coords.latitude,
        longitud: ubicacion.coords.longitude,
      },
      timestamp: ubicacion.timestamp,
    };
    // console.log("Enviando: ", otraUbicacion);
    // fetch(RUTA_API, {
    //   method: "POST",
    //   body: JSON.stringify(otraUbicacion),
    // }); // No esperamos el then porque no hacemos nada cuando se termine
  };

  const loguear = (texto) => {
    $log.innerText += "\n" + texto;
    // console.log("\n" + texto);
  };

  const onErrorDeUbicacion = (err) => {
    $latitud.innerText = "Error obteniendo ubicación: " + err.message;
    $longitud.innerText = "Error obteniendo ubicación: " + err.message;
    // console.log("Error obteniendo ubicación: ", err);
  };

  const detenerWatcher = () => {
    if (idWatcher) {
      navigator.geolocation.clearWatch(idWatcher);
    }
  };

  const opcionesDeSolicitud = {
    enableHighAccuracy: true, // Alta precisión
    maximumAge: 0, // No queremos caché
    timeout: 5000, // Esperar solo 5 segundos
  };

  $btnIniciar.addEventListener("click", () => {
    detenerWatcher();
    idWatcher = navigator.geolocation.watchPosition(
      onUbicacionConcedida,
      onErrorDeUbicacion,
      opcionesDeSolicitud
    );
  });

  $btnDetener.addEventListener("click", detenerWatcher);

  $latitud.innerText = "Cargando...";
  $longitud.innerText = "Cargando...";

  $("#btnIniciar_cor").click();
};
document.addEventListener("DOMContentLoaded", funcionInit);
