A simple trait to implement eloquent model search method in Laravel
==========================================

Simple Searchable is a trait for Laravel that adds a simple search function to Eloquent Models, inspired by the package [nicolaslopezj/searchable](https://github.com/nicolaslopezj/searchable).
Simple Searchable allows you to search a table including its relationship fields in belongsTo's relationships.
This is not optimized for big searches, but sometimes you just need to make it simple (Although it is not slow).

# Installation

Run the following command in your application:

```
"leandrogrando/simple-searchable": "1.*"
```

# Usage

Add the trait to your model and your search fields (Searchable fields are optional. [You can define them on the fly](#specifying-searchable-fields-on-the-fly).

```php
use LeandroGrando\SimpleSearchable\SimpleSearchable;

class User extends Model
{
    use SimpleSearchable;

    /**
     * Searchable fields.
     *
     * @var array
     */
    protected $searchable = [
        'name',
        'email',
        'city.name' // Field in a relationship
        'city.status.name' // Field in a multi relationship
    ];

    public function city()
    {
        return $this->belongsTo('City::class');
    }

}
```

Now you can search your model.

```php
// Simple search
$users = User::search($query)->get();

// Search and get relations
// It will not get the relations if you don't do this
$users = User::search($query)
            ->with('city.state')
            ->get();
```


## Search Paginated

As easy as laravel default queries

```php
// Search with relations and paginate
$users = User::search($query)
            ->with('city.state')
            ->paginate(20);
```

## Mix queries

Search method is compatible with any eloquent method. You can do things like this:

```php
// Search only active users
$users = User::where('status', 'active')
            ->search($query)
            ->paginate(20);
```

## Specifying searchable fields on the fly

If necessary, you can pass an array with the searchable fields in the search method itself as the second parameter:

```php

$users = User::search($query, [
                'city.name',
                'city.state.name'
                'city.state.uf'
            ])-get();
```

In the example above, the searchable attribute of the User model is ignored and the fields passed in the array of the second parameter are used.

## Contributing

Anyone is welcome to contribute. Fork, make your changes, and then submit a pull request.
