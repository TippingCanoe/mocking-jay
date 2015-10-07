<?php
namespace TippingCanoe\MockingJay;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\FilesystemCache;
use Faker\Factory;
use Faker\Generator;
use TippingCanoe\MockingJay\Annotations\Count;
use TippingCanoe\MockingJay\Annotations\TypeHint;

class MockingJay {

	/**
	 * @var MockingJay
	 */
	protected static $instance;

	/**
	 * @var bool
	 */
	protected static $debug = false;

	private static $basicFakerProviders = [
		'string' => 'sentence',
		'int' => 'randomDigitNotNull',
		'float' => 'randomFloat',
		'bool' => 'boolean',
	];

	/**
	 * @var CachedReader
	 */
	protected $reader;

	/**
	 * @var \Faker\Generator
	 */
	protected $faker;

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	protected function __construct(Reader $reader, Generator $generator) {

		$this->reader = $reader;
		$this->faker = $generator;
	}

	public static function getProjectRoot() {

		return MockingJay::getSrcRoot() . "/..";
	}

	public static function getSrcRoot() {

		$path = dirname(__FILE__);

		return $path . "/../..";
	}

	public static function getVendorRoot() {

		return MockingJay::getProjectRoot() . "/vendor";
	}

	public static function mock($class) {

		$mockingJay = static::getInstance();

		$reflectedClass = new \ReflectionClass($class);
		$reflectedClassInstance = $reflectedClass->newInstance();

		$inWhiteListMode = $mockingJay->getReader()->getClassAnnotation($reflectedClass, 'TippingCanoe\MockingJay\Annotations\Whitelist') !== null;

		foreach ($reflectedClass->getProperties() as $reflectedProperty) {
			foreach ($mockingJay->getReader()->getPropertyAnnotations($reflectedProperty) as $propertyAnnotation) {
				if ($propertyAnnotation instanceof TypeHint) {
					/** @var $mockAnnotation \TippingCanoe\MockingJay\Annotations\Mock */
					$mockAnnotation = $mockingJay->getReader()->getPropertyAnnotation($reflectedProperty, 'TippingCanoe\MockingJay\Annotations\Mock');
					if (($inWhiteListMode && $mockAnnotation !== null) || (!$inWhiteListMode && $mockingJay->getReader()->getPropertyAnnotation($reflectedProperty, 'TippingCanoe\MockingJay\Annotations\IgnoreMock') === null)) {
						$wasMocked = false;
						$mockedValue = null;

						if ($mockAnnotation != null) {
							if ($mockAnnotation->fakerProvider != null) {
								$mockedValue = $mockingJay->getFaker()->{$mockAnnotation->fakerProvider};
								$wasMocked = true;
							} else if ($mockAnnotation->callback != null) {
								$mockedValue = $reflectedClassInstance->{$mockAnnotation->callback}();
								$wasMocked = true;
							}
						}

						if (!$wasMocked) {
							$mockedValue = static::generateMockValueForTypeHint($mockingJay, $propertyAnnotation, $mockingJay->getReader()->getPropertyAnnotation($reflectedProperty, 'TippingCanoe\MockingJay\Annotations\Count'));
							$wasMocked = true;
						}

						if ($wasMocked) {
							$reflectedProperty->setAccessible(true);
							$reflectedProperty->setValue($reflectedClassInstance, $mockedValue);
						}
					}
				}
			}
		}

		return $reflectedClassInstance;
	}

	/**
	 * Controls whether Annotations are efficiently cached on the file system or not.
	 *
	 * @param $debug
	 */
	public static function setDebug($debug) {

		// Need to recreate the instace.
		static::$instance = null;
		static::$debug = $debug;
	}

	protected static function generateMockValueForTypeHint(MockingJay $mockingJay, TypeHint $typeHint, Count $count = null) {

		$mockedValue = null;

		if ($typeHint->baseType == TypeHint::$arrayType) {
			$mockedValue = [];
			$mockValueCount = $count == null ? null : $count->count;
			if ($mockValueCount == null) {
				if ($count != null && $count->min !== null && $count->max !== null) {
					$mockValueCount = rand($count->min, $count->max);
				} else {
					$mockValueCount = rand(0, 10);
				}
			}

			$genericTypeHint = new TypeHint($typeHint->genericType);

			for ($i = 0; $i < $mockValueCount; $i++) {
				$mockedValue[] = static::generateMockValueForTypeHint($mockingJay, $genericTypeHint);
			}
		} else if (array_key_exists($typeHint->baseType, static::$basicFakerProviders)) {
			$mockedValue = $mockingJay->getFaker()->{static::$basicFakerProviders[$typeHint->baseType]};
		} else {
			// Recurse.
			$mockedValue = static::mock($typeHint->baseType);
		}

		return $mockedValue;
	}

	/**
	 * Creates an instance of the *Singleton* if necessary.
	 *
	 * @return MockingJay
	 */
	protected static function getInstance() {

		if (static::$instance == null) {
			AnnotationRegistry::registerAutoloadNamespace('TippingCanoe\MockingJay\Annotations', MockingJay::getSrcRoot());
			AnnotationRegistry::registerAutoloadNamespace('\TippingCanoe\MockingJay\Annotations', MockingJay::getSrcRoot());

			static::$instance = new MockingJay(
				new CachedReader(
					new BasicAnnotationReader(),
					new FilesystemCache(MockingJay::getProjectRoot() . "/.cache/"),
					static::$debug
				),
				Factory::create());
		}

		return static::$instance;
	}

	/**
	 * @return CachedReader
	 */
	public function getReader() {

		return $this->reader;
	}

	/**
	 * @return Generator
	 */
	public function getFaker() {

		return $this->faker;
	}

	/**
	 * Private clone method to prevent cloning of the instance of the
	 * *Singleton* instance.
	 *
	 * @return void
	 */
	private function __clone() {
	}

	/**
	 * Private unserialize method to prevent unserializing of the *Singleton*
	 * instance.
	 *
	 * @return void
	 */
	private function __wakeup() {
	}
}