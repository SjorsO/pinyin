# Readme
Convert Chinese to pinyin

Originally made for [subtitletools.com](https://subtitletools.com/make-chinese-pinyin-subtitles)

## Install
```bash
composer require sjorso/pinyin
```

## Usage
```php
$pinyinConverter = new \SjorsO\Pinyin\Pinyin();
 
// wǒ xiǎng nǐ yǒu gè hěn hǎo de guānyú Lumbergh 
$pinyin = $pinyinConverter->convert('我想你有个很好的关于Lumbergh');
 
// Milton. fāshēng shénmeshì qíng le?
$pinyin = $pinyinConverter->convert('Milton.发生什么事情了？');
```

## Dictionary
the `pinyin-characters-array.php` file is a parsed version of [CC-CEDICT](https://www.mdbg.net/chinese/dictionary?page=cc-cedict) 

## License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
