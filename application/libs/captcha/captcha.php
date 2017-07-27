<?php

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if (!defined('PATH_MCAP')) define('PATH_MCAP', dirname(__FILE__) .DS);
if (!defined('PATH_MCAP_RESOURCES')) define('PATH_MCAP_RESOURCES', PATH_MCAP . 'resources'.DS);
if (!defined('PATH_MCAP_FONTS')) define('PATH_MCAP_FONTS', PATH_MCAP . 'resources'.DS. 'fonts' . DS);

if( isset($_GET['width']) and  isset($_GET['height']) ){
	$captcha = new captcha();
	$captcha->generate_image($_GET['width'],$_GET['height'],$_GET['color'] ? $_GET['color'] : null);
}

class captcha {
	
	public $characters 		= 6;
	public $background 		= array(0,0,0);
	public $color 			= array(255,255,255);
	public $random_colors	= FALSE;
	public $file_mode 		= 'png';
	
	private $fuentes = array();
	
	function __construct() {
		$fuentes  = $this->listar_fonts(PATH_MCAP_FONTS);
		for($i=0; $i<count( $fuentes ); $i++){
			$size = explode('-', $fuentes[$i])[1] ? explode('-', $fuentes[$i])[1] : 25;
			array_push($this->fuentes, array("font"=>$fuentes[$i], "size" => $size) );
		}
		
	}
	
	private function listar_fonts($path){
	    $dir = opendir($path);
	    $files = array();
	    while ($current = readdir($dir)){
	        if( $current != "." && $current != "..") {
	           $files[] = $current;            
	        }
	    }
	    return $files;
	}


	public function generate_control($width=250, $height=50, $characters=6){
$control = 
'<div class="mega-captcha">
	<div style="background-color: rgb('. $this->background[0].','.$this->background[1].','.$this->background[2].'); display: block; width: 100%">
		<img id="mega-captcha-image" class="img-responsive mega-captcha-image" src="/api/captcha/generate/'.$width.'/'.$height.'/'.implode(';', $this->background).'" />
	</div>
	<div class="input-group">
		<input id="mega-captcha" type="text" class="form-control mega-captcha-input" maxlength="'.$characters.'" required>
		<span class="input-group-btn">
			<button id="mega-captcha-sound" type="button" class="btn btn-default disabled"><span class="fa fa-volume-up"></span></button>
			<button id="mega-captcha-refresh" type="button" class="btn btn-default"  onclick="javascript:mega_captcha_refresh()"><span class="fa fa-refresh"></span></button>
			<button id="mega-captcha-help" type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="right" data-html="true" data-content="<strong>No soy un robot</strong><br/>Ingrese los caracteres que aparecen en la imagen. <br/>Puede cambiar la imagen por otra haciendo click en refrescar <span class='."'fa fa-refresh'".'></span>"><span class="fa fa-question"></span></button>
		</span>					
	</div>
</div>
<script>
	function mega_captcha_refresh(){
		document.getElementById("mega-captcha-image").setAttribute("src", "/api/captcha/generate/'.$width.'/'.$height.'"); 
	}
</script>
<style>
	.mega-captcha{
		width: '.$width.'px;
		height: '.($height+52).'px;
		border: 1px solid #BCBCBC;
		border-radius: 4px 4px 4px 4px;
		-moz-border-radius: 4px 4px 4px 4px;
		-webkit-border-radius: 4px 4px 4px 4px;
		padding: 5px;
	}.mega-captcha-image{
		min-width: 150px;
		min-height: 50px;
	}.mega-captcha input.mega-captcha-input{	
		height: 34px;
		margin-top: 5px;
		float: left;
		text-align:center;
		font-size:18px;
	}.mega-captcha span{
		padding-top: 5px;
		padding-bottom: 0px
	}.mega-captcha .btn{
		padding: 5px 12px;
	}.mega-captcha .input-group {
	    position: relative;
	    display: table;
	    border-collapse: separate;
	}
</style>';

	return $control;
	}
	
	public function generate_image($width, $height, $color = null){
		//morva, secrcode,stencilia, lottepape, edosz, gimme
		$count = (count($this->fuentes) - 1);
		$font_randon  = rand(0, $count);
		$fuente = $this->fuentes[$font_randon]["font"];
		$font_dir = PATH_MCAP_FONTS . $fuente;
		$font_size  = $this->fuentes[$font_randon]["size"];

		$imagen = imagecreatetruecolor($width, $height);

		$this->fondo = imagecolorallocate($imagen, $this->background[0], $this->background[1], $this->background[2]);

		$this->color 	= imagecolorallocate($imagen, $this->color[0], $this->color[1], $this->color[2]);
		
		/*Generate a random string using md5*/ 
		$md5_hash = md5(rand(0,999).time());
		
		$clave = substr($md5_hash, 0, $this->characters );
		$hash = implode( ' ', str_split($clave) );

		// Se genera un angulo aleatorio para girar el texto
		$maximo_angulo = 10;
		$angulo = rand(-100 * $maximo_angulo, 100 * $maximo_angulo) / 100.0;

		imagefilledrectangle($imagen, 0, 0, $width, $height, $this->fondo);

		if (function_exists("imagettftext") && $font_dir != ""){
			$caja_texto = imagettfbbox($font_size, $angulo, $font_dir, $hash);
			$min_x = min($caja_texto[0], $caja_texto[2], $caja_texto[4], $caja_texto[6]);
			$max_x = max($caja_texto[0], $caja_texto[2], $caja_texto[4], $caja_texto[6]);
			$min_y = min($caja_texto[1], $caja_texto[3], $caja_texto[5], $caja_texto[7]);
			$max_y = max($caja_texto[1], $caja_texto[3], $caja_texto[5], $caja_texto[7]);
			$x = max(0, ($caja_texto[0] - $min_x) + rand(0, $width - ($max_x - $min_x)));
			$y = max(0, ($caja_texto[1] - $min_y) + rand(0, $height - ($max_y - $min_y)));
			imagettftext($imagen, $font_size, $angulo, $x, $y, $this->color, $font_dir, $hash);
		}
    	 
    	// --> Dibujar una distorsi�n final
		for($j=0;$j<500;$j++){
			$color = imagecolorallocate($imagen, rand(50,200), rand(50,200), rand(50,200));		
			imagesetpixel($imagen,rand(0,$width),rand(0,$height),$this->color);
		} 
		
		$random_colors	= imagecolorallocate($imagen, rand(50,200), rand(50,200), rand(50,200));
		for($j=0;$j<100;$j++){
			$color = imagecolorallocate($imagen, rand(50,200), rand(50,200), rand(50,200));		
			imagesetpixel($imagen,rand(0,$width),rand(0,$height),$random_colors);
		}
		
		$random_colors	= imagecolorallocate($imagen, rand(50,200), rand(50,200), rand(50,200));
		for($j=0;$j<100;$j++){
			$color = imagecolorallocate($imagen, rand(50,200), rand(50,200), rand(50,200));		
			imagesetpixel($imagen,rand(0,$width),rand(0,$height),$random_colors);
		} // <-- fin */	
		
		
			
    	$_SESSION['mc_captcha'] = $clave;
    	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

		
    	// Generar imagen de salida
		switch($this->file_mode){
			case 'gif':
				header('Content-type: image/gif');
				imagegif($imagen);
			break;	
			
			case 'jpeg':
				header('Content-type: image/jpeg');
				imagejpeg($imagen);
			break;
			
			default:
				header('Content-type: image/png');
				imagepng($imagen);
			break;
		}
		
		/*Destroy*/
		imagedestroy($imagen);
		
	}
	
	

}

?>