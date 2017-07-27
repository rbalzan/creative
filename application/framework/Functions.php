<?php

define('HTML_DISABLED', 'disabled="true"');
define('HTML_FORM_CONTROL', 'class="form-control"');
define('HTML_INPUT_GROUP', 'class="input-group-btn"');



function strtodate( $fecha, $format = 'Y-m-d h:i:s' ){
	$time = strtotime($fecha);
	$fecha = date($format,$time);
	return $fecha;
}


function esta_entre($val1,$val2){
	
}

/**
* Agregar un ToolTips de Botstrap a un contendor HTML
* @param undefined $text Texto a mostrar
* @param undefined $pos Posición en la que aparecerá el tooltips. Por defecto: top
* 
* @return
*/
function add_tooltips( $text, $pos = 'top' ){
	return 'data-toggle="tooltip" data-placement="'.$pos.'" data-original-title="'.$text.'"';
}


/**
* Agregar un ToolTips de Botstrap a un contendor HTML
* @param undefined $text Texto a mostrar
* @param undefined $pos Posición en la que aparecerá el tooltips. Por defecto: top
* 
* @return
*/
function add_popover( $text, $titulo, $pos = 'top' ){
	return ' data-toggle="popover" data-placement="top" data-trigger="hover" data-html="true" title="'.$titulo.'" data-content="'.$text.'"';
}




function include_functions( $fn ){
	$dir_functions 	= PATH_THEME_ACTIVE . 'config'.DS. $fn. '.php';
	include $dir_functions;
}





#*************************************************************






/**
* Returns a GUIDv4 string
*
* Uses the best cryptographically secure method 
* for all supported pltforms with fallback to an older, 
* less secure version.
*
* @param bool $trim
* @return string
*/
function GUID ($trim = true){
    // Windows
    if (function_exists('com_create_guid') === true) {
        if ($trim === true)
            return trim(com_create_guid(), '{}');
        else
            return com_create_guid();
    }

    // OSX/Linux
    if (function_exists('openssl_random_pseudo_bytes') === true) {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    // Fallback (PHP 4.2+)
    mt_srand((double)microtime() * 10000);
    $charid = strtolower(md5(uniqid(rand(), true)));
    $hyphen = chr(45);                  // "-"
    $lbrace = $trim ? "" : chr(123);    // "{"
    $rbrace = $trim ? "" : chr(125);    // "}"
    $guidv4 = $lbrace.
              substr($charid,  0,  8).$hyphen.
              substr($charid,  8,  4).$hyphen.
              substr($charid, 12,  4).$hyphen.
              substr($charid, 16,  4).$hyphen.
              substr($charid, 20, 12).
              $rbrace;
    return $guidv4;
}



function san_sanitize($input, $type) {
  switch ($type) {
    // 1- Input Validation
 
    case 'int': // comprueba si $input es integer
		return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
	break;
 
    case 'string': // comprueba si $input es string
		$data = trim($input); 
		$data = stripslashes($data); 
		$data = filter_var($data, FILTER_SANITIZE_STRING);
		return $data;
	break;
 
    case 'digit': // comprueba si $input contiene solo numeros
      if (ctype_digit($input)) {
        $output = TRUE;
      } else {
        $output = FALSE;
      }
      break;
 
    case 'upper': // comprueba si $input contiene solo mayusculas
      if ($input == strtoupper($input)) {
        $output = TRUE;
      } else {
        $output = FALSE;
      }
      break;
 
    case 'lower': // comprueba si $input contiene solo minusculas
      if ($input == strtolower($input)) {
        $output = TRUE;
      } else {
        $output = FALSE;
      }
      break;
      
    case 'email': // comprueba si $input tiene formato de email
      $reg_exp = "/^[-.0-9A-Z]+@([-0-9A-Z]+.)+([0-9A-Z]){2,4}$/i";
      if (preg_match($reg_exp, $input)) {
        $output = TRUE;
      } else {
        $output = FALSE;
      }
      break;
 
    // 2- SQL Encoding
 
    case 'sql': // escapar los caracteres que no son legales en SQL
 
		// si magic_quotes_gpc esta activado primero aplicar stripslashes()
		// de lo contrario los datos seran escapados dos veces
		if (get_magic_quotes_gpc()) {
			$input = stripslashes($input);
		}
		// requiere una conexion MySQL, de lo contrario dara error
		return mysql_real_escape_string(trim($input));
      break;
 
    // 3- Output Filtering
 
    case 'no_html': // los datos van a una pagina web, escapar para HTML
      $output = htmlentities(trim($input), ENT_QUOTES);
      break;
 
    case 'shell_arg': // los datos van al shell, escapar para shell argument
      $output = escapeshellarg(trim($input));
      break;
 
    case 'shell_cmd': // los datos van al shell, escapar para shell command
      $output = escapeshellcmd(trim($input));
      break;
 
    case 'url': // los datos forman parte de una URL, escapar para URL
 
      // htmlentities() traduce a HTML el separador &
      $output = htmlentities(urlencode(trim($input)));
      break;
 
    case 'comment': // los datos solo pueden contener algunos tags HTML
      $output = strip_tags($input, '<b><i><img>');
      break;
  }
  return $output;
}



/**
* Limpia un String eliminando cualquier aparición de palabras reservadas de SQL
* @param undefined $valor
* 
* @return
*/
function san_clean_sql( $valor ) {
	$valor = str_ireplace("SELECT ","",$valor);
	$valor = str_ireplace("UPDATE ","",$valor);
	$valor = str_ireplace("DELETE ","",$valor);
	$valor = str_ireplace("COPY ","",$valor);		
	$valor = str_ireplace("DROP ","",$valor);
	$valor = str_ireplace("DUMP ","",$valor);
	//$valor = str_ireplace(" OR ","",$valor);
	//$valor = str_ireplace("LIKE ","",$valor);
	//$valor = str_ireplace("FROM ","",$valor);
	//$valor = str_ireplace("WHERE ","",$valor);
	//$valor = str_ireplace("%","",$valor);		
	$valor = str_ireplace("--","",$valor);
	$valor = str_ireplace("^","",$valor);
	//$valor = str_ireplace("[","",$valor);
	//$valor = str_ireplace("]","",$valor);
	/*$valor = str_ireplace("\\","",$valor);
	$valor = str_ireplace("!","",$valor);
	$valor = str_ireplace("¡","",$valor);
	$valor = str_ireplace("?","",$valor);
	$valor = str_ireplace("=","",$valor);
	$valor = str_ireplace("&","",$valor);
	$valor = str_ireplace("'","",$valor);
	$valor = str_ireplace('"',"",$valor);
	$valor = str_ireplace("\'","",$valor);
	$valor = str_ireplace("/'","",$valor);*/
	$valor = mysql_real_escape_string($valor);
	return $valor;
}


/**
* Limpia un Array eliminando cualquier aparición de palabras reservadas de SQL
* @param undefined $arr
* 
* @return
*/
function san_clean_sql_array( $arr ) {
	array_walk($arr,"clean_sql");
}



/**
* Escapa un String de cualquier HTML, TAG o caracter especial
* @param undefined $valor String a escapar
* 
* @return String Espacado
*/
function san_sanitize_taghtml( $valor ) {
	$valor = strip_tags($valor);
	$valor = htmlspecialchars($valor);
	$valor = addslashes($valor);
	return $valor;
}


abstract class FORMAT_DATE {
	const DIA	= 1;
	const MESES	= 2;
	const YEAR	= 3;
	const HORA	= 4;
}


function date_values( FORMAT_DATE $format  ){
	switch( $format ){
		
		case FORMAT_DATE::DIA:
			$dias = array();
			for($i = 1; $i <= 31; $i++){
				$dias[]  = $i;
			}
			return $dias;
		break;
		
		case FORMAT_DATE::MESES:		
			return array(
				1 => "Enero", 
				2 => "Febrero",
				3 => "Marzo",
				4 => "Abril",
				5 => "Mayo",
				6 => "Junio",
				7 => "Julio",
				8 => "Agosto",
				9 => "Septiembre",
				10 => "Octubre",
				11 => "Noviembre",
				12 => "Diciembre"
			);
		break;
		
		
		default:
			break;
	}
}


/*****************************************************************
* Validar la utenticidad del Token
* @param undefined $string
* @param undefined $keyword
* 
* @return
*/
function validate_token( $token, $controller='', $template = NULL ){	
	$template = $template ? str_ireplace('{text}','Error de seguridad en Token', $template) : 'Error de seguridad en Token';
	$controller = str_ireplace('controller', '', $controller);
	
	$tkn = new Token();
	if( !$tkn->validar($token, $controller ) ){
		header('Content-type: application/json; charset=utf-8');
		echo json_encode(
			array(
				'status'	=> 600,
				'response'	=> array(
					'message'	=> $template,
					'icon'		=> 'error',
					'token'		=> $tkn->generate($controller)
				)
			));
		exit;
	} else {return TRUE;}
}


/*****************************************************************
* Validar la utenticidad del Token
* @param undefined $string
* @param undefined $keyword
* 
* @return
*/
function validar_token( $token, $controller='', $template = NULL ){	
	$template = $template ? str_ireplace('{text}','Error de seguridad en Token', $template) : 'Error de seguridad en Token';
	$controller = str_ireplace('controller', '', $controller);
	
	$tkn = new Token();
	if( !$tkn->validar($token, $controller ) ){
		header('Content-type: application/json; charset=utf-8');
		echo json_encode(
			array(
				'status'	=> 600,
				'response'	=> array(
					'message'	=> $template,
					'icon'		=> 'error',
					'token'		=> $tkn->generar($controller)
				)
			));
		exit;
	} else {return TRUE;}
}

/**
* Generar un nuevo Token
* @param string $controller Controlador donde fue creado el Token
* 
* @return
*/
function generar_token( $controller = '' ){
	$controller = str_ireplace('controller', '', $controller);
	$tkn = new Token();
	return $tkn->generar($controller);
}



/**
* Generar un nuevo Token
* @param string $controller Controlador donde fue creado el Token
* 
* @return
*/
function generate_token( $controller = '' ){
	$controller = str_ireplace('controller', '', $controller);
	$tkn = new Token();
	return $tkn->generate($controller);
}



function is_email($email){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return false;
    }
    
    return true;
}


/**
* Devuelve un String con la longitud determinada por el parametro $lenght
* @param undefined $lenght string Logitud del Token generado
* 
* @return String Token genereado
*/
function hash_url( $lenght=10 ){
	return strtoupper(substr(sha1(time().mt_rand(0, microtime())), 0, $lenght));
}
  
function url_backend( $url ){
	return '/backend/'. $url .'/?tokenurl='. hash_url();
}

function link_backend( $url, $text, $icon ){
	$data_link = $url;
	$url = '/backend/'. $url .'/?tokenurl='. hash_url();
	return '<a href="'.$url.'" data-link="'.$data_link.'"><i class="'.$icon.'"></i> <span>'.$text.'</span></a>';
}



/**
* Verifica si el usuario está autenticado
* @param string $ambito ['backend', 'frontend']
* 
* @return
*/
function autenticado( $ambito = 'backend' ){
	 return Session::get($ambito)['autenticado'] === FALSE ? FALSE : TRUE;
}



function check_pass($pass) {
	
	$count = strlen($pass);
	$entropia = 0;
	
	$regla = '<br/>La contraseña no cumple con los requerimientos mínimos de seguridad. Debe cumplir con lo siguiente:'.
	     		'<ul>'.
	         		'<li>Tener entre 6 y 30 caracteres alfanuméricos</li>'.
	         		'<li>Al menos una letra minúscula y una mayúscula</li>'.
	         		'<li>Contener números</li>'.
	         		'<li>Contener alguno de estos simbolos: @ * . !</li>'.
	     		'</ul>';
	     		
    // Si el password tiene menos de 6 caracteres
    if ($count==0) {
         return array(
         	'status' => 0,
         	'statusText'=>
         		'La contraseña ingresada no puede estar vacía.'. $regla);
    }
    if ($count < 6) {
         return array('status'=>0,'statusText'=>'La contraseña es muy corta'. $regla);
    }
   
    // Contamos cuantas mayusculas, minusculas, numeros y simbolos existen 
    $upper = 0; $lower = 0; $numeros = 0; $otros = 0;
    
    for ($i = 0, $j = strlen($pass); $i < $j; $i++) {
        $c = substr($pass,$i,1);
        if (preg_match('/^[[:upper:]]$/',$c)) {
            $upper++;
        } elseif (preg_match('/^[[:lower:]]$/',$c)) {
            $lower++;
        } elseif (preg_match('/^[[:digit:]]$/',$c)) {
            $numeros++;
        } else {
            $otros++;
        }
    }

   // Calculamos la entropia
  
	$entropia= ($upper*10) + ($lower*5) + ($numeros*5) + ($otros*15);
  
	if ($entropia<28){
    	return array('status'=>1,'statusText'=>'Contraseña muy debil.'. $regla);    
    	
	}elseif($entropia<36) {
        return array('status'=>2,'statusText'=>'Contraseña debil'. $regla); 
        
	}elseif($entropia<60) {
		return array('status'=>3,'statusText'=>'Contraseña con seguridad media'. $regla); 
		
	}elseif($entropia<128) {
		return array('status'=>4,'statusText'=>'Contraseña fuerte');
		 
	}else {
		return array('status'=>5,'statusText'=>'Contraseña muy fuerte'); 
	}
        
}



if (!function_exists('is_str_contain')) {
  function is_str_contain($string, $keyword)
  {
    if (empty($string) || empty($keyword)) return false;
    $keyword_first_char = $keyword[0];
    $keyword_length = strlen($keyword);
    $string_length = strlen($string);
	
    // case 1
    if ($string_length < $keyword_length) return false;

    // case 2
    if ($string_length == $keyword_length) {
      if ($string == $keyword) return true;
      else return false;
    }

    // case 3
    if ($keyword_length == 1) {
      for ($i = 0; $i < $string_length; $i++) {
        // Check if keyword's first char == string's first char
        if ($keyword_first_char == $string[$i]) {
          return true;
        }
      }
    }

    // case 4
    if ($keyword_length > 1) {
      for ($i = 0; $i < $string_length; $i++) {
        /*
        the remaining part of the string is equal or greater than the keyword
        */
        if (($string_length + 1 - $i) >= $keyword_length) {

          // Check if keyword's first char == string's first char
          if ($keyword_first_char == $string[$i]) {
            $match = 1;
            for ($j = 1; $j < $keyword_length; $j++) {
              if (($i + $j < $string_length) && $keyword[$j] == $string[$i + $j]) {
                $match++;
              }
              else {
                return false;
              }
            }

            if ($match == $keyword_length) {
              return true;
            }

            // end if first match found
          }

          // end if remaining part
        }
        else {
          return false;
        }

        // end for loop
      }

      // end case4
    }

    return false;
  }
}






function breadcrumbs($sep = ' ›› ', $home = 'Inicio') {
	$base  = '<ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
	
	//Get the server http address
	$sitio = ($_SERVER['HTTPS'] ? 'https://' : 'http://').$_SERVER['HTTP_HOST'];
	
	//Get all vars en skip the empty ones
	$crumbs = array_filter( explode("/",$_SERVER["REQUEST_URI"]) );
	
	//Create the homepage breadcrumb
	$base .= sprintf('<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
						<a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="%s" title="%s">
							<span itemprop="name">%s</span>
						</a>
						<meta itemprop="position" content="%d" />
					  </li>'
					, BASE_URL, BASE_URL, $home, 1);
	
	//Count all not empty breadcrumbs
	$nm     =   count($crumbs);
	$i      =   1;
	//Loop through the crumbs
	foreach($crumbs as $crumb){
		//grab the last crumb
		$last_piece = end($crumbs);

	    //Make the link look nice
	    $link    =  ucfirst( str_replace( array(".php","-","_"), array(""," "," ") ,$crumb) );
	       
	    //Loose the last seperator
	    $sep     =  $i==$nm?'':$sep;
	    //Add crumbs to the root
	    $sitio   .=  '/'.$crumb;
	    //Check if last crumb
	    if ($last_piece!==$crumb){
	    	//Make the next crumb
	    	//$base     .= '<li><a href="'.$sitio.'">'.$link.'</a>'.$sep.'</li>';
	    	
    		$base .= sprintf('<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
					<a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="%s" title="%s">
						<span itemprop="name">%s</span>
					</a>
					<meta itemprop="position" content="%d" />
				  </li>'
				, $sitio, $sitio, $link, 1);
					
	    } else {
	    	//Last crumb, do not make it a link
	    	//$base     .= '<li class="active">'.ucfirst( str_replace( array(".php","-","_"), array(""," "," ") ,$last_piece)).'</li>';
	    	$base .= sprintf('<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
		
						<span itemprop="name">%s</span>

					<meta itemprop="position" content="%d" />
				  </li>'
				, ucfirst( str_replace( array(".php","-","_"), array(""," "," ") ,$last_piece)), 1);
				
				
	    }
	    $i++;
	}
	$base .=  '</ol>';
	//Return the result
	return $base;
}


function html_button_loading(){
	return "data-loading-text=\"<span class='fa fa-spinner fa-spin'></span>  Procesando\"" ;
}