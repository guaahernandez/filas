/**
 * ============================================================================
 * Sistema de Control de Filas - Controlador de Navegación y Tiquetes (menus.js)
 * ============================================================================
 * Este script gestiona todas las interacciones de usuario en el quiosco:
 * 1. Navegación dinámica entre niveles de menú (principal, preferencial, vendedores).
 * 2. Emisión y registro de turnos/tiquetes (tanto estándar como en modo Android).
 * 3. Lectura y captura de números de factura mediante escáner de código de barras.
 * 4. Temporizadores de reinicio automático tras emitir un tiquete o confirmar entrega.
 * 5. Generación y formateo de la trama de texto para impresión térmica de recibos.
 * ============================================================================
 */

// Variables globales para la gestión de datos y número de factura capturado
var tabla;
var _numfac;

/**
 * Captura la pulsación de teclas en campos de texto (escaneo de código de barras/QR)
 * Detecta cuando el lector de código de barras envía el carácter Enter (código 13).
 * 
 * @param {Event}   event   - Evento nativo de teclado (keyup)
 * @param {string}  prefer  - Bandera de atención preferencial ('1' o '0')
 * @param {string}  android - Indicador de ejecución en entorno Android ('1' o '0')
 * @param {string}  agenci  - Código de la sucursal/agencia
 */
async function onKeyUp(event, prefer, android, agenci) {
    var keycode = event.keyCode;
    var link;
    var codigo;

    // Si se presiona la tecla 'Enter' (código 13, enviado automáticamente por lectores de barras)
    if (keycode == '13') {
        _numfac = $('#numfac').val();
        
        // Muestra la pantalla de confirmación "Pase a Entregas" con el número de factura
        opmenu_enc("pasaentreg", _numfac);
        
        if (android == "1") {
            // --- Flujo para Dispositivos Android ---
            // 1. Crea el tiquete de entrega en la base de datos
            await $.post("../ajax/a_menus.php?op=creaticket", { agenci: agenci, tipoco: "E", prefer: 0, vended: "", factur: _numfac }, function (e) {
                dato = e.split("|");
            });     
            
            codigt = dato[0]; // ID correlativo del tiquete
            turno = dato[1];  // Número de turno generado

            // 2. Guarda la relación entre factura y tiquete en la tabla 'factura_entrega'
            await $.post("../ajax/a_menus.php?op=guarda_fact_ticket", { codigt: codigt, agenci: agenci, turno: turno, factur: _numfac }, function (e) {
                // Confirmación de guardado
            });    
            
        } else {
            // --- Flujo para Quioscos Windows / Middleware Local ---
            // Redirige mediante el protocolo URI local para enviar la factura a impresión
            setTimeout(function () { 
                link = '-';
                link += (prefer == '1' ? 'PF=' : 'F=') + _numfac;
                window.location.href = link;
            }, 100);    
        }
    }
}

/**
 * Disparador de acción para avanzar de pantalla simulando clic en el elemento #pasar
 */
function pasar() {
    alert("Pasar");
    $('#pasar')[0].click();
}

/**
 * Carga un submenú específico en el contenedor #botones (ej: menú preferencial)
 * 
 * @param {string} ubmenu - Clave del submenú a renderizar
 */
function cargamenu(ubmenu) {
    $.post("../ajax/a_menus.php?op=cargamenu", { ubmenu: ubmenu }, function (e) {
        document.getElementById("botones").innerHTML = e;
    });    
}

/**
 * Carga el menú de selección de vendedores asignados a una sucursal específica
 * 
 * @param {string} agenci - Código de la sucursal/sede
 */
function cargamenu_v(agenci) {
    document.getElementById("botones").innerHTML = '';
    $.post("../ajax/a_menus.php?op=cargamenu_v", { agenci: agenci }, function (e) {
        document.getElementById("botones").innerHTML = e;
    });    	
}

/**
 * Carga los botones de la pantalla solicitada y gestiona temporizadores de retorno
 * 
 * @param {string} ubmenu - Nombre de la vista/sección a desplegar
 * @param {string} numfac - Número de factura (opcional, para vistas de confirmación)
 */
function opmenu_enc(ubmenu, numfac = "") {
    $.post("../ajax/a_menus.php?op=opmenu_enc", { ubmenu: ubmenu, numfac: numfac }, function (e) {
        document.getElementById("botones").innerHTML = e;

        // Si la pantalla es 'retirartic' (Retire su Tiquete), retorna al menú principal tras 4 segundos
        if (ubmenu == 'retirartic') {            
            setTimeout(function () { 
                opmenu_enc('principal');
            }, 4000);
        }

        // Si la pantalla es 'pasaentreg' (Pase a Entregas), retorna al menú principal tras 4 segundos
        if (ubmenu == 'pasaentreg') {            
            setTimeout(function () { 
                opmenu_enc('principal');
            }, 4000);
        }

        // Si la pantalla es 'codigobr' (Lectura de código de barras), auto-enfoca el campo de texto
        if (ubmenu == 'codigobr') {            
            document.getElementById("numfac").focus();
        }
    });    
}

/**
 * Consulta la lista de agencias/compañías y las inserta en el elemento select #agenci
 */
async function getcias() {
    await $.post("../ajax/a_videos.php?op=getcias", function (e) {
        $("#agenci").html(e);
    });   
}

/**
 * Crea e imprime un nuevo tiquete de atención en el quiosco
 * 
 * @param {string}  agenci   - Código de la sucursal activa
 * @param {string}  tipoco   - Tipo de trámite (V: Ventas, E: Entregas, C: Cajas, P: Preferencial)
 * @param {string}  prefer   - Indicador de preferencia ('1': Sí, '0': No)
 * @param {string}  vended   - Código de vendedor seleccionado (opcional)
 * @param {string}  numfac   - Número de factura vinculada (opcional)
 * @param {boolean} pAndroid - Indicador si el quiosco corre sobre plataforma Android
 */
async function creaticket(agenci, tipoco, prefer, vended, numfac = "", pAndroid = false) {
    // Si se seleccionó la opción de Atención Preferencial, abre el submenú de tipos preferenciales
    if (tipoco == 'P') {
        cargamenu('prefer');
    } else {
        if (pAndroid) {
            console.log("prefer: " + prefer);
            console.log("tipoco: " + tipoco);

            // Si es preferencial y es ventas, asigna el prefijo 'PV'
            if (prefer == '1' && tipoco == 'V') {
                tipoco = 'P' + tipoco;
            }
            console.log("tipoco: " + tipoco);

            // --- Creación del tiquete en la Base de Datos ---
            await $.post("../ajax/a_menus.php?op=creaticket", { agenci: agenci, tipoco: tipoco, prefer: prefer, vended: vended, numfac: numfac }, function (e) {
                // Muestra la pantalla indicando al cliente que retire su tiquete impreso
                opmenu_enc('retirartic');
                
                // Obtiene los datos detallados del tiquete para construir la trama de impresión
                $.post("../ajax/a_menus.php?op=obtener_imprimir", { codigo: e }, function (dat) {
                    data = JSON.parse(dat);

                    // Estructuración de la trama de texto para la impresora térmica
                    var text = "";
                    text += "Repuestos La Guaca";
                    text += "|BIENVENIDO(A)|" + data["agencia"] + "|Su turno es";
                    text += '|' + data["ticket"];
                    text += "|" + data["vengoa"];
                    
                    if (data["vended"] != null) {
                        text += "|Vendedor: " + data["vended"];
                    } else { 
                        text += "|"; 
                    }
                    
                    text += "|Emision: " + data["fechac"];
                    text += "|" + data["ticket"];

                    // Codifica la trama y envía la orden a través del esquema de URL local / driver de impresión
                    var textEncoded = encodeURI(text);
                    window.location.href = "-V=" + textEncoded;
                    console.log(text);
                });
                
            });   
        } else {    
            // En modo quiosco estándar, despliega la pantalla de confirmación
            opmenu_enc('retirartic');
        }
    }
}