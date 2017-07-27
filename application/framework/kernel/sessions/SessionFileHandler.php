<?php


class SessionFileHandler implements SessionHandlerInterface
{
    private $save_path = PATH_SESSION;

    public function __construct(){
		// Set handler to overide SESSION
		session_set_save_handler(
			array($this, "open"),
			array($this, "close"),
			array($this, "read"),
			array($this, "write"),
			array($this, "destroy"),
			array($this, "gc")
		);

        register_shutdown_function('session_write_close');
	}

    /**
     * Undocumented function
     *
     * @param [type] $savePath
     * @param [type] $sessionName
     * @return void
     */
    public function open($savePath, $sessionName)
    {
        $this->savePath = $savePath;
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0777);
        }
        return true;
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function close() {
        $this->gc(ini_get('session.gc_maxlifetime')); 
        return true;
    }


    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function read($id) {
        if (file_exists($this->save_path . "sess_$id")) {
            return Creative::get( 'Hash' )->decode(
                @file_get_contents($this->save_path . "sess_$id")
            );
        }
        return "";
    }


    /**
     * Undocumented function
     *
     * @param [type] $id
     * @param [type] $data
     * @return void
     */
    public function write($id, $data) {
        $data = Creative::get( 'Hash' )->encode($data);
        return file_put_contents( $this->save_path . "sess_$id", $data) === false ? false : true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        $file = $this->save_path . "sess_$id";
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }


    /**
     * Undocumented function
     *
     * @param [type] $maxlifetime
     * @return void
     */
    public function gc($maxlifetime)
    {
        foreach (glob( $this->save_path . "sess_*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }
        return true;
    }
}

