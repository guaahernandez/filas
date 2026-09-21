/**
 * ============================================================================
 * Sistema de Control de Filas - Controlador Base de Quiosco (kiosco.js)
 * ============================================================================
 * Este script inicializa las funciones principales de la interfaz del quiosco:
 * 1. Carga inicial del menú principal de trámites y opciones.
 * 2. Carga y gestión de la lista de reproducción de videos en bucle continuo.
 * ============================================================================
 */

// Variable global para referencia de tablas de datos
var tabla;

/**
 * Función de inicialización
 * Se ejecuta automáticamente al cargar la página del quiosco
 */
function init() {   
    // Carga el menú principal de selección de trámites en el contenedor de botones
    opmenu_enc('principal');
    
    // Inicia el reproductor de videos institucionales / promocionales
    cargavideos();
    
    // Opción para forzar pantalla completa (comentada por control del sistema operativo)
    // document.documentElement.requestFullscreen();
}

/**
 * Solicita y renderiza un nivel de menú o pantalla interactiva del quiosco
 * 
 * @param {string} ubmenu - Nombre o clave de la vista/menú a consultar (ej: 'principal', 'retirartic')
 * @param {string} prefer - Bandera de atención preferencial (opcional)
 */
function opmenu_enc(ubmenu, prefer) {
    // Petición AJAX al backend para obtener los botones HTML del menú solicitado
    $.post("../ajax/a_menus.php?op=opmenu_enc", { ubmenu: ubmenu, prefer: prefer }, function (e) {
        // Inyecta los botones y elementos gráficos en el panel derecho del quiosco
        document.getElementById("botones").innerHTML = e;

        if (ubmenu == 'retirartic') {
            // Temporizador opcional de retorno al menú principal
            // var refreshid = setInterval(function(){
            //     opmenu_enc('principal');
            // }, 5000);
        }
    });    
}

/**
 * Administra la reproducción secuencial e ininterrumpida de videos promocionales
 * Consulta la API y escucha el evento 'ended' del elemento <video> para avanzar al siguiente video
 */
function cargavideos() {
    var n = 0; // Identificador del video inicial
    var data;

    // Consulta el primer video de la lista de reproducción
    $.post("../ajax/a_menus.php?op=cargavideos", { codigo: n }, function (e) {
        data = JSON.parse(e);
        // Asigna la ruta URL al reproductor de video
        vid.setAttribute("src", "http://ws.laguacamaya.cr:13565/filas/assets/videos/" + data["direcc"]);
        n = data["codigo"];

        // Evento que detecta cuando el video actual finalizó
        vid.addEventListener("ended", () => {
            // Solicita el siguiente video en la secuencia mediante su código
            $.post("../ajax/a_menus.php?op=cargavideos", { codigo: n }, function (e) {
                data = JSON.parse(e);
                // Actualiza la fuente multimedia para continuar la reproducción sin pausas
                vid.setAttribute("src", "http://ws.laguacamaya.cr:13565/filas/assets/videos/" + data["direcc"]);
                n = data["codigo"];                
            }); 
        });        
    });
}

// Ejecución automática al cargar el script
init();