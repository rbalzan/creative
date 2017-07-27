<?php

/**
 * Undocumented class
 */
class keyCommand extends Console {
    
    /**
     * 
     **/
    public function execute(array $args, array $options = array()){
        include PATH_FRAMEWORK . 'Functions.php';
        $hash_key = GUID();

        $content = '';
        if ( file_exists(PATH_APP . 'settings.json') ){
			$file = fopen(PATH_APP . 'settings.json', 'r');
			while(!feof($file)) {
				$content .= fgets($file);
			}
			fclose($file);
		} else {
			exit( 'Error in Environment Configuration File' );
		}

        $content = json_decode($content , true);
        $content['hash_key'] = $hash_key;
        $content = json_encode($content);
        $content = explode( ',', $content );

        $file = fopen(PATH_APP . 'settings.json', 'w+b');
        
        $i = 0;
        $end = count($content) - 1;

        foreach( $content as $linea ){
            if( $i == 0 ){
                fwrite($file, "{\n");
                fwrite($file, str_ireplace('{', '', "\t".$linea.",\n" ));
            } else if( $i == $end ){
                fwrite($file, str_ireplace('}', '', "\t".$linea."\n" ));
                fwrite($file, "}");
            } else {
                fwrite($file, "\t".$linea.",\n");
            }
            $i++;
        }
        

        $this->terminate(200, "Application Hash Key [{$hash_key}] set successfully");
    }    
}
?>