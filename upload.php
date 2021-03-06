<?php
/**
**
**  BY iCODEART
**
**********************************************************************
**                     REDES SOCIALES                            ****
**********************************************************************
**                                                                ****
** FACEBOOK: https://www.facebook.com/icodeart                    ****
** TWIITER: https://twitter.com/icodeart                          ****
** YOUTUBE: https://www.youtube.com/c/icodeartdeveloper           ****
** GITHUB: https://github.com/icodeart                            ****
** TELEGRAM: https://telegram.me/icodeart                         ****
** EMAIL: info@icodeart.com                                       ****
**                                                                ****
**********************************************************************
**********************************************************************
**/

// Verificamos si se ha enviado el form
if (isset($_POST["subir"]))
{
	// Recibimos algun dato del form y comprobamos que el input no este vacio
	if($_FILES["archivo"]["name"] !== "")
	{
		// Definimos los tipos de archivos permitidos
		$formatos   = array(".doc",".pdf");
		// definimos los mimetype de cada archivo permitido --> http://www.sitepoint.com/web-foundations/mime-types-summary-list/
		$mimes      = array("application/msword","application/pdf");		
		// Obtenemos el nombre temporal del archivo
		$temporal   = $_FILES["archivo"]["tmp_name"];
		// Informacion del archivo
		$finfo      = finfo_open(FILEINFO_MIME_TYPE);
		// obtenemos el mimetype
		$mimetype   = finfo_file($finfo, $temporal);
		// Obtenemos el nombre y extension del archivo cargado en el input
		$archivo    = $_FILES["archivo"]["name"];
		// Obtenemos el nombre del archivo pero sin la extension, ejemplo: un archivo llamado icodeart.doc solo obtendra icodeart
		$nombre     = strstr($archivo, ".", true);
		// Obtenemos solo la extension del archivo, ejemplo: un archivo llamado icodeart.doc solo obtendra .doc
		$extension  = substr($archivo, strrpos($archivo, "."));
		// obtenemos el tipo de archivo
		$tipo       = $_FILES['archivo']['type'];
		// Generamos un numero aleatorio entre el 0 y 9999999
		$rand       = rand("0","9999999");
		// Definimos el nombre de la carpeta donde se subira el archivo
		$carpeta    = "sub";
		// Obtenemos los permisos de la carpeta
		$permisos   = substr(sprintf("%o", fileperms("$carpeta")), -4);
		// Definimos donde se subira el archivo, el nombre del archivo se conforma por $nombre-$rand+$extension, por ejemplo un archivo llamado icodeart.doc, se subira a la carpeta Imagenes con el nombre icodeart-8746377.doc, donde el numero se obtiene al azar de la variable $rand.
		$directorio = "$carpeta/$nombre"."-"."$rand"."$extension";
		// Obtenemos el url de la pagina actual, ejemplo http://icodeart.com
		$link       = "http://".$_SERVER["HTTP_HOST"];
		// Obtenemos el url donde se subio el archivo, ejemplo: http://icodeart.com/Imagenes/icodeart-8746377.doc
		$url        = "$link"."/"."$directorio";

		// verificamos el mimetype del archivo
		if (in_array($mimetype, $mimes))
		{
			// Verificamos que el tipo del archivo este permitido
	    	if (in_array($tipo, $mimes))
	    	{
				// Verificamos que la extension del archivo este permitido
				if (in_array($extension, $formatos))
				{
					// si la carpeta existe, verificamos que tiene los permisos necesarios
					if ($permisos!=0777)
					{
						// Si no tiene los permisos, se los asignamos
						chmod("$carpeta", 0777);
					}
					else
					{
						// Si no tiene los permisos, y no se pudo cambiar, mostramos un error
						echo "Error al subir el archivo, comprueba que la carpeta tiene los permisos correspondientes";
					}
					// Si la carpeta esta creada y con los permisos necesarios y el archivos esta permitido movemos el archivo a la carpeta.
					if (move_uploaded_file($temporal, $directorio))
					{
						echo " <b>Subido en:</b><br><br> $url";
					}
					else
					{
						// De lo contrario mostramos un error
						echo "Error al subir el archivo";
					}      
				}
				else
				{
					// Si el archivo no esta permitido
					echo "Archivo no permitido";
				}
			}
			else
			{
				// si el tipo de archivo no esta permitido
				echo "Error al subir el archivo";
			}
		}
		else
		{
			// si el mimetype no esta permitido
			echo "Error al subir el archivo";
		}
	}
}
?>
