<?php

/**
 * 
 */
abstract class SettingsAnalyzer 
{
    
	/**
	 * Analyze the structure of the Environment 
	 * configuration File and load them into the application
	 * 
	 * @author Brayan Rincon <brincon@megacreativo.com>
	 */
    public static function execute()
    {
        $content = '';
        if ( file_exists( PATH_APP . 'settings.json') ){
			$file = fopen( PATH_APP . 'settings.json', 'r');
			while(!feof($file)) {
				$content .= fgets($file);
			}
			fclose($file);
		} else {
			exit( '<strong>Error in Environment Configuration File</strong>' );
		}

		$content = json_decode($content , true);

		if( $content['hash_key'] == '' ){
			echo( 'You must generate a new Hash Key. Use the command <strong>"php creative key generate"</strong> to generate a new Hash Key, or modify the key of the file <strong>"/aplication/configuration.json"</strong>' );
			exit;
		}

        foreach( $content as $key => $value ){
			define( strtoupper($key), $value );
		}
    }
}

?>