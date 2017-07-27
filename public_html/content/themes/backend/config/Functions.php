<?php

abstract class FORMAT {
	const DATE_LONG = 1;
	const TEL =2;
}

function formato( FORMAT $format, $value ){
	switch( $format ){
		case 1:	//DATE_LONG
			$value = explode('-',$value);					
			$dia = $value[2];$mes = $value[1];$año = $value[0];		
			switch($mes) {
					case 1 :  return $dia." de Enero de $año"; 		break;
					case 2 :  return $dia." de Febrero de $año";	break;
					case 3 :  return $dia." de Marzo de $año";		break;
					case 4 :  return $dia." de Abril de $año";		break;
					case 5 :  return $dia." de Mayo de $año";			break;
					case 6 :  return $dia." de Junio de $año";		break;
					case 7 :  return $dia." de Julio de $año";		break;
					case 8 :  return $dia." de Agosto de $año";		break;
					case 9 :  return $dia." de Septiembre de $año";break;
					case 10 : return $dia." de Octubre de $año";	break;
					case 11 : return $dia." de Noviembre de $año";break;
					case 12 : return $dia." de Diciembre de $año";break;
				}			
		break;
		
		
		
		case 2:	//TEL
			return sprintf("+%s (%s) - %s", $value['pais'], $value['codigo'], $value['tel']);
		break;
	}
}