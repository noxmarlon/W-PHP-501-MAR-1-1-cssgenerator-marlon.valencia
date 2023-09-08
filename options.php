<?php

function getInstructions()
{
    global $argc;

    // Default values
    $instructions = [
        'recursive' => NULL,
        'img_name' => 'sprite.png',
        'sheet_name' => 'style.css',
        'size' => NULL,
        'columns' => NULL,
        'padding' => NULL,
    ];


    // Gets options from CLI
    if ($argc > 2) {

        $opt = getopt('ri:s:o:c:p:', ['recursive', 'output-image:', 'output-style:', 'override-size:', 'columns_number:', 'padding:']);

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

            // size
            if (key_exists('o', $opt) || key_exists('override-size', $opt)) {
                $val = key_exists('o', $opt) ? $opt['o'] : $opt['override-size'];
                $val = (int)$val;

                // Parsed user input (== 0 if not correctly casted)
                if ($val !== 0) { 
                    $instructions['size'] = $val;
                } else {
                    echo "La taille doit être un nombre entier et être supérieur à 0.\n";
                    return;
                };
            };

            //  max n of columns
            if (key_exists('c', $opt) || key_exists('columns_number', $opt)) {
                $val = key_exists('c', $opt) ? $opt['c'] : $opt['columns_number'];
                $val = (int)$val;

                if ($val !== 0) {
                    $instructions['columns'] = $val;
                } else {
                    echo "Le nombre d'élément doit être un nombre entier et être supérieur à 0.\n";
                    return;
                };
            };

            // padding option
            if (key_exists('p', $opt) || key_exists('padding', $opt)) {
                $val = key_exists('p', $opt) ? $opt['p'] : $opt['padding'];
                $val = (int)$val;

                if ($val !== 0) {
                    $instructions['padding'] = $val;
                } else {
                    echo "Le padding doit être un nombre entier et être supérieur à 0.\n";
                    return;
                };
            };
        };
    };

    return $instructions;
};
