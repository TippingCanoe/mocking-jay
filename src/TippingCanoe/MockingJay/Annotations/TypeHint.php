<?php
namespace TippingCanoe\MockingJay\Annotations;


use Faker\Factory;


/**
 * Class TypeHint
 *
 * @package TippingCanoe\Apiarian\Annotations
 */
class TypeHint {

	private static $basicTypes = [
		'string' => 'string',
		'int' => 'int',
		'integer' => 'int',
		'float' => 'float',
		'bool' => 'bool',
		'boolean' => 'bool'
	];

	private static $arrayType = 'array';

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


	public static function parse($type, array $imports) {

		$typeInfo = explode(" ", $type);

		$baseType = $typeInfo[0];
		if (false !== $sanitizedBaseType = TypeHint::getSanitizedName($baseType, $imports)) {
			if ($sanitizedBaseType == TypeHint::$arrayType) {
				$genericType = null;
				if (substr($baseType, -2) == TypeHint::$arrayTypeShort) {
					$genericType = substr($baseType, 0, -2);
				} else {
					$genericType = $typeInfo[1];
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

	private static function getSanitizedName($string, array $imports) {

		if (array_key_exists($string, TypeHint::$basicTypes)) {
			return TypeHint::$basicTypes[$string];
		} else if ($string == TypeHint::$arrayType) {
			return TypeHint::$arrayType;
		} else if (substr($string, -2) == TypeHint::$arrayTypeShort) {
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

	public function mock(Factory $faker) {

		if (array_key_exists($this->baseType, TypeHint::$basicTypes)) {
			return $this->mock($this->baseType, $faker);
		}

		// @TODO, need ot handle arrays and objects, passing onto their fakers recursively. Probalyb move this back up.
	}
}