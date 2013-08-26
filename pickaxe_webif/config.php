<?php

//$test1 = read_ini_file('Tor');
//print ($test1 . "<BR>");

//write_ini_file('Tor', 'disabled');

//$test2 = read_ini_file('Tor');
//print ($test2 . "<BR>");

function read_ini_file($read_parameter) 
{
    $ini_array = parse_ini_file("/etc/PIckaxe/PIckaxeConfig.ini");
    #print_r($ini_array) . "<BR>";
    return($ini_array["$read_parameter"]);
}

// #If you want to replace a Setting in the ini, use this code:
/// bool SetCMSSettings(string $Setting, string $replace, string $INI_PATH)
/// $Setting = The Setting, which you want to replace
/// $replace = the new value

function write_ini_fileBROKEN($Setting, $replace)
{
    $INI_PATH = '/etc/PIckaxe/PIckaxeConfig.ini';
//    $ini = fopen($INI_PATH,"w+");
    $ini = fopen($INI_PATH,"w+");
    $i = 0;
    while($Content = fgets($ini))
    {
        if(preg_match("/".$Setting."/", $Content)) 
        {
            fwrite($ini, $Setting."=".$replace);
             $i = 1;
        }
        else
        {
            fwrite($ini, $Content);
        }
    }
    // If, the setting wasnt replaced.
    if($i == 0) 
    {
        fwrite($ini, $Setting."=".$replace);
    }
    fclose($ini);
}



//public function SetCMSSettings($Setting, $replace, $INI_PATH)
//{            
//    $ini = fopen($INI_PATH,"w+");            
//            
//                
//            $i = 0;
//            while($Content = fgets($ini)) 
//            {
//                if(preg_match("/".$Setting."/", $Content)) {
//                    fwrite($ini, $Setting."=".$replace);
//                    $i = 1;
//               } else {
//                    fwrite($ini, $Content);
//                }                
//            }
//            // If, the setting wasnt replaced.
//            if($i == 0) {
//                fwrite($ini, $Setting."=".$replace);
//    }
//fclose($ini);
//}

#write_ini_file($ini_array);
//function write_ini_file2($assoc_arr) { 
function write_ini_file($Setting, $replace) { 
    $path = "/etc/PIckaxe/PIckaxeConfig.ini";
//  From Read function... :P
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

// Parse with sections
//$ini_array2 = parse_ini_file("config.ini.php", true);
//print_r($ini_array2);
?>

