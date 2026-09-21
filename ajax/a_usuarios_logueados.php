<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Usuarios Conectados / Logueados (a_usuarios_logueados.php)
 * ============================================================================
 * Este controlador procesa las peticiones asíncronas para la monitorización
 * y desconexión remota de usuarios / vendedores activos en el sistema.
 * 
 * Operaciones disponibles (vía $_GET['op']):
 * - 'listar'    : Retorna los usuarios actualmente conectados por sede para DataTables.
 * - 'desloguear': Cierra la sesión activa de un usuario de forma remota.
 * - 'selectSede': Construye el selector HTML de sedes respetando los permisos de sesión.
 * ============================================================================
 */

// Inicialización de la sesión PHP si no existe una activa
if (strlen(session_id()) < 1) {
	session_start();
}

// Inclusión del modelo de usuarios logueados
require_once "../models/m_usuarios_logueados.php";

// Instanciación del modelo
$usuariosLogueados = new UsuariosLogueados();

// Sanitización de parámetros recibidos por POST
$nombc03 = isset($_POST["nombc03"]) ? limpiarCadena($_POST["nombc03"]) : "";
$sede = isset($_POST["sede"]) ? limpiarCadena($_POST["sede"]) : "";

// Enrutador de operaciones
switch ($_GET["op"]) {
    
    // Lista los usuarios con sesión activa filtrados por sede
    case 'listar':
        $rspta = $usuariosLogueados->listar($sede);
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-warning" onclick="desloguear(\'' . $reg->nombc03 . '\')"><i class="fa fa-sign-out-alt"></i> Desloguear</button>',
                "1" => $reg->nombc03,
                "2" => $reg->estac03,
                "3" => $reg->fechlog
            );
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    // Fuerza el cierre de sesión / deslogueo de un usuario
    case 'desloguear':
        $rspta = $usuariosLogueados->desloguear($nombc03);
        echo $rspta ? "Usuario deslogueado" : "No se pudo desloguear al usuario";
        break;

    // Genera el selector de sedes según la sede asignada al usuario en sesión
    case 'selectSede':
        require_once "../models/m_sedes.php";
        $sedeModel = new Sede();
        $rspta = $sedeModel->select();
        while ($reg = $rspta->fetch_object()) {
            if ($_SESSION["n_sede"] != '0') {
                if ($_SESSION["n_sede"] == $reg->codigo) {
                    echo '<option selected value=' . $reg->codigo . '>' . $reg->codigo . ' - ' . $reg->nombre . '</option>';
                }
            } else {
                echo '<option value=' . $reg->codigo . '>' . $reg->codigo . ' - ' . $reg->nombre . '</option>';
            }
        }
        break;
}
?>
