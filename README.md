# php7-checker

**This project is now deprecated in favor of [php7cc](https://github.com/sstalle/php7cc)**.

~~php7-checker is a PHP library that parses your code and statically detects
some errors that could prevent it to run on PHP7.~~

~~*Disclamer*: this tool is a static analyzer. As it doesn't run your code it's
far from being 100% reliable. If you need to ensure that some code will run on
PHP 7, nothing will do a better job than a complete test suite run on the
targeted version of PHP. If you want to test it locally (f.e. because your code
is not open source), you can still have a look to
[JoliCi](https://github.com/jolicode/JoliCi).~~

## Installation

#### Globally (Composer)

To install php7-checker, install Composer and issue the following command:
```bash
./composer.phar global require jolicode/php7-checker
```

Then, make sure you have ``~/.composer/vendor/bin`` in your ``PATH``, and
you're good to go:
```bash
export PATH="$PATH:$HOME/.composer/vendor/bin"
```

## Usage

You can run the checker on a given file or directory:

```bash
php7-checker /path/to/dir
php7-checker /path/to/file
```

## Further documentation

Discover more by reading the docs:

* [Errors checked](doc/01-errors-checked.md)

You can see the current and past versions using one of the following:

* the `git tag` command
* the [releases page on Github](https://github.com/jolicode/php7-checker/releases)
* the file listing the [changes between versions](CHANGELOG.md)

And finally some meta documentation:

* [versioning and branching models](VERSIONING.md)
* [contribution instructions](CONTRIBUTING.md)

## Credits

* [All contributors](https://github.com/jolicode/php7-checker/graphs/contributors)

## License

php7-checker is licensed under the MIT License - see the [LICENSE](LICENSE) file
for details.
