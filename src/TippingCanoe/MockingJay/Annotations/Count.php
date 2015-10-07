<?php
namespace TippingCanoe\MockingJay\Annotations;

/**
 * Class Count
 *
 * @package TippingCanoe\MockingJay\Annotations
 * @Annotation
 * @Target("PROPERTY")
 */
class Count {

	/**
	 * @var int
	 */
	public $count;

	/**
	 * @var int
	 */
	public $min;

	/**
	 * @var int
	 */
	public $max;
}