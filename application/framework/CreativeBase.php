<?php


/**
* 
*/
class CreativeBase {
	
	private $StatusFramework = true;
	
	const CLASS_FILE_EXT = '.php';
	
	public static function get_info(){
		return 'Creative - A PHP Framework For Web Mega Creativo';
	}
	
	public static function get_tpl_exception( $title, $error_info = '', $other_info = '' ){
		
		$content_file = '';
		$file = '../creative/tpl/exception.tpl';
		
		if (file_exists($file)){
			$file = fopen($file, 'r');
			while(!feof($file)) {
				$content_file .= fgets($file);
			}
			fclose($file);
		} else {
			die('Eror');
		}
		
		
		$content_file = str_ireplace('@title',$title, $content_file);
		
		$content_file = str_ireplace('@header',CreativeBase::get_info(), $content_file);
		
		$content_file = str_ireplace('@error_info',$error_info, $content_file);
		
		$content_file = str_ireplace('@description',$other_info, $content_file);
		return $content_file;
	}


	public static function run_exception( $title, $error_info = '', $other_info = '' ){
		
		$content_file = '';
		$file = '../creative/tpl/exception.tpl';
		
		if (file_exists($file)){
			$file = fopen($file, 'r');
			while(!feof($file)) {
				$content_file .= fgets($file);
			}
			fclose($file);
		} else {
			die('Error');
		}
		
		$content_file = str_ireplace('@title',$title, $content_file);		
		$content_file = str_ireplace('@header',CreativeBase::get_info(), $content_file);		
		$content_file = str_ireplace('@error_info',$error_info, $content_file);		
		$content_file = str_ireplace('@description',$other_info, $content_file);
		
		
		$debug = debug_backtrace();
		$out = '';
		foreach( $debug as $key => $value){
			$out .= '<strong>File:</strong> '.$value['file'].'<br/>';
			$out .= '<strong>Line:</strong> '.$value['line'].'<br/>';
			$out .= '<strong>Class:</strong> '.$value['class'].'<br/><br/>';
		}
		
		$content_file = str_ireplace('@calleds',$out, $content_file);
		
		throw new Exception($content_file);
	}
	
	
	public static function get_version(){
		return '4.0.0';
	}

	public static function get_author(){
		return'Brayan Ernesto Rincon [brayan262@gmail.com]';
	}

	public static function get_framework_path(){
		return dirname(__FILE__).'/';
	}
		
}



