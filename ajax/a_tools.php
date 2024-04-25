<?php
function map_field_type_to_bind_type($field_type)
{
    // echo $field_type;
    switch ($field_type)
    {
    case MYSQLI_TYPE_DECIMAL:
    case MYSQLI_TYPE_NEWDECIMAL:
    case MYSQLI_TYPE_FLOAT:
    case MYSQLI_TYPE_DOUBLE:
        return 'd';

    case MYSQLI_TYPE_BIT:
    case MYSQLI_TYPE_TINY:
    case MYSQLI_TYPE_SHORT:
    case MYSQLI_TYPE_LONG:
    case MYSQLI_TYPE_LONGLONG:
    case MYSQLI_TYPE_INT24:
    case MYSQLI_TYPE_YEAR:
    case MYSQLI_TYPE_ENUM:
        return 'i';

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

    case MYSQLI_TYPE_TINY_BLOB:
    case MYSQLI_TYPE_MEDIUM_BLOB:
    case MYSQLI_TYPE_LONG_BLOB:
    case MYSQLI_TYPE_BLOB:
        return 'b';

    default:
        trigger_error("unknown type: $field_type");
        return 's';
    }
}
?>