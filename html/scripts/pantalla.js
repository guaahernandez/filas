/**
 * ============================================================================
 * Sistema de Control de Filas - Módulo de Pantalla / Turnero (pantalla.js)
 * ============================================================================
 * Este script controla la lógica interactiva de la pantalla de sala de espera:
 * 1. Consulta y actualización periódica (polling) de los turnos llamados a ventanilla/módulo.
 * 2. Carga y actualización de los textos informativos en la marquesina (cinta de noticias).
 * 3. Reproducción continua y secuencial de videos promocionales (lista de reproducción en bucle).
 * ============================================================================
 */

// Variable global para referencia de tablas de datos (si aplica)
var tabla;

/**
 * Función de inicialización
 * Se ejecuta automáticamente al cargar el script en pantalla.php
 */
function init() {
    // Inicia la carga y el refresco periódico de los turnos en pizarra y marquesina
    listar_det_pant();

    // Inicia el reproductor de videos en bucle secuencial
    cargavideos();
}

/**
 * Consulta y actualiza los turnos llamados en la pizarra y la marquesina informativa
 * 
 * @param {string} agenci - Código de la agencia/sucursal (opcional si se maneja por sesión)
 * @param {string} ubicac - Ubicación o módulo de atención (opcional)
 */
function listar_det_pant(agenci, ubicac) {
    // Contador de ciclos para controlar la frecuencia de actualización del texto informativo
    var CambiaTexto = 0;

    // --- Carga Inicial Inmediata ---
    // 1. Obtiene la lista actual de turnos llamados y la inyecta en el contenedor #pizarra
    $.post("../ajax/a_pantalla.php?op=listar_det_pant", function (e) {
        document.getElementById("pizarra").innerHTML = e;

        // 2. Obtiene el texto informativo inicial y lo asigna a la marquesina #txtmarquee
        $.post("../ajax/a_textos.php?op=gettexto", function (e) {
            document.getElementById("txtmarquee").innerText = e;
        });
    });

    // --- Refresco Periódico Automático (Polling cada 4 segundos) ---
    var refreshid = setInterval(function () {
        $.post("../ajax/a_pantalla.php?op=listar_det_pant", function (e) {
            // Actualiza el contenido visual de la pizarra con los turnos vigentes
            document.getElementById("pizarra").innerHTML = e;

            CambiaTexto++;
            // Cada 100 ciclos (aprox. 400 segundos / ~6.6 minutos), actualiza el mensaje de la marquesina
            if (CambiaTexto == 100) {
                $.post("../ajax/a_textos.php?op=gettexto", function (e) {
                    document.getElementById("txtmarquee").innerText = e;
                });
                // Reinicia el contador de ciclos
                CambiaTexto = 0;
            }
        });
    }, 4000);
}

/**
 * Gestiona la reproducción secuencial e infinita de videos promocionales
 * Consulta la API de videos y escucha el evento 'ended' del elemento <video>
 * para cargar dinámicamente el siguiente archivo multimedia sin recargar la página.
 */
function cargavideos() {
    var n = 0; // Código / índice del video actual para la secuencia
    var data;

    // Solicita el primer video (código inicial = 0)
    $.post("../ajax/a_menus.php?op=cargavideos", { codigo: n }, function (e) {
        data = JSON.parse(e);
        // Asigna la ruta URL del video al reproductor <video id="vid">
        vid.setAttribute("src", "http://ws.laguacamaya.cr:13565/filas/assets/videos/" + data["direcc"]);
        n = data["codigo"];

        // Escucha el evento 'ended': cuando finaliza el video actual, carga el siguiente
        vid.addEventListener("ended", () => {
            // Solicita al backend el siguiente video en la lista usando el último código recibido
            $.post("../ajax/a_menus.php?op=cargavideos", { codigo: n }, function (e) {
                data = JSON.parse(e);
                // Actualiza la fuente del video para reproducir el siguiente de inmediato
                vid.setAttribute("src", "http://ws.laguacamaya.cr:13565/filas/assets/videos/" + data["direcc"]);
                n = data["codigo"];
            });
        });
    });
}

// Ejecución automática al cargar el archivo
init();