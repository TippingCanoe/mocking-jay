<?php

include(dirname(__FILE__) . "/vendor/autoload.php");

/**
 * Class Foo
 */
class Foo {

	/**
	 * You can use basic types and we'll guess at the format.
	 *
	 * @var string
	 */
	public $lorem;

	/**
	 * You can use arrays.
	 *
	 * @var array<int>
	 */
	public $ipsum;

	/**
	 * Or arrays in another format.
	 * And you can specify the count of items to generate.
	 *
	 * @var array string
	 * @\TippingCanoe\MockingJay\Annotations\Count(count=3)
	 */
	public $dolor;

	/**
	 * Or arrays in yet another format.
	 * And you can specify the range of items to generate.
	 *
	 * @var float[]
	 * @\TippingCanoe\MockingJay\Annotations\Count(min=0, max=3)
	 */
	public $sit;

	/**
	 * You can provide a custom callback to generate the value.
	 *
	 * @\TippingCanoe\MockingJay\Annotations\Mock(callback="generateAmit")
	 * @var string
	 */
	public $amit;

	/**
	 * You can use any provider from https://github.com/fzaninotto/Faker#formatters.
	 *
	 * @\TippingCanoe\MockingJay\Annotations\Mock(fakerProvider="name")
	 * @var string
	 */
	public $consectetur;

	/**
	 * You can use another Object.
	 *
	 * @var Bar
	 */
	public $adipiscing;

	/**
	 * And, of course, combine and mix these features.
	 *
	 * @var Bar[]
	 * @\TippingCanoe\MockingJay\Annotations\Count(count=4)
	 */
	public $lacinia;

	/**
	 * You can ignore certain properties.
	 *
	 * @\TippingCanoe\MockingJay\Annotations\IgnoreMock()
	 * @var int
	 */
	public $elit;

	public function generateAmit() {

		return "AMIT!";
	}
}

/**
 * Class Bar
 * If a class is annotated with `Whitelist`, only the properties specifically annotated with `Mock` will be included.
 *
 * @\TippingCanoe\MockingJay\Annotations\Whitelist()
 */
class Bar {

	/**
	 * @var boolean
	 */
	public $lorem;

	/**
	 * @\TippingCanoe\MockingJay\Annotations\Mock()
	 * @var string
	 */
	public $ipsum;
}

// Mock an instance of `Foo` and dump it out.
var_dump(\TippingCanoe\MockingJay\MockingJay::mock("Foo"));