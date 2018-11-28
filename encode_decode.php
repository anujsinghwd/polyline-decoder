<?php

class Polyline
{
    /**
     * @var int $precision
     */
    //protected static $precision = 5;
    protected static $precision = 6;
    
    /**
     *
     * @param array $points List of points to encode. Can be a list of tuples,
     *                      or a flat, one-dimensional array.
     * @return string encoded string
     */
    final public static function encode()
    {
        /**
        * If points in string format
          * $points = '77.2681117 28.5495164,77.2675753 28.5515426,77.2674680 28.5539929,77.2665989 28.5551331,77.2633159 28.5577529,77.2506559 28.5625023,77.2525871 28.5667802';
          *  $i =0;
          * $points = explode(",", $points);
          *  foreach($points as $sub){
          *      $points[$i] = array_reverse(explode(' ', $sub));
          *      $i++;
          *  }
          *  $points = self::flatten($points);
        */
        $points = self::flatten($points = array(
            array(41.89084,-87.62386),
            array(41.89086,-87.62279),
            array(41.89028,-87.62277),
            array(41.89028,-87.62385),
            array(41.89084,-87.62386)
        ));
        $encodedString = '';
        $index = 0;
        $previous = array(0,0);
        foreach ( $points as $number ) {
            $number = (float)($number);
            $number = (int)round($number * pow(10, static::$precision));
            $diff = $number - $previous[$index % 2];
            $previous[$index % 2] = $number;
            $number = $diff;
            $index++;
            $number = ($number < 0) ? ~($number << 1) : ($number << 1);
            $chunk = '';
            while ( $number >= 0x20 ) {
                $chunk .= chr((0x20 | ($number & 0x1f)) + 63);
                $number >>= 5;
            }
            $chunk .= chr($number + 63);
            $encodedString .= $chunk;
        }
        return $encodedString;
    }
    /**
     * @param string $string Encoded string to extract points from.
     *
     * @return array points
     */
    final public static function decode()
    {
        $result = array();
        $result = array();
        $value = filter_var($path, FILTER_SANITIZE_STRING); //decoded string goes here
        $index = 0;
        $points = array();
        $lat = 0;
        $lng = 0;
        while ($index < strlen($value)) {
            $b;
            $shift = 0;
            $result = 0;
            do {
                $b = ord(substr($value, $index++, 1)) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b > 31);
            $dlat = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lat += $dlat;
            $shift = 0;
            $result = 0;
            do {
                $b = ord(substr($value, $index++, 1)) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b > 31);
            $dlng = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lng += $dlng;
            $points[] = array($lat/pow(10, static::$precision), $lng/pow(10, static::$precision));
         }

        }
        /*
        * Returning a string like(lng lat, lng lat......)
          *  foreach (array_chunk($points, 2) as $sub) {
          *      $tmpArr[] = implode(' ', array_reverse($sub));
          *  }
          *  $result = implode(',', $tmpArr);
          *  return $result;
        */
        return $points;
    }
    /**
     * Reduce multi-dimensional to single list
     *
     * @param array $array Subject array to flatten.
     *
     * @return array flattened
     */
    final public static function flatten( $array )
    {
        $flatten = array();
        array_walk_recursive(
            $array, // @codeCoverageIgnore
            function ($current) use (&$flatten) {
                $flatten[] = $current;
            }
        );
        return $flatten;
    }
    /**
     * Concat list into pairs of points
     *
     * @param array $list One-dimensional array to segment into list of tuples.
     *
     * @return array pairs
     */
    final public static function pair( $list )
    {
        return is_array($list) ? array_chunk($list, 2) : array();
    }
}
