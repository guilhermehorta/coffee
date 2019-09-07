<?php

namespace App\Http\Traits;

trait AuxFunc
{
	/*
    *--------------------------------------------------------------------------
    * AuxFunc Trait
    *--------------------------------------------------------------------------
    *
    * Este trait fornece métodos algébricos:
    * - Conversão de float para fração.
    * - Arredondamento de floats
    */
   

    /**
    * Converter um float para fração
    *
    * @param float $float
    * @return string
    */
    public static function float2rat($float, $tolerance = 1.e-3)
    {   
        $h1=1; $h2=0;
        $k1=0; $k2=1;
        $b = 1/$float;
        do {
            $b = 1/$b;
            $a = floor($b);
            $aux = $h1; $h1 = $a*$h1+$h2; $h2 = $aux;
            $aux = $k1; $k1 = $a*$k1+$k2; $k2 = $aux;
            $b = $b-$a;
        } while (abs($float-$h1/$k1) > $float*$tolerance);

        if ($k1 == 1) return "$h1";

        return "$h1/$k1";
    }

}