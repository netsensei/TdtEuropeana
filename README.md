# The Datatank Europeana

[![Latest Version](https://img.shields.io/github/release/netsensei/TdtEuropeana.svg?style=flat-square)](https://github.com/netsensei/TdtEuropeana/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

## An Installed Resource for Europeana

This package contains a ready to use installed resource for [The Datatank](thedatatank.com). This turns The Datatank into a proxy for the [Europeana API](labs.europeana.eu). The Datatank REST API calls are funneled to the Europeana API. The responses are cached by The Datatank.

Note: This is an independent project which is currently not endorsed.

### What is Europeana?

[Europeana](http://www.europeana.eu) is an internet portal that acts as an interface to books, paintings, films, museum objects and archival records that have been digitised throughout Europe. More then 2.000 institutions across Europe have contributed. These range from large names such as the Rijksmuseum, the British Library or the Louvre to regional archives and local museums.

See: [Wikipedia article](https://en.wikipedia.org/wiki/Europeana).

### What does this Installed Resource provide?

The Installed Resource allows you to set up a simple RESTful web API that can be leveraged by your own specific applications, without having to deal with the complexity of the larger Europeana API.

At this point, the resource is geared towards creating a simple API for returning specific record collections uploaded by a specific data provider ie. National Library of Norway, Flemish Art Collection and others.

This allows data providers to set up an API with a high level overview of their holdings in Europeana really quickly.

## Installation

### Fetch the code

Clone this repository and move the TdtEuropeana folder to the `installed/` folder.

```bash
git clone https://github.com/netsensei/TdtEuropeana
cd TdtEuropeana
cp -R TdtEuropeana <TdtRoot>/installed
```

### Fetch the PHP API library

Open up the `composer.json` file in the root of your Datatank installation and add the `colada/europeana` package like this:

```json
    "require": {
        "colada/europeana": "dev-master",
    },
```

Next run `composer update` to automatically fetch the package, install it in the `vendor/` folder and add it to the `vendor/composer/autoload_psr4.php` class.

The installed resource will autmatically pick up any loaded library classes and use them.

### Get an API Key

You will need an API key before you can connect to the API endpoint. You can register an account an obtain a key at the [Europeana Labs](http://labs.europeana.eu/api/registration/) website.

### Configuration

To start configuring, you need to copy the `tdteuropeana.php` file to your `app/config` folder in your Datatank installation:

```bash
cp <RepoRoot>/tdteuropeana.php <TdtRoot>app/config
```

After copying, open up the configuration file and start altering the values.
At least you need to set the `apiKey` property with the key you just got after registering.

## Usage

To start using the installed resource, start creating new dataset definitions
using the Datatank UI. See [Getting started](http://docs.thedatatank.com/5.6/installation) for more information.

Alternatively, you could import the packaged definitions that come with this project:

```bash
cd <TdtRoot>
artisan datatank:import <RepoRoot>/definitions.json
```

To set the `providerId` in the configuration, navigate to either `http://yourproject/europeana/providers` or this Europeana API call: `http://europeana.eu/api/v2/providers.json?wskey=yourkey`
to see a list of all the available providers and their id's. Use the values in the `identifier` property.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related, please email matthias@colada.be instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
