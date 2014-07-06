<?php 

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TripleBelongsToMany extends BelongsToMany {

	/**
	 * Key of the third model.
	 * 
	 * @var string
	 */
	protected $thirdKey;

	/**
	 * Third model instance.
	 * 
	 * @var mixed
	 */
	protected $third;

	/**
	 * Pivot columns to retrieve.
	 *
	 * @var array
	 */
	protected $pivotColumns = [];

	/**
	 * Create a new triple belongs to many relation.
	 * 
	 * @param Builder $query
	 * @param Model   $parent
	 * @param Model   $third
	 * @param string  $table
	 * @param string  $foreignKey
	 * @param string  $otherKey
	 * @param string  $thirdKey
	 * @param string  $relationName
	 */
	public function __construct(Builder $query, Model $parent, Model $third, $table, $foreignKey, $otherKey, $thirdKey, $relationName = null)
	{
		parent::__construct($query, $parent, $table, $foreignKey, $otherKey, $relationName);

		$this->thirdKey       = $thirdKey;
		$this->third          = $third;
		$this->pivotColumns[] = $thirdKey;
	}

	/**
	 * Get the fully qualified "third key" of the relation
	 * 
	 * @return string
	 */
	public function getThirdKey()
	{
		return $this->table.'.'.$this->thirdKey;
	}

	/**
	 * Attach 3 models.
	 * 
	 * @param  mixed    $id
	 * @param  array    $attributes
	 * @param  boolean  $touch
	 * @return void
	 */
	public function attach($id, array $attributes = array(), $touch = true)
	{
		// First check if developer provided an array of keys or models to attach
		// and set other key as additional pivot data for generic attach method
		// in order to make sure it is always saved upon attaching if provided.
		if (is_array($id) && count($id) > 1)
		{
			$otherId = ($id[1] instanceof Model) ? $id[1]->getKey() : $id[1];
			$id      = ($id[0] instanceof Model) ? $id[0]->getKey() : $id[0];

			$attributes[$this->thirdKey] = $otherId;
		}

		return parent::attach($id, $attributes, $touch);
	}

	/**
	 * Create a new pivot model instance.
	 *
	 * @param  array  $attributes
	 * @param  bool   $exists
	 * @return \Illuminate\Database\Eloquent\Relations\Pivot
	 */
	public function newPivot(array $attributes = array(), $exists = false)
	{
		$pivot = $this->related->newTriplePivot($this->parent, $this->third, $attributes, $this->table, $exists);

		return $pivot->setTriplePivotKeys($this->foreignKey, $this->otherKey, $this->thirdKey);
	}
	
}
