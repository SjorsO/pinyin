# Readme
Convert Chinese to pinyin

Originally made for [subtitletools.com](https://subtitletools.com)

## Install
```bash
composer require sjorso/pinyin
```

## Usage
```php
$pinyinConverter = new \SjorsO\Pinyin\Pinyin();
 
// wǒ xiǎng nǐ yǒu gè hěn hǎo de guānyú Lumbergh 
$pinyin = $pinyinConverter->convert('我想你有个很好的关于Lumbergh');
```

## License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
