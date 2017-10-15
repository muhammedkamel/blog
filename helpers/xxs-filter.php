<?php

/**
 * Class Common To clean the XSS
 * @package App\Http\Controllers
 */
class XSSFilter
{
    /**
     * clean request
     * @param $request
     */
    public static function globalXssClean($fields)
    {
        $fields = is_array($fields)? self::arrayStripTags($fields) : self::fieldStripTags($fields);
        return $fields;
    }

    public static function arrayStripTags($array)
    {
        $result = array();
        foreach ($array as $key => $value) {
            // Don't allow tags on key either, maybe useful for dynamic forms.
            $key = strip_tags($key);
            // If the value is an array, we will just recurse back into the
            // function to keep stripping the tags out of the array,
            // otherwise we will set the stripped value.
            if (is_array($value)) {
                $result[$key] = static::arrayStripTags($value);
            } else {
                // I am using strip_tags(), you may use htmlentities(),
                // also I am doing trim() here, you may remove it, if you wish.
                $result[$key] = trim(strip_tags($value));
            }
        }
        return $result;
    }

    private static function fieldStripTags($field){
        return trim(strip_tags($field));
    }
}