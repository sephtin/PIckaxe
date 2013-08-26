<?php

function read_ini_file($read_parameter) 
{
    $ini_array = parse_ini_file("/etc/PIckaxe/PIckaxeConfig.ini");
    #print_r($ini_array) . "<BR>";
    return($ini_array["$read_parameter"]);
}


function write_ini_file($Setting, $replace) { 
    $path = "/etc/PIckaxe/PIckaxeConfig.ini";
    $assoc_arr = parse_ini_file("/etc/PIckaxe/PIckaxeConfig.ini");
    $assoc_arr["$Setting"] = "$replace";
    $content = "";
    foreach ($assoc_arr as $key=>$elem) { 
        if(is_array($elem)) 
        { 
            for($i=0;$i<count($elem);$i++) 
            { 
                $content .= $key."[] = \"".$elem[$i]."\"\n"; 
            } 
        } 
        else if($elem=="") $content .= $key." = \n"; 
        else $content .= $key." = \"".$elem."\"\n"; 
    } 

    if (!$handle = fopen($path, 'w')) { 
        return false; 
    } 
    if (!fwrite($handle, $content)) { 
        fclose($handle);
        return false; 
    } 
    fclose($handle); 
    return true; 
}

?>

