<?php
/* Copyright (C) 2005-2007 Erwin Ried

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, 
MA 02110-1301, USA. */

// Configuración por defecto
$default_InternalCodeLength = 10;
$default_ImageFileMode = 'png'; // jpeg, png*, gif (default*)
$outputImageDefault_width = 180;
$outputImageDefault_height = 60;

// Acceso a base de datos
include 'db.php';

@$userMode = $_GET['modo'];
if(strlen($userMode)>0)
{
	switch($userMode)
	{
		// --------------------------------------------------------------------------------------------
		case 'nuevo':
		
		@$newCode_Length = $_GET['largo'];
		@$newCode_WithNumbers = $_GET['numeros'];
		@$newCode_WithLetters = $_GET['letras'];
		
		// Configuración por defecto
		if($newCode_Length!='')
		{
			if(intval($newCode_Length)>7)
			{
				$newCode_Length = 7;
			}
			else
			{
				if(intval($newCode_Length)<2)
				{
					$newCode_Length = 2;
				}
			}
		}
		else
		{
			$newCode_Length = 5;
		}
		
		if(intval($newCode_WithNumbers)>1)
		{
			$newCode_WithNumbers = 1;
		}
		else
		{
			if(intval($newCode_WithNumbers)<1)
			{
				$newCode_WithNumbers = 0;
			}
		}
		
		if(intval($newCode_WithLetters)>1)
		{
			$newCode_WithLetters = 1;
		}
		else
		{
			if(intval($newCode_WithLetters)<1)
			{
				$newCode_WithLetters = 0;
			}
		}
		
		if(intval($newCode_WithNumbers)+intval($newCode_WithLetters)==0)
		{
			$newCode_WithNumbers = 1;
			$newCode_WithLetters = 1;
		}
		
		// Generar código identificador externo
		do
		{		
			// Limpiar
			$internalCode = '';
			
			// Generar
			for($i=0;$i<$default_InternalCodeLength;$i++)
			{
				$internalCode .= chr(rand(ord('a'),ord('z')));
			}
			
			// Verificar si es único
			$sql = "SELECT `id` FROM `captcha` WHERE `code` LIKE '$internalCode'";
			$withSameCode = mysql_num_rows(consultaRapidaMuda($sql));
			
		}while($withSameCode>0);
		
		// Generar código de verificación
		$verificationCode = '';
			
		// Generar
		for($i=0;$i<$newCode_Length;$i++)
		{
			if(intval($newCode_WithNumbers)==intval($newCode_WithLetters))
			{
				// Números y letras
				if(rand(0,1))
				{
					$verificationCode .= chr(rand(ord('0'),ord('9')));
				}
				else
				{
					$verificationCode .= chr(rand(ord('A'),ord('Z')));
				}
			}
			else
			{
				if(intval($newCode_WithNumbers))
				{
					// Sólo números
					$verificationCode .= chr(rand(ord('0'),ord('9')));
				}
				else
				{
					// Sólo letras
					$verificationCode .= chr(rand(ord('A'),ord('Z')));
				}
			}
		}
		
		// Insertar elemento en la base de datos
		$this_machine = $_SERVER['REMOTE_ADDR'];
		
		$sql = 	"INSERT INTO `captcha` " .
				"(`id`, `code`, `verification`, `used`, `created`, `created_by`) " .
				"VALUES ('', '$internalCode', '$verificationCode', '0', NOW(), '$this_machine')";

		consultaRapidaMuda($sql);
		echo $internalCode;		
		break;	
			
		// --------------------------------------------------------------------------------------------
		case 'imagen':
		
		@$internalImageCode = $_GET['codigo'];
		
		$isValid = true;
		for($i=0;$i<strlen($internalImageCode);$i++)
		{
			$ascLetra = ord(substr($internalImageCode,$i,1));
			
			if($ascLetra > ord('z') || $ascLetra < ord('a'))
			{
				$isValid = false;
				break;
			}
		}
		
		// Accesar al código de verificación
		$verificationCode = '';
		if($isValid)
		{
			$sql = "SELECT `verification` FROM `captcha` WHERE `code` LIKE '$internalImageCode' LIMIT 1";
			$elemento = consultaRapida($sql);
			
			$verificationCode = $elemento['verification'];
			
			if(strlen($verificationCode)==0)
			{
				$isValid = false;
			}
		}
		
		if($isValid)
		{
			
			$image = imagecreatetruecolor($outputImageDefault_width,$outputImageDefault_height);
			
			$fuente = 'files/font.ttf';
			
			$total = strlen($verificationCode);
			$tamanoMin = 29+(-2*$total);
			$tamanoMax = 50+(-4*$total);
			
			imagefill($image,0,0,imagecolorallocate($image,rand(240,255),rand(240,255),rand(240,255)));				
			
			// --> Estampar las letras y números distractores de fondo
			for($i=0;$i<30;$i++)
			{
				$color = imagecolorallocate($image, rand(180,240), rand(180,240), rand(180,240));

				for($j=0;$j<30;$j++)
				{
					if(rand(0,1)==1)
					{
						$letra = chr(rand(ord('A'),ord('Z')));
					}
					else
					{
						$letra = chr(rand(ord('0'),ord('9')));
					}
							
					$tamano = rand($tamanoMax,$tamanoMin);
			
					imagettftext($image,$tamano,rand(-40,40),
					rand(0,$outputImageDefault_width),
					rand($tamano,$outputImageDefault_height),$color,$fuente,$letra);	
					
					// --> Aplicar una distorsión leve
					for($j=0;$j<30;$j++)
					{
						imagesetpixel($image,rand(0,$outputImageDefault_width),
									rand(0,$outputImageDefault_height),$color);
					} // <-- fin */				
				}
			} // <-- fin */
			
			// --> Estampar las letras y números principales
			for($i=0;$i<$total;$i++)
			{
				$color = imagecolorallocate($image, rand(0,150), rand(0,150), rand(0,150));
				$letra = substr($verificationCode,$i,1);
				
				$giro = rand(-20,20);
				$tamano = rand($tamanoMax,$tamanoMin);
				imagettftext($image,$tamano,$giro,
				($outputImageDefault_width/($total+2))*($i+1),
				($outputImageDefault_height+$tamano)/2,$color,$fuente,$letra);	
				
				$giro = $giro + rand(-5,5);
				imagettftext($image,$tamano,$giro,
				($outputImageDefault_width/($total+2))*($i+1),
				($outputImageDefault_height+$tamano)/2,$color,$fuente,$letra);	
				
				$giro = $giro + rand(-5,5);
				imagettftext($image,$tamano,$giro,
				($outputImageDefault_width/($total+2))*($i+1),
				($outputImageDefault_height+$tamano)/2,$color,$fuente,$letra);	
										
			} // <-- fin */
			
			// --> Dibujar una distorsión final
			for($j=0;$j<2000;$j++)
			{
				$color = imagecolorallocate($image, rand(50,200), rand(50,200), rand(50,200));
			
				imagesetpixel($image,rand(0,$outputImageDefault_width),
							rand(0,$outputImageDefault_height),$color);
			} // <-- fin */
		}
		else
		{
			$image = imagecreatefrompng('files/code_error.png');
		}		
	
		// Generar imagen de salida
		switch($default_ImageFileMode)
		{
			case 'gif':
			header('Content-type: image/gif');
			imagegif($image);
			break;	
			
			case 'jpeg':
			header('Content-type: image/jpeg');
			imagejpeg($image);
			break;
			
			default:
			header('Content-type: image/png');
			imagepng($image);
		}
	
		break;
		
		// --------------------------------------------------------------------------------------------
		case 'verificar':
		
		@$internalImageCode = $_GET['codigo'];
		@$verificationGuess = $_GET['usuario'];
					
		$isValid = true;
		for($i=0;$i<strlen($internalImageCode);$i++)
		{
			$ascLetra = ord(substr($internalImageCode,$i,1));
			
			if($ascLetra > ord('z') || $ascLetra < ord('a'))
			{
				$isValid = false;
				break;
			}
		}
		
		$verificationGuess = strtoupper($verificationGuess);
		if($isValid)
		{
			for($i=0;$i<strlen($verificationGuess);$i++)
			{
				$ascLetra = ord(substr($verificationGuess,$i,1));
				
				if(!(($ascLetra <= ord('9') && $ascLetra >= ord('0')) || ($ascLetra <= ord('Z') && $ascLetra >= ord('A'))))
				{
					$isValid = false;
					break;
				}
			}
		}
		
		// Accesar al código de verificación
		$verificationCode = '';
		if($isValid)
		{
			$sql = "SELECT `verification` FROM `captcha` WHERE `used`=0 AND `code` LIKE '$internalImageCode' LIMIT 1";
			$elemento = consultaRapida($sql);
			
			$verificationCode = $elemento['verification'];
			
			if(strlen($verificationCode)==0)
			{
				$isValid = false;
			}
			else
			{
				$this_machine = $_SERVER['REMOTE_ADDR'];
		
				$sql = "UPDATE `captcha` SET `used` = '1', `modified_by` = '$this_machine', `modified` = NOW() WHERE `code` LIKE '$internalImageCode' LIMIT 1;";
				$elemento = consultaRapidaMuda($sql);
			}
		}
		
		if($isValid)
		{
			if($verificationCode==$verificationGuess)
			{				
				echo 1; // Corresponde
			}
			else
			{
				echo 0; // No corresponde
			}
		}
		else
		{
			echo -1; // No existe
		}
	
	}
}


?>
