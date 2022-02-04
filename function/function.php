<?php

function protect($temp, $isUpper = 0){
    if(get_magic_quotes_gpc()) $temp = stripslashes($temp);
    //$temp = mysql_real_escape_string($temp);

    //$search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
    //$replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

    $search = array("'", "--");
    $replace = array("''", " ");
    $temp = str_replace($search, $replace, $temp);
    //$temp = htmlentities($temp, ENT_QUOTES);
    $temp = trim($temp);
    if($isUpper)
        $temp = strtoupper($temp);
    return $temp;
}
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
function returnToText($text,$isEditor = 0){
    if($isEditor)
    {
        $text = str_replace("\\r\\n","&#013;",$text);
    }
    else
    {
        $text = str_replace("\\r\\n","<br/>",$text);
    }
    $text = str_replace('\\',"",$text);

    return $text;
}
function s_datediff( $str_interval, $dt_menor, $dt_maior, $relative=false){

    if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
    if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);

    $diff = date_diff( $dt_menor, $dt_maior, ! $relative);

    $daysInYear = 360;

    switch( $str_interval){
        case "y":
            $total = $diff->y + $diff->m / 12 + $diff->d / $daysInYear; break;
        case "m":
            $total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
            break;
        case "d":
            $total = $diff->y * $daysInYear + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
            break;
        case "h":
            $total = ($diff->y * $daysInYear + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
            break;
        case "i":
            $total = (($diff->y * $daysInYear + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
            break;
        case "s":
            $total = ((($diff->y * $daysInYear + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
            break;
        case "days":
            $total = $diff->days;
            break;
       }
    if( $diff->invert) return -1 * $total;
    else return $total;
}
function createZip($files = array(),$destination = '',$overwrite = false) {
    if(file_exists($destination) && !$overwrite) { return "File already exist."; }
    $valid_files = array();
    if(is_array($files)) {
        foreach($files as $file) {
            if(file_exists($file)) {
                $valid_files[] = $file;
            }
        }
    }
    if(count($valid_files)) {
        //create the archive
        $zip = new ZipArchive();
        if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
            return "File already exist";
        }
        foreach($valid_files as $file) {
            $zip->addFile($file,$file);
        }
        $zip->close();

        if(file_exists($destination))
        {
            return "File created";
        }
        else
        {
            return "File can't be created";
        }
    }
    else
    {
        return "No file to be zipped";
    }
}
function createThumb($directory, $image, $destination, $size) {
    //$image_file = $image;
    $image_ar = explode('.', $image);
    $image_file = $image_ar[0]."_".$size.".".$image_ar[1];
    $image = $directory.$image;
    if (file_exists($image)) {

        $source_size = getimagesize($image);

        if ($source_size !== false) {

            $thumb_width = $size;
            $thumb_height = $size;

            switch($source_size['mime']) {
                case "image/jpeg":
                    $source = imagecreatefromjpeg($image);
                break;
                case "image/png":
                    $source = imagecreatefrompng($image);
                break;
                case "image/gif":
                    $source = imagecreatefromgif($image);
                break;
            }

            $source_aspect = round(($source_size[0] / $source_size[1]), 1);
            $thumb_aspect = round(($thumb_width / $thumb_height), 1);

            if ($source_aspect < $thumb_aspect) {
            $new_size = array($thumb_width, ($thumb_width / $source_size[0]) * $source_size[1]);
            $source_pos = array(0, ($new_size[1] - $thumb_height) / 2);
            } else if ($source_aspect > $thumb_aspect) {
            $new_size = array(($thumb_width / $source_size[1]) * $source_size[0], $thumb_height);
            $source_pos = array(($new_size[0] - $thumb_width) / 2, 0);
            } else {
            $new_size = array($thumb_width, $thumb_height);
            $source_pos = array(0, 0);
            }

            if ($new_size[0] < 1) $new_size[0] = 1;
            if ($new_size[1] < 1) $new_size[1] = 1;

            $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
            imagecopyresampled($thumb, $source, 0, 0, $source_pos[0], $source_pos[1], $new_size[0], $new_size[1], $source_size[0], $source_size[1]);

            switch($source_size['mime']) {
            case 'image/jpeg':
                    imagejpeg($thumb, $destination.$image_file);
            break;
            case 'image/png':
                    imagepng($thumb, $destination.$image_file);
            break;
            case 'image/gif':
                    imagegif($thumb, $destination.$image_file);
            break;
            }
        }
      }
}
function generateBreadCrumbs($crumbs,$isPicture){
    $breadCrumbs = "";
    foreach($crumbs AS $crumb)
    {
        if($breadCrumbs) $breadCrumbs .= "&nbsp;&rsaquo;&nbsp;";

        $breadCrumbs .= "<span";
        if($crumb[2]) $breadCrumbs.= " class='pointer' onClick='".$crumb[2].";'";
        $breadCrumbs .= "><img src='".$crumb[1]."'/>&nbsp;".$crumb[0]."</span>";
    }
    return "<h6 id='breadcrumbs'>".$breadCrumbs."</h6><br/><hr/><br/>";
}

function encrypt($key, $plainText){
    $key = pack('H*', md5($key).md5($key));

    # create a random IV to use with CBC encoding
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

    # creates a cipher text compatible with AES (Rijndael block size = 128)
    # to keep the text confidential
    # only suitable for encoded input that never ends with value 00h
    # (because of default zero padding)
    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,$plainText, MCRYPT_MODE_CBC, $iv);

    # prepend the IV for it to be available for decryption
    $ciphertext = $iv . $ciphertext;

    # encode the resulting cipher text so it can be represented by a string
    $ciphertext_base64 = base64_encode($ciphertext);

    return $ciphertext_base64;
}
function decrypt($key, $encryptedText){
    $key = pack('H*', md5($key).md5($key));

    # create a random IV to use with CBC encoding
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

    $ciphertext_dec = base64_decode($encryptedText);

    # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
    $iv_dec = substr($ciphertext_dec, 0, $iv_size);

    # retrieves the cipher text (everything except the $iv_size in the front)
    $ciphertext_dec = substr($ciphertext_dec, $iv_size);

    # may remove 00h valued characters from end of plain text
    $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

    return $plaintext_dec;
}
function PHPArrayToJSONText($datas){
    $Ojbs = array();
    foreach($datas AS $index => $data)
    {
        if(is_array($data))
        {
            $Ojbs[] = '"'.protect($index,1).'":'.PHPArrayToJSONText($data);
        }
        /*
        else if(is_int($data))
        {
            $Ojbs[] = $index.":".$data;
        }
        */
        else
        {
            $Ojbs[] = '"'.protect($index,1).'":"'.protect($data,1).'"';
        }
    }
    return "{".implode(",",$Ojbs)."}";
}
function JSONTextToPHPArray($text){
    return json_decode(json_encode(json_decode($text)), true);
}
function generateNumber($number){
    if(round($number) != $number){
        return number_format($number,2,",",".");
    }
    else{
        return number_format($number,0,",",".");
    }
}
function generatePrice($price){
    return "Rp ".generateNumber($price);
    /*
    if(round($price) != $price){
        return "Rp ".number_format($price,2,",",".");
    }
    else{
        return "Rp ".number_format($price,0,",",".");
    }
    */
}
function generatePercent($number1, $number2){
    return number_format(($number1/$number2)*100,2,",",".")." %";
}
function generateDecimal($number){
    return number_format($number,2,",",".");
}
function generateColor($opacity, $dif){
    $var = (1-$opacity) * 255;

    $total_range = (255 - $var) * 6;
    $lamda = floor($total_range / $dif);

    $red = 255;
    $green = $var;
    $blue = $var;

    //echo 'lamda = '.$lamda;
    $colors = array();
    $step = 1;
    if($dif > 4)
    {
        for($counter = 0 ; $counter < $dif ; $counter++)
        {
            if($step == 1)
            {
                if($blue + $lamda > 255)
                {
                    $step++;
                    $temp = $lamda - (255 - $blue);
                    $blue = 255;
                    $red = $red - $temp;
                    //echo "<br/>".$counter."push1";
                    $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
                    $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
                    $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
                    array_push($colors, "#".$r.$g.$b);
                }
                else
                {
                    $blue = $blue + $lamda;
                    //echo "<br/>".$counter."push1";
                    $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
                    $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
                    $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
                    array_push($colors, "#".$r.$g.$b);
                }
            }
            else if($step == 2)
            {
                if($red - $lamda < $var)
                {
                    $step++;
                    $temp = $lamda - ($red - $var);
                    $red = $var;
                    $green = $green + $temp;
                    //echo "<br/>".$counter."push2";
                    $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
                    $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
                    $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
                    array_push($colors, "#".$r.$g.$b);
                }
                else
                {
                    $red = $red - $lamda;
                    //echo "<br/>".$counter."push2";
                    $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
                    $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
                    $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
                    array_push($colors, "#".$r.$g.$b);
                }
            }
            else if($step == 3)
            {
                if($green + $lamda > 255)
                {
                    $step++;
                    $temp = $lamda - (255 - $green);
                    $green = 255;
                    $blue = $blue - $temp;
                    //echo "<br/>".$counter."push3";
                    $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
                    $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
                    $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
                    array_push($colors, "#".$r.$g.$b);
                }
                else
                {
                    $green = $green + $lamda;
                    //echo "<br/>".$counter."push3";
                    $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
                    $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
                    $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
                    array_push($colors, "#".$r.$g.$b);
                }
            }
            else if($step == 4)
            {
                if($blue - $lamda < $var)
                {
                    $step++;
                    $temp = $lamda - ($blue - $var);
                    $blue = $var;
                    $red = $red + $temp;
                    //echo "<br/>".$counter."push4";
                    $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
                    $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
                    $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
                    array_push($colors, "#".$r.$g.$b);
                }
                else
                {
                    $blue = $blue - $lamda;
                    //echo "<br/>".$counter."push4";
                    $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
                    $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
                    $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
                    array_push($colors, "#".$r.$g.$b);
                }
            }
            else if($step == 5)
            {
                if($red + $lamda > 255)
                {
                    $step++;
                    $temp = $lamda - (255 - $red);
                    $red = 255;
                    $green = $green - $temp;
                    //echo "<br/>".$counter."push5";
                    $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
                    $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
                    $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
                    array_push($colors, "#".$r.$g.$b);
                }
                else
                {
                    $red = $red + $lamda;
                    //echo "<br/>".$counter."push5";
                    $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
                    $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
                    $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
                    array_push($colors, "#".$r.$g.$b);
                }
            }
            else if($step == 6)
            {
                if($green - $lamda < $var)
                {
                    $step++;
                    $temp = $lamda - ($green - $var);
                    $green = $var;
                    $blue = $blue + $temp;
                    //echo "<br/>".$counter."push6";
                    $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
                    $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
                    $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
                    array_push($colors, "#".$r.$g.$b);
                }
                else
                {
                    $green = $green - $lamda;
                    //echo "<br/>".$counter."push6";
                    $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
                    $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
                    $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
                    array_push($colors, "#".$r.$g.$b);
                }
            }
        }
    }
    else if($dif == 1)
    {
        array_push($colors, "#000000");
    }
    else if($dif == 2)
    {
        $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
        $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
        $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
        array_push($colors, "#".$r.$g.$b);

        $red = $var;$green = 255;$blue = 255;
        $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
        $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
        $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
        array_push($colors, "#".$r.$g.$b);
    }
    else if($dif == 3)
    {
        $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
        $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
        $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
        array_push($colors, "#".$r.$g.$b);

        $red = $var;$green = $var;$blue = 255;
        $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
        $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
        $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
        array_push($colors, "#".$r.$g.$b);

        $red = $var;$green = 255;$blue = $var;
        $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
        $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
        $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
        array_push($colors, "#".$r.$g.$b);
    }
    else if($dif == 4)
    {
        $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
        $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
        $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
        array_push($colors, "#".$r.$g.$b);

        $red = 255-($var/2);$green = $var;$blue = 255;
        $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
        $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
        $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
        array_push($colors, "#".$r.$g.$b);

        $red = $var;$green = 255;$blue = 255;
        $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
        $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
        $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
        array_push($colors, "#".$r.$g.$b);

        $red = 255-($var/2);$green = 255;$blue = $var;
        $r = strlen(dechex($red)) == 1 ? '0'.dechex($red) : dechex($red);
        $g = strlen(dechex($green)) == 1 ? '0'.dechex($green) : dechex($green);
        $b = strlen(dechex($blue)) == 1 ? '0'.dechex($blue) : dechex($blue);
        array_push($colors, "#".$r.$g.$b);
    }
    //echo '<pre>';
    //print_r($colors);
    //echo '</pre>';

    //foreach($colors as $color) echo '<div style="height:50px;width:50px;background-color:'.$color.'"></div>';
    return $colors;
}
function extractColor($rgbText){
    $r = 0;
    $g = 0;
    $b = 0;

    if(strlen($rgbText) == 7)
    {
        if(substr($rgbText,0,1) == "#")
        {
            $rgb = substr($rgbText, 1);
            $r = hexdec(substr($rgb,0,2));
            $g = hexdec(substr($rgb,2,2));
            $b = hexdec(substr($rgb,4,2));
        }
    }

    return array(
        "r" => $r,
        "g" => $g,
        "b" => $b
    );
}

function arrayToString($array){
    if(count($array))
    {
        return "|".implode("|",$array)."|";
    }
    else return "";
}
function stringToArray($string){
    if($string)
    {
        return explode("|",substr($string, 1, strlen($string)-2));
    }
    else return array();
}
function directoryToArray($directory, $recursive) {
    $array_items = array();
    if ($handle = opendir($directory)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($directory. "/" . $file)) {
                    if($recursive) {
                        $array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
                    }
                    $file = $directory . "/" . $file;
                    $array_items[] = preg_replace("/\/\//si", "/", $file);
                } else {
                    $file = $directory . "/" . $file;
                    $array_items[] = preg_replace("/\/\//si", "/", $file);
                }
            }
        }
        closedir($handle);
    }
    return $array_items;
}
function print_array($Array){
    echo "<pre>";print_r($Array);echo "</pre>";
}

function generateExcelObj($excel,$result,$sheetName){
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
    {
        if(!isset($excel[$sheetName]))$excel[$sheetName] = array();

        if(!isset($excel[$sheetName]["Header"]))
        {
            $excel[$sheetName]["Header"] = array();
            foreach($row AS $header => $value)
            {
                array_push($excel[$sheetName]["Header"],$header);
            }
        }

        if(!isset($excel[$sheetName]["Data"]))$excel[$sheetName]["Data"] = array();
        array_push($excel[$sheetName]["Data"],$row);
    }
    return $excel;
}
function insertExcelObj($objPHPExcel,$counterSheet,$excel){
    foreach($excel AS $sheetName => $dataSheet)
    {
        if($sheetName)
        {
            $objPHPExcel->createSheet($counterSheet);
            $objPHPExcel->setActiveSheetIndex($counterSheet)->setTitle(substr($sheetName,0,31));

            //WRITE HEADER
            $rowstart = 1;
            for($column = 0 ; $column < count($dataSheet['Header']) ; $column++)
            {
                $cell = numToExcelAlpha($column).$rowstart;
                $objPHPExcel->setActiveSheetIndex($counterSheet)->setCellValueExplicit($cell, $dataSheet['Header'][$column], PHPExcel_Cell_DataType::TYPE_STRING);
            }

            //WRITE DATA
            for($row = 0 ; $row < count($dataSheet['Data']) ; $row++)
            {
                $column = 0;
                foreach($dataSheet['Data'][$row] AS $field => $value)
                {
                    $cell = numToExcelAlpha($column).($row+$rowstart+1);
                    $objPHPExcel->setActiveSheetIndex($counterSheet)->setCellValueExplicit($cell, $value, PHPExcel_Cell_DataType::TYPE_STRING);
                    $column++;
                }
            }
            $excel[$sheetName]["IndexSheet"] = $counterSheet;
            $counterSheet++;
        }
    }
    return array(
        $objPHPExcel,
        $counterSheet,
        $excel
    );
}
function generateLaporanExcel($Configs){
    $objPHPExcel = $Configs["objPHPExcel"];
    $Columns = $Configs["Columns"];
    $Datas = $Configs["Datas"];
    $FirstRow = $Configs["FirstRow"];
    $NewFile = $Configs["NewFile"];

    $styleFontRed = array('color' => array('rgb' => '000000'));
    $styleFontBold = array('bold' => true);
    $styleFontBoldRed = array('bold' => true,'color' => array('rgb' => '000000'));

    $rowExcel = $FirstRow;
    $Sums = array();
    $No = 1;

    if(isset($Columns["Names"]))$ColumnNames = $Columns["Names"];else $ColumnNames = array();
    if(isset($Columns["Numbers"]))$ColumnNumbers = $Columns["Numbers"];else $ColumnNumbers = array();
    if(isset($Columns["Sums"]))$ColumnCountSums = $Columns["Sums"];else $ColumnCountSums = array();

    foreach($Datas AS $index => $row)
    {
        $row["No"] = $No;
        foreach($row AS $ColumnName => $Value)
        {
            //if(isset($Columns[$ColumnName]))
            if(in_array($ColumnName,$ColumnNames))
            {
                //$ColumnCount = $Columns[$ColumnName];
                $ColumnCount = array_search($ColumnName, $ColumnNames);
                $ColumnLetter = numToExcelAlpha($ColumnCount);
                if(in_array($ColumnName,$ColumnNumbers))
                {
                    //CURRENCY
                    $Value = $Value * 1;
                    $objPHPExcel->getActiveSheet()->getStyle($ColumnLetter.$rowExcel)->getNumberFormat()->setFormatCode('#,##0');
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit($ColumnLetter.$rowExcel, $Value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    if(in_array($ColumnName,$ColumnCountSums))
                    {
                        if(!isset($Sums[$ColumnName]))$Sums[$ColumnName] = 0;
                        $Sums[$ColumnName] += $Value;
                    }
                }
                else
                {
                    //TEXT
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit($ColumnLetter.$rowExcel, $Value, PHPExcel_Cell_DataType::TYPE_STRING);
                }
            }
        }
        $No++;
        $rowExcel++;
    }

    if(count($Sums))
    {
        foreach($Sums AS $ColumnName => $Value)
        {
            //$ColumnCount = $Columns[$ColumnName];
            $ColumnCount = array_search($ColumnName, $ColumnNames);
            $ColumnLetter = numToExcelAlpha($ColumnCount);

            $objPHPExcel->getActiveSheet()->getStyle($ColumnLetter.$rowExcel)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($ColumnLetter.$rowExcel, $Value, PHPExcel_Cell_DataType::TYPE_NUMERIC);

            $objPHPExcel->getActiveSheet()->getStyle($ColumnLetter.$rowExcel)->applyFromArray(array('font' => $styleFontBold));
        }
    }

    return $objPHPExcel;
}
/* old development version
function generateLaporanExcel($GenerateExcelConfigs){
    $Configs = $GenerateExcelConfigs;
    $objPHPExcel = $GenerateExcelConfigs["objPHPExcel"];
    $Columns = $GenerateExcelConfigs["Columns"];
    $Datas = $GenerateExcelConfigs["Datas"];
    $FirstRow = $GenerateExcelConfigs["FirstRow"];
    $NewFile = $GenerateExcelConfigs["NewFile"];

    $styleFontRed = array('color' => array('rgb' => '000000'));
    $styleFontBold = array('bold' => true);
    $styleFontBoldRed = array('bold' => true,'color' => array('rgb' => '000000'));

    $rowExcel = $FirstRow;
    $Sums = array();
    $No = 1;

    if(isset($Columns["Names"]))$ColumnNames = $Columns["Names"];else $ColumnNames = array();
    if(isset($Columns["Numbers"]))$ColumnNumbers = $Columns["Numbers"];else $ColumnNumbers = array();
    if(isset($Columns["Sums"]))$ColumnCountSums = $Columns["Sums"];else $ColumnCountSums = array();

    foreach($Datas AS $index => $row)
    {
        $row["No"] = $No;
        foreach($row AS $ColumnName => $Value)
        {
            //if(isset($Columns[$ColumnName]))
            if(in_array($ColumnName,$ColumnNames))
            {
                //$ColumnCount = $Columns[$ColumnName];
                $ColumnCount = array_search($ColumnName, $ColumnNames);
                $ColumnLetter = numToExcelAlpha($ColumnCount);
                if(in_array($ColumnName,$ColumnNumbers))
                {
                    //CURRENCY
                    $Value = $Value * 1;
                    $objPHPExcel->getActiveSheet()->getStyle($ColumnLetter.$rowExcel)->getNumberFormat()->setFormatCode('#,##0');
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit($ColumnLetter.$rowExcel, $Value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    if(in_array($ColumnName,$ColumnCountSums))
                    {
                        if(!isset($Sums[$ColumnName]))$Sums[$ColumnName] = 0;
                        $Sums[$ColumnName] += $Value;
                    }
                }
                else
                {
                    //TEXT
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit($ColumnLetter.$rowExcel, $Value, PHPExcel_Cell_DataType::TYPE_STRING);
                }
            }
        }
        $No++;
        $rowExcel++;
    }

    if(count($Sums))
    {
        foreach($Sums AS $ColumnName => $Value)
        {
            //$ColumnCount = $Columns[$ColumnName];
            $ColumnCount = array_search($ColumnName, $ColumnNames);
            $ColumnLetter = numToExcelAlpha($ColumnCount);

            $objPHPExcel->getActiveSheet()->getStyle($ColumnLetter.$rowExcel)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($ColumnLetter.$rowExcel, $Value, PHPExcel_Cell_DataType::TYPE_NUMERIC);

            $objPHPExcel->getActiveSheet()->getStyle($ColumnLetter.$rowExcel)->applyFromArray(array('font' => $styleFontBold));
        }
    }

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter->save($NewFile);
}
*/

function cellColor($objPHPExcel,$cells,$color){
    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}
function numToExcelAlpha($num) {
    $numeric = $num % 26;
    $letter = chr(65 + $numeric);
    $num2 = intval($num / 26);
    if ($num2 > 0) {
        return numToExcelAlpha($num2 - 1) . $letter;
    } else {
        return $letter;
    }
}
function alphaToExcelNum($a) {
    $a = strtoupper($a);
    $num = 0;
    $strarra = array_reverse(str_split($a));
    for($i = 0 ; $i < strlen($a) ; $i++)
    {
        $num += (ord($strarra[$i])-64) * pow(26,$i);
    }
    $num = $num - 1;
    return $num;
}

function generateRandomString($length = 10) {
    //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function generateId($id){
    return strtoupper(base_convert($id,10,36)."-".base_convert(time(),10,36));
}
function generateUniqueId($char){
    return strtoupper($char."-".base_convert(time(),10,36))."-".rand(100,999);
}

function generateITURFTicketNumber($Year,$Month){
    $connect_sqlsrv = connect_sql_server("gaia");

    $query = "SELECT TOP 1 [Number] FROM [FACT_ITURFTicket]
        WHERE YEAR([DateTime]) = ".$Year."
        AND MONTH([DateTime]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateBPPNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("gaia");

    $query = "SELECT TOP 1 [Number] FROM [FACT_ProcurementPlan]
        WHERE [POSId] = ".$POSId."
        AND YEAR([Date]) = ".$Year."
        AND MONTH([Date]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generatePONumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number] FROM [FACT_PurchaseOrder]
        WHERE [POSId] = ".$POSId."
        AND YEAR([Date]) = ".$Year."
        AND MONTH([Date]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateBSNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number] FROM [FACT_BranchShop]
        WHERE [POSId] = ".$POSId."
        AND YEAR([DateTime]) = ".$Year."
        AND MONTH([DateTime]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateNORNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [OrderNumber]
        FROM [FACT_Unit]
        WHERE [POSId] = ".$POSId."
        AND YEAR([InvoiceDate]) = ".$Year."
        AND MONTH([InvoiceDate]) = ".$Month."
        ORDER BY [OrderNumber] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["OrderNumber"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateLogisticNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number] FROM [FACT_Logistic]
        WHERE [POSId] = ".$POSId."
        AND YEAR([Date]) = ".$Year."
        AND MONTH([Date]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateCustomerDepositNumber($POSId,$TypeId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number]
        FROM [FACT_CustomerDeposit]
        WHERE [POSId] = ".$POSId."
        AND [TypeId] = ".$TypeId."
        AND CONVERT(VARCHAR(6), [Date], 112) = '".$Year.$Month."'
        ORDER BY [Number] DESC";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateSPKDistributionNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number]
        FROM [FACT_SPKDistribution]
        WHERE [POSId] = ".$POSId."
        AND YEAR([Date]) = ".$Year."
        AND MONTH([Date]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateJournalCardNumber($POSId,$Year,$Month,$ReferenceTypeId){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [JournalCardNumber] FROM [FACT_JournalCard1]
        WHERE [POSId] = ".$POSId."
        AND [ReferenceTypeId] = ".$ReferenceTypeId."
        AND YEAR([DateTime]) = ".$Year."
        AND MONTH([DateTime]) = ".$Month."
        ORDER BY [JournalCardNumber] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["JournalCardNumber"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateLoanPaymentNumber($POSId,$Year,$Month,$CardCodeId){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number] FROM [FACT_LoanPayment]
        WHERE [POSId] = ".$POSId."
        AND [CardCodeId] = ".$CardCodeId."
        AND YEAR([DateTime]) = ".$Year."
        AND MONTH([DateTime]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateLoanGroupNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number] FROM [FACT_LoanGroup]
        WHERE [POSId] = ".$POSId."
        AND YEAR([Date]) = ".$Year."
        AND MONTH([Date]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateUACNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number]
        FROM [FACT_InterCompanyTransaction]
        WHERE [TypeId] = 1
        AND [POSId] = ".$POSId."
        AND YEAR([DateTime]) = ".$Year."
        AND MONTH([DateTime]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateKACNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number]
        FROM [FACT_InterCompanyTransaction]
        WHERE [TypeId] = 5
        AND [POSId] = ".$POSId."
        AND YEAR([DateTime]) = ".$Year."
        AND MONTH([DateTime]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateLACNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number]
        FROM [FACT_InterCompanyTransaction]
        WHERE [TypeId] = 4
        AND [POSId] = ".$POSId."
        AND YEAR([DateTime]) = ".$Year."
        AND MONTH([DateTime]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateACSONumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number] FROM [FACT_AccessorySalesOrder]
        WHERE [POSId] = ".$POSId."
        AND YEAR([Date]) = ".$Year."
        AND MONTH([Date]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateGINumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number] FROM [FACT_GoodIssue]
        WHERE [POSId] = ".$POSId."
        AND YEAR([Date]) = ".$Year."
        AND MONTH([Date]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateClaimFakpolNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number] FROM [FACT_ClaimFakpol]
        WHERE [POSId] = {$POSId}
        AND YEAR([FakpolInvoiceDate]) = {$Year}
        AND MONTH([FakpolInvoiceDate]) = {$Month}
        ORDER BY [Number] DESC";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateClaimCashbackNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number]
        FROM [FACT_ClaimCashback]
        WHERE [POSId] = {$POSId}
        AND YEAR([Date]) = {$Year}
        AND MONTH([Date]) = {$Month}
        ORDER BY [Number] DESC";

    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generatePKBGroupPDCNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number] FROM [FACT_PKBGroupDPC]
        WHERE [POSId] = {$POSId}
        AND YEAR([Date]) = {$Year}
        AND MONTH([Date]) = {$Month}
        ORDER BY [Number] DESC";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateTreasureBillNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number] FROM [FACT_TreasureBill]
        WHERE [POSId] = ".$POSId."
        AND YEAR([DateTime]) = ".$Year."
        AND MONTH([DateTime]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateMemoInternalNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number] FROM [FACT_MemoInternal]
        WHERE [POSId] = ".$POSId."
        AND YEAR([Date]) = ".$Year."
        AND MONTH([Date]) = ".$Month."
        ORDER BY [Number] DESC;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateKKNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $CardCodeId = 17;//BKK

    $query = "SELECT TOP 1 [JournalCardNumber]
        FROM [FACT_JournalCard1]
        WHERE [POSId] = ".$POSId."
        AND YEAR([DateTime]) = ".$Year."
        AND MONTH([DateTime]) = ".$Month."
        AND [CardCodeId] = ".$CardCodeId."
        ORDER BY [JournalCardNumber] DESC;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["JournalCardNumber"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}

function generateJournalCard1Number($BranchId,$CardCodeId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [JournalCardNumber]
        FROM [FACT_JournalCard1]
        WHERE [POSId] = ".$BranchId."
        AND YEAR([DateTime]) = ".$Year."
        AND MONTH([DateTime]) = ".$Month."
        AND [CardCodeId] = ".$CardCodeId."
        ORDER BY [JournalCardNumber] DESC;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["JournalCardNumber"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateBookingServiceNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [BSNumber] FROM [FACT_BookingPKB]
        WHERE [POSId] = ".$POSId."
        AND YEAR([BSDate]) = ".$Year."
        AND MONTH([BSDate]) = ".$Month."
        ORDER BY [BSNumber] DESC;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["BSNumber"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generatePKBNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [PKBNumber] FROM [FACT_PKB]
        WHERE [POSId] = ".$POSId."
        AND YEAR([PKBDate]) = ".$Year."
        AND MONTH([PKBDate]) = ".$Month."
        ORDER BY [PKBNumber] DESC;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["PKBNumber"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateGRNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "SELECT TOP 1 [Number]
        FROM [FACT_PurchaseOrderGoodReceive]
        WHERE [POSId] = ".$POSId."
        AND YEAR([Date]) = ".$Year."
        AND MONTH([Date]) = ".$Month."
        ORDER BY [Number] DESC;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateAssetNumber($POSId,$Year,$Month,$AssetCategoryTypeId){
    $connect_sqlsrv = connect_sql_server("gaia");

    $query = "SELECT TOP 1 [Number]
        FROM [FACT_Asset]
        WHERE [POSId] = ".$POSId."
        AND [AssetCategoryTypeId] = ".$AssetCategoryTypeId."
        AND YEAR([Date]) = ".$Year."
        ORDER BY [Number] DESC;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}

function journalTransaction($DateTime, $ReferenceTypeId, $ReferenceIds, $POSId, $JournalTransactionItemId, $Value, $JurnalDescription = "", $IsJurnalBalik = 0){
    /*
    SUDAH TIDAK DIGUNAKAN, PAKAI
    tryJournalTransaction + writeJournalTransaction
    */

    $datas = array();
    $datas["DebitCOA6Id"] = "";
    $datas["CreditCOA6Id"] = "";
    $datas["QueryDebit"] = "";
    $datas["QueryCredit"] = "";
    $datas["Message"] = "";

    $JurnalDescription = protect($JurnalDescription,1);

    $ReferenceId = 0;
    $ReferenceId2 = 0;
    $ReferenceId3 = 0;
    $ReferenceId4 = 0;
    $ReferenceId5 = 0;
    $ReferenceId6 = 0;

    if(is_array($ReferenceIds))
    {
        $ReferenceId = $ReferenceIds[0];
        if(isset($ReferenceIds[1]))$ReferenceId2 = $ReferenceIds[1];
        if(isset($ReferenceIds[2]))$ReferenceId3 = $ReferenceIds[2];
        if(isset($ReferenceIds[3]))$ReferenceId4 = $ReferenceIds[3];
        if(isset($ReferenceIds[4]))$ReferenceId5 = $ReferenceIds[4];
        if(isset($ReferenceIds[5]))$ReferenceId6 = $ReferenceIds[5];
    }
    else
    {
        $ReferenceId = $ReferenceIds;
    }

    $connect_sqlsrv = connect_sql_server("plutus");

    $query = "INSERT INTO [LOG_WriteJournal]
        ([DateTime],[ReferenceTypeId],[ReferenceId1],[ReferenceId2],[ReferenceId3],[ReferenceId4],[ReferenceId5],[ReferenceId6]
        ,[JournalTransactionItemId],[Value],[JurnalDescription],[IsJurnalBalik])
        VALUES
        ('".$DateTime."',".$ReferenceTypeId.",".$ReferenceId.",".$ReferenceId2.",".$ReferenceId3.",".$ReferenceId4.",".$ReferenceId5.",".$ReferenceId6."
        ,".$JournalTransactionItemId.",".$Value.",'".$JurnalDescription."',".$IsJurnalBalik.");";
    sqlsrv_query($connect_sqlsrv, $query);

    $rows = getTable(array(
        "database" => "plutus",
        "tableName" => "VIEW_AllJournalTransaction",
        "filtersIn" => array(
            "ItemId" => array($JournalTransactionItemId),
            "POSId" => array($POSId)
        ),
        "isKey" => 0
    ));
    if(count($rows))
    {
        $row = $rows[0];

        $JournalTransactionId = $row["JournalId"];
        $JournalTransaction = $row["Journal"];
        $DebitCOA6Id = $row["DebitCOA6Id"];
        $CreditCOA6Id = $row["CreditCOA6Id"];

        $datas["DebitCOA6Id"] = $row["DebitCOA6Id"];
        $datas["CreditCOA6Id"] = $row["CreditCOA6Id"];

        if($DebitCOA6Id && $CreditCOA6Id)
        {
            if(!$IsJurnalBalik)
            {
                //INSERT DEBIT
                $query = "INSERT INTO [FACT_JournalTransaction]
                    ([JournalTransactionId],[JournalTransaction],[DateTime],[Description],[COA6Id],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3],[ReferenceId4],[ReferenceId5],[ReferenceId6],[Debit],[Credit])
                    VALUES
                    (".$JournalTransactionId.",'".$JournalTransaction."','".$DateTime."','".$JurnalDescription."',".$DebitCOA6Id.",".$ReferenceTypeId.",".$ReferenceId.",".$ReferenceId2.",".$ReferenceId3.",".$ReferenceId4.",".$ReferenceId5.",".$ReferenceId6.",".$Value.",0);";
                $result = sqlsrv_query($connect_sqlsrv, $query);
                $datas["QueryDebit"] = $query;

                //INSERT CREDIT
                $query = "INSERT INTO [FACT_JournalTransaction]
                    ([JournalTransactionId],[JournalTransaction],[DateTime],[Description],[COA6Id],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3],[ReferenceId4],[ReferenceId5],[ReferenceId6],[Debit],[Credit])
                    VALUES
                    (".$JournalTransactionId.",'".$JournalTransaction."','".$DateTime."','".$JurnalDescription."',".$CreditCOA6Id.",".$ReferenceTypeId.",".$ReferenceId.",".$ReferenceId2.",".$ReferenceId3.",".$ReferenceId4.",".$ReferenceId5.",".$ReferenceId6.",0,".$Value.");";
                $result = sqlsrv_query($connect_sqlsrv, $query);
                $datas["QueryCredit"] = $query;
            }
            else if($IsJurnalBalik)
            {
                //INSERT DEBIT
                $query = "INSERT INTO [FACT_JournalTransaction]
                    ([JournalTransactionId],[JournalTransaction],[DateTime],[Description],[COA6Id],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3],[ReferenceId4],[ReferenceId5],[ReferenceId6],[Debit],[Credit])
                    VALUES
                    (".$JournalTransactionId.",'(R) ".$JournalTransaction."','".$DateTime."','".$JurnalDescription."',".$CreditCOA6Id.",".$ReferenceTypeId.",".$ReferenceId.",".$ReferenceId2.",".$ReferenceId3.",".$ReferenceId4.",".$ReferenceId5.",".$ReferenceId6.",".$Value.",0);";
                $result = sqlsrv_query($connect_sqlsrv, $query);
                $datas["QueryDebit"] = $query;

                //INSERT CREDIT
                $query = "INSERT INTO [FACT_JournalTransaction]
                    ([JournalTransactionId],[JournalTransaction],[DateTime],[Description],[COA6Id],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3],[ReferenceId4],[ReferenceId5],[ReferenceId6],[Debit],[Credit])
                    VALUES
                    (".$JournalTransactionId.",'(R) ".$JournalTransaction."','".$DateTime."','".$JurnalDescription."',".$DebitCOA6Id.",".$ReferenceTypeId.",".$ReferenceId.",".$ReferenceId2.",".$ReferenceId3.",".$ReferenceId4.",".$ReferenceId5.",".$ReferenceId6.",0,".$Value.");";
                $result = sqlsrv_query($connect_sqlsrv, $query);
                $datas["QueryCredit"] = $query;
            }
        }
    }
    else
    {
        $datas["Message"] = "Jurnal belum di set";
    }
    return $datas;
}
function tryJournalTransaction($UniqueId, $DateTime, $ApplicationId = 3, $ReferenceTypeId, $ReferenceIds, $POSId, $JournalTransactionItemId, $Value, $JurnalDescription = "", $IsJurnalBalik = 0){
    $datas = array();
    $datas["DebitCOA6Id"] = 0;
    $datas["CreditCOA6Id"] = 0;
    $datas["QueryDebit"] = "";
    $datas["QueryCredit"] = "";
    $datas["Message"] = "";

    if($UniqueId)
    {
        $JurnalDescription = protect($JurnalDescription,1);

        $ReferenceId = 0;
        $ReferenceId2 = 0;
        $ReferenceId3 = 0;
        $ReferenceId4 = 0;
        $ReferenceId5 = 0;
        $ReferenceId6 = 0;

        if(is_array($ReferenceIds))
        {
            $ReferenceId = $ReferenceIds[0];
            if(isset($ReferenceIds[1]))$ReferenceId2 = $ReferenceIds[1];
            if(isset($ReferenceIds[2]))$ReferenceId3 = $ReferenceIds[2];
            if(isset($ReferenceIds[3]))$ReferenceId4 = $ReferenceIds[3];
            if(isset($ReferenceIds[4]))$ReferenceId5 = $ReferenceIds[4];
            if(isset($ReferenceIds[5]))$ReferenceId6 = $ReferenceIds[5];
        }
        else
        {
            $ReferenceId = $ReferenceIds;
        }

        $connect_sqlsrv = connect_sql_server("plutus");

        $query = "INSERT INTO [LOG_WriteJournal]
            ([UniqueId],[DateTime],[ApplicationId],[ReferenceTypeId],[ReferenceId1],[ReferenceId2],[ReferenceId3],[ReferenceId4],[ReferenceId5],[ReferenceId6]
            ,[JournalTransactionItemId],[Value],[JurnalDescription],[IsJurnalBalik])
            VALUES
            ('".$UniqueId."','".$DateTime."',".$ApplicationId.",".$ReferenceTypeId.",".$ReferenceId.",".$ReferenceId2.",".$ReferenceId3.",".$ReferenceId4.",".$ReferenceId5.",".$ReferenceId6."
            ,".$JournalTransactionItemId.",".$Value.",'".$JurnalDescription."',".$IsJurnalBalik.");";
        $datas["QueryLog"] = $query;
        sqlsrv_query($connect_sqlsrv, $query);

        $rows = getTable(array(
            "database" => "plutus",
            "tableName" => "VIEW_AllJournalTransaction",
            "filtersIn" => array(
                "ItemId" => array($JournalTransactionItemId),
                "POSId" => array($POSId)
            ),
            "isKey" => 0
        ));
        if(count($rows))
        {
            $row = $rows[0];

            $JournalTransactionId = $row["JournalId"];
            $JournalTransaction = $row["Journal"];
            $DebitCOA6Id = $row["DebitCOA6Id"];
            $CreditCOA6Id = $row["CreditCOA6Id"];

            $datas["DebitCOA6Id"] = $row["DebitCOA6Id"];
            $datas["CreditCOA6Id"] = $row["CreditCOA6Id"];

            if($DebitCOA6Id && $CreditCOA6Id)
            {
                if(!$IsJurnalBalik)
                {
                    //INSERT DEBIT
                    $query = "INSERT INTO [TEMP_JournalTransaction]
                        ([UniqueId],[JournalTransactionId],[JournalTransaction],[DateTime],[Description],[COA6Id],[ApplicationId],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3],[ReferenceId4],[ReferenceId5],[ReferenceId6],[Debit],[Credit])
                        VALUES
                        ('".$UniqueId."',".$JournalTransactionId.",'".$JournalTransaction."','".$DateTime."','".$JurnalDescription."',".$DebitCOA6Id.",".$ApplicationId.",".$ReferenceTypeId.",".$ReferenceId.",".$ReferenceId2.",".$ReferenceId3.",".$ReferenceId4.",".$ReferenceId5.",".$ReferenceId6.",".$Value.",0);";
                    $datas["QueryDebit"] = $query;
                    $result = sqlsrv_query($connect_sqlsrv, $query);

                    //INSERT CREDIT
                    $query = "INSERT INTO [TEMP_JournalTransaction]
                        ([UniqueId],[JournalTransactionId],[JournalTransaction],[DateTime],[Description],[COA6Id],[ApplicationId],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3],[ReferenceId4],[ReferenceId5],[ReferenceId6],[Debit],[Credit])
                        VALUES
                        ('".$UniqueId."',".$JournalTransactionId.",'".$JournalTransaction."','".$DateTime."','".$JurnalDescription."',".$CreditCOA6Id.",".$ApplicationId.",".$ReferenceTypeId.",".$ReferenceId.",".$ReferenceId2.",".$ReferenceId3.",".$ReferenceId4.",".$ReferenceId5.",".$ReferenceId6.",0,".$Value.");";
                    $datas["QueryCredit"] = $query;
                    $result = sqlsrv_query($connect_sqlsrv, $query);
                }
                else if($IsJurnalBalik)
                {
                    //INSERT DEBIT
                    $query = "INSERT INTO [TEMP_JournalTransaction]
                        ([UniqueId],[JournalTransactionId],[JournalTransaction],[DateTime],[Description],[COA6Id],[ApplicationId],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3],[ReferenceId4],[ReferenceId5],[ReferenceId6],[Debit],[Credit])
                        VALUES
                        ('".$UniqueId."',".$JournalTransactionId.",'(R) ".$JournalTransaction."','".$DateTime."','".$JurnalDescription."',".$CreditCOA6Id.",".$ApplicationId.",".$ReferenceTypeId.",".$ReferenceId.",".$ReferenceId2.",".$ReferenceId3.",".$ReferenceId4.",".$ReferenceId5.",".$ReferenceId6.",".$Value.",0);";
                    $datas["QueryDebit"] = $query;
                    $result = sqlsrv_query($connect_sqlsrv, $query);

                    //INSERT CREDIT
                    $query = "INSERT INTO [TEMP_JournalTransaction]
                        ([UniqueId],[JournalTransactionId],[JournalTransaction],[DateTime],[Description],[COA6Id],[ApplicationId],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3],[ReferenceId4],[ReferenceId5],[ReferenceId6],[Debit],[Credit])
                        VALUES
                        ('".$UniqueId."',".$JournalTransactionId.",'(R) ".$JournalTransaction."','".$DateTime."','".$JurnalDescription."',".$DebitCOA6Id.",".$ApplicationId.",".$ReferenceTypeId.",".$ReferenceId.",".$ReferenceId2.",".$ReferenceId3.",".$ReferenceId4.",".$ReferenceId5.",".$ReferenceId6.",0,".$Value.");";
                    $datas["QueryCredit"] = $query;
                    $result = sqlsrv_query($connect_sqlsrv, $query);
                }
            }
        }
        else
        {
            $datas["Message"] = "Jurnal belum di set";
        }
    }
    return $datas;

}
function writeJournalTransaction($UniqueId){
    if($UniqueId)
    {
        $connect_sqlsrv = connect_sql_server("plutus");

        $query = "INSERT INTO [FACT_JournalTransaction]
            ([UniqueId],[JournalTransactionId],[JournalTransaction],[DateTime],[Description],[COA6Id],[ApplicationId],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3],[ReferenceId4],[ReferenceId5],[ReferenceId6],[Debit],[Credit])
            SELECT[UniqueId],[JournalTransactionId],[JournalTransaction],[DateTime],[Description],[COA6Id],[ApplicationId],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3],[ReferenceId4],[ReferenceId5],[ReferenceId6],[Debit],[Credit]
            FROM [TEMP_JournalTransaction]
            WHERE [UniqueId] = '".$UniqueId."'
            ORDER BY [Id];";
        sqlsrv_query($connect_sqlsrv, $query);
    }
}

function recalculateHPPPersediaan($SparepartPOSId,$AdditionalQuantity,$AdditionalValue){
    $rows = getTable(array(
        "database" => "plutus",
        "tableName" => "VIEW_AllSparepartPOS",
        "filtersIn" => array(
            "SparepartPOSId" => array($SparepartPOSId)
        ),
        "isKey" => 0
    ));
    $SparepartPOS = $rows[0];
    $SparepartId = $SparepartPOS["SparepartId"];

    $ExistingHPP = $SparepartPOS["HPP"];
    $ExistingQuantity = $SparepartPOS["Quantity"] * 1;
    $ExistingValue = $ExistingHPP * $ExistingQuantity;

    $FinalQuantity = $ExistingQuantity + $AdditionalQuantity;
    $FinalValue = $ExistingValue + $AdditionalValue;
    $FinalHPP = $FinalValue / $FinalQuantity;

    $FinalHPPRound = round($FinalHPP);
    $FinalValueRound = $FinalHPPRound * $FinalQuantity;
    $CadanganPembulatan = $FinalValue - $FinalValueRound;
    $CadanganPembulatan = round($FinalValue - $FinalValueRound);
    $AdditionalValueRound = $AdditionalValue - $CadanganPembulatan;

    return array(
        "HPP" => $FinalHPPRound,
        "CadanganPembulatan" => $CadanganPembulatan,
        "AdditionalValue" => $AdditionalValueRound
    );
}

function terbilang($x){
  $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  if ($x < 12)
    return " " . $abil[$x];
  elseif ($x < 20)
    return terbilang($x - 10) . " belas";
  elseif ($x < 100)
    return terbilang($x / 10) . " puluh" . terbilang($x % 10);
  elseif ($x < 200)
    return " seratus" . terbilang($x - 100);
  elseif ($x < 1000)
    return terbilang($x / 100) . " ratus" . terbilang($x % 100);
  elseif ($x < 2000)
    return " seribu" . terbilang($x - 1000);
  elseif ($x < 1000000)
    return terbilang($x / 1000) . " ribu" . terbilang($x % 1000);
  elseif ($x < 1000000000)
    return terbilang($x / 1000000) . " juta" . terbilang($x % 1000000);
}

function getApprovalPlutusOnPlutusRequester($ReferenceTypeId,$ReferenceId,$ReferenceId2,$ReferenceId3,$ApprovalTypeId){
    $Approvals = array();
    $ApprovalDetails = getTable(array(
        "database" => "plutus",
        "tableName" => "VIEW_AllApprovalDetail",
        "filtersIn" => array(
            "ReferenceTypeId" => array($ReferenceTypeId),
            "ReferenceId" => array($ReferenceId),
            "ReferenceId2" => array($ReferenceId2),
            "ReferenceId3" => array($ReferenceId3),
            "ApprovalTypeId" => array($ApprovalTypeId),
            "IsEnable" => array(1)
        ),
        "orders" => array(
            "Id" => "ASC",
            "ApprovalTypeItemOrder" => "ASC"
        ),
        "isKey" => 0
    ));
    foreach($ApprovalDetails AS $index => $ApprovalDetail)
    {
        if($ApprovalDetail["StatusCode"] == "AP")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["ApproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["ApproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["ApproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "black";
        }
        else if($ApprovalDetail["StatusCode"] == "DAP")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["DisapproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["DisapproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["DisapproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "retro_red";
        }
        else if($ApprovalDetail["StatusCode"] == "OS")
        {
            $ApprovalDetail["UserApproveDisapprove"] = "...MENUNGGU...";
            $ApprovalDetail["DateTimeApproveDisapprove"] = "...MENUNGGU...";
            $ApprovalDetail["GeneralNotes"] = "...MENUNGGU...";
            $ApprovalDetail["RowClass"] = "retro_orange";
        }
        $ApprovalDetail["StatusCode"] = "<p class='center underline' title='".$ApprovalDetail["Status"]."'>".$ApprovalDetail["StatusCode"]."</p>";

        $Approvals[] = $ApprovalDetail;
    }
    return $Approvals;
}
function getApprovalPlutusOnPlutusApproval($ApprovalId){
    $Approvals = array();

    $rows = getTable(array(
        "database" => "plutus",
        "tableName" => "FACT_Approval",
        "filtersIn" => array(
            "Id" => array($ApprovalId)
        ),
        "isKey" => 0
    ));
    $Approval = $rows[0];
    $Group = $Approval["Group"];

    //GET APPROVALS
    $ReferenceTypeId = $Approval["ReferenceTypeId"];
    $ReferenceId = $Approval["ReferenceId"];
    $ReferenceId2 = $Approval["ReferenceId2"];
    $ReferenceId3 = $Approval["ReferenceId3"];
    $ApprovalTypeId = $Approval["ApprovalTypeId"];

    $ApprovalDetails = getTable(array(
        "database" => "plutus",
        "tableName" => "VIEW_AllApprovalDetail",
        "filtersIn" => array(
            "ReferenceTypeId" => array($ReferenceTypeId),
            "ReferenceId" => array($ReferenceId),
            "ReferenceId2" => array($ReferenceId2),
            "ReferenceId3" => array($ReferenceId3),
            "ApprovalTypeId" => array($ApprovalTypeId),
            "Group" => array($Group)
        ),
        "orders" => array(
            "ApprovalTypeItemOrder" => "ASC"
        ),
        "isKey" => 0
    ));

    foreach($ApprovalDetails AS $index => $ApprovalDetail)
    {
        if($ApprovalDetail["StatusCode"] == "NU")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["ApproveByUser"].$ApprovalDetail["DisapproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["ApproveDateTime"].$ApprovalDetail["DisapproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["ApproveGeneralNotes"].$ApprovalDetail["DisapproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "light_grey";
            $ApprovalDetail["Action"] = "";
        }
        else if($ApprovalDetail["StatusCode"] == "AP")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["ApproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["ApproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["ApproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "black";
            $ApprovalDetail["Action"] = "";
        }
        else if($ApprovalDetail["StatusCode"] == "DAP")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["DisapproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["DisapproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["DisapproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "retro_red";
            $ApprovalDetail["Action"] = "";
        }
        else if($ApprovalDetail["StatusCode"] == "OS")
        {
            $ApprovalDetail["UserApproveDisapprove"] = "...MENUNGGU...";
            $ApprovalDetail["DateTimeApproveDisapprove"] = "...MENUNGGU...";
            $ApprovalDetail["GeneralNotes"] = "...MENUNGGU...";
            $ApprovalDetail["RowClass"] = "retro_orange";
            if($ApprovalId == $ApprovalDetail["Id"])
            {
                $ApprovalDetail["Action"] = "<p class='center'><span class='k-button' title='APPROVE' onClick='viewWindowApproveRequestOpen(".$Approval["Id"].");'><i class='fa fa-thumbs-up'></i></span>";
                $ApprovalDetail["Action"] .= " <span class='k-button' title='DISAPPROVE' onClick='viewWindowDisapproveRequestOpen(".$Approval["Id"].");'><i class='fa fa-thumbs-down'></i></span></p>";
            }
        }

        $ApprovalDetail["StatusCode"] = "<p class='center underline' title='".$ApprovalDetail["Status"]."'>".$ApprovalDetail["StatusCode"]."</p>";
        if($ApprovalDetail["DateTimeApproveDisapprove"] != "...MENUNGGU...")
            $ApprovalDetail["DateTimeApproveDisapprove"] = date('d M Y, H:i:s', strtotime($ApprovalDetail["DateTimeApproveDisapprove"]));

        $Approvals[] = $ApprovalDetail;
    }

    return $Approvals;
}
function getApprovalPlutusOnGaiaApproval($ReferenceTypeId,$ReferenceId,$ReferenceId2,$ReferenceId3,$ApprovalTypeIds){
    $Approvals = array();

    $ApprovalDetails = getTable(array(
        "database" => "plutus",
        "tableName" => "VIEW_AllApprovalDetail",
        "filtersIn" => array(
            "ReferenceTypeId" => array($ReferenceTypeId),
            "ReferenceId" => array($ReferenceId),
            "ReferenceId2" => array($ReferenceId2),
            "ReferenceId3" => array($ReferenceId3),
            "ApprovalTypeId" => $ApprovalTypeIds,
            "IsEnable" => array(1)
        ),
        "orders" => array(
            "ApprovalTypeItemOrder" => "ASC"
        ),
        "isKey" => 0
    ));

    foreach($ApprovalDetails AS $index => $ApprovalDetail)
    {
        if($ApprovalDetail["StatusCode"] == "AP")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["ApproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["ApproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["ApproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "black";
            $ApprovalDetail["Action"] = "";

            if($ReferenceTypeId == 29){ //DO UNIT
                $ApprovalDetail["Action"] .= "<p class='center'><span class='k-button' title='DISAPPROVE' onClick='viewWindowDisapproveRequestOpen(".$ApprovalDetail["Id"].");'><i class='fa fa-thumbs-down'></i></span></p>";
            }
        }
        else if($ApprovalDetail["StatusCode"] == "DAP")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["DisapproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["DisapproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["DisapproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "retro_red";
            $ApprovalDetail["Action"] = "";

            if($ReferenceTypeId == 29){ //DO UNIT
                $ApprovalDetail["Action"] = "<p class='center'><span class='k-button' title='APPROVE' onClick='viewWindowApproveRequestOpen(".$ApprovalDetail["Id"].");'><i class='fa fa-thumbs-up'></i></span></p>";
            }
        }
        else if($ApprovalDetail["StatusCode"] == "OS")
        {
            $ApprovalDetail["UserApproveDisapprove"] = "...MENUNGGU...";
            $ApprovalDetail["DateTimeApproveDisapprove"] = "...MENUNGGU...";
            $ApprovalDetail["GeneralNotes"] = "...MENUNGGU...";
            $ApprovalDetail["RowClass"] = "retro_orange";

            if($ReferenceTypeId == 29){ //DO UNIT
                $ApprovalDetail["Action"] = "<p class='center'><span class='k-button' title='APPROVE' onClick='viewWindowApproveRequestOpen(".$ApprovalDetail["Id"].");'><i class='fa fa-thumbs-up'></i></span>";
                $ApprovalDetail["Action"] .= " <span class='k-button' title='DISAPPROVE' onClick='viewWindowDisapproveRequestOpen(".$ApprovalDetail["Id"].");'><i class='fa fa-thumbs-down'></i></span></p>";
            }
            else{
                $ApprovalDetail["Action"] = "<p class='center'><span class='k-button' title='APPROVE' onClick='summaryWindowApproveRequestOpen(".$ApprovalDetail["Id"].");'><i class='fa fa-thumbs-up'></i></span>";
                $ApprovalDetail["Action"] .= " <span class='k-button' title='DISAPPROVE' onClick='summaryWindowDisapproveRequestOpen(".$ApprovalDetail["Id"].");'><i class='fa fa-thumbs-down'></i></span></p>";
            }
        }
        $ApprovalDetail["StatusCode"] = "<p class='center underline' title='".$ApprovalDetail["Status"]."'>".$ApprovalDetail["StatusCode"]."</p>";

        $Approvals[] = $ApprovalDetail;
    }

    return $Approvals;
}

function plutusAddApproval($ApprovalTypeId,$ReferenceTypeId,$ReferenceId,$ReferenceId2,$ReferenceId3,$UserId,$GeneralNotes){ //OUT OF DATE

    /* **************************************************
    SUDAH OUT OF DATE, GUNAKAN plutusAddApproval2 INSTEAD
    ************************************************** */

    $connect_sqlsrv = connect_sql_server("plutus");
    $Group = generateUniqueId($UserId);
    $GeneralNotes = protect($GeneralNotes,1);

    $rows = getTable(array(
        "database" => "plutus",
        "tableName" => "DIM_ApprovalType",
        "filtersIn" => array(
            "Id" => array($ApprovalTypeId)
        ),
        "isKey" => 0
    ));
    $ApprovalType = $rows[0];
    $ApprovalTypeName = $ApprovalType["Name"];

    $ApprovalTypeItems = getTable(array(
        "database" => "plutus",
        "tableName" => "DIM_ApprovalTypeItem",
        "filtersIn" => array(
            "ApprovalTypeId" => array($ApprovalTypeId),
            "IsEnable" => array(1)
        ),
        "orders" => array(
            "order" => "ASC"
        ),
        "isKey" => 0
    ));
    foreach($ApprovalTypeItems AS $index => $ApprovalTypeItem)
    {
        $ApprovalTypeItemId = $ApprovalTypeItem["Id"];
        $ApprovalTypeItemOrder = $ApprovalTypeItem["Order"];
        $ApprovalTypeItemName = $ApprovalTypeItem["Name"];

        $query = "INSERT INTO [FACT_Approval]
            ([Group],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3]
            ,[ApprovalTypeId],[ApprovalTypeName],[ApprovalTypeItemId],[ApprovalTypeItemOrder],[ApprovalTypeItemName],[ApprovalGeneralNotes]
            ,[IsEnable],[CreatedByUserId])
            OUTPUT [Inserted].[Id]
            VALUES
            ('".$Group."',".$ReferenceTypeId.",".$ReferenceId.",".$ReferenceId2.",".$ReferenceId3."
            ,".$ApprovalTypeId.",'".$ApprovalTypeName."',".$ApprovalTypeItemId.",".$ApprovalTypeItemOrder.",'".$ApprovalTypeItemName."','".$GeneralNotes."'
            ,1,".$UserId."
            )
            ";
        $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        if(isset($row["Id"]))
        {
            $ApprovalId = $row["Id"];
            $ApprovalAuthorizationIds = array();
            $ApprovalTypeItemPositions = getTable(array(
                "database" => "plutus",
                "tableName" => "DIM_ApprovalTypeItemPosition",
                "filtersIn" => array(
                    "ApprovalTypeItemId" => array($ApprovalTypeItem["Id"])
                ),
                "isKey" => 0
            ));
            foreach($ApprovalTypeItemPositions AS $index => $ApprovalTypeItemPosition)
            {
                $PositionId = $ApprovalTypeItemPosition["PositionId"];

                $query = "INSERT INTO [FACT_ApprovalAuthorization]
                    ([ApprovalId],[PositionId])
                    OUTPUT [Inserted].[Id]
                    VALUES
                    (".$ApprovalId.",".$PositionId.")
                    ";
                $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                $ApprovalAuthorizationIds[] = $row["Id"];
            }

            if(!count($ApprovalAuthorizationIds))
            {
                $query = "DELETE FROM [FACT_Approval] WHERE [Id] = ".$ApprovalId.";";
                $result = sqlsrv_query($connect_sqlsrv, $query);
                $ApprovalTypeName = "";
            }
        }
    }
    return $ApprovalTypeName;
}

function plutusAddApproval2($Params){
    if(isset($Params["ApplicationId"]))$ApplicationId = $Params["ApplicationId"];
    else $ApplicationId = 3;
    if(isset($Params["ApprovalTypeId"]))$ApprovalTypeId = $Params["ApprovalTypeId"];
    else $ApprovalTypeId = 0;
    if(isset($Params["ReferenceTypeId"]))$ReferenceTypeId = $Params["ReferenceTypeId"];
    else $ReferenceTypeId = 0;
    if(isset($Params["ReferenceId1"]))$ReferenceId1 = $Params["ReferenceId1"] * 1;
    else $ReferenceId1 = 0;
    if(isset($Params["ReferenceId2"]))$ReferenceId2 = $Params["ReferenceId2"] * 1;
    else $ReferenceId2 = 0;
    if(isset($Params["ReferenceId3"]))$ReferenceId3 = $Params["ReferenceId3"] * 1;
    else $ReferenceId3 = 0;
    if(isset($Params["UserId"]))$UserId = $Params["UserId"] * 1;
    else $UserId = 0;
    if(isset($Params["GeneralNotes"]))$GeneralNotes = $Params["GeneralNotes"];
    else $GeneralNotes = "";
    if(isset($Params["InternalNotes"]))$InternalNotes = $Params["InternalNotes"];
    else $InternalNotes = "";

    $returns = array();
    $returns["Query"] = "";
    $returns["ApprovalTypeName"] = "";
    $returns["ApprovalIds"] = array();
    $returns["ApprovalAuthorizationIds"] = array();

    $connect_sqlsrv = connect_sql_server("plutus");
    $Group = generateUniqueId($UserId);
    $returns["Group"] = $Group;

    $rows = getTable(array(
        "database" => "plutus",
        "tableName" => "DIM_ApprovalType",
        "filtersIn" => array(
            "Id" => array($ApprovalTypeId),
        ),
        "isKey" => 0
    ));
    $ApprovalType = $rows[0];
    $ApprovalTypeName = $ApprovalType["Name"];
    $returns["ApprovalTypeName"] = $ApprovalTypeName;

    $ApprovalTypeItems = getTable(array(
        "database" => "plutus",
        "tableName" => "DIM_ApprovalTypeItem",
        "filtersIn" => array(
            "ApprovalTypeId" => array($ApprovalTypeId),
            "IsEnable" => array(1)
        ),
        "orders" => array(
            "order" => "ASC"
        ),
        "isKey" => 0
    ));
    foreach($ApprovalTypeItems AS $index => $ApprovalTypeItem)
    {
        $ApprovalTypeItemId = $ApprovalTypeItem["Id"];
        $ApprovalTypeItemOrder = $ApprovalTypeItem["Order"];
        $ApprovalTypeItemName = $ApprovalTypeItem["Name"];

        $query = "INSERT INTO [FACT_Approval]
            ([Group],[ApplicationId],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3]
            ,[ApprovalTypeId],[ApprovalTypeName],[ApprovalTypeItemId],[ApprovalTypeItemOrder],[ApprovalTypeItemName],[ApprovalGeneralNotes]
            ,[IsEnable],[CreatedByUserId])
            OUTPUT [Inserted].[Id]
            VALUES
            ('".$Group."',".$ApplicationId.",".$ReferenceTypeId.",".$ReferenceId1.",".$ReferenceId2.",".$ReferenceId3."
            ,".$ApprovalTypeId.",'".$ApprovalTypeName."',".$ApprovalTypeItemId.",".$ApprovalTypeItemOrder.",'".$ApprovalTypeItemName."','".$GeneralNotes."'
            ,1,".$UserId.");
            ";
        $returns["Query"] = $query;
        $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        if(isset($row["Id"]))
        {
            $ApprovalId = $row["Id"];
            $returns["ApprovalIds"][] = $ApprovalId;
            $ApprovalAuthorizationIds = array();
            $ApprovalTypeItemPositions = getTable(array(
                "database" => "plutus",
                "tableName" => "DIM_ApprovalTypeItemPosition",
                "filtersIn" => array(
                    "ApprovalTypeItemId" => array($ApprovalTypeItem["Id"])
                ),
                "isKey" => 0
            ));
            foreach($ApprovalTypeItemPositions AS $index => $ApprovalTypeItemPosition)
            {
                $PositionId = $ApprovalTypeItemPosition["PositionId"];

                $query = "INSERT INTO [FACT_ApprovalAuthorization]
                    ([ApprovalId],[PositionId])
                    OUTPUT [Inserted].[Id]
                    VALUES
                    (".$ApprovalId.",".$PositionId.")
                    ";
                $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                $ApprovalAuthorizationId = $row["Id"];
                $ApprovalAuthorizationIds[] = $ApprovalAuthorizationId;
                $returns["ApprovalAuthorizationIds"][] = $ApprovalAuthorizationId;
            }

            if(!count($ApprovalAuthorizationIds))
            {
                $query = "DELETE FROM [FACT_Approval] WHERE [Id] = ".$ApprovalId.";";
                $result = sqlsrv_query($connect_sqlsrv, $query);
                $ApprovalTypeName = "";
            }
        }
    }
    return $returns;
}

function TDEAddApproval($Params){
    //MANDATORY
    $ApprovalTypeId = $Params["ApprovalTypeId"];
    $ApplicationId = $Params["ApplicationId"];
    $ReferenceTypeId = $Params["ReferenceTypeId"];
    $ReferenceId = $Params["ReferenceId"];
    $UserId = $Params["UserId"];

    //OPSIONAL
    if(isset($Params["ReferenceId2"]))$ReferenceId2 = $Params["ReferenceId2"];
    else $ReferenceId2 = 0;

    if(isset($Params["ReferenceId3"]))$ReferenceId3 = $Params["ReferenceId3"];
    else $ReferenceId3 = 0;

    if(isset($Params["GeneralNotes"]))$GeneralNotes = protect($Params["GeneralNotes"],1);
    else $GeneralNotes = "";

    if($ApprovalTypeId && $ApplicationId && $ReferenceTypeId && $ReferenceId && $UserId){
        $connect_sqlsrv = connect_sql_server("plutus");
        $Group = generateUniqueId($UserId);

        $rows = getTable(array(
            "database" => "plutus",
            "tableName" => "DIM_ApprovalType",
            "filtersIn" => array(
                "Id" => array($ApprovalTypeId)
            ),
            "isKey" => 0
        ));
        $ApprovalType = $rows[0];
        $ApprovalTypeName = $ApprovalType["Name"];

        $ApprovalTypeItems = getTable(array(
            "database" => "plutus",
            "tableName" => "DIM_ApprovalTypeItem",
            "filtersIn" => array(
                "ApprovalTypeId" => array($ApprovalTypeId),
                "IsEnable" => array(1)
            ),
            "orders" => array(
                "order" => "ASC"
            ),
            "isKey" => 0
        ));

        foreach($ApprovalTypeItems AS $index => $ApprovalTypeItem)
        {
            $ApprovalTypeItemId = $ApprovalTypeItem["Id"];
            $ApprovalTypeItemOrder = $ApprovalTypeItem["Order"];
            $ApprovalTypeItemName = $ApprovalTypeItem["Name"];

            $query = "INSERT INTO [FACT_Approval]
                ([Group],[ApplicationId],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3]
                ,[ApprovalTypeId],[ApprovalTypeName],[ApprovalTypeItemId],[ApprovalTypeItemOrder],[ApprovalTypeItemName],[ApprovalGeneralNotes]
                ,[IsEnable],[CreatedByUserId])
                OUTPUT [Inserted].[Id]
                VALUES
                ('{$Group}',{$ApplicationId},{$ReferenceTypeId},{$ReferenceId},{$ReferenceId2},{$ReferenceId3},{$ApprovalTypeId},'{$ApprovalTypeName}',{$ApprovalTypeItemId},{$ApprovalTypeItemOrder},'{$ApprovalTypeItemName}','{$GeneralNotes}',1,{$UserId})";
                // echo $query; die();
            $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

            if(isset($row["Id"]))
            {
                $ApprovalId = $row["Id"];
                $ApprovalAuthorizationIds = array();
                $ApprovalTypeItemPositions = getTable(array(
                    "database" => "plutus",
                    "tableName" => "DIM_ApprovalTypeItemPosition",
                    "filtersIn" => array(
                        "ApprovalTypeItemId" => array($ApprovalTypeItem["Id"])
                    ),
                    "isKey" => 0
                ));

                foreach($ApprovalTypeItemPositions AS $index => $ApprovalTypeItemPosition)
                {
                    $PositionId = $ApprovalTypeItemPosition["PositionId"];

                    $query = "INSERT INTO [FACT_ApprovalAuthorization]
                        ([ApprovalId],[PositionId])
                        OUTPUT [Inserted].[Id]
                        VALUES
                        (".$ApprovalId.",".$PositionId.")
                        ";
                    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
                    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                    $ApprovalAuthorizationIds[] = $row["Id"];
                }

                if(!count($ApprovalAuthorizationIds))
                {
                    $query = "DELETE FROM [FACT_Approval] WHERE [Id] = ".$ApprovalId.";";
                    $result = sqlsrv_query($connect_sqlsrv, $query);
                    $ApprovalTypeName = "";
                }
            }
        }

        return $ApprovalTypeName;
    }

    return "";
}

function getTDEApproval($DBName,$ApprovalId){
    $Approvals = array();

    $rows = getTable(array(
        "database" => $DBName,
        "tableName" => "FACT_Approval",
        "filtersIn" => array(
            "Id" => array($ApprovalId)
        ),
        "isKey" => 0
    ));
    $Approval = $rows[0];
    $Group = $Approval["Group"];

    //GET APPROVALS
    $ReferenceTypeId = $Approval["ReferenceTypeId"];
    $ReferenceId = $Approval["ReferenceId"];
    $ReferenceId2 = $Approval["ReferenceId2"];
    $ReferenceId3 = $Approval["ReferenceId3"];
    $ApprovalTypeId = $Approval["ApprovalTypeId"];

    $ApprovalDetails = getTable(array(
        "database" => $DBName,
        "tableName" => "VIEW_AllApprovalDetail_".$ReferenceTypeId,
        "filtersIn" => array(
            "ReferenceTypeId" => array($ReferenceTypeId),
            "ReferenceId" => array($ReferenceId),
            "ReferenceId2" => array($ReferenceId2),
            "ReferenceId3" => array($ReferenceId3),
            "ApprovalTypeId" => array($ApprovalTypeId),
            "Group" => array($Group)
        ),
        "orders" => array(
            "ApprovalTypeItemOrder" => "ASC"
        ),
        "isKey" => 0
    ));

    foreach($ApprovalDetails AS $index => $ApprovalDetail)
    {
        if($ApprovalDetail["StatusCode"] == "NU")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["ApproveByUser"].$ApprovalDetail["DisapproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["ApproveDateTime"].$ApprovalDetail["DisapproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["ApproveGeneralNotes"].$ApprovalDetail["DisapproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "light_grey";
            $ApprovalDetail["Action"] = "";
        }
        else if($ApprovalDetail["StatusCode"] == "AP")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["ApproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["ApproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["ApproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "black";
            if($ApprovalId == $ApprovalDetail["Id"])
            {
                $ApprovalDetail["Action"] = "<p class='center'><span class='k-button' title='DISAPPROVE' onClick='viewWindowDisapproveRequestOpen(".$Approval["Id"].");'><i class='fa fa-thumbs-down'></i></span></p>";
            }
        }
        else if($ApprovalDetail["StatusCode"] == "DAP")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["DisapproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["DisapproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["DisapproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "retro_red";
            if($ApprovalId == $ApprovalDetail["Id"])
            {
                $ApprovalDetail["Action"] = "<p class='center'><span class='k-button' title='APPROVE' onClick='viewWindowApproveRequestOpen(".$Approval["Id"].");'><i class='fa fa-thumbs-up'></i></span></p>";
            }
        }
        else if($ApprovalDetail["StatusCode"] == "OS")
        {
            $ApprovalDetail["UserApproveDisapprove"] = "...MENUNGGU...";
            $ApprovalDetail["DateTimeApproveDisapprove"] = "...MENUNGGU...";
            $ApprovalDetail["GeneralNotes"] = "...MENUNGGU...";
            $ApprovalDetail["RowClass"] = "retro_orange";

            if($ApprovalId == $ApprovalDetail["Id"])
            {
                $ApprovalDetail["Action"] = "<p class='center'><span class='k-button' title='APPROVE' onClick='viewWindowApproveRequestOpen(".$Approval["Id"].");'><i class='fa fa-thumbs-up'></i></span>";
                $ApprovalDetail["Action"] .= " <span class='k-button' title='DISAPPROVE' onClick='viewWindowDisapproveRequestOpen(".$Approval["Id"].");'><i class='fa fa-thumbs-down'></i></span></p>";
            }
        }

        $ApprovalDetail["StatusCode"] = "<p class='center underline' title='".$ApprovalDetail["Status"]."'>".$ApprovalDetail["StatusCode"]."</p>";

        $Approvals[] = $ApprovalDetail;
    }

    return $Approvals;
}

function gaiaAddApproval($DBName,$ApprovalTypeId,$ReferenceTypeId,$ReferenceId,$ReferenceId2,$ReferenceId3,$UserId,$GeneralNotes){
    $connect_sqlsrv = connect_sql_server($DBName);
    $Group = generateUniqueId($UserId);

    $rows = getTable(array(
        "database" => $DBName,
        "tableName" => "DIM_ApprovalType",
        "filtersIn" => array(
            "Id" => array($ApprovalTypeId)
        ),
        "isKey" => 0
    ));
    $ApprovalType = $rows[0];
    $ApprovalTypeName = $ApprovalType["Name"];


    $query = "  SELECT *
                FROM [DIM_ApprovalTypeItem]
                WHERE [ApprovalTypeId] = {$ApprovalTypeId}
                AND [IsEnable] = 1
            ";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $ApprovalTypeItems = [];
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
        $ApprovalTypeItems[] = $row;
    }

    foreach($ApprovalTypeItems AS $index => $ApprovalTypeItem)
    {
        $ApprovalTypeItemId = $ApprovalTypeItem["Id"];
        $ApprovalTypeItemOrder = $ApprovalTypeItem["Order"];
        $ApprovalTypeItemName = $ApprovalTypeItem["Name"];

        $query = "INSERT INTO [FACT_Approval]
            ([Group],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3]
            ,[ApprovalTypeId],[ApprovalTypeName],[ApprovalTypeItemId],[ApprovalTypeItemOrder],[ApprovalTypeItemName],[ApprovalGeneralNotes]
            ,[IsEnable],[CreatedByUserId])
            OUTPUT [Inserted].[Id]
            VALUES
            ('".$Group."',".$ReferenceTypeId.",".$ReferenceId.",".$ReferenceId2.",".$ReferenceId3."
            ,".$ApprovalTypeId.",'".$ApprovalTypeName."',".$ApprovalTypeItemId.",".$ApprovalTypeItemOrder.",'".$ApprovalTypeItemName."','".$GeneralNotes."'
            ,1,".$UserId."
            )
            ";
        $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        $ApprovalId = $row["Id"];

        $ApprovalTypeItemPositions = getTable(array(
            "database" => $DBName,
            "tableName" => "DIM_ApprovalTypeItemPosition",
            "filtersIn" => array(
                "ApprovalTypeItemId" => array($ApprovalTypeItem["Id"])
            ),
            "isKey" => 0
        ));
        foreach($ApprovalTypeItemPositions AS $index => $ApprovalTypeItemPosition)
        {
            $PositionId = $ApprovalTypeItemPosition["PositionId"];

            $query = "INSERT INTO [FACT_ApprovalAuthorization]
                ([ApprovalId],[PositionId])
                OUTPUT [Inserted].[Id]
                VALUES
                (".$ApprovalId.",".$PositionId.")
                ";
            $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
        }
    }
    return $ApprovalTypeName;
}

function gaiaAddApproval2($Params){
    if(isset($Params["ApplicationId"]))$ApplicationId = $Params["ApplicationId"];
    else $ApplicationId = 1;
    if(isset($Params["ApprovalTypeId"]))$ApprovalTypeId = $Params["ApprovalTypeId"];
    else $ApprovalTypeId = 0;
    if(isset($Params["ReferenceTypeId"]))$ReferenceTypeId = $Params["ReferenceTypeId"];
    else $ReferenceTypeId = 0;
    if(isset($Params["ReferenceId1"]))$ReferenceId1 = $Params["ReferenceId1"] * 1;
    else $ReferenceId1 = 0;
    if(isset($Params["ReferenceId2"]))$ReferenceId2 = $Params["ReferenceId2"] * 1;
    else $ReferenceId2 = 0;
    if(isset($Params["ReferenceId3"]))$ReferenceId3 = $Params["ReferenceId3"] * 1;
    else $ReferenceId3 = 0;
    if(isset($Params["UserId"]))$UserId = $Params["UserId"] * 1;
    else $UserId = 0;
    if(isset($Params["GeneralNotes"]))$GeneralNotes = $Params["GeneralNotes"];
    else $GeneralNotes = "";
    if(isset($Params["InternalNotes"]))$InternalNotes = $Params["InternalNotes"];
    else $InternalNotes = "";

    $returns = array();
    $returns["Query"] = "";
    $returns["ApprovalTypeName"] = "";
    $returns["ApprovalIds"] = array();
    $returns["ApprovalAuthorizationIds"] = array();

    $connect_sqlsrv = connect_sql_server("gaia");

    $Group = generateUniqueId($UserId);
    $returns["Group"] = $Group;

    $rows = getTable(array(
        "database" => "gaia",
        "tableName" => "DIM_ApprovalType",
        "filtersIn" => array(
            "Id" => array($ApprovalTypeId),
        ),
        "isKey" => 0
    ));
    $ApprovalType = $rows[0];
    $ApprovalTypeName = $ApprovalType["Name"];
    $returns["ApprovalTypeName"] = $ApprovalTypeName;

    $ApprovalTypeItems = getTable(array(
        "database" => "gaia",
        "tableName" => "DIM_ApprovalTypeItem",
        "filtersIn" => array(
            "ApprovalTypeId" => array($ApprovalTypeId),
            "IsEnable" => array(1)
        ),
        "orders" => array(
            "order" => "ASC"
        ),
        "isKey" => 0
    ));
    foreach($ApprovalTypeItems AS $index => $ApprovalTypeItem)
    {
        $ApprovalTypeItemId = $ApprovalTypeItem["Id"];
        $ApprovalTypeItemOrder = $ApprovalTypeItem["Order"];
        $ApprovalTypeItemName = $ApprovalTypeItem["Name"];

        $query = "INSERT INTO [FACT_Approval]
            ([Group],[ApplicationId],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3]
            ,[ApprovalTypeId],[ApprovalTypeName],[ApprovalTypeItemId],[ApprovalTypeItemOrder],[ApprovalTypeItemName],[ApprovalGeneralNotes]
            ,[IsEnable],[CreatedByUserId])
            OUTPUT [Inserted].[Id]
            VALUES
            ('".$Group."',".$ApplicationId.",".$ReferenceTypeId.",".$ReferenceId1.",".$ReferenceId2.",".$ReferenceId3."
            ,".$ApprovalTypeId.",'".$ApprovalTypeName."',".$ApprovalTypeItemId.",".$ApprovalTypeItemOrder.",'".$ApprovalTypeItemName."','".$GeneralNotes."'
            ,1,".$UserId.");
            ";
        $returns["Query"] = $query;
        $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        if(isset($row["Id"]))
        {
            $ApprovalId = $row["Id"];
            $returns["ApprovalIds"][] = $ApprovalId;
            $ApprovalAuthorizationIds = array();
            $ApprovalTypeItemPositions = getTable(array(
                "database" => "gaia",
                "tableName" => "DIM_ApprovalTypeItemPosition",
                "filtersIn" => array(
                    "ApprovalTypeItemId" => array($ApprovalTypeItem["Id"])
                ),
                "isKey" => 0
            ));
            foreach($ApprovalTypeItemPositions AS $index => $ApprovalTypeItemPosition)
            {
                $PositionId = $ApprovalTypeItemPosition["PositionId"];

                $query = "INSERT INTO [FACT_ApprovalAuthorization]
                    ([ApprovalId],[PositionId])
                    OUTPUT [Inserted].[Id]
                    VALUES
                    (".$ApprovalId.",".$PositionId.")
                    ";
                $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                $ApprovalAuthorizationId = $row["Id"];
                $ApprovalAuthorizationIds[] = $ApprovalAuthorizationId;
                $returns["ApprovalAuthorizationIds"][] = $ApprovalAuthorizationId;
            }

            if(!count($ApprovalAuthorizationIds))
            {
                $query = "DELETE FROM [FACT_Approval] WHERE [Id] = ".$ApprovalId.";";
                $result = sqlsrv_query($connect_sqlsrv, $query);
                $ApprovalTypeName = "";
            }
        }
    }
    return $returns;
}

function seleneAddApproval($DBName,$ApprovalTypeId,$ReferenceTypeId,$ReferenceId,$ReferenceId2,$ReferenceId3,$UserId,$GeneralNotes){
    $connect_sqlsrv = connect_sql_server($DBName);
    $Group = generateUniqueId($UserId);

    $rows = getTable(array(
        "database" => $DBName,
        "tableName" => "DIM_ApprovalType",
        "filtersIn" => array(
            "Id" => array($ApprovalTypeId)
        ),
        "isKey" => 0
    ));
    $ApprovalType = $rows[0];
    $ApprovalTypeName = $ApprovalType["Name"];


    $query = "  SELECT *
                FROM [DIM_ApprovalTypeItem]
                WHERE [ApprovalTypeId] = {$ApprovalTypeId}
                AND [IsEnable] = 1
            ";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $ApprovalTypeItems = [];
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
        $ApprovalTypeItems[] = $row;
    }

    foreach($ApprovalTypeItems AS $index => $ApprovalTypeItem)
    {
        $ApprovalTypeItemId = $ApprovalTypeItem["Id"];
        $ApprovalTypeItemOrder = $ApprovalTypeItem["Order"];
        $ApprovalTypeItemName = $ApprovalTypeItem["Name"];

        $query = "INSERT INTO [FACT_Approval]
            ([Group],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3]
            ,[ApprovalTypeId],[ApprovalTypeName],[ApprovalTypeItemId],[ApprovalTypeItemOrder],[ApprovalTypeItemName],[ApprovalGeneralNotes]
            ,[IsEnable],[CreatedByUserId])
            OUTPUT [Inserted].[Id]
            VALUES
            ('".$Group."',".$ReferenceTypeId.",".$ReferenceId.",".$ReferenceId2.",".$ReferenceId3."
            ,".$ApprovalTypeId.",'".$ApprovalTypeName."',".$ApprovalTypeItemId.",".$ApprovalTypeItemOrder.",'".$ApprovalTypeItemName."','".$GeneralNotes."'
            ,1,".$UserId."
            )
            ";
        $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        $ApprovalId = $row["Id"];

        $ApprovalTypeItemPositions = getTable(array(
            "database" => $DBName,
            "tableName" => "DIM_ApprovalTypeItemPosition",
            "filtersIn" => array(
                "ApprovalTypeItemId" => array($ApprovalTypeItem["Id"])
            ),
            "isKey" => 0
        ));
        foreach($ApprovalTypeItemPositions AS $index => $ApprovalTypeItemPosition)
        {
            $PositionId = $ApprovalTypeItemPosition["PositionId"];

            $query = "INSERT INTO [FACT_ApprovalAuthorization]
                ([ApprovalId],[PositionId])
                OUTPUT [Inserted].[Id]
                VALUES
                (".$ApprovalId.",".$PositionId.")
                ";
            $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
        }
    }
    return $ApprovalTypeName;
}

function seleneAddApproval2($Params){
    if(isset($Params["ApplicationId"]))$ApplicationId = $Params["ApplicationId"];
    else $ApplicationId = 1;
    if(isset($Params["ApprovalTypeId"]))$ApprovalTypeId = $Params["ApprovalTypeId"];
    else $ApprovalTypeId = 0;
    if(isset($Params["ReferenceTypeId"]))$ReferenceTypeId = $Params["ReferenceTypeId"];
    else $ReferenceTypeId = 0;
    if(isset($Params["ReferenceId1"]))$ReferenceId1 = $Params["ReferenceId1"] * 1;
    else $ReferenceId1 = 0;
    if(isset($Params["ReferenceId2"]))$ReferenceId2 = $Params["ReferenceId2"] * 1;
    else $ReferenceId2 = 0;
    if(isset($Params["ReferenceId3"]))$ReferenceId3 = $Params["ReferenceId3"] * 1;
    else $ReferenceId3 = 0;
    if(isset($Params["UserId"]))$UserId = $Params["UserId"] * 1;
    else $UserId = 0;
    if(isset($Params["GeneralNotes"]))$GeneralNotes = $Params["GeneralNotes"];
    else $GeneralNotes = "";
    if(isset($Params["InternalNotes"]))$InternalNotes = $Params["InternalNotes"];
    else $InternalNotes = "";

    $returns = array();
    $returns["Query"] = "";
    $returns["ApprovalTypeName"] = "";
    $returns["ApprovalIds"] = array();
    $returns["ApprovalAuthorizationIds"] = array();

    $connect_sqlsrv = connect_sql_server("selene");
    $Group = generateUniqueId($UserId);
    $returns["Group"] = $Group;

    $rows = getTable(array(
        "database" => "selene",
        "tableName" => "DIM_ApprovalType",
        "filtersIn" => array(
            "Id" => array($ApprovalTypeId),
        ),
        "isKey" => 0
    ));
    $ApprovalType = $rows[0];
    $ApprovalTypeName = $ApprovalType["Name"];
    $returns["ApprovalTypeName"] = $ApprovalTypeName;

    $ApprovalTypeItems = getTable(array(
        "database" => "selene",
        "tableName" => "DIM_ApprovalTypeItem",
        "filtersIn" => array(
            "ApprovalTypeId" => array($ApprovalTypeId),
            "IsEnable" => array(1)
        ),
        "orders" => array(
            "order" => "ASC"
        ),
        "isKey" => 0
    ));
    foreach($ApprovalTypeItems AS $index => $ApprovalTypeItem)
    {
        $ApprovalTypeItemId = $ApprovalTypeItem["Id"];
        $ApprovalTypeItemOrder = $ApprovalTypeItem["Order"];
        $ApprovalTypeItemName = $ApprovalTypeItem["Name"];

        $query = "  INSERT INTO [FACT_Approval]
                    ([Group],[ApplicationId],[ReferenceTypeId],[ReferenceId],[ReferenceId2],[ReferenceId3]
                    ,[ApprovalTypeId],[ApprovalTypeName],[ApprovalTypeItemId],[ApprovalTypeItemOrder],[ApprovalTypeItemName],[ApprovalGeneralNotes]
                    ,[IsEnable],[CreatedByUserId])
                    OUTPUT [Inserted].[Id]
                    VALUES
                    ('".$Group."',".$ApplicationId.",".$ReferenceTypeId.",".$ReferenceId1.",".$ReferenceId2.",".$ReferenceId3."
                    ,".$ApprovalTypeId.",'".$ApprovalTypeName."',".$ApprovalTypeItemId.",".$ApprovalTypeItemOrder.",'".$ApprovalTypeItemName."','".$GeneralNotes."'
                    ,1,".$UserId.");
                    ";
        $returns["Query"] = $query;
        $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        if(isset($row["Id"]))
        {
            $ApprovalId = $row["Id"];
            $returns["ApprovalIds"][] = $ApprovalId;
            $ApprovalAuthorizationIds = array();
            $ApprovalTypeItemPositions = getTable(array(
                "database" => "selene",
                "tableName" => "DIM_ApprovalTypeItemPosition",
                "filtersIn" => array(
                    "ApprovalTypeItemId" => array($ApprovalTypeItem["Id"])
                ),
                "isKey" => 0
            ));
            foreach($ApprovalTypeItemPositions AS $index => $ApprovalTypeItemPosition)
            {
                $PositionId = $ApprovalTypeItemPosition["PositionId"];

                $query = "INSERT INTO [FACT_ApprovalAuthorization]
                    ([ApprovalId],[PositionId])
                    OUTPUT [Inserted].[Id]
                    VALUES
                    (".$ApprovalId.",".$PositionId.")
                    ";
                $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                $ApprovalAuthorizationId = $row["Id"];
                $ApprovalAuthorizationIds[] = $ApprovalAuthorizationId;
                $returns["ApprovalAuthorizationIds"][] = $ApprovalAuthorizationId;
            }

            if(!count($ApprovalAuthorizationIds))
            {
                $query = " DELETE FROM [FACT_Approval] WHERE [Id] = ".$ApprovalId.";";
                $result = sqlsrv_query($connect_sqlsrv, $query);
                $ApprovalTypeName = "";
            }
        }
    }
    return $returns;
}

function getApprovalGaiaOnGaiaApproval($DBName,$ApprovalId){
    $Approvals = array();

    $rows = getTable(array(
        "database" => $DBName,
        "tableName" => "FACT_Approval",
        "filtersIn" => array(
            "Id" => array($ApprovalId)
        ),
        "isKey" => 0
    ));
    $Approval = $rows[0];
    $Group = $Approval["Group"];

    //GET APPROVALS
    $Group = $Approval["Group"];
    $ReferenceTypeId = $Approval["ReferenceTypeId"];
    $ReferenceId = $Approval["ReferenceId"];
    $ReferenceId2 = $Approval["ReferenceId2"];
    $ReferenceId3 = $Approval["ReferenceId3"];
    $ApprovalTypeId = $Approval["ApprovalTypeId"];

    $ApprovalDetails = getTable(array(
        "database" => $DBName,
        "tableName" => "VIEW_AllApprovalDetail",
        "filtersIn" => array(
            "ReferenceTypeId" => array($ReferenceTypeId),
            "ReferenceId" => array($ReferenceId),
            "ReferenceId2" => array($ReferenceId2),
            "ReferenceId3" => array($ReferenceId3),
            "ApprovalTypeId" => array($ApprovalTypeId),
            "Group" => array($Group),
            "IsEnable" => array(1)
        ),
        "orders" => array(
            "ApprovalTypeItemOrder" => "ASC"
        ),
        "isKey" => 0
    ));

    foreach($ApprovalDetails AS $index => $ApprovalDetail)
    {
        if($ApprovalDetail["StatusCode"] == "NU")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["ApproveByUser"].$ApprovalDetail["DisapproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["ApproveDateTime"].$ApprovalDetail["DisapproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["ApproveGeneralNotes"].$ApprovalDetail["DisapproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "light_grey";
            $ApprovalDetail["Action"] = "";
        }
        else if($ApprovalDetail["StatusCode"] == "AP")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["ApproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["ApproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["ApproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "black";
            if($ApprovalId == $ApprovalDetail["Id"])
            {
                $ApprovalDetail["Action"] = "<p class='center'><span class='k-button' title='DISAPPROVE' onClick='viewWindowDisapproveRequestOpen(".$Approval["Id"].");'><i class='fa fa-thumbs-down'></i></span></p>";
            }
        }
        else if($ApprovalDetail["StatusCode"] == "DAP")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["DisapproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["DisapproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["DisapproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "retro_red";
            if($ApprovalId == $ApprovalDetail["Id"])
            {
                $ApprovalDetail["Action"] = "<p class='center'><span class='k-button' title='APPROVE' onClick='viewWindowApproveRequestOpen(".$Approval["Id"].");'><i class='fa fa-thumbs-up'></i></span></p>";
            }
        }
        else if($ApprovalDetail["StatusCode"] == "OS")
        {
            $ApprovalDetail["UserApproveDisapprove"] = "...MENUNGGU...";
            $ApprovalDetail["DateTimeApproveDisapprove"] = "...MENUNGGU...";
            $ApprovalDetail["GeneralNotes"] = "...MENUNGGU...";
            $ApprovalDetail["RowClass"] = "retro_orange";
            if($ApprovalId == $ApprovalDetail["Id"])
            {
                $ApprovalDetail["Action"] = "<p class='center'><span class='k-button' title='APPROVE' onClick='viewWindowApproveRequestOpen(".$Approval["Id"].");'><i class='fa fa-thumbs-up'></i></span>";
                $ApprovalDetail["Action"] .= " <span class='k-button' title='DISAPPROVE' onClick='viewWindowDisapproveRequestOpen(".$Approval["Id"].");'><i class='fa fa-thumbs-down'></i></span></p>";
            }
        }

        $ApprovalDetail["StatusCode"] = "<p class='center underline' title='".$ApprovalDetail["Status"]."'>".$ApprovalDetail["StatusCode"]."</p>";

        $Approvals[] = $ApprovalDetail;
    }

    return $Approvals;
}

function getApprovalSeleneOnSeleneApproval($DBName,$ApprovalId){
    $Approvals = array();

    $rows = getTable(array(
        "database" => $DBName,
        "tableName" => "FACT_Approval",
        "filtersIn" => array(
            "Id" => array($ApprovalId)
        ),
        "isKey" => 0
    ));
    $Approval = $rows[0];
    $Group = $Approval["Group"];

    //GET APPROVALS
    $ReferenceTypeId = $Approval["ReferenceTypeId"];
    $ReferenceId = $Approval["ReferenceId"];
    $ReferenceId2 = $Approval["ReferenceId2"];
    $ReferenceId3 = $Approval["ReferenceId3"];
    $ApprovalTypeId = $Approval["ApprovalTypeId"];

    $ApprovalDetails = getTable(array(
        "database" => $DBName,
        "tableName" => "VIEW_AllApprovalDetail_".$ReferenceTypeId,
        "filtersIn" => array(
            "ReferenceTypeId" => array($ReferenceTypeId),
            "ReferenceId" => array($ReferenceId),
            "ReferenceId2" => array($ReferenceId2),
            "ReferenceId3" => array($ReferenceId3),
            "ApprovalTypeId" => array($ApprovalTypeId),
            "Group" => array($Group)
        ),
        "orders" => array(
            "ApprovalTypeItemOrder" => "ASC"
        ),
        "isKey" => 0
    ));

    foreach($ApprovalDetails AS $index => $ApprovalDetail)
    {
        if($ApprovalDetail["StatusCode"] == "NU")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["ApproveByUser"].$ApprovalDetail["DisapproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["ApproveDateTime"].$ApprovalDetail["DisapproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["ApproveGeneralNotes"].$ApprovalDetail["DisapproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "light_grey";
            $ApprovalDetail["Action"] = "";
        }
        else if($ApprovalDetail["StatusCode"] == "AP")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["ApproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["ApproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["ApproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "black";
            $ApprovalDetail["Action"] = "";
        }
        else if($ApprovalDetail["StatusCode"] == "DAP")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["DisapproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["DisapproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["DisapproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "retro_red";
            $ApprovalDetail["Action"] = "";
        }
        else if($ApprovalDetail["StatusCode"] == "OS")
        {
            $ApprovalDetail["UserApproveDisapprove"] = "...MENUNGGU...";
            $ApprovalDetail["DateTimeApproveDisapprove"] = "...MENUNGGU...";
            $ApprovalDetail["GeneralNotes"] = "...MENUNGGU...";
            $ApprovalDetail["RowClass"] = "retro_orange";
            if($ApprovalId == $ApprovalDetail["Id"])
            {
                $ApprovalDetail["Action"] = "<p class='center'><span class='k-button' title='APPROVE' onClick='viewWindowApproveRequestOpen(".$Approval["Id"].");'><i class='fa fa-thumbs-up retro_green'></i></span>";
                $ApprovalDetail["Action"] .= " <span class='k-button' title='DISAPPROVE' onClick='viewWindowDisapproveRequestOpen(".$Approval["Id"].");'><i class='fa fa-thumbs-down retro_red'></i></span></p>";
            }
        }

        $ApprovalDetail["StatusCode"] = "<p class='center underline' title='".$ApprovalDetail["Status"]."'>".$ApprovalDetail["StatusCode"]."</p>";

        $Approvals[] = $ApprovalDetail;
    }

    return $Approvals;
}

function getApprovalSeleneOnSeleneApproval2($DBName,$ReferenceTypeId,$ReferenceId,$ReferenceId2,$ReferenceId3,$ApprovalTypeId){
    $Approvals = array();

    $connect_sqlsrv = connect_sql_server($DBName);
    $query = "  SELECT *
                FROM [VIEW_AllApprovalDetail_{$ReferenceTypeId}]
                WHERE [ReferenceTypeId] = {$ReferenceTypeId}
                AND [ReferenceId] = {$ReferenceId}
                AND [ReferenceId2] = {$ReferenceId2}
                AND [ReferenceId3] = {$ReferenceId3}
                AND [ApprovalTypeId] = {$ApprovalTypeId}
                AND [IsEnable] = 1
                ORDER BY [ApprovalTypeItemOrder] ASC
            ";
    // print_array($query);
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $ApprovalDetails = [];
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
        $ApprovalDetails[] = $row;
    }

    if(count($ApprovalDetails))
    {
        foreach($ApprovalDetails AS $index => $ApprovalDetail)
        {
            if($ApprovalDetail["StatusCode"] == "AP")
            {
                $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["ApproveByUser"];
                $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["ApproveDateTime"];
                $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["ApproveGeneralNotes"];
                $ApprovalDetail["RowClass"] = "black";
                $ApprovalDetail["Action"] = "";
            }
            else if($ApprovalDetail["StatusCode"] == "DAP")
            {
                $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["DisapproveByUser"];
                $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["DisapproveDateTime"];
                $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["DisapproveGeneralNotes"];
                $ApprovalDetail["RowClass"] = "retro_red";
                $ApprovalDetail["Action"] = "";
            }
            else if($ApprovalDetail["StatusCode"] == "OS")
            {
                $ApprovalDetail["UserApproveDisapprove"] = "...MENUNGGU...";
                $ApprovalDetail["DateTimeApproveDisapprove"] = "...MENUNGGU...";
                $ApprovalDetail["GeneralNotes"] = "...MENUNGGU...";
                $ApprovalDetail["RowClass"] = "retro_orange";
                $ApprovalDetail["Action"] = "<p class='center'><span class='k-button' title='APPROVE' onClick='viewWindowApproveRequestOpen(".$ApprovalDetail["Id"].",&quot;".$DBName."&quot;);'><i class='fa fa-thumbs-up'></i></span>";
                $ApprovalDetail["Action"] .= " <span class='k-button' title='DISAPPROVE' onClick='viewWindowDisapproveRequestOpen(".$ApprovalDetail["Id"].",&quot;".$DBName."&quot;);'><i class='fa fa-thumbs-down'></i></span></p>";
            }
            $ApprovalDetail["StatusCode"] = "<p class='center underline' title='".$ApprovalDetail["Status"]."'>".$ApprovalDetail["StatusCode"]."</p>";

            $Approvals[] = $ApprovalDetail;
        }
    }

    return $Approvals;
}

function getApprovalRequester($Params){
    $Approvals = array();
    if(isset($Params["DBName"]))$DBName = $Params["DBName"];
    else $DBName = "gaia";

    $ReferenceTypeId = $Params["ReferenceTypeId"];
    $ReferenceId = $Params["ReferenceId"];
    $ReferenceId2 = $Params["ReferenceId2"];
    $ReferenceId3 = $Params["ReferenceId3"];
    $ApprovalTypeId = $Params["ApprovalTypeId"];

    $ApprovalDetails = getTable(array(
        "database" => $DBName,
        "tableName" => "VIEW_AllApprovalDetail",
        "filtersIn" => array(
            "ReferenceTypeId" => array($ReferenceTypeId),
            "ReferenceId" => array($ReferenceId),
            "ReferenceId2" => array($ReferenceId2),
            "ReferenceId3" => array($ReferenceId3),
            "ApprovalTypeId" => array($ApprovalTypeId),
            "IsEnable" => array(1)
        ),
        "orders" => array(
            "ApprovalTypeItemOrder" => "ASC",
            "Id" => "ASC",
        ),
        "isKey" => 0
    ));
    foreach($ApprovalDetails AS $index => $ApprovalDetail)
    {
        if($ApprovalDetail["StatusCode"] == "AP" || $ApprovalDetail["StatusCode"] == "REQ CO")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["ApproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["ApproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["ApproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "black";
        }
        else if($ApprovalDetail["StatusCode"] == "DAP")
        {
            $ApprovalDetail["UserApproveDisapprove"] = $ApprovalDetail["DisapproveByUser"];
            $ApprovalDetail["DateTimeApproveDisapprove"] = $ApprovalDetail["DisapproveDateTime"];
            $ApprovalDetail["GeneralNotes"] = $ApprovalDetail["DisapproveGeneralNotes"];
            $ApprovalDetail["RowClass"] = "retro_red";
        }
        else if($ApprovalDetail["StatusCode"] == "OS")
        {
            $ApprovalDetail["UserApproveDisapprove"] = "...MENUNGGU...";
            $ApprovalDetail["DateTimeApproveDisapprove"] = "...MENUNGGU...";
            $ApprovalDetail["GeneralNotes"] = "...MENUNGGU...";
            $ApprovalDetail["RowClass"] = "retro_orange";

            if($ApprovalDetail["IsAdditional"])
            {
                $ApprovalTypeItemName = $ApprovalDetail["ApprovalTypeItemName"];
                $ApprovalTypeItemName .= " <span class='k-button' title='DELETE' onClick='ApprovalDeleteApprovalFormValidation(".$ApprovalDetail["Id"].");'>";
                    $ApprovalTypeItemName .= "<i class='fa fa-times'></i>";
                $ApprovalTypeItemName .= "</span>";

                $ApprovalDetail["ApprovalTypeItemName"] = $ApprovalTypeItemName;
            }
        }
        $ApprovalDetail["StatusCode"] = "<p class='center underline' title='".$ApprovalDetail["Status"]."'>".$ApprovalDetail["StatusCode"]."</p>";

        $Approvals[] = $ApprovalDetail;
    }
    return $Approvals;
}

function getDatesFromRange($start, $end) {
    $interval = new DateInterval('P1D'); // PT5M 5 min
    $realEnd = new DateTime($end);
    $realEnd->add($interval);
    $period = new DatePeriod(
         new DateTime($start),
         $interval,
         $realEnd
    );
    $array = [];
    foreach($period as $date) {
        $array[] = $date->format('Y-m-d');
    }
    return $array;
}

function getDatesFromRangeExcludeSundays($start,$end){
    $realStart = strtotime($start);
    $realEnd = strtotime($end);

    $array = [];
    while ($realStart <= $realEnd) {
        if (date('N', $realStart) <= 6) {
            $current = date('Y-m-d', $realStart);
            $array[] = $current;
        }
        $realStart += 86400;
    }
    return $array;
}

function HRDCalculateIHKAlpha($Params){
    if(isset($Params['userId']))$userId = $Params['userId'];
    else $userId = 0;
    if(isset($Params['server']))$server = $Params['server'];
    else $server = '';
    if(isset($Params['type']))$AbsenIjin = $Params['type'];
    else $AbsenIjin = 'ABSENSI';

    $connect_gaia = connect_sql_server("gaia".$server);

    if($AbsenIjin == "ABSENSI")$KeyColumn = "CreatedByUserId";
    else if($AbsenIjin == "REQUEST")$KeyColumn = "EditedByUserId";

    $returns["IsError"] = 1;
    if($KeyColumn){
        if($AbsenIjin == "ABSENSI")
        {
            $query = " WITH [Logs] AS (
                    SELECT [Absensi].*
                    FROM [Gaia].[dbo].[FACT_HRDAbsensi][Absensi]
                    JOIN (SELECT [POSId],[FingerId],[EmployeeName],[Date]
                        FROM [Gaia].[dbo].[FACT_HRDAbsensi]
                        WHERE [IsEnable] = 1
                        GROUP BY [POSId],[FingerId],[Date],[EmployeeName]
                        HAVING COUNT([Id]) > 1)[DP]
                    ON [Absensi].[FingerId] = [DP].[FingerId]
                    AND [Absensi].[POSId] = [DP].[POSId]
                    AND [Absensi].[EmployeeName] = [DP].[EmployeeName]
                    AND [Absensi].[Date] = [DP].[Date]
                    WHERE [Absensi].[IsEnable] = 1
                )
                UPDATE [A]
                SET [IsEnable] = 0
                FROM [Gaia].[dbo].[FACT_HRDAbsensi][A]
                WHERE [Id] IN (
                    SELECT MIN([Id])[Id]
                    FROM [Logs]
                    WHERE [IsEnable] = 1
                    GROUP BY [POSId],[FingerId],[EmployeeName],[Date]
            );";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
             //  HAPUS DATA ABSENSI YANG  DUPLIKAT

            $query = "  UPDATE [Absensi]
                        SET [IsEnable] = 0
                        FROM [Gaia].[dbo].[FACT_HRDAbsensi][Absensi]
                        JOIN [Uranus].[dbo].[VIEW_AllEmployee][Employee]
                            ON [Absensi].[FingerId] = [Employee].[Id]
                            AND [Employee].[EmployeeStatusId] IN (4,5,6,7,9,10)
                            AND [Absensi].[Date] >= [Employee].[EmployeeStatusStartDate]
                        WHERE YEAR([Absensi].[Date]) >= 2021
                        AND [Absensi].[IsEnable] = 1;";
            $stmt = sqlsrv_query($connect_gaia, $query);//  HAPUS DATA ABSENSI YANG  SUDAH RESIGN
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }

            $query = "  UPDATE [Absensi]
                    SET [IsEnable] = 0
                    FROM [Gaia].[dbo].[FACT_HRDAbsensi][Absensi]
                    JOIN [Uranus].[dbo].[VIEW_AllEmployee][Employee]
                        ON [Absensi].[FingerId] = [Employee].[Id]
                        AND [Absensi].[Date] < [Employee].[FirstEmployeeStatusStartDate]
                    WHERE YEAR([Absensi].[Date]) >= 2021
                    AND [Absensi].[IsEnable] = 1;";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            } //  HAPUS DATA ABSENSI TANGGAL ABSEN YANG LEBIH DULU DARI TANGGAL KONTRAK
        }

        // <editor-fold defaultstate="collapsed" desc="FLUSH DATA THK & ALPHA">
            $query = "  UPDATE [FACT_HRDAbsensi]
                        SET [IHKIn] = 0,[IHKOut] = 0,[Alpa] = 1
                        WHERE [IsSync] = 1
                        AND [".$KeyColumn."] = ".$userId."
                        AND [IsEnable] = 1;";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="UPDATE JAM MASUK BRANCH SCHEDULE">
            $query = "  UPDATE [Absensi]
                        SET [Absensi].[DueTimeIn] = [PosAbsensi].[TimeIn]
                            ,[Absensi].[DueTimeOut] = [PosAbsensi].[TimeOut]
                        FROM [Gaia].[dbo].[FACT_HRDAbsensi][Absensi]
                        JOIN [FACT_POSAbsensi][PosAbsensi]
                            ON[Absensi].[POSId] = [PosAbsensi].[POSId]
                        JOIN [DIM_UniversalDimmension][Day]
                            ON [PosAbsensi].[DayId] = [Day].[Id]
                            AND [Absensi].[Day] = [Day].[Name1]
                        WHERE [Absensi].[IsSync] = 1
                        AND [Absensi].[IsEnable] = 1
                        AND [Absensi].[".$KeyColumn."] = ".$userId.";";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }

            $query =  " UPDATE [Absensi]
                SET [Absensi].[Alpa] = 0
                    ,[Absensi].[DueTimeIn] = [PosAbsensi].[TimeIn]
                    ,[Absensi].[DueTimeOut] = [PosAbsensi].[TimeOut]
                FROM [Gaia].[dbo].[FACT_HRDAbsensi][Absensi]
                JOIN [Uranus].[dbo].[VIEW_AllEmployeeDateStatusPosition][DSP]
                    ON [Absensi].[Date] = [DSP].[date_id]
                    AND [Absensi].[FingerId] = [DSP].[EmployeeId]
                JOIN [Gaia].[dbo].[DIM_UniversalDimmension][UD]
                    ON [Absensi].[Day] = [UD].[Name1]
                JOIN [FACT_POSAbsensi][PosAbsensi]
                    ON[DSP].[POSId] = [PosAbsensi].[POSId]
                    AND [UD].[Id] = [PosAbsensi].[DayId]
                    WHERE [Absensi].[IsSync] = 1
                AND [Absensi].[IsEnable] = 1
                AND [Absensi].[TimeIn] != '00:00:00.0000000'
                AND [Absensi].[TimeOut] != '00:00:00.0000000'
                AND [Absensi].[DueTimeIn] = '00:00:00.0000000'
                AND [Absensi].[DueTimeOut] = '00:00:00.0000000'
                AND [Absensi].[POSId] != [DSP].[POSId]
                AND [Absensi].[".$KeyColumn."] = ".$userId.";";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="UPDATE ALPHA KLO ADA ABSEN">
            $query = "  UPDATE [FACT_HRDAbsensi]
                        SET [Alpa] = 0
                        WHERE [IsSync] = 1
                        AND [IsEnable] = 1
                        AND [".$KeyColumn."] = ".$userId."
                        AND ([TimeIn] != '00:00:00.0000000' OR [TimeOut] != '00:00:00.0000000');";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="UPDATE IHKIN KLO TIDAK TELAT">
            $query = "  UPDATE [FACT_HRDAbsensi]
                    SET [IHKIn] = 1
                    WHERE [IsSync] = 1
                    AND [IsEnable] = 1
                    AND [Day] != 'Minggu'
                    AND [".$KeyColumn."] = ".$userId."
                    AND [TimeIn] != '00:00:00.0000000'
                    AND [TimeIn] <= [DueTimeIn];";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="UPDATE IHKOUT KLO TIDAK PULANG CEPAT">
            $query = "  UPDATE [FACT_HRDAbsensi]
                    SET [IHKOut] = 1
                    WHERE [IsSync] = 1
                    AND [IsEnable] = 1
                    AND [Day] != 'Minggu'
                    AND [".$KeyColumn."] = ".$userId."
                    AND [TimeOut] != '00:00:00.0000000'
                    AND [TimeOut] >= [DueTimeOut];";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="UPDATE REQUEST ABSENSI">
            $query = "  UPDATE [Absensi]
                        SET [Absensi].[Dinas] = CASE WHEN [Request].[RequestTypeId] = 1 THEN 1 ELSE 0 END --DINAS
                            ,[Absensi].[IjinTelat] = CASE WHEN [Request].[RequestTypeId] = 2 THEN 1 ELSE 0 END --TERLAMBAT MASUK
                            ,[Absensi].[IjinPulangCepat] = CASE WHEN [Request].[RequestTypeId] = 3 THEN 1 ELSE 0 END --PULANG LEBIH CEPAT
                            ,[Absensi].[Sakit] = CASE WHEN [Request].[RequestTypeId] = 4 THEN 1 ELSE 0 END --SAKIT
                            ,[Absensi].[Cuti] = CASE WHEN [Request].[RequestTypeId] = 5 THEN 1 ELSE 0 END --CUTI TAHUNAN
                            ,[Absensi].[IjinKhusus] = CASE WHEN [Request].[RequestTypeId] = 6 THEN 1 ELSE 0 END --IJIN KHUSUS
                            ,[Absensi].[DispensasiAbsenPulang] = CASE WHEN [Request].[RequestTypeId] = 7 THEN 1 ELSE 0 END --DISPEN ABSEN PULANG
                            ,[Absensi].[DispensasiMasuk] = CASE WHEN [Request].[RequestTypeId] = 8 THEN 1 ELSE 0 END --DISPEN MASUK
                            ,[Absensi].[CutiKhusus] = CASE WHEN [Request].[RequestTypeId] = 9 THEN 1 ELSE 0 END --CUTI KHUSUS
                            ,[Absensi].[DispensasiMasukMatiLampu] = CASE WHEN [Request].[RequestTypeId] = 10 THEN 1 ELSE 0 END --DISPEN MASUK MATI LAMPU
                            ,[Absensi].[Training] = CASE WHEN [Request].[RequestTypeId] = 11 THEN 1 ELSE 0 END --TRAINING
                            --,[Absensi].[LiburBersama] = CASE WHEN [Request].[RequestTypeId] = 12 THEN 1 ELSE 0 END --TRAINING
                            ,[Absensi].[SuratTugas] = CASE WHEN [Request].[RequestTypeId] = 13 THEN 1 ELSE 0 END --SURAT TUGAS
                            ,[Absensi].[IjinHariPengganti] = CASE WHEN [Request].[RequestTypeId] = 14 THEN 1 ELSE 0 END --IJIN HARI PENGGANTI
                            ,[Absensi].[CutiKhususIbadahHaji] = CASE WHEN [Request].[RequestTypeId] = 15 THEN 1 ELSE 0 END --CUTI KHUSUS IBADAH HAJI
                            ,[Absensi].[IjinJatahPulang] = CASE WHEN [Request].[RequestTypeId] = 16 THEN 1 ELSE 0 END --IJIN JATAH PULANG
                            ,[Absensi].[IjinTravelingKaryawan] = CASE WHEN [Request].[RequestTypeId] = 17 THEN 1 ELSE 0 END --IJIN TRAVELING KARYAWAN
                            ,[Absensi].[IjinBelumMemilkiCuti] = CASE WHEN [Request].[RequestTypeId] = 18 THEN 1 ELSE 0 END --IJIN BELUM MEMIMILIKI CUTI
                            ,[Absensi].[IjinUmroh] = CASE WHEN [Request].[RequestTypeId] = 19 THEN 1 ELSE 0 END --IJIN UMROH
                            ,[Absensi].[IjinBencanaAlam] = CASE WHEN [Request].[RequestTypeId] = 20 THEN 1 ELSE 0 END --IJIN BENCANA ALAM
                        FROM [Gaia].[dbo].[FACT_HRDAbsensi][Absensi]
                        JOIN [FACT_HRDAbsensiRequest] [Request]
                            ON [Absensi].[IsSync] = 1
                            AND [Absensi].[".$KeyColumn."] = ".$userId."
                            AND [Request].[IsEnable] = 1
                            AND [Absensi].[IsEnable] = 1
                            AND [Request].[ApprovalHRD] = 1
                            AND [Request].[IsDisapprove] = 0
                            AND [Absensi].[FingerId] = [Request].[EmployeeId]
                            AND [Request].[Date] = [Absensi].[Date];";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="UPDATE THK KLO DINAS & TRAINING">
            $query = "  UPDATE [FACT_HRDAbsensi]
                        SET [IHKIn] = 1,[IHKOut] = 1,[Alpa] = 0
                        WHERE [IsSync] = 1
                        AND [IsEnable] = 1
                        AND [".$KeyColumn."] = ".$userId."
                        AND ([Dinas] = 1 OR [Training] = 1)
                        AND [Day] != 'Minggu';";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="UPDATE ALPHA KLO IJIN">
            $query = "  UPDATE [FACT_HRDAbsensi]
                        SET [IHKIn] = 0, [IHKOut] = 0, [Alpa] = 0
                        WHERE [IsSync] = 1
                        AND [IsEnable] = 1
                        AND [".$KeyColumn."] = ".$userId."
                        AND ([IjinBelumMemilkiCuti] = 1 OR [IjinBencanaAlam] = 1 OR [IjinHariPengganti]= 1 OR [IjinJatahPulang] = 1 OR [IjinKhusus] = 1 OR [IjinTravelingKaryawan]= 1 OR [IjinUmroh] = 1);";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="UPDATE ALPHA KLO CUTI">
            $query = "  UPDATE [FACT_HRDAbsensi]
                        SET [IHKIn] = 0, [IHKOut] = 0, [Alpa] = 0
                        WHERE [IsSync] = 1
                        AND [IsEnable] = 1
                        AND [".$KeyColumn."] = ".$userId."
                        AND ([Cuti] = 1 OR [CutiKhusus] = 1 OR [CutiKhususIbadahHaji] = 1);";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="UPDATE ALPHA KLO SAKIT">
            $query = "  UPDATE [FACT_HRDAbsensi]
                        SET [IHKIn] = 0, [IHKOut] = 0, [Alpa] = 0
                        WHERE [IsSync] = 1
                        AND [IsEnable] = 1
                        AND [".$KeyColumn."] = ".$userId."
                        AND ([Sakit] = 1);";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="UPDATE IHKIN KLO DISPEN MASUK">
            $query = "  UPDATE [FACT_HRDAbsensi]
                        SET [IHKIn] = 1,[Alpa] = 0
                        WHERE [IsSync] = 1
                        AND [IsEnable] = 1
                        AND [".$KeyColumn."] = ".$userId."
                        AND ([DispensasiMasuk] = 1 OR [DispensasiMasukMatiLampu] = 1);";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="UPDATE IHKOUT KLO DISPEN PULANG">
            $query = "  UPDATE [FACT_HRDAbsensi]
                        SET [IHKOut] = 1,[Alpa] = 0
                        WHERE [IsSync] = 1
                        AND [IsEnable] = 1
                        AND [".$KeyColumn."] = ".$userId."
                        AND [DispensasiAbsenPulang] = 1;";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="NO ALPHA KLO HARI LIBUR NASIONAL, THK HANGUS">
            $query = "  UPDATE [Absensi]
                    SET [Absensi].[IHKIn] = 0
                        ,[Absensi].[IHKOut] = 0
                        ,[Absensi].[Alpa] = 0
                    FROM [Gaia].[dbo].[FACT_HRDAbsensi][Absensi]
                    JOIN [Uranus].[dbo].[DIM_POS][POS]
                        ON [POS].[Id] = [Absensi].[POSId]
                    JOIN [Uranus].[dbo].[DIM_Branch][Branch]
                        ON [Branch].[Id] = [POS].[BranchId]
                    JOIN [FACT_HRDAbsensiOffDay][Holiday]
                        ON [Absensi].[Date] = [Holiday].[Date]
                        AND [Holiday].[BranchId] IS NULL
                        AND [Holiday].[IsFinance] IS NULL
                    WHERE [Absensi].[IsSync] = 1
                    AND [Absensi].[IsEnable] = 1
                    AND [Absensi].[".$KeyColumn."] = ".$userId.";";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="NO ALPHA KLO HARI LIBUR BRANCH, THK HANGUS">
            $query = "  UPDATE [Absensi]
                        SET [Absensi].[IHKIn] = 0
                            ,[Absensi].[IHKOut] = 0
                            ,[Absensi].[Alpa] = 0
                        FROM [Gaia].[dbo].[FACT_HRDAbsensi][Absensi]
                        JOIN [Uranus].[dbo].[DIM_POS][POS]
                            ON [POS].[Id] = [Absensi].[POSId]
                        JOIN [Uranus].[dbo].[DIM_Branch][Branch]
                            ON [Branch].[Id] = [POS].[BranchId]
                        JOIN [FACT_HRDAbsensiOffDay][HolidayBranch]
                            ON [Absensi].[Date] = [HolidayBranch].[Date]
                            AND [Branch].[Id] = [HolidayBranch].[BranchId]
                            AND [HolidayBranch].[IsFinance] IS NULL
                        WHERE [Absensi].[IsSync] = 1
                        AND [Absensi].[IsEnable] = 1
                        AND [Absensi].[".$KeyColumn."] = ".$userId.";";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="UPDATE THK KLO MINGGU ADA SURAT TUGAS, MEM BY PASS HARI MINGGU ATAU LIBUR">
            $query = "  UPDATE [FACT_HRDAbsensi]
                    SET [IHKIn] = 1, [IHKOut] = 1, [Alpa] = 0
                    WHERE [IsSync] = 1
                    AND [IsEnable] = 1
                    AND [".$KeyColumn."] = ".$userId."
                    AND [SuratTugas] = 1;";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            } //TIDAK MEMPENGARUHI IHK
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="KARYAWAN HO DI ABSENSI PUSAT HARI SABTU">
            $query = "  UPDATE [Absensi]
                        SET [Absensi].[IHKIn] = 1
                            ,[Absensi].[IHKOut] = 1
                        FROM [Gaia].[dbo].[FACT_HRDAbsensi][Absensi]
                        JOIN [Uranus].[dbo].[VIEW_AllEmployee][Employee]
                            ON [Absensi].[IsSync] = 1
                            AND [Absensi].[".$KeyColumn."] = ".$userId."
                            AND [Absensi].[FingerId] = [Employee].[Id]
                            AND [Absensi].[POSId] IN (100,111,121)
                            AND [Employee].[POS] IN ('PUSAT','PUSAT BLOK J','PUSAT BLOK I','PUSAT BLOK M')
                        WHERE [Day] = 'Sabtu'
                        AND [Absensi].[IsEnable] = 1
                        AND [TimeIn] != '00:00:00.0000000'
                        AND [TimeOut] != '00:00:00.0000000'
                        AND DATEDIFF(MINUTE,[Absensi].[TimeIn],'12:00:00')
                                + CASE WHEN [Absensi].[TimeOut] > '13:00:00' THEN DATEDIFF(MINUTE,'13:00:00',[Absensi].[TimeOut]) ELSE 0 END >= 5*60;";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="RESET ALPA KALO TIDAK SESUAI POSNYA">
            $query = "  UPDATE [Absensi]
                        SET [Absensi].[Alpa] = 0
                        FROM [Gaia].[dbo].[FACT_HRDAbsensi][Absensi]
                        JOIN [Uranus].[dbo].[VIEW_AllEmployeeDateStatusPosition][position]
                            ON [Absensi].[FingerId] = [position].[EmployeeId]
                            AND [Absensi].[Date] = [position].[date_id]
                        JOIN [Uranus].[dbo].[VIEW_EmployeeStatusFirstPosition][FirstPosition]
                            ON [Absensi].[FingerId] = [FirstPosition].[EmployeeId]
                            AND [Absensi].[Date] <= [FirstPosition].[StartDate]
                        WHERE [Absensi].[".$KeyColumn."] = ".$userId."
                            AND [Absensi].[IsSync] = 1
                            AND [Absensi].[IsEnable] = 1
                            AND [Absensi].[POSId] != [position].[POSId]
                            AND [Absensi].[Alpa] = 1;";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="RESET THK KLO BUKAN HARI KERJA">
            $query = "  UPDATE [FACT_HRDAbsensi]
                        SET [Alpa] = 0
                        WHERE [IsSync] = 1
                        AND [".$KeyColumn."] = ".$userId."
                        AND [IsEnable] = 1
                        AND [DueTimeIn] = '00:00:00.0000000'
                        AND [DueTimeOut] = '00:00:00.0000000';";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="DONE">
            $query = "  UPDATE [FACT_HRDAbsensi]
                        SET [IsSync] = 2
                        WHERE [IsSync] = 1
                        AND [IsEnable] = 1
                        AND [".$KeyColumn."] = ".$userId.";";
            $stmt = sqlsrv_query($connect_gaia, $query);
            if ( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
        // </editor-fold>
        $returns["IsError"] = 0;
    }
    return $returns;
}

function GetPositionHierarchy($DBName,$ParentPositionId){
    $ParentPositionIds = getTable(array(
        "database" => $DBName,
        "tableName" => "REL_PositionHierarchy",
        "filtersIn" => array(
            "ParentPositionId" => array($ParentPositionId)
        ),
        "isKey" => 0
    ));

    $ChildrenPositionIds = array();
    if(count($ParentPositionIds))
    {
        $GrandChildrenPositionIds = array();
        foreach($ParentPositionIds AS $indexParents => $ParentPositionId)
        {
            if(!in_array($ParentPositionId["ChildPositionId"], $ChildrenPositionIds, true)){
                $ChildrenPositionIds[] = $ParentPositionId["ChildPositionId"];
            }
        }

        if(count($ChildrenPositionIds)){
            foreach ($ChildrenPositionIds AS $indexChildren => $ChildrenPositionId)
            {
                if(!in_array(GetPositionHierarchy($DBName,$ChildrenPositionId), $GrandChildrenPositionIds, true)){
                    $GrandChildrenPositionIds[] = GetPositionHierarchy($DBName,$ChildrenPositionId);
                }
            }
            if(count($GrandChildrenPositionIds)){
                foreach($GrandChildrenPositionIds AS $indexGrand => $GrandChildrenPosition)
                {
                    foreach($GrandChildrenPosition AS $indexGrandChildren => $GrandChildrenPositionId)
                    {
                        if(!in_array($GrandChildrenPositionId, $ChildrenPositionIds, true)){
                            $ChildrenPositionIds[] = $GrandChildrenPositionId;
                        }
                    }
                }
            }
        }

    }
    return $ChildrenPositionIds;
}

function GetEmployeeStatusPositionHistory($EmployeeId){

    $connect_uranus = connect_sql_server("uranus");

    $TimeLines = array();
    $EmployeeStatuses = array();
    $EmployeeStatusPositions = array();

    $BeforeEndDate = "";
    $query = "SELECT [Status].[Id][StId],[Status].[StartDate],[Status].[EndDate],[MsStatus].[Id][StatusId],[MsStatus].[Name][StatusName],[Status].[DocumentNumber]
        FROM [FACT_EmployeeStatus][Status]
        JOIN [DIM_EmployeeStatus][MsStatus]
            ON[Status].[EmployeeStatusId] = [MsStatus].[Id]
        WHERE [Status].[EmployeeId] = ".$EmployeeId."
        AND [Status].[IsEnable] = 1
        ORDER BY [Status].[StartDate]DESC,[Status].[EndDate]DESC,[Status].[Id]DESC";
    $result = sqlsrv_query($connect_uranus, $query, array(), array("Scrollable"=>"buffered"));
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
    {
        $EStId = $row["StId"] * 1;
        $StartDate = $row["StartDate"];
        $EndDate = $row["EndDate"];
        $StatusId = $row["StatusId"] * 1;
        $StatusName = protect($row["StatusName"],1);
        $DocumentNumber = protect($row["DocumentNumber"],1);

        if(!$BeforeEndDate)
        {
            if($StatusId == 1)$EndDate = date("Y-m-d");//STATUS TETAP
        }
        else
        {
            if($EndDate != $BeforeEndDate)
            {
                if($StatusId == 1)$EndDate = $BeforeEndDate;//STATUS TETAP
                else
                {
                    if($EndDate > $BeforeEndDate)$EndDate = $BeforeEndDate;
                    else if($EndDate < $BeforeEndDate)
                    {
                        $NextStartDateTime = DateTime::createFromFormat('Y-m-d', $EndDate);
                        $NextStartDateTime -> modify("+1 day");
                        $NextStartDate = $NextStartDateTime->format('Y-m-d');
                        $TimeLines[$NextStartDate] = array();
                    }
                }
            }
        }

        if($StartDate <= $EndDate)
        {
            if($BeforeEndDate)$TimeLines[$BeforeEndDate] = array();
            $EmployeeStatuses["ES_".$EStId] = array(
                "Id" => $EStId,
                "StartDate" => $StartDate,
                "EndDate" => $EndDate,
                "StatusId" => $StatusId,
                "StatusName" => $StatusName,
                "DocumentNumber" => $DocumentNumber,
            );

            $TimeLines[$StartDate] = array();
            $TimeLines[$EndDate] = array();

            $BeforeEndDateTime = DateTime::createFromFormat('Y-m-d', $StartDate);
            $BeforeEndDateTime -> modify("-1 day");
            $BeforeEndDate = $BeforeEndDateTime->format('Y-m-d');
        }
    }

    $BeforeEndDate = "";
    $query = "SELECT [Status].[Id][StId],[MsStatus].[Id][StatusId]
        ,[Position].[Id][StPosId],[Position].[StartDate],[Position].[EndDate],[Position].[Description]
        ,[MsCBP].[Company],[MsCBP].[CompanyAlias],[MsCBP].[Branch],[MsCBP].[BranchAlias],[MsCBP].[POS]
        ,[MsOS].[Directorate],[MsOS].[Division],[MsOS].[Department],[MsOS].[SubDepartment],[MsOS].[Section],[MsOS].[Position],[MsGroup].[Name][Group]
        FROM [FACT_EmployeeStatus][Status]
        JOIN [DIM_EmployeeStatus][MsStatus]
            ON[Status].[EmployeeStatusId] = [MsStatus].[Id]
            JOIN [FACT_EmployeeStatusPosition][Position]
                ON [Status].[Id] = [Position].[EmployeeStatusId]
                JOIN [VIEW_CompanyBranchPOS][MsCBP]
                    ON [Position].[POSId] = [MsCBP].[POSId]
                JOIN [VIEW_AllOS6Position][MsOS]
                    ON [Position].[PositionId] = [MsOS].[PositionId]
                JOIN [DIM_EmployeeGroup][MsGroup]
                    ON [Position].[EmployeeGroupId] = [MsGroup].[Id]
        WHERE [Status].[EmployeeId] = ".$EmployeeId."
        AND [Status].[IsEnable] = 1
        AND [Position].[IsEnable] = 1
        ORDER BY [Status].[StartDate]DESC,[Status].[EndDate]DESC,[Status].[Id]DESC,[Position].[StartDate]DESC,[Position].[EndDate]DESC,[Position].[Id]DESC";
    $result = sqlsrv_query($connect_uranus, $query, array(), array("Scrollable"=>"buffered"));
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
    {
        $ESId = $row["StId"] * 1;
        $StatusId = $row["StatusId"] * 1;

        $EStPosId = $row["StPosId"] * 1;
        $StartDate = $row["StartDate"];
        $EndDate = $row["EndDate"];
        $Description = protect($row["Description"],1);

        $Company = protect($row["Company"],1);
        $CompanyAlias = protect($row["CompanyAlias"],1);
        $Branch = protect($row["Branch"],1);
        $BranchAlias = protect($row["BranchAlias"],1);
        $POS = protect($row["POS"],1);

        $Directorate = protect($row["Directorate"],1);
        $Division = protect($row["Division"],1);
        $Department = protect($row["Department"],1);
        $SubDepartment = protect($row["SubDepartment"],1);
        $Section = protect($row["Section"],1);
        $Position = protect($row["Position"],1);
        $Group = protect($row["Group"],1);

        //RE WRITE END DATE
        if(!$BeforeEndDate)
        {
            if($StatusId == 1)$EndDate = date("Y-m-d");//STATUS TETAP
        }
        else
        {
            if($EndDate != $BeforeEndDate)
            {
                if($StatusId == 1)$EndDate = $BeforeEndDate;//STATUS TETAP
                else
                {
                    if($EndDate > $BeforeEndDate)$EndDate = $BeforeEndDate;
                    else if($EndDate < $BeforeEndDate)
                    {
                        $NextStartDateTime = DateTime::createFromFormat('Y-m-d', $EndDate);
                        $NextStartDateTime -> modify("+1 day");
                        $NextStartDate = $NextStartDateTime->format('Y-m-d');
                        $TimeLines[$NextStartDate] = array();
                    }
                }
            }
        }

        if($StartDate <= $EndDate)
        {
            if($BeforeEndDate)$TimeLines[$BeforeEndDate] = array();
            $EmployeeStatusPositions["ESP_".$EStPosId] = array(
                "EmployeeStatusId" => $ESId,
                "StatusId" => $StatusId,

                "Id" => $EStPosId,
                "StartDate" => $StartDate,
                "EndDate" => $EndDate,
                "Description" => $Description,

                "Company" => $Company,
                "CompanyAlias" => $CompanyAlias,
                "Branch" => $Branch,
                "BranchAlias" => $BranchAlias,
                "POS" => $POS,

                "Directorate" => $Directorate,
                "Division" => $Division,
                "Department" => $Department,
                "SubDepartment" => $SubDepartment,
                "Section" => $Section,
                "Position" => $Position,
                "Group" => $Group,
            );

            $TimeLines[$StartDate] = array();
            $TimeLines[$EndDate] = array();

            $BeforeEndDateTime = DateTime::createFromFormat('Y-m-d', $StartDate);
            $BeforeEndDateTime -> modify("-1 day");
            $BeforeEndDate = $BeforeEndDateTime->format('Y-m-d');
        }
    }

    ksort($TimeLines);
    $Counter = 1;
    foreach($TimeLines AS $Date => $TimeLine)
    {
        $TimeLines[$Date] = array(
            "Index" => $Counter,
            "IsUsed" => 0
        );
        $Counter++;
    }

    foreach($EmployeeStatuses AS $index => $EmployeeStatus)
    {
        $EmployeeStatuses[$index]["StartIndex"] = $TimeLines[$EmployeeStatuses[$index]["StartDate"]]["Index"];
        $EmployeeStatuses[$index]["EndIndex"] = $TimeLines[$EmployeeStatuses[$index]["EndDate"]]["Index"];

        $TimeLines[$EmployeeStatuses[$index]["StartDate"]]["IsUsed"] = 1;
        $TimeLines[$EmployeeStatuses[$index]["EndDate"]]["IsUsed"] = 1;
    }

    foreach($EmployeeStatusPositions AS $index => $EmployeeStatusPosition)
    {
        $EmployeeStatusPositions[$index]["StartIndex"] = $TimeLines[$EmployeeStatusPositions[$index]["StartDate"]]["Index"];
        $EmployeeStatusPositions[$index]["EndIndex"] = $TimeLines[$EmployeeStatusPositions[$index]["EndDate"]]["Index"];

        $TimeLines[$EmployeeStatusPositions[$index]["StartDate"]]["IsUsed"] = 1;
        $TimeLines[$EmployeeStatusPositions[$index]["EndDate"]]["IsUsed"] = 1;
    }

    return array(
        "TimeLines" => $TimeLines,
        "EmployeeStatuses" => array_reverse($EmployeeStatuses),
        "EmployeeStatusPositions" => array_reverse($EmployeeStatusPositions),
    );
}

function SendEmails($Emails,$Account){
    $returndata = "";
    $returndata["IsEmailSent"] = 0;
    if($Account == 1){
        $EmailAccount = 'it.ho.tms@gmail.com';
        $EmailPassword = 'Trimandiri123';
        $Port = 465;
        $SMTPSecure = "ssl";
        $Host = "smtp.gmail.com";
    }
    else if ($Account == 2){
        $EmailAccount = 'tms.system.it@gmail.com';
        $EmailPassword = 'Tms@supp0rt';
        $Port = 587;
        $SMTPSecure = "tls";
        $Host = "smtp.gmail.com";
    }
    else if ($Account == 3){
        $EmailAccount = 'system.tde@trimandirigroup.com';
        $EmailPassword = 'Trimandiri@12345';
        $Port = 465;
        $SMTPSecure = "ssl";
        $Host = "srv76.niagahoster.com";
    }

    $mail = new PHPMailer(true);
    foreach($Emails AS $index => $Email)
    {
        if(count($Email["EmailTos"]))
        {
            $mail = new PHPMailer(true);
            $returndata["EmailTos"] = array();
            foreach($Email["EmailTos"] AS $indexEmailTo => $EmailTo)
            {
                if(empty($EmailTo) || trim($EmailTo) == false)
                {

                }
                else
                {
                    $mail->AddAddress($EmailTo);
                    $returndata["EmailTos"][] = $EmailTo;

                    if(isset($Email["EmailCCs"]))
                    {
                        if(count($Email["EmailCCs"]))
                        {
                            $returndata["EmailCC"] = array();
                            foreach($Email["EmailCCs"] AS $indexEmailCC => $EmailCC)
                            {
                                if(empty($EmailCC) || trim($EmailCC) == false)
                                {

                                }
                                else
                                {
                                    $mail->addCC($EmailCC);

                                    $returndata["EmailCC"][] = $EmailCC;
                                }
                            }
                        }
                    }

                    $mail->IsSMTP(); // telling the class to use SMTP
                    // $mail->Host = "gmail.com"; // SMTP server
                    $mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
                    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    $mail->SMTPAuth = true; // enable SMTP authentication
                    $mail->Host = $Host; // sets the SMTP server

                    $mail->Port = $Port;
                    $mail->SMTPSecure = $SMTPSecure;
                    $mail->Username = $EmailAccount; // SMTP account username
                    $mail->Password = $EmailPassword; // SMTP account password
                    $mail->setFrom('system.no-reply@trimandirigroup.com', 'TMS AUTO EMAIL SYSTEM');
                    $mail->AddReplyTo('system.no-reply@trimandirigroup.com', 'TMS AUTO EMAIL SYSTEM');


                    $EmailSubject = "NO SUBJECT";
                    if(isset($Email["EmailSubject"]))
                    {
                        $mail->Subject = $Email["EmailSubject"];
                        $EmailSubject = $Email["EmailSubject"];
                    }

                    $EmailBody = "NO CONTENT";
                    if(isset($Email["EmailMessage"]))
                    {
                        $EmailBody = $Email["EmailMessage"];
                    }

                    $returndata["attachementFile"] ="";
                    if(isset($Email["NewFile"]))
                    {
                        $NewFolder = $Email["NewFolder"];
                        $NewFileName = $Email["NewFileName"];
                        $attachementFile = $NewFolder.$NewFileName;

                        $returndata["attachementFile"] = $attachementFile;
                        $mail->AddAttachment($attachementFile,$NewFileName);

                    }

                    $EmailMessage = "<html>
                                        <head>
                                            <title> ".$EmailSubject." </title>
                                            <style type='text/css'>
                                                body{margin:0px;padding:0px;font-family:'lucida grande',verdana,sans-serif;font-size: 11px;}
                                                body table{border-collapse:collapse;width:100%;}
                                                body th, body tr, body td{padding:0;}
                                                body h1, body h2, body h3, body h4, body h5, body h6, body p{margin:0px;}
                                                body p{font-size:11px;}
                                                body h6{font-size:14px;}
                                                body h5{font-size:16px;}
                                                body h4{font-size:18px;}
                                                body h3{font-size:20px;}
                                                body h2{font-size:24px;}
                                                body h1{font-size:28px;}
                                                body ul, body li{list-style:none;margin:0px;padding:0px;}
                                                body img{border:none; vertical-align: bottom}
                                                body hr{margin:0;height:1px;border: none;}
                                                body a{}
                                                body a:link{}
                                                body a:visited{}
                                                body a:hover{}

                                                body .black{color:#000000;}
                                                body .white{color:#FFFFFF;}
                                                body .red{color:#FF0000;}
                                                body .green{color:#00FF00;}
                                                body .blue{color:#0000FF;}
                                                body .yellow{color:#FFFF00;}
                                                body .toska{color:#00FFFF;}
                                                body .magenta{color:#FF00FF;}

                                                body .light_grey{color:#A9A9A9;}
                                                body .grey{color:#808080;}
                                                body .dark_grey{color:#D3D3D3;}

                                                body .broken_white{color:#EFEFEF;}
                                                body .ls_red{color:#960000;}

                                                body .astra_darkblue{color:#1d388d;}
                                                body .astra_lightblue{color:#00ade6;}

                                                body .bold{font-weight:bold;}
                                                body .italic{font-style:italic;}
                                                body .strip{text-decoration:line-through;}
                                            </style>
                                        </head>
                                        <body>
                                            ".$EmailBody."
                                        </body>
                                    </html>";
                    $mail->Body = $EmailMessage;
                    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
                    // $mail->send();

                }
            }
        }
        if(!$mail->send()){
            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
        else{
            $returndata["IsEmailSent"] = 1;
        }
    }
    return $returndata;
}

function SendEmailsLHKL($Emails,$Account){
    // LAPORAN HARIAL KETERLAMBATAN LOGISTIC
    $IsEmailSent = 0;

    if($Account == 1){
        $EmailAccount = 'it.ho.tms@gmail.com';
        $EmailPassword = 'Trimandiri123';
        $Port = 465;
        $SMTPSecure = "ssl";
        $Host = "smtp.gmail.com";
    }
    else if ($Account == 2){
        $EmailAccount = 'tms.system.it@gmail.com';
        $EmailPassword = 'Tms@supp0rt';
        $Port = 587;
        $SMTPSecure = "tls";
        $Host = "smtp.gmail.com";
    }
    else if ($Account == 3){
        $EmailAccount = 'tde@trimandirigroup.com';
        $EmailPassword = 'swOA@vON32';
        $Port = 587;
        $SMTPSecure = "tls";
        $Host = "trimandirigroup.com";
    }
    else if ($Account == 4){
        $EmailAccount = 'linggih.lukis.tresna@trimandirigroup.com';
        $EmailPassword = 'q0rvJOap1f98VkIQ';
        $Port = 587;
        $SMTPSecure = 'tls';
        $Host = 'smtp-relay.sendinblue.com';
    }

    foreach($Emails AS $index => $Email)
    {
        if(count($Email["EmailTos"]))
        {
            $mail = new PHPMailer(true);
            foreach($Email["EmailTos"] AS $indexEmailTo => $EmailTo)
            {
                if(empty($EmailTo) || trim($EmailTo) == false)
                {

                }
                else
                {
                    $mail->AddAddress($EmailTo);

                    if(isset($Email["EmailCCs"]))
                    {
                        if(count($Email["EmailCCs"]))
                        {
                            foreach($Email["EmailCCs"] AS $indexEmailCC => $EmailCC)
                            {
                                if(empty($EmailCC) || trim($EmailCC) == false)
                                {

                                }
                                else
                                {
                                    $mail->addCC($EmailCC);
                                }
                            }
                        }
                    }

                    // Optional
                    if(isset($Email["EmailBCCs"]))
                    {
                        if(count($Email["EmailBCCs"]))
                        {
                            foreach($Email["EmailBCCs"] AS $indexEmailBCC => $EmailBCC)
                            {
                                if(empty($EmailBCC) || trim($EmailBCC) == false)
                                {

                                }
                                else{
                                    $mail->addBCC($EmailBCC);
                                }
                            }
                        }
                    }

                    $mail->IsSMTP(); // telling the class to use SMTP
                    // $mail->Host = "gmail.com"; // SMTP server
                    $mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
                    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    $mail->SMTPAuth = true; // enable SMTP authentication
                    $mail->Host = $Host; // sets the SMTP server

                    $mail->Port = $Port;
                    $mail->SMTPSecure = $SMTPSecure;
                    $mail->Username = $EmailAccount; // SMTP account username
                    $mail->Password = $EmailPassword; // SMTP account password
                    $mail->setFrom('system.no-reply@trimandirigroup.com', 'TMS AUTO EMAIL SYSTEM');
                    $mail->AddReplyTo('system.no-reply@trimandirigroup.com', 'TMS AUTO EMAIL SYSTEM');


                    $EmailSubject = "NO SUBJECT";
                    if(isset($Email["EmailSubject"]))
                    {
                        $mail->Subject = $Email["EmailSubject"];
                        $EmailSubject = $Email["EmailSubject"];
                    }


                    $EmailBody = "NO CONTENT";
                    if(isset($Email["EmailMessage"]))
                    {
                        $EmailBody = $Email["EmailMessage"];
                    }
                    if(isset($Email["NewFile"]))
                    {
                        $NewFile = $Email["NewFile"];
                        $NewFileName = $Email["NewFileName"];
                        $attachementFile = $NewFile.$NewFileName;
                        $mail->AddAttachment($attachementFile,$NewFileName);
                    }
                    // echo $attachementFile;

                    $EmailMessage = "
                        <!DOCTYPE html>
                        <html lang='en'>
                        <head>
                            <meta charset='UTF-8'>
                            <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
                            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' integrity='sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm' crossorigin='anonymous'>
                            <title>Email</title>
                        </head>
                        <body>
                            ".$EmailBody."
                        </body>
                        <script src='https://code.jquery.com/jquery-3.2.1.slim.min.js' integrity='sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN' crossorigin='anonymous'></script>
                        <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js' integrity='sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q' crossorigin='anonymous'></script>
                        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js' integrity='sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl' crossorigin='anonymous'></script>
                        </html>
                    ";

                    $mail->Body = $EmailMessage;
                    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
                    // $mail->send();
                }
            }
        }

        if(!$mail->send())
        {
            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
        else{
            $IsEmailSent = 1;

        }
    }
    return $IsEmailSent;
}

function RemoveSpecialCharacter($string){
    $data_slug = trim($string," ");
    $search = array('/','\\',':',';','!','@','#','$','%','^','*','(',')','_','+','=','|','{','}','[',']','"',"'",'<','>',',','?','~','`','&',' ','.');
    $data_slug = str_replace($search, "", $data_slug);
    return $data_slug;
}

function AutoJTDOUnit($params){
    $UniqueId = $params["UniqueId"];
    $UnitId = $params["UnitId"];
    $DPPPrice = $params["DPPPrice"] * 1;
    $PPNPrice = $params["PPNPrice"] * 1;

    $query = "
    WITH [FirstUnitPOSId] AS (
        SELECT [UnitId],MIN([Id])[UnitPOSId]
        FROM [FACT_UnitPOS]
        GROUP BY [UnitId]
    )
    SELECT
    [Unit].[Id][UnitId],[InvoiceNumber],[InvoiceDate],[Unit].[BranchId],[Unit].[POSId]
    ,[Brand],[VehicleGroup],[VehicleType],[MaterialCode]
    ,[VIN],[EngineNumber],[ColorDescription],[Year]
    ,[DPP].[Nominal][DPP]
    ,[PPN].[Nominal][PPN]
    FROM [FACT_Unit][Unit]
    JOIN [FirstUnitPOSId]
        ON [Unit].[Id] = [FirstUnitPOSId].[UnitId]
    JOIN [FACT_UnitPOS][UnitPOS]
        ON [FirstUnitPOSId].[UnitPOSId] = [UnitPOS].[Id]
    LEFT OUTER JOIN [FACT_UnitPOSPrice][DPP]
        ON [FirstUnitPOSId].[UnitPOSId] = [DPP].[UnitPOSId]
        AND [DPP].[UnitPriceTypeId] = 9
    LEFT OUTER JOIN [FACT_UnitPOSPrice][PPN]
        ON [FirstUnitPOSId].[UnitPOSId] = [PPN].[UnitPOSId]
        AND [PPN].[UnitPriceTypeId] = 10
    LEFT OUTER JOIN [FACT_UnitPOSPrice][AR]
        ON [FirstUnitPOSId].[UnitPOSId] = [AR].[UnitPOSId]
        AND [AR].[UnitPriceTypeId] = 15
    WHERE [Unit].[POJTUniqueId] IS NULL
    AND [Unit].[InvoiceDate] >= '2020-01-01'
    AND [Unit].[BranchId] = 17
    ORDER BY [InvoiceDate]";

    /*
    SELECT * FROM [FACT_UnitPOS] WHERE [UnitId] = 47229
    SELECT * FROM [FACT_UnitPOSPrice] WHERE [UnitPOSId] = 47947
    */
}

function getRandomColor(){
    $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];

    return $color;
}

function ResetArrayKeys($array, $childName = 'items') {
    foreach ($array as $i => $val) {
        if (is_array($val[$childName])){
            $array[$i][$childName] = ResetArrayKeys($val[$childName]);
        }
    }
    return array_values($array);
}
function generateCaseNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("gaia");

    $query = "SELECT TOP 1 [Number] FROM [FACT_LegalCases]
        WHERE [POSId] = ".$POSId."
        AND YEAR([Date]) = ".$Year."
        AND MONTH([Date]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
function generateLegalPermitNumber($POSId,$Year,$Month){
    $connect_sqlsrv = connect_sql_server("gaia");

    $query = "SELECT TOP 1 [Number] FROM [FACT_LegalPermit]
        WHERE [POSId] = ".$POSId."
        AND YEAR([DateStart]) = ".$Year."
        AND MONTH([DateStart]) = ".$Month."
        ORDER BY [Number] DESC
    ;";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $ExistingNumber = $row["Number"] * 1;
    $NextNumber = $ExistingNumber + 1;

    return $NextNumber;
}
