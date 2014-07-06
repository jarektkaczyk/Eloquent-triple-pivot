<?php

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;

class TriplePivot extends Pivot {

	/**
	 * Third model key name
	 * 
	 * @var string
	 */
	protected $thirdKey;

	/**
	 * Third model
	 *
	 * @var Illuminate\Database\Eloquent\Model
	 */
	protected $third;

	/**
	 * @param Model   $parent
	 * @param array   $attributes
	 * @param string  $table
	 * @param boolean $exists
	 * @param string  $thirdKey
	 */
	public function __construct(Model $parent, Model $third, $attributes, $table, $exists = false)
	{
		parent::__construct($parent, $attributes, $table, $exists);

		$this->thirdKey = $third->getKeyName();
		$this->third    = $third;
	}


	public function getThirdKey()
	{
		return $this->thirdKey;
	}

	public function getThird()
	{
		return $this->third;
	}

	/**
	 * Set the key names for the pivot model instance.
	 *
	 * @param  string  $foreignKey
	 * @param  string  $otherKey
	 * @param  string  $thirdKey
	 * @return TriplePivot
	 */
	public function setTriplePivotKeys($foreignKey, $otherKey, $thirdKey)
	{
		$this->foreignKey = $foreignKey;

		$this->otherKey = $otherKey;

		$this->thirdKey = $thirdKey;

		return $this;
	}


}
