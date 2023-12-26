---
description: Example of a usage for this library as a Laravel cast on an Eloquent model.
---

# Use as a Laravel cast

Imagine we have a Laravel based project and one of our models stores bytes so we can convert them freely.

Create the cast using Laravel's command:

```sh
php artisan make:cast ByteUnit
```

Now copy the following in your `app\Casts\ByteUnit.php` file:

```php
<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use OpenSoutheners\ByteUnitConverter\ByteUnitConverter;

class ByteUnit implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return (string) ByteUnitConverter::new($value)->nearestUnit();
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}

```

Next and finally you can add this cast class to any of your models that requires this on some of their attributes like so:

```php
<?php

namespace App\Models;

use App\Casts\ByteUnit;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'disk_available' => ByteUnit::class,
    ];
}
```

And voilá! You now have this cast to your model results on your API or any controller using `toArray` or `toJson` methods.

{% hint style="info" %}
Check more documentation on this at the official Laravel documentation about [Eloquent custom casts](https://laravel.com/docs/10.x/eloquent-mutators#custom-casts).
{% endhint %}
