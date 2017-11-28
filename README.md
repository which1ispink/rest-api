# which1ispink/rest-api

This is a simple REST API for a hypothetical browser-based game.

Everything is written from scratch in PHP, no framework or libraries 
used. Composer is only used to install PHPUnit and for its PSR-4 
autoloading capabilities.

The focus is on the "core" code of a RESTful API (which takes a lot 
of inspiration from many popular MVC frameworks), and less on the 
actual (hypothetical) game. With that said, there's a good basis 
to build on to extend the actual game code, for example with how 
a service layer is used, along with plain PHP objects and the data 
mapper pattern being used to map them to and from the database.

## Installation
- Run `composer install`
- Import the included SQL file to your database server.
- Copy the `database.config.php.dist` 
file to a file with the same name without the ".dist" part, changing 
the database host, user, and password to fit your own.

## Usage
Run it with the PHP built-in server:

```bash
$ php -S localhost:8080 -t public/
```

## Tests
To run the unit tests:

```bash
$ vendor/bin/phpunit
```

## Documentation & examples
For simplicity's sake, the API only supports returning data in 
JSON format, and taking in JSON as request bodies. It also 
only deals with one resource; Characters.

A full character representation will look as follows:
```json
{
    "id": 1,
    "name": "Abraham Lincoln",
    "type": "Vampire hunter",
    "experience_points": 1000,
    "level": 10,
    "power_points": 650,
    "health": 88,
    "is_alive": true,
    "date_created": "2017-11-01 19:42:01"
}
```

And here's a list of the supported character types (case-sensitive):
- Vampire
- Vampire hunter
- Zombie
- Ninja
- Pirate

Here's a breakdown of all the different endpoints, details and 
example requests:

#### Get a character
`GET /characters/{id}`

Where {id} is a number. This returns either a character object 
(code 200 - OK), or a message explaining that no character was found 
with the given ID (code 404 - not found).

#### Get all characters
`GET /characters`

This returns either an array of character objects (code 200 - OK), 
or a message explaining that no characters were found 
(code 404 - not found).

#### Add a new character
`POST /characters`

The provided JSON request body should look as follows:
```json
{
    "name": "Zombie boy",
    "type": "Zombie"
}
```

The "type" parameter is even optional; if not provided, the default 
character type (Vampire hunter) will be used.

The "name" parameter is required and you'll get a validation error 
if it's not provided.

The above request will either return the created character object 
(code 201 - created), or you'll get a message with a validation 
error (code 400 - bad request). 

#### Apply an action on a character
`PATCH /characters/{id}`

Now this one is interesting. I chose the PATCH HTTP method because 
it fits what this endpoint does more than PUT. I decided that you 
can't "change" a character's attributes, but rather apply "actions" 
on it. Each request body would include an "action" parameter 
and a "value" parameter. Here are the supported actions, 
and then their JSON request body representations:

- change_name (changes the character name)
```json
{
    "action": "change_name",
    "value": "New name"
}
```

- transform (transforms the character to the given type. Again, type 
must be from the character types list above)
```json
{
    "action": "transform",
    "value": "Zombie"
}
```

- gain_experience (adds experience points to the character)
```json
{
    "action": "gain_experience",
    "value": 50
}
```

- power_up (increases the character's power points by the given amount)
```json
{
    "action": "power_up",
    "value": 20
}
```

- power_down (decreases the character's power points by the given amount)
```json
{
    "action": "power_down",
    "value": 20
}
```

- heal (increases the character's health by the given amount)
```json
{
    "action": "heal",
    "value": 25
}
```

- restore_full_health (restores the character's full health)
```json
{
    "action": "restore_full_health",
    "value": true
}
```

- revive (revives a dead character, giving it a low amount of health)
```json
{
    "action": "revive",
    "value": true
}
```

- damage (decreases the character's health by the given amount. The 
character would die if health gets to 0)
```json
{
    "action": "damage",
    "value": 33
}
```

#### Kill a character
`DELETE /characters/{id}`

Where {id} is a number. This either returns no content 
(code 204 - no content), meaning the character was killed successfully, 
or a message explaining what went wrong. For example a character 
not being found at the given ID (code 404 - not found), or a 
character being already dead (code 409 - conflict).

## Contributing
All pull requests must adhere to the [PSR-2 standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).

Some features/improvements that I would suggest looking at include:
- Adding support for returning and accepting other data types other than 
JSON.
- Adding pagination support.
- Adding filtering support.
- Adding more stuff to the actual game code, other than characters.
- Adding more unit tests.
- Any other improvements to the overall design that you see fit.

## License
This project is licensed under the MIT license. See [License File](LICENSE.md) for more information.
