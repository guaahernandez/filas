<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Modelo de Usuarios del Sistema (m_usuario.php)
 * ============================================================================
 * Este modelo gestiona las operaciones de base de datos sobre la tabla 'usuarios',
 * administrando las cuentas de acceso al sistema, credenciales encriptadas,
 * asignación de roles, departamentos y validación de login.
 * 
 * Funcionalidades:
 * - Inserción y actualización de usuarios.
 * - Cambio y consulta de contraseña cifrada (SHA256).
 * - Activación y desactivación de cuentas.
 * - Validación de credenciales de inicio de sesión (verificar).
 * ============================================================================
 */

// Inclusión del archivo de conexión a la base de datos
require "../config/Conexion.php";

class Usuario {

	/**
	 * Constructor de la clase
	 */
	public function __construct() {

	}

	/**
	 * Inserta un nuevo usuario en la tabla 'usuarios'
	 * 
	 * @param string $nombre         Nombre del usuario
	 * @param string $apellidos      Apellidos
	 * @param string $login          Nombre de usuario para iniciar sesión
	 * @param int    $iddepartamento ID del departamento asignado
	 * @param int    $idtipousuario  ID del rol / tipo de usuario
	 * @param string $email          Correo electrónico
	 * @param string $clavehash      Contraseña encriptada en SHA256
	 * @param string $imagen         Nombre del archivo de foto de perfil
	 * @param string $usuariocreado  Nombre del creador
	 * @param string $codigo_persona Código de identificación / empleado
	 * @param string $n_sede         Código de la sede asignada
	 * @return bool
	 */
	public function insertar($nombre, $apellidos, $login, $iddepartamento, $idtipousuario, $email, $clavehash, $imagen, $usuariocreado, $codigo_persona, $n_sede) {
		date_default_timezone_set('America/Costa_Rica');
		$fechacreado = date('Y-m-d H:i:s');
		$sql = "INSERT INTO usuarios (nombre, apellidos, login, iddepartamento, idtipousuario, email, password, imagen, estado, fechacreado, usuariocreado, codigo_persona, n_sede) 
			VALUES ('$nombre', '$apellidos', '$login', '$iddepartamento', '$idtipousuario', '$email', '$clavehash', '$imagen', '1', '$fechacreado', '$usuariocreado', '$codigo_persona', '$n_sede')";
		return ejecutarConsulta($sql);
	}

	/**
	 * Actualiza los datos de un usuario existente
	 */
	public function editar($idusuario, $nombre, $apellidos, $login, $iddepartamento, $idtipousuario, $email, $imagen, $usuariocreado, $codigo_persona, $n_sede) {
		$sql = "UPDATE usuarios SET nombre='$nombre', apellidos='$apellidos', login='$login', iddepartamento='$iddepartamento', idtipousuario='$idtipousuario', email='$email', imagen='$imagen', usuariocreado='$usuariocreado', codigo_persona='$codigo_persona', n_sede='$n_sede'    
			WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Actualiza exclusivamente la contraseña (hash SHA256) del usuario
	 */
	public function editar_clave($idusuario, $clavehash) {
		$sql = "UPDATE usuarios SET password='$clavehash' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene el ID y hash de contraseña para validación en modales
	 */
	public function mostrar_clave($idusuario) {
		$sql = "SELECT idusuario, password FROM usuarios WHERE idusuario='$idusuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Desactiva un usuario (estado = 0)
	 */
	public function desactivar($idusuario) {
		$sql = "UPDATE usuarios SET estado='0' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Activa un usuario (estado = 1)
	 */
	public function activar($idusuario) {
		$sql = "UPDATE usuarios SET estado='1' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene los datos de un usuario específico
	 */
	public function mostrar($idusuario) {
		$sql = "SELECT * FROM usuarios WHERE idusuario='$idusuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Lista todos los usuarios registrados
	 */
	public function listar() {
		$sql = "SELECT * FROM usuarios";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene la cantidad total de usuarios activos
	 */
	public function cantidad_usuario() {
		$sql = "SELECT count(*) nombre FROM usuarios WHERE estado=1";
		return ejecutarConsulta($sql);
	}

	/**
	 * Valida las credenciales de acceso al sistema (Login) y retorna los datos del usuario autenticado
	 * 
	 * @param string $login Nombre de usuario
	 * @param string $clave Hash SHA256 de la contraseña
	 * @return resource|mysqli_result
	 */
	public function verificar($login, $clave) {
		$sql = "SELECT u.codigo_persona, u.idusuario, u.nombre, u.apellidos, u.login, u.idtipousuario";
		$sql .= ", u.iddepartamento, u.email, u.imagen, tu.nombre as tipousuario, d.descripcion AS departamento, u.n_sede";
		$sql .= " FROM usuarios u INNER JOIN tipousuario tu ON u.idtipousuario=tu.idtipousuario";
		$sql .= " LEFT JOIN cias d ON d.iddepartamento=u.`iddepartamento`";
		$sql .= " WHERE login='$login' AND password='$clave' AND u.estado='1'"; 
		return ejecutarConsulta($sql);  
	}
}
?>
