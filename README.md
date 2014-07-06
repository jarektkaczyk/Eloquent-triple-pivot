Eloquent-triple-pivot
=====================

Laravel/Eloquent triple model many-to-many relation

Working example of Eloquent Relation for many-to-many between 3 models/tables.

Usage

1. 3 models: User, Tag, Track
2. Add use statement in the trait accordingly
3. add use trait to the models
4. define the relations as tripleBelongsToMany

---

	tables
	
	users: id, ..
	tags: id, ..
	tracks: id, ..
	tag_track_user: id, tag_id, track_id, user_id, ..

	
	// -------------------------------------
	// 2 add use statement to the trait
	
	use WhateverNamespace\TripleBelongsToMany;
	
	trait TriplePivotModelTrait {
	  ...
	}
	
	
	// -------------------------------------
	// 3 use and implement the trait
	
	use WhateverNamespace\TriplePivotModelTrait;

	class User extends Eloquent {
	  
	  // each of the models must use this trait
	  use TriplePivotModelTrait;
	
	
	  // -------------------------------------
	  // 4  Relation definition
	
	  public function tags()
		{
			return $this->tripleBelongsToMany('Tag', 'Track');
			
			// table name and the keys may be overriden of course:
			// tripleBelongsToMany($related, $third, $table = null, $foreignKey = null, $otherKey = null, $thirdKey = null, $relation = null)
		}
		
		...
	}
	
	$user = User::with('tags')->first();
	
	// like ordinary belongsToMany:
	$user->tags; // collection of tags
	$user->tags->first(); // Tag model
	
	// additional features in the context of this relation:
	$user->tags->first()->third; // third model = Track
	
	// to save provide array of ids: [related_id, third_id]
	$user->tags()->attach([$tagId, $trackId]);
	
	// or models:
	$anotherTag = Tag::find($tagId);
	$anotherTrack = Track::find($trackId);
	
	$user->tags()->attach([$anotherTag, $anotherTrack[);
	
	
	
	
