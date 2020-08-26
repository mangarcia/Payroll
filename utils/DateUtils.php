<?php

/**
 * Description of FileUtils
 *
 * @author Studio7
 */
class DateUtils
{
	public static function getCurrentDate ($dateFormat)
	{
		date_default_timezone_set ("America/Bogota");

		switch ($dateFormat)
		{
			case DateFormats::STRING_FORMAT:
				return self::getDateString (date("Y-m-d"));
			break;

			case DateFormats::DATE_FORMAT:
				return date("Y-m-d");
			break;

			default:
			break;
		}
	}

	public static function getDateString($date)
	{
            $fecha_array = getdate(strtotime($date));

            switch($fecha_array["wday"])
            {
                case 0: $dia_cadena = "Domingo";   break;
                case 1: $dia_cadena = "Lunes";     break;
                case 2: $dia_cadena = "Martes";    break;
                case 3: $dia_cadena = "Miercoles"; break;
                case 4: $dia_cadena = "Jueves";    break;
                case 5: $dia_cadena = "Viernes";   break;
                case 6: $dia_cadena = "Sabado";    break;
            }

            switch($fecha_array["mon"])
            {
                case 1 : $mes_cadena = "Enero"; 	 break;
                case 2 : $mes_cadena = "Febrero"; 	 break;
                case 3 : $mes_cadena = "Marzo"; 	 break;
                case 4 : $mes_cadena = "Abril"; 	 break;
                case 5 : $mes_cadena = "Mayo"; 	 	 break;
                case 6 : $mes_cadena = "Junio"; 	 break;
                case 7 : $mes_cadena = "Julio"; 	 break;
                case 8 : $mes_cadena = "Agosto"; 	 break;
                case 9 : $mes_cadena = "Septiembre"; break;
                case 10: $mes_cadena = "Octubre"; 	 break;
                case 11: $mes_cadena = "Noviembre";  break;
                case 12: $mes_cadena = "Diciembre";  break;
            }

            $fecha_formato_cadena = $dia_cadena . " " .$fecha_array['mday'] . " de " . $mes_cadena . " de " . $fecha_array['year'];
            return $fecha_formato_cadena;
        }

        public static function horaToAmPm($hora){
            $hora = $hora;
            $partes_hora = explode(":", $hora);
            if($partes_hora[0] < 12){
                $horario = "am";
            }else{
                $partes_hora[0] = $partes_hora[0] - 12;
                $horario = "pm";
            }
            return $partes_hora[0] .":".$partes_hora[1]." " . $horario;
        }
}


abstract class DateFormats
{
	const STRING_FORMAT = 1;
	const DATE_FORMAT   = 2;
}