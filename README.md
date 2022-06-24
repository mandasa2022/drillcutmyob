# Laramyob - MYOB in Laravel, made easy.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mandasa2022/drillcutmyob.svg?style=flat-square)](https://packagist.org/packages/mandasa2022/drillcutmyob)
[![Build Status](https://img.shields.io/travis/mandasa2022/drillcutmyob/master.svg?style=flat-square)](https://travis-ci.org/mandasa2022/drillcutmyob)
[![Quality Score](https://img.shields.io/scrutinizer/g/mandasa2022/drillcutmyob.svg?style=flat-square)](https://scrutinizer-ci.com/g/mandasa2022/drillcutmyob)
[![Total Downloads](https://img.shields.io/packagist/dt/mandasa2022/drillcutmyob.svg?style=flat-square)](https://packagist.org/packages/mandasa2022/drillcutmyob)

A handy Laravel wrapper around MYOB AccountRight v2. This is still in alpha stage and will include breaking changes regularily. Full Readme in progress.

## Installation

You can install the package via composer:

```bash
composer require mandasa2022/drillcutmyob
```

## Setup
ENV requirements:

```
MYOB_CLIENT_ID=
MYOB_CLIENT_SECRET=
MYOB_REDIRECT_URI=myob/login
MYOB_GRANT_TYPE=authorization_code
MYOB_SCOPE=CompanyFile
```

Publish the preset configuration to store your MYOB authentication details
```bash
php artisan vendor:publish --provider="Mandasa\Drillcutmyob\LaramyobServiceProvider" --tag="migrations"
php artisan migrate
```

You'll now need to authenticate with something like the following:

``` php
use Mandasa\Drillcutmyob\Laramyob;
use Mandasa\Drillcutmyob\Models\Remote\CompanyFile;
use Mandasa\Drillcutmyob\Models\Remote\Contact\Customer;

$drillcutmyob = new Laramyob;
//Redirect your user to MYOB to authenticate account right v2
$drillcutmyob->authenticate()->getCode();
//When the code is returned, get your access token
$drillcutmyob->authenticate()->getToken();
//Now you can save your credentials like so
//You would first load the company files the MYOB user has access to
$drillcutmyob->of(CompanyFile::class)->load();
//Then save them like so (the username and passwords are Base64 encoded in Laramyob)
$drillcutmyob->authenticate()->saveCompanyFileCredentials([
        'username' => 'Administrator',
        'password' => '',
        'company_file_guid' => '8bf1611b-1666-4f8f-8b7f-ee4cf4fee2ff',
        'company_file_name' => 'API Sandbox Demo 48',
        'company_file_uri'  => 'https:\/\/ar1.api.myob.com\/accountright\/8bf1611b-1666-4f8f-8b7f-ee4cf4fee2ff'
]);
```

## Usage

### Get

Once that's completed you'll be able to query the API as you normally would
```php
//And now query the API with the supported models (and paginate if supported)
$drillcutmyob->of(Customer::class)->page(1); //page 1
//Or (if the Model is a paginted model it will stil default to pagination due to MYOB api restrictions)
$drillcutmyob->of(Customer::class)->load(); //page 1
$drillcutmyob->of(Customer::class)->load(2); //page 2

//You can also load the specified model by UID
$drillcutmyob->of(Customer::class)->loadByUid('8bf1611b-1666-4f8f-8b7f-ee4cf4fee2ff');

//Or just return the first from a search
$drillcutmyob->of(TaxCode::class)->whereCode('GST')->first();

//The customer class also has some helper function (whereEmail)
$drillcutmyob->of(Customer::class)->whereEmail('lukesimoncurtis@gmail.com')->get();
```

You can also expose the Raw API for MYOB if appropriate
```php
$drillcutmyob->rawGet('/Contact/Employee');
$drillcutmyob->rawPost('/Contact/Employee', $data);
```

### Post
Once you're ready to post you can do the following, to, for example, save a Customer

```php
$taxCode = $this->drillcutmyob->of(TaxCode::class)->whereCode('GST')->first();

$customer = (new Customer)->create([
    'CompanyName'    => 'Creativecurtis',
    'LastName'       => 'curtis',
    'FirstName'      => 'luke',
    'IsIndividual'   => false,
    "TaxCode"        => [
        "UID" => $taxCode['UID'],
    ],
    "FreightTaxCode" => [
        "UID" => $taxCode['UID'],
    ],
])

$drillcutmyob->save($customer);
```

### Testing

``` bash
composer test
```

### Todo:
- [x] Create API Auth.
- [x] Create basic model syntax for retrieving data
- [x] Implement base model for encodable data
- [x] Create request class
- [ ] Clean up request class.
- [ ] Create get and set for appropriate models instead of current free-for-all
- [ ] Write tests
- [ ] Make OAuth2 request class less dependant on request class

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Luke Curtis](https://github.com/lukecurtis93)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).