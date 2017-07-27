<?php

class Console extends ConsoleKit\Command {


    private $_status = array(
        '200' => 'Ok',
        '201' => 'Created',
        '409' => 'Conflict',
        '405' => 'Reset Content',
        '500' => 'Internal Server Error',
    );
    protected $console ;

    
    protected function get_template( $template ){
        $path_tpl = __DIR__ . "/templates/{$template}.tpl";
        $content = '';

        if (file_exists($path_tpl)){
			$file = fopen($path_tpl, 'r');
			while(!feof($file)) {
				$content .= fgets($file);
			}
			fclose($file);
		} else {
			$this->terminate(500);
		}
        return $content;
    }

    protected function write_file( $file, $content ){
        $controller_file = fopen( $file,'w' );
        fwrite($controller_file, $content);
        fclose($controller_file);
    }

    protected function terminate( $status = 200, $message = '' ){
        $message = $message ? $message : $message;
        $this->writeln( $status." ".$this->_status[$status] ."! " . $message );
        exit;
    }




}
?>