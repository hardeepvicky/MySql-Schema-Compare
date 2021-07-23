<?php
function get_named_to_str($named)
{
    $arr = [];
    foreach($named as $k => $v)
    {
        $arr[] = trim($k) . ":" . trim($v);
    }
    
    return implode("/", $arr);
}

function base64_to_file($base64_string, $output_file) {
    // open the output file for writing
    $ifp = fopen( $output_file, 'wb' ); 

    $data = explode( ',', $base64_string );
    
    if (count($data) > 1)
    {
        $data = $data[1];
    }
    else
    {
        $data = $data[0];
    }
    
    // we could add validation here with ensuring count( $data ) > 1
    fwrite( $ifp, base64_decode( $data) );

    // clean up the file resource
    fclose( $ifp ); 

    return $output_file; 
}

function array_diff_with_headers($first, $second, $key_headers)
{
    $arr = array();
    
    foreach($first as $k => $v)
    {
        $key = isset($key_headers[$k]) ? $key_headers[$k] : $k;
        
        if (!isset($second[$k]))
        {
            $arr[$key] = $v;
        }
        else if ($v != $second[$k])
        {
            $arr[$key] = $v;
        }
    }
    
    return $arr;
}

function array_key_value_pair_list($glue, $arr)
{
    $list = array();
    
    foreach($arr as $k => $v)
    {
        $list[] = $k . $glue . $v;
    }
    
    return $list;
}

function curl_get_request($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);     
    $res = curl_exec($ch); 
    curl_close($ch);
    
    return $res;
}

function curl_post_request($url, $params)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $res = curl_exec($ch); 
    curl_close($ch);
    
    return $res;
}

function strClean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-_]/', '', $string); // Removes special chars.
}

function namespaceExists($namespace) 
{
    $namespace .= "\\";
    foreach(get_declared_classes() as $name)
        if(strpos($name, $namespace) === 0) return true;
    return false;
}

function convert_excel_datetime($datetime)
{
    $date = DateTime::createFromFormat('d-m-y H:i', $datetime);
    
    if ($date === false)
    {
        return false;
    }
    
    return date_format($date, 'Y-m-d H:i:s');
}

function convert_excel_date($datetime)
{
    $arr = explode("-", $datetime);
    
    if (count($arr) != 3)
    {
        return false;
    }
    
    if (strlen($arr[0]) > 2 || strlen($arr[1]) != 3 || strlen($arr[2]) != 4)
    {
        return false;
    }
    
    $date = DateTime::createFromFormat('d-M-Y', $datetime);
    
    if ($date === false)
    {
        return false;
    }
    
    return date_format($date, 'Y-m-d');
}

function string_match_in_list($text, array $list, $min_similartiy_per = 80)
{
    foreach ($list as $k => $v)
    {
        similar_text($text, $v, $match_per);
        
        if ($match_per >= $min_similartiy_per)
        {
            return $k;
        }
    }
    
    return false;
}

function view_google_link($id)
{
    return "https://drive.google.com/uc?export=view&id=" . $id;
}

function download_start($file, $content_type)
{
    header('Content-Description: File Transfer');
    header("Content-Type: $content_type");
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    unlink($file);
    exit;
}

function if_exist($list, $key, $default = "")
{
    if (isset($list[$key]))
    {
        return $list[$key];
    }
    else
    {
        return $default;
    }
}