<?php
namespace TippingCanoe\MockingJay\Annotations;

/**
 * Class TypeHint
 *
 * @package TippingCanoe\MockingJay\Annotations
 */
class TypeHint {

	public static $arrayType = 'array';

	/**
	 * Map of possible names to a sanitized version.
	 *
	 * @var array
	 */
	private static $basicTypes = [
		'string' => 'string',
		'int' => 'int',
		'integer' => 'int',
		'float' => 'float',
		'bool' => 'bool',
		'boolean' => 'bool'
	];

	private static $arrayTypeShort = '[]';

	public $baseType;

	public $genericType;

	/**
	 * TypeHint constructor.
	 *
	 * @param null $baseType
	 * @param null $genericType
	 */
	public function __construct($baseType, $genericType = null) {

		$this->baseType = $baseType;
		$this->genericType = $genericType;
	}

	/**
	 * Sanitizes the given type into a known value, if possible, handling checking for arrays.
	 *
	 * @param $type
	 * @param array $imports
	 * @return bool|TypeHint
	 */
	public static function parse($type, array $imports) {
		$typeInfo = explode(" ", $type);

		$baseType = trim($typeInfo[0]);
		if (false !== $sanitizedBaseType = TypeHint::getSanitizedName($baseType, $imports)) {
			if ($sanitizedBaseType == TypeHint::$arrayType) {
				$genericType = null;
				// Check for [] and <> and array.
				if (substr($baseType, -2) == TypeHint::$arrayTypeShort) {
					$genericType = trim(substr($baseType, 0, -2));
				} else if (preg_match("/array(?:.*?)<(.*?)>/", $type, $matches)) {
					$genericType = trim($matches[1]);
				} else {
					$genericType = trim($typeInfo[1]);
				}

				if (false !== $sanitizedGenericType = TypeHint::getSanitizedName($genericType, $imports)) {
					return new TypeHint($sanitizedBaseType, $sanitizedGenericType);
				} else {
					return new TypeHint($sanitizedBaseType);
				}
			} else {
				return new TypeHint($sanitizedBaseType);
			}
		}

		return false;
	}

	/**
	 * Sanitizes the given type string into a known value, if possible.
	 *
	 * @param $string
	 * @param array $imports
	 * @return bool|string
	 */
	private static function getSanitizedName($string, array $imports) {
		if (array_key_exists($string, TypeHint::$basicTypes)) {
			return TypeHint::$basicTypes[$string];
		} else if ($string == TypeHint::$arrayType) {
			return TypeHint::$arrayType;
		} else if (substr($string, -2) == TypeHint::$arrayTypeShort) {
			return TypeHint::$arrayType;
		} else if (preg_match("/array<(.*?)>/", $string, $matches)) {
			return TypeHint::$arrayType;
		} else if (class_exists($string, false)) {
			return $string;
		} else if (class_exists($imports['__NAMESPACE__'] . '\\' . $string)) {
			return $imports['__NAMESPACE__'] . '\\' . $string;
		} else {
			foreach ($imports as $import) {
				if (substr($import, strrpos($import, '\\') + 1) == $string) {
					return $import;
				}
			}
		}

		return false;
	}
}