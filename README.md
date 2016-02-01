# Triple Pivot

*This package is not maintained anymore, let me know if you wish to work on it*

A way to link 3 many-to-many relations together in Laravel 4's Eloquent.

---

## Contents

### Usage

### Setup

1. Create 3 models: User, Tag, Track
2. Set up your tables: users, tags, tracks, pivot table (defaults to tag_track_user, in the example below we use custom name users_tags_tracks)
3. Require in `composer.json` and `config/app.php`
4. Add the trait in all 3 models
5. Define the relation method as `->tripleBelongsToMany()`
6. (Optional) Create a nice-name relation for the `->third()` method

---

## Usage

	// Get the first User, and autoload their tags
	$user = User::with( 'tags' )->first();

	// Like an ordinary belongsToMany
	$user->tags; // Collection of tags
	$user->tags->first(); // Tag model

	// Get the track associated with a given tag for the user
	$user->tags->first()->third; // Track model
	$user->tags->first()->track; // Track model (only if you did step 6)

	// Attach a tag/track to a user
	$user->tags()->attach( [ $tagId, $trackId ] ); // Pass an array of 2 IDs
	$user->tags()->attach( [ Tag::find( $tagId ), Track::find( $trackId ) ] ); // Pass an array of 2 models

---

## Setup

### 1. Create 3 models

**Models/User.php** (should already exist)

	<?php
	
	namespace Models;	
    class User extends \Eloquent {
    }

**Models/Tag.php**

	<?php

	namespace Models
	
	class Tag extends \Eloquent {
	}

**Models/Track.php**

	<?php
	
	namespace Model;
	
	class Track extends \Eloquent {
	}

### 2. Set up the database tables

**database/migrations/1_0_0_0_create_triple_pivot_tables.php**: Create the tables we're going to be joining together (`users` may already have a migration).

	<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;

	class CreateTriplePivotTables extends Migration {

		public function down() {
			Schema::drop( 'users' );
			Schema::drop( 'tags' );
			Schema::drop( 'tracks' );
			Schema::drop( 'users_tags_tracks' );
		}

		public function up() {
			Schema::create( 'users', function ( Blueprint $table ) {
				$table->increments('id');
				$table->string('email');
			} );
		
			Schema::create( 'tags', function ( Blueprint $table ) {
				$table->increments('id');
				$table->string('name');
			} );
		
			Schema::create( 'tracks', function ( Blueprint $table ) {
				$table->increments('id');
				$table->string('name');
			} );
	
			Schema::create( 'users_tags_tracks', function ( Blueprint $table ) {
				$table->integer( 'user_id' )->unsigned()->nullable();
				$table->integer( 'tag_id' )->unsigned()->nullable();
				$table->integer( 'track_id' )->unsigned()->nullable();
			} );
		}

	}

### Require the package

**composer.json**: Add in the package definition.

	"require": {
        "laravel/framework": "4.2.*",
        ...
        "jarektkaczyk/eloquent-triple-pivot": "dev-master"
    },

Run `composer update -o` in the Terminal.

**config/app.php**: Add the service provider to the `providers` array.

	'providers' => array(
	
		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		...
		'Jarektkaczyk\TriplePivot\TriplePivotServiceProvider',

	),

### 4. Add the trait into all of our models

**Models/User.php**: Two `use` statements - one to pull in the namespaced Trait, one to use it in the Model.

	<?php
	
	namespace Models;
	use Jarektkaczyk\TriplePivot\TriplePivotTrait;
	
    class User extends \Eloquent {
    	use TriplePivotTrait;
    }

**Models/Tag.php**: As above

	<?php

	namespace Models;
	use Jarektkaczyk\TriplePivot\TriplePivotTrait;
	
	class Tag extends \Eloquent {
    	use TriplePivotTrait;
	}

**Models/Track.php**: As above

	<?php
	
	namespace Models;
	use Jarektkaczyk\TriplePivot\TriplePivotTrait;
	
	class Track extends \Eloquent {
    	use TriplePivotTrait;
	}

### 5. Define the `tripleBelongsToMany` relation

**Models/User.php**

	<?php
	
	namespace Models;
	use Jarektkaczyk\TriplePivot\TriplePivotTrait;
	
    class User extends \Eloquent {
    	use TriplePivotTrait;
    	
		/**
		 * @return \Jarektkaczyk\TriplePivot\TripleBelongsToMany
		 */
		public function tags() {
			return $this->tripleBelongsToMany( 'Models\Tag', 'Models\Track', 'users_tags_tracks' );
		}
    }

### 6. (Optional) Define a nicer method than `->third` on `Models/Tag`

**Models/Tag.php**: Create a new method `getTrackAttribute()` which forwards on to the `getThirdAttribute()` method so we can call `$tag->track->name` instead of `$tag->third->name`.

	<?php

	namespace Models;
	use Jarektkaczyk\TriplePivot\TriplePivotTrait;
	
	class Tag extends \Eloquent {
    	use TriplePivotTrait;

		/**
		 * @return \Illuminate\Database\Eloquent\Model
		 */
		public function getTrackAttribute() {
			return $this->getThirdAttribute();
		}
	}
