<?php
namespace TippingCanoe\MockingJay;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\FilesystemCache;
use Faker\Factory;
use TippingCanoe\MockingJay\Annotations\TypeHint;

class MockingJay {

	/**
	 * @var CachedReader
	 */
	protected $reader;

	/**
	 * @var \Faker\Generator
	 */
	protected $faker;

	/**
	 * MockingJay constructor.
	 *
	 * @param bool $debug
	 */
	public function __construct($debug = false) {

		AnnotationRegistry::registerAutoloadNamespace('TippingCanoe\MockingJay\Annotations', MockingJay::getSrcRoot());

		$this->reader = new CachedReader(
			new ApiAnnotationReader(),
			new FilesystemCache(MockingJay::getProjectRoot() . "/.cache/"),
			$debug
		);

		$this->faker = Factory::create();
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

	public function mock($class) {

		$reflector = new \ReflectionClass($class);
		$mock = $reflector->newInstance();

		foreach ($reflector->getProperties() as $reflectorProperty) {
			foreach ($this->reader->getPropertyAnnotations($reflectorProperty) as $propertyAnnotation) {
				if ($propertyAnnotation instanceof TypeHint) {
					$reflectorProperty->setAccessible(true);
					$reflectorProperty->setValue($mock, $propertyAnnotation->mock($this->faker));
				}
			}
		}

		return $mock;
	}
}