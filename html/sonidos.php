<?php
    session_start();
    $dirname='../assets/audios/'.$_SESSION["agenci"];
    //si es un directorio lo abro
    
    if (is_dir($dirname)) $dir_handle = opendir($dirname);
    //si no es un directorio devuelvo false para avisar de que ha habido un error
    if (!$dir_handle)
        return false;
        //recorro el contenido del directorio fichero a fichero
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            //si no es un directorio elemino el fichero con unlink()
            if (!is_dir($dirname."/".$file)){
                $dir = $dirname."/".$file;
                echo $dir; 
                $_SESSION["mp3"] = $dir; 
                break;                  
            }
        }
    }

    closedir($dir_handle);
?>