<?php

class ViewXML extends ViewAPI {
	 public function __construct($module, $status = 400)
	{
        $this->status = $status;
        $this->module = str_ireplace('controller','', $module);
	}
	
    /**
     * Imprime el cuerpo de la respuesta y setea el código de respuesta
     * @param mixed $body de la respuesta a enviar
     */
    public function response($body, $header = array())
    {
        if ($this->status) {
            http_response_code($this->status);
        }
		if( !isset($body['status']) ){
			$body['status']= $this->status;
		}
		if( !isset($body['statusText']) ){
			$body['statusText']= $this->statusText;
		}
		$body['module'] = $this->module;
		
		header('Content-Type: text/xml; charset=utf-8');
        $xml = new SimpleXMLElement('<response/>');
        self::parse($body, $xml);
        print $xml->asXML();
        exit;
    }

    /**
     * Convierte un array a XML
     * @param array $data arreglo a convertir
     * @param SimpleXMLElement $xml_data elemento raíz
     */
    public function parse($data, &$xml_data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = 'item' . $key;
                }
                $subnode = $xml_data->addChild($key);
                self::parse($value, $subnode);
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }
}


?>