<?php
namespace Blog\Helpers;

/**
 * Class Common To clean the XSS
 * @package App\Http\Controllers
 */
class XSSFilter {
	/**
	 * clean request
	 * @param $request
	 */
	public static function globalXssClean($fields) {
		$fields = is_array($fields) ? self::arrayStripTags($fields) : self::fieldStripTags($fields);
		return $fields;
	}

	public static function arrayStripTags($array) {
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

	private static function fieldStripTags($field) {
		return trim(strip_tags($field));
	}

	public static function escape($fields) {
		$fields = is_array($fields) ? self::escapeArray($fields) : self::escapeObject($fields);
		return $fields;
	}

	private static function escapeObject($field) {
		$array = (array) $field;
		foreach ($array as $field => $value) {
			$array[$field] = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
		}
		return (Object) $array;
	}

	private static function escapeArray(array $fields) {
		for ($i = 0; $i < count($fields); $i++) {
			$fields[$i] = self::escapeObject($fields[$i]);
		}
		return $fields;
	}
}