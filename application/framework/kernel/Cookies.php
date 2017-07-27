<?php


/**
 * Create a cookie with the name "myCookieName" and value "testing cookie value"

$cookie = new Cookie();
* 
// Set cookie name
$cookie->setName('myCookieName');
* 
// Set cookie value
$cookie->setValue("testing cookie value");
* 
// Set cookie expiration time
$cookie->setTime("+1 hour");
* 
// Create the cookie
$cookie->create();
* 
// Get the cookie value.
print_r($cookie->get());
* 
// Delete the cookie.
//$cookie->delete();

 */


/**
 * Cookie manager.
 */
class Cookie {
    /**
     * Cookie name - the name of the cookie.
     * @var bool
     */
    private $name = false;

    /**
     * Cookie value
     * @var string
     */
    private $value = "";

    /**
     * Cookie life time
     * @var DateTime
     */
    private $time;

    /**
     * Cookie domain
     * @var bool
     */
    private $domain = false;

    /**
     * Cookie path
     * @var bool
     */
    private $path = false;

    /**
     * Cookie secure
     * @var bool
     */
    private $secure = false;

    /**
     * Constructor
     */
	public function __construct( $name, $value, $day, $path, $httpOnly ) {
		$this->domain = "." . $_SERVER['HTTP_HOST'];
		$this->name = $name;
		$this->value = $value;
		$this->path = $path;
		$this->http_only = $httpOnly || true;
		$this->set_secure( TRUE );
		$this->set_time( $day );
	}
	
	
    /**
     * Create or Update cookie.
     */
    public function create() {
        return setcookie($this->name, $this->get_value(), $this->get_time(), $this->get_path(), $this->get_domain(), $this->get_secure(), true);
    }

    /**
     * Return a cookie
     * @return mixed
     */
    public function get(){
        return $_COOKIE[$this->get_name()];
    }

    /**
     * Delete cookie.
     * @return bool
     */
    public function delete(){
        return setcookie($this->name, '', time() - 3600, $this->get_path(), $this->get_domain(), $this->get_secure(), true);
    }


    /**
     * @param $domain
     */
    public function set_domain($domain) {
        $this->domain = $domain;
    }

    /**
     * @return bool
     */
    public function get_domain() {
        return $this->domain;
    }

    /**
     * @param $id
     */
    public function set_name($id) {
        $this->name = $id;
    }

    /**
     * @return bool
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * @param $path
     */
    public function set_path($path) {
        $this->path = $path;
    }

    /**
     * @return bool
     */
    public function get_path() {
        return $this->path;
    }

    /**
     * @param $secure
     */
    public function set_secure($secure = null) {
		if( !$secure ){
			if( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ) {
				$this->secure = true;
			}
		} else {
			$this->secure = $secure;
		}
    }

    /**
     * @return bool
     */
    public function get_secure() {
        return $this->secure;
    }

    /**
     * @param $time
     */
    public function set_time($time) {
        // Create a date
        $date = new DateTime();
        // Modify it (+1hours; +1days; +20years; -2days etc)
        $date->modify($time);
        // Store the date in UNIX timestamp.
        $this->time = $date->getTimestamp();
    }

    /**
     * @return bool|int
     */
    public function get_time() {
        return $this->time;
    }

    /**
     * @param string $value
     */
    public function set_value($value) {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function get_value() {
        return $this->value;
    }
}


?>