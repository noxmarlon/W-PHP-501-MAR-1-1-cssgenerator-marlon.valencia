<?php 
// read images   ///
function getInstructions()
{
    global $argc;

    // Default values
    $instructions = [
        'recursive' => NULL,
        'img_name' => 'sprite.png',
        'sheet_name' => 'style.css',
        
    ];


    // Gets options from CLI
    if ($argc > 2) {

        $opt = getopt('ri:s:o:c:p:', ['recursive', 'output-image:', 'output-style:']);

        if (isset($opt) && $opt > 0) {
            
            // recursiva
            if (key_exists('r', $opt) || key_exists('recursive', $opt)) {
                $instructions['recursive'] = true;
            };

            // output file name
            if (key_exists('i', $opt) || key_exists('output-image', $opt)) {
                $val = key_exists('i', $opt) ? $opt['i'] : $opt['output-image'];
                if (!str_ends_with($val, '.png')) {
                    $val .= '.png';
                };
                $instructions['img_name'] = $val;
            };

            //css  name
            if (key_exists('s', $opt) || key_exists('output-style', $opt)) {
                $val = key_exists('s', $opt) ? $opt['s'] : $opt['output-style'];
                if (!str_ends_with($val, '.css')) {
                    $val .= '.css';
                };
                $instructions['sheet_name'] = $val;
            };
        };
    };
    return $instructions;
};        
function listpng($files)
{
    
    if( file_exists($files))
    {
        global $images,$img,$tbb,$tableromain;

        $imgDir = $files;
        $dirHandle = opendir($imgDir);
        $tableromain = array();
        $images = [];
        while ($item = readdir($dirHandle)) {
            if (is_dir($item))
                continue;
            $im = imagecreatefrompng($imgDir.'/'.$item);
            $images[] = $im;
            $img = $im;
            $tbb= substr($item,0,-4);
            $tableroup=array($img,$tbb,imagesx($im),imagesy($im),betterX($images),betterY($images));
            array_push($tableromain,$tableroup);
            } 
             // }
            
            // if ($ == true)
            // {
            //     if(is_dir("$imgDir "."/"."$item") && $item !== '.' && $item !== '..')
            // {
            //     listpng("$imgDir"."/"."$item");
            // }
            
    }
    
    closedir($dirHandle); 
  generarsprite($images); // ok falta recurisva 
  crearcss($tableromain,$name = "style");
}

/// find MAX //

function betterX($files)
{
    
    $betterX= 0;
    foreach ($files as $index => $image) {
        $width = imagesx($image);
        
        $betterX += $width;
    }
return $betterX;
}
function betterY($files)
{
    $betterY= 0;
    foreach ($files as $index => $image) {
        $height = imagesy($image);
        if($betterY < $height)
        $betterY = $height;
    }
return $betterY;
}
/// generate css

function crearcss($array1,$name = "style")
{
    $count = count($array1);
    $tableronom= array_column($array1,1);
    $implodee= implode(", .",$tableronom);
    $img_css = ".$implodee\n {\nbackground-image: url(prueba.png);\nbackground-repeat: no-repeat" ."\n"  . "}" ;
     
     $abrircss = fopen("$name.css",'w');
    fwrite($abrircss,$img_css );

    $y=0;
    for($i=0; $i<$count; $i++)
    {
        if ($i>0)
            $y+=$array1[$i-1][5];
        
        fwrite($abrircss, "." .$array1[$i][1]."\n"
        .'{'."\n".'width: '.$array1[$i][2].'px;'
        ."\n".'height: '.$array1[$i][3].'px;'
        ."\n".'background-position : -0px -'.$y.'px;'
        ."\n".'}'."\n\n"
        );   
    }
    fclose($abrircss);
}

//  ESPITRE //
function generarsprite($images)
{
    
    $count= count($images);
    for($i=0;$i<$count; $i++)
    $resized = imagecreatetruecolor(betterX($images), betterY($images));

    $stockY= 0;
    $stockX= 0;
    $offset=0;
    // $tablero= array();
    
    foreach($images as $index => $image){
    $width = imagesx($image);
    
        $height= imagesy($image);
        $stockX += $width ;
        $stockY += $height ;
        /////////////////////
        if($index != 0)
        {
            $offset +=imagesx($images[$index-1]);
    
        }
         $x= $width;
         $y= $height;
     
        imagecopymerge($resized,$image, $offset,0,0,0,$width,$height, 100);    
    }
     imagepng($resized,"prueba.png");
}
 
listpng($argv[1]);
?>