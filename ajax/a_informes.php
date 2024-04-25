<?php 

if (strlen(session_id())<1) 
	session_start();

require "../config/Conexion.php";
require "a_tools.php";

$wher = $_POST["wher"];

switch ($_GET["op"]) {
    
    case 'turnosresumido':
        
        $sql="SELECT s.`sede` Sede, s.`descrip` Nombre, f_totalturnos(s.`id`,0)Total,f_totalturnos(s.`id`,1)Completados 
        FROM sedes s 
        WHERE s.filas = 1 $wher;";
        echo $sql;
	    $rspta = ejecutarConsulta($sql);
        $campos = array();
        $tipos = array();
        $sumas = array();
        $class = array();
        ?>
        <table id="tdatos" class="table table-sm table-striped table-bordered table-condensed table-hover">
            <thead>
                <tr>
                <?php
                    $fields=$rspta->fetch_fields();
                    foreach ($fields as $field) {
                        if(map_field_type_to_bind_type($field->type)=='d'){
                            $class[] = "text-end";
                            echo "<th class='text-end'>$field->name</th>";
                        }else{
                            $class[] = "";
                            echo "<th>$field->name</th>";
                        }
                        
                        $campos[] = $field->name;
                        $tipos[] = map_field_type_to_bind_type($field->type);
                        $sumas[] = 0;
                    }
                ?>
                </tr>
            </thead>
            <tbody>
        <?php
            $fila = 0;
            while ($reg=$rspta->fetch_array()) {
                echo '<tr>';
                for ($i=0; $i < count($campos); $i++) { 
                    if($tipos[$i] == 'd'){
                        $sumas[$i] += $reg[$i];
                    }else{
                        $sumas[$i] = "";
                    }
                    echo "<td class='$class[$i]'>$reg[$i]</td>";
                    
                }
                echo '</tr>';                
            }
            
        ?>
            </tbody>
            <tfoot>
                <tr>
                <?php
                    
                    for ($i=0; $i < count($campos); $i++) { 
                        echo "<th class='$class[$i]'>$sumas[$i]</th>";
                    }
                       
                ?>
                </tr>
            </tfoot>
        </table>
        <?php
        
        break;
    }
 ?>


