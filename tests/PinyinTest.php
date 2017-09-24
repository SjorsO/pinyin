<?php

namespace SjorsO\Pinyin\Tests;

use PHPUnit\Framework\TestCase;
use SjorsO\Pinyin\Pinyin;

class PinyinTest extends TestCase
{
    /** @var $pinyin PinYin */
    protected static $pinyin = null;

    public function setUp()
    {
        parent::setUp();

        if(self::$pinyin === null) {
            self::$pinyin = new Pinyin();
        }
    }

    private function assertPinyin($expected, $chinese)
    {
        $this->assertSame($expected, self::$pinyin->convert($chinese));
    }

    /** @test */
    function converting_only_chinese_to_pinyin()
    {
        $this->assertPinyin('wǒ yào', '我要');
        $this->assertPinyin('dìfāng máfan', '地方 麻烦');
        $this->assertPinyin('wǒ yàoshi huàn le hàomǎ yào tā yǒu shénme yòng', '我要是換了號碼要它有什么用');
        $this->assertPinyin('péngyou lā', '朋友拉');
        $this->assertPinyin('tā bèi zhuǎnsòng dào dī jiè hù de yīyuàn', '他被轉送到低戒護的醫院');
        $this->assertPinyin('bùdéliǎo', '不得了');
    }

    /** @test */
    function it_uses_the_most_common_pinyin()
    {
        $this->assertPinyin('le', '了');
        $this->assertPinyin('shuō', '說');
        $this->assertPinyin('de', '的');
        $this->assertPinyin('me', '么');
    }

    /** @test */
    function converting_chinese_with_english_to_pinyin()
    {
        $this->assertPinyin('zhè shì 17 tiān lǐ dìèrcì', '这是17天里第二次');
        $this->assertPinyin('14 tiān yòu 16 xiǎoshí', '14天又16小时');
        $this->assertPinyin('bùjiǔqián xiàwǔ 2 shí 25 fēn', '不久前下午2时25分');
        $this->assertPinyin('xiōng shì wèi fù 42', '兄是位富42');
        $this->assertPinyin('wǒmen xūyào tántán guānyú TPS bàogào de shìqing', '我们需要谈谈关于TPS报告的事情');
        $this->assertPinyin('Milton. fāshēng shénmeshì qíng le?', 'Milton.发生什么事情了？');
        $this->assertPinyin('wǒ xiǎng nǐ yǒu gè hěn hǎo de guānyú Lumbergh', '我想你有个很好的关于Lumbergh');
        $this->assertPinyin('wǒ shì Bob Slydell. zhè shì wǒ de zhùshǒu', '我是 Bob Slydell.这是我的助手');
    }

    /** @test */
    function converting_chinese_that_has_english_in_brackets()
    {
        $this->assertPinyin("wǒ (nice) yào", "我(nice)要");
        $this->assertPinyin("bǐ dàodá (Carry me) zhōngdiǎn gèng měihǎo", "比到达(Carry me)终点更美好");
        $this->assertPinyin("bǐ dàodá [Carry me] zhōngdiǎn gèng měihǎo", "比到达[Carry me]终点更美好");
        $this->assertPinyin("bǐ dàodá zhōngdiǎn gèng měihǎo (Carry me)", "比到达终点更美好(Carry me)");
        $this->assertPinyin("[Carry me] bǐ dàodá zhōngdiǎn gèng měihǎo", "[Carry me]比到达终点更美好");
        $this->assertPinyin("[good wǒ job]", "[good 我 job]");
        $this->assertPinyin("(good wǒ job)", "(good 我 job)");
    }

    /** @test */
    function converting_when_chinese_is_contained_in_something()
    {
        // Sometimes chinese is surrounded by (weird chinese) quotes
        $this->assertPinyin("wǒ shuō: \"hǎo a, yòu shǎo yī jiàn máfan shì\"", "我说：“好啊，又少一件麻烦事”");
        $this->assertPinyin("\"wǒ W!\"", "“我W!”");
        $this->assertPinyin("yígè jiào \"yuègòng\" de jiāhuo", "一个叫“越共”的家伙");
        $this->assertPinyin("wǒ shuō, \"tā shì gè shígànjiā,", "我说，“他是个实干家，");
        $this->assertPinyin("tā yōngyǒu suǒyǒu wèntí de dáàn\"", "他拥有所有问题的答案”");

        $this->assertPinyin("\"hǎo a péngyou\"", "\"好啊 朋友\"");
        $this->assertPinyin("'hǎo a péngyou'", "'好啊 朋友'");

        // Chinese is rarely in brackets, but it should still work nicely
        $this->assertPinyin("chéngwéi kǎmén (jùfēng de zuì)", "成为卡门(飓风的最)");
        $this->assertPinyin("chéngwéi kǎmén [jùfēng de zuì]", "成为卡门[飓风的最]");
        $this->assertPinyin("(jùfēng de zuì) chéngwéi kǎmén", "(飓风的最)成为卡门");
        $this->assertPinyin("[jùfēng de zuì] chéngwéi kǎmén", "[飓风的最]成为卡门");
        $this->assertPinyin("kǎmén (jùfēng de zuì) chéngwéi", "卡门(飓风的最)成为");
        $this->assertPinyin("kǎmén [jùfēng de zuì] chéngwéi", "卡门[飓风的最]成为");
    }

    /** @test */
    function converting_should_normalize_punctuation()
    {
        $this->assertPinyin("wǒ ,.!?:'',.!?:''", "我 ，。！？：‘’，。！？：‘’");
        // A space is added before the opening quote (“)
        $this->assertPinyin("wǒ said: \"WOW!\"", "我 said：“WOW!”");

        $this->assertPinyin("[āgānzhèngzhuàn]", "【阿甘正传】");
        $this->assertPinyin("(nǐ bùshì wǒ de péngyou)", "（你不是我的朋友）");
        $this->assertPinyin("\"nǐ\"", "「你」");
        $this->assertPinyin("tā shuō \"bàoqiàn , lìngxiōng guòshì\"", "他說「抱歉 ， 令兄過世」");
        $this->assertPinyin("\"lìngxiōng shì wèi fùwēng\"", "「令兄是位富翁」");
    }

    /** @test */
    function converting_should_handle_punctuation_well()
    {
        $this->assertPinyin("děngděng, xiǎo xiōngdì", "等等，小兄弟");
        $this->assertPinyin("wǒ de shàngdì…", "我的上帝…");
        $this->assertPinyin("ń…", "嗯…");
        $this->assertPinyin("nǐ zhǐbuguò shì yìzhī… lièquǎn", "你只不过是一只…猎犬");
        $this->assertPinyin("māma zài nǎr?", "妈妈在哪儿？");
        $this->assertPinyin("a! nǐ gāng pǎo guò", "啊！你刚跑过");
        $this->assertPinyin("dànshì, yīnwèi dìyī:", "但是，因为第一：");
        $this->assertPinyin("yī: bǎohù hǎo nǐ de jiǎo", "一：保护好你的脚");
        $this->assertPinyin("qī... liù...", "七...六...");
        $this->assertPinyin("nǐhǎo. wǒ shì fúěr sī", "你好。我是福尔斯");
        $this->assertPinyin("hēi! qùnǐde!", "嘿！去你的！");

        $this->assertPinyin("fānyì & jiàoduì & zǒngjiān", "翻譯&校對&總監");
        $this->assertPinyin("fānyì & jiàoduì &", "翻譯&校對&");
        $this->assertPinyin("fānyì & & jiàoduì &", "翻譯&&校對&");
        $this->assertPinyin("& jiàoduì & zǒngjiān", "&校對&總監");

        // The ones below are questionable
        $this->assertPinyin("bù bā· gān bǔ xiā gōngsī de lǎobǎn?", self::$pinyin->convert("布巴·甘捕虾公司的老板？"));
    }

    /** @test */
    function converting_handles_strange_things()
    {
        // sometimes #'s and *'s are used to mark lyrics in subtitles
        $this->assertPinyin("#shì wèi huīwǔ qízhì#", "#是为挥舞旗帜#");
        $this->assertPinyin("*shì wèi huīwǔ qízhì*", "*是为挥舞旗帜*");
    }

    /** @test */
    function converting_handles_urls_well()
    {
        $this->assertPinyin("www.ZiMuZu.tv huānyíng jiāoliú", "www.ZiMuZu.tv 欢迎交流");
        $this->assertPinyin("jiāoliú www.ZiMuZu.tv huānyíng", "交流www.ZiMuZu.tv欢迎");
        $this->assertPinyin("www.ZiMuZu.com huānyíng jiāoliú", "www.ZiMuZu.com 欢迎交流");

        $this->assertPinyin("http://www.ZiMuZu.tv huānyíng jiāoliú", "http://www.ZiMuZu.tv 欢迎交流");
        $this->assertPinyin("jiāoliú http://www.ZiMuZu.tv huānyíng", "交流http://www.ZiMuZu.tv欢迎");
        $this->assertPinyin("http://www.ZiMuZu.com huānyíng jiāoliú", "http://www.ZiMuZu.com 欢迎交流");

        $this->assertPinyin("https://www.ZiMuZu.tv huānyíng jiāoliú", "https://www.ZiMuZu.tv 欢迎交流");
        $this->assertPinyin("jiāoliú https://www.ZiMuZu.tv huānyíng", "交流https://www.ZiMuZu.tv欢迎");
        $this->assertPinyin("https://www.ZiMuZu.com huānyíng jiāoliú", "https://www.ZiMuZu.com 欢迎交流");
    }

    /** @test */
    function converting_handles_spaces_well()
    {
        // 2 or 3 spaces are converted to 1
        $this->assertPinyin("- nà hǎo - hǎo", "- 那好 - 好");
        $this->assertPinyin("- nà hǎo - hǎo", "- 那好  - 好");
        $this->assertPinyin("- nà hǎo - hǎo", "- 那好   - 好");

        // 5 or more spaces are converted to 4
        $this->assertPinyin("- nà hǎo    - hǎo", "- 那好    - 好");
        $this->assertPinyin("- nà hǎo    - hǎo", "- 那好     - 好");
        $this->assertPinyin("- nà hǎo    - hǎo", "- 那好      - 好");
        $this->assertPinyin("- nà hǎo    - hǎo", "- 那好                               - 好");

        $this->assertPinyin("- kàn guò    - nǐ zhīdào zěnme kàn dìtú ma", "- 看过      - 你知道怎么看地图吗");
    }

    /** @test */
    function it_returns_the_input_string_if_no_chinese_to_convert()
    {
        $input = [
            " ，。！？：‘’，。！？：‘’",
            "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVW 1234567890-=_,.()[]{}~!@#$%^&*+",
            "This man is out of ideas.",
            "What is Holland?",
            "What(is)Holland?",
        ];

        foreach($input as $string) {
            $this->assertSame($string, self::$pinyin->convert($string));
        }
    }
}
