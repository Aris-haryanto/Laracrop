# Bitlys Laravel
Create Shorten URL with Bitly API in Laravel

### How to install
- Run in your terminal:
```
$ cd yourprojectdirectory
$ composer require arisharyanto/bitlys:dev-master
```
- Add the service providers in config/app.php:
```
Arisharyanto\Bitlys\BitlysServiceProvider::class,
```
- Run this command in the terminal
```
$ php artisan vendor:publish --provider="Arisharyanto\Bitlys\BitlysServiceProvider"  
```
- Add access_token from your bitly apps in config/bitlys.php:
```
'access_token' => 'your access token',
```

### How to use

Just add `use Arisharyanto\Bitlys\Bitlys`
```
Bitlys::shorten($longUrl);  # Create short bitly url
Bitlys::expand($shortUrl);  # Convert shortUrl to longUrl
Bitlys::clicks($shortUrl);  # Returns the number of clicks on a single Bitlink
Bitlys::countries($shortUrl);  # Returns metrics about the countries referring click traffic to a single Bitlink
```
Simple !

### License

See the license [https://github.com/Aris-haryanto/Bitlys-Laravel/blob/master/LICENSE](https://github.com/Aris-haryanto/Bitlys-Laravel/blob/master/LICENSE)


### Author


Aris Haryanto
visit my website [https://arindasoft.wordpress.com/](https://arindasoft.wordpress.com/)
