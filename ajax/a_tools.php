<?php
/**
 * ============================================================================
 * Sistema de Control de Filas - Utilidades y Helpers AJAX (a_tools.php)
 * ============================================================================
 * Contiene funciones de soporte para el mapeo de tipos de datos en sentencias
 * preparadas de MySQLi (bind_param), convirtiendo tipos internos de MySQLi
 * a las letras identificadoras requeridas ('i', 'd', 's', 'b').
 * ============================================================================
 */

/**
 * Mapea una constante de tipo de dato de MySQLi al carácter correspondiente
 * utilizado en mysqli_stmt::bind_param.
 * 
 * @param int $field_type Constante de tipo MySQLi (ej: MYSQLI_TYPE_LONG, MYSQLI_TYPE_STRING)
 * @return string Letra correspondiente al tipo de dato:
 *                'i' (entero), 'd' (doble/decimal), 's' (cadena/fecha), 'b' (blob)
 */
function map_field_type_to_bind_type($field_type)
{
    switch ($field_type)
    {
        // Tipos Decimales y Punto Flotante -> 'd' (Double / Float)
        case MYSQLI_TYPE_DECIMAL:
        case MYSQLI_TYPE_NEWDECIMAL:
        case MYSQLI_TYPE_FLOAT:
        case MYSQLI_TYPE_DOUBLE:
            return 'd';

        // Tipos Numéricos Enteros y Boleanos -> 'i' (Integer)
        case MYSQLI_TYPE_BIT:
        case MYSQLI_TYPE_TINY:
        case MYSQLI_TYPE_SHORT:
        case MYSQLI_TYPE_LONG:
        case MYSQLI_TYPE_LONGLONG:
        case MYSQLI_TYPE_INT24:
        case MYSQLI_TYPE_YEAR:
        case MYSQLI_TYPE_ENUM:
            return 'i';

        // Tipos Cadena de Texto, Fechas y Tiempos -> 's' (String)
        case MYSQLI_TYPE_TIMESTAMP:
        case MYSQLI_TYPE_DATE:
        case MYSQLI_TYPE_TIME:
        case MYSQLI_TYPE_DATETIME:
        case MYSQLI_TYPE_NEWDATE:
        case MYSQLI_TYPE_INTERVAL:
        case MYSQLI_TYPE_SET:
        case MYSQLI_TYPE_VAR_STRING:
        case MYSQLI_TYPE_STRING:
        case MYSQLI_TYPE_CHAR:
        case MYSQLI_TYPE_GEOMETRY:
            return 's';

        // Tipos Binarios y Objetos Grandes -> 'b' (Blob)
        case MYSQLI_TYPE_TINY_BLOB:
        case MYSQLI_TYPE_MEDIUM_BLOB:
        case MYSQLI_TYPE_LONG_BLOB:
        case MYSQLI_TYPE_BLOB:
            return 'b';

        // Tipo por defecto si no se reconoce
        default:
            trigger_error("Tipo de campo desconocido: $field_type");
            return 's';
    }
}
?>