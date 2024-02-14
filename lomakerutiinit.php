<?php
function randomString($length = 3){
    return bin2hex(random_bytes($length));
    }

function arvo($kentta) {
    /* Muistaa lomakkeen kentän vanhan arvon, jos sellainen on. */
    if (!isset($GLOBALS['virheet'][$kentta])){
        return $_POST[$kentta] ?? "";
        }
    return "";
    }
    
    
    function is_invalid($kentta) {
    /* Palauttaa tyhjän tai "is-invalid"-tyyliluokan, jos
       palvelimen validointitulos antaa siihen aihetta selaimen 
       virheilmoitustekstin näyttämiseksi Javascriptin ja Bootstrapin
       avulla. */
    return (isset($GLOBALS['virheet'][$kentta])) ? " is-invalid" : "";
    }
    
    function virheilmoitus($kentta) {
    /* Palauttaa kentälle virheen mukaisen 
       oletusvirheilmoitustekstin, jos
       palvelimen validointitulos antaa siihen aihetta. */    
    return $GLOBALS['virheilmoitukset'][$kentta] ?? "";
    }
    
    function pattern($kentta) {
    /* Palauttaa kentälle pattern-attribuutin, jonka malline on 
       sama HTML-, Javascript- ja PHP-koodissa. */
    return (isset($GLOBALS['pattern'][$kentta])) ?  
        "pattern=\"" . trim($GLOBALS['pattern'][$kentta],"/") . "\" " :
        "";
    }
    
    /* 
    function validoi($kentta,$arvo) {
    if (isset($GLOBALS['pattern'][$kentta]))     
        return preg_match($GLOBALS['pattern'][$kentta], $arvo);
    else return true;
    }
    */
    
    function validoi($kentta,$arvo) {
        /* Palauttaa true, jos kentän arvo on kelvollinen tai, jos
           kentälle ei ole määritetty mallinetta. */
        $tulos = true;
        if (isset($GLOBALS['pattern'][$kentta])){
            $arvo = (array) $arvo; 
            foreach ($arvo as $a) {
                $tulos = preg_match($GLOBALS['pattern'][$kentta], $a);
                if (!$tulos) {
                    break;
                    }
                }
            }           
        elseif (isset($GLOBALS['numeeriset']) && in_array($kentta,$GLOBALS['numeeriset'])) {
            $tulos = is_numeric($arvo);
            }
        return $tulos;
        }
    
    function desimaali($kentta) {
    return str_replace(",",".",$kentta);
    }


    function input_kentta($kentta,$type = 'text',$required = true,$autofocus = false, $label = ""){
    /**
     * This function generates an HTML input field with certain attributes.
     * 
     * @param string $kentta The name, id, and label for the input field.
     * @param string $type The type of the input field (like 'text', 'email', 'password', etc.). Default is 'text'.
     * @param bool $required Determines whether the input field is required or not. If true, the "required" attribute is added to the input field. Default is true.
     * @param bool $autofocus Determines whether the input field should get focus when the page loads. If true, the "autofocus" attribute is added to the input field. Default is false.
     * 
     * @return void Outputs the HTML for the input field.
     */
    $label = $label ? $label : $kentta;
    //$label ?: $kentta;  
    $required = ($required) ? "required" : "";
    $autofocus = ($autofocus) ? "autofocus" : "";
    echo '<div class="row mb-2">';
    echo "<label class=\"form-label col-sm-3\" for=\"$kentta\">".ucfirst($label)."</label>";
    echo '<div class="col-sm-8">';
    echo '<input class="form-control'.is_invalid($kentta).
         "\" type=\"$type\" name=\"$kentta\" id=\"$kentta\" ".
         pattern($kentta)."$autofocus $required value=\"".arvo($kentta)."\">";
    echo '<div class="invalid-feedback">'.virheilmoitus($kentta).'</div>';
    echo '</div></div>';
    }
    
    function input_checkbox($kentta,$value,$label){
    /**
     * This function generates an HTML checkbox field with certain attributes.
     * 
     * @param string $kentta The name, id, and label for the input field.
     * @param string $value The value for the input field.
     * @param string $label The label for the input field.
     * 
     * @return void Outputs the HTML for the input field.
     */
    $checked = (isset($_POST[$kentta]) && in_array($value,$_POST[$kentta])) ? " checked" : "";  
    echo '<div class="form-check">';
    echo "<input class=\"form-check-input\" type=\"checkbox\" value=\"$value\" 
          name=\"$kentta"."[]\" id=\"$kentta\" $checked>";
    echo "<label class=\"form-check-label\" for=\"$kentta\">$label</label>";
    echo '</div>';
    }
    

    function input_radio($kentta,$value,$label,$required){    
    /**
     * This function generates an HTML radio button with certain attributes.
     * 
     * @param string $kentta The name, id, and label for the input field.
     * @param string $value The value for the input field.
     * @param string $label The label for the input field.
     * 
     * @return void Outputs the HTML for the input field.
     */
    $required = ($required) ? "required" : "";
    $checked = (isset($_POST[$kentta]) && $_POST[$kentta] == $value) ? " checked" : ""; 
    echo '<div class="form-check">';
    echo "<input class=\"form-check-input\" type=\"radio\" value=\"$value\" 
          name=\"$kentta\" id=\"$kentta\" $checked $required>";
    echo "<label class=\"form-check-label\" for=\"$kentta\">$label</label>";
    echo '</div>';
    }

    function input_select($kentta,$optiot,$required = false){
    /**
     * This function generates an HTML select field with certain attributes.
     * 
     * @param string $kentta The name, id, and label for the input field.
     * @param array $optiot An array of options for the select field.
     * 
     * @return void Outputs the HTML for the input field.
     */
    $required = ($required) ? "required" : "";
    echo '<div class="row mb-2">';
    echo "<label class=\"form-label col-sm-3\" for=\"$kentta\">".ucfirst($kentta)."</label>";
    echo '<div class="col-sm-8">';
    echo "<select class=\"form-select".is_invalid($kentta)."\" 
          name=\"$kentta\" id=\"$kentta\" $required>";
    echo "<option value=\"\">Valitse $kentta</option>";
    foreach ($optiot as $value => $optio) {
        $selected = ($optio == arvo($kentta)) ? " selected" : "";
        echo "<option value=\"$value\"$selected>$optio</option>";
        }
    echo '</select>';
    echo '<div class="invalid-feedback">'.virheilmoitus($kentta).'</div>';
    echo '</div></div>';
    }

    
    function input_datalist($kentta,$optiot,$required = false,$label = ""){
        /**
         * This function generates an HTML datalist field with certain attributes.
         *
         * @param string $kentta The name, id, and label for the input field.
         * @param array $optiot An array of options for the datalist field.
         *
         * @return void Outputs the HTML for the input field.
         * Mukana selaimen validointi, palvelimen validointi, virheilmoitukset ja vanhan arvon muistaminen:
         *
         * pattern($kentta)
         * is_invalid($kentta)
         * virheilmoitus($kentta)
         * arvo($kentta)
         */    
        $required = ($required) ? "required " : "";

        echo '<div class="row mb-2">';
        echo "<label class=\"form-label col-sm-3\" for=\"$kentta\">".ucfirst($label)."</label>";
        echo '<div class="col-sm-8">';
        echo "<input list=\"$kentta\" class=\"form-control".is_invalid($kentta).
        "\" type=\"text\" name=\"$kentta\" $required".is_invalid($kentta).pattern($kentta)." value=\"".arvo($kentta)."\">";
        echo '<div class="invalid-feedback">'.virheilmoitus($kentta).'</div>';
        echo "<datalist id=\"$kentta\">";
        foreach ($optiot as $optio) {
            echo "<option value=\"$optio\">";   
            }
        echo "</datalist>";
        echo '</div></div>';
    }
