<?php
namespace TippingCanoe\MockingJay\Annotations;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Mock
 *
 * @package TippingCanoe\MockingJay\Annotations
 * @Annotation
 * @Target("PROPERTY")
 */
class Mock {

	/**
	 * @var string
	 */
	public $fakerProvider;

	/**
	 * @var string
	 */
	public $callback;
}