<?php
require_once 'HTML/QuickForm2.php';
require_once 'HTML/QuickForm2/Renderer.php';
require_once dirname(__FILE__) . '/../HTML/QuickForm2/Element/BackgroundText.php';

class HTML_QuickForm2_Element_BackgroundTextTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->bt = new HTML_QuickForm2_Element_BackgroundText();
    }


    public function testSetBackgroundText()
    {
        $this->bt->setBackgroundText('foo');
        $s = (string)$this->bt;
        $this->assertContains('value="foo', $s);
    }

    public function testSetBackgroundTextChange()
    {
        $this->bt->setBackgroundText('foo');
        $this->bt->setBackgroundText('bar');
        $s = (string)$this->bt;
        $this->assertContains('value="bar', $s);
        $this->assertNotContains('foo', $s);
    }

    public function testSetBackgroundTextUnsetEmptyString()
    {
        $this->bt->setBackgroundText('foo');
        $this->bt->setBackgroundText('');
        $s = (string)$this->bt;
        $this->assertNotContains('foo', $s);
    }

    public function testSetBackgroundTextUnsetNull()
    {
        $this->bt->setBackgroundText('foo');
        $this->bt->setBackgroundText(null);
        $s = (string)$this->bt;
        $this->assertNotContains('foo', $s);
    }



    public function testSetBackgroundClassWithoutText()
    {
        $this->bt->setBackgroundClass('foo-background');
        $s = (string)$this->bt;
        $this->assertNotContains('foo-background', $s);
    }

    public function testSetBackgroundClass()
    {
        $this->bt->setBackgroundText('bar');
        $this->bt->setBackgroundClass('foo-background');
        $s = (string)$this->bt;
        $this->assertContains('class="foo-background"', $s);
    }

    public function testSetBackgroundClassUnsetText()
    {
        $this->bt->setBackgroundText('bar');
        $this->bt->setBackgroundClass('foo-background');
        $s = (string)$this->bt;
        $this->assertContains('class="foo-background"', $s);

        $this->bt->setBackgroundText(null);
        $s = (string)$this->bt;
        $this->assertNotContains('foo-background', $s);
    }



    public function testSetInvisibleChar()
    {
        $this->bt->setInvisibleChar('!foo!');
        $this->bt->setBackgroundText('bar');
        $s = (string)$this->bt;
        $this->assertContains('!foo!', $s);
    }

    public function testSetInvisibleCharNull()
    {
        $this->bt->setInvisibleChar(null);
        $this->bt->setBackgroundText('bar');
        $s = (string)$this->bt;
        $this->assertContains('value="bar"', $s);
    }

    public function testSetInvisibleCharEmptyString()
    {
        $this->bt->setInvisibleChar('');
        $this->bt->setBackgroundText('bar');
        $s = (string)$this->bt;
        $this->assertContains('value="bar"', $s);
    }

    public function testSetInvisibleCharAfterText()
    {
        $this->bt->setInvisibleChar('!foo!');
        $this->bt->setBackgroundText('bar');
        $this->bt->setInvisibleChar('!foz!');
        $s = (string)$this->bt;
        $this->assertContains('!foz!', $s);
    }



    /**
     * Verify that render() updates the background text attributes
     */
    public function testRender()
    {
        $renderer = HTML_QuickForm2_Renderer::factory('default');

        $this->bt->setBackgroundText('foobar');
        $s = (string)$this->bt->render($renderer);
        $this->assertContains('foobar', $s);

        //renderers do not render elements again, so we need a new one
        $renderer = HTML_QuickForm2_Renderer::factory('default');
        $this->bt->setBackgroundText('');
        $s2 = (string)$this->bt->render($renderer);
        $this->assertNotContains('foobar', $s2);
    }

    /**
     * Verify that toString() updates the background text attributes
     */
    public function testToString()
    {
        $this->bt->setBackgroundText('foobar');
        $s = (string)$this->bt;
        $this->assertContains('foobar', $s);

        $this->bt->setBackgroundText('');
        $s = (string)$this->bt;
        $this->assertNotContains('foobar', $s);
    }



    public function testDetectBrokenBrowsersNoAgent()
    {
        unset($_SERVER['HTTP_USER_AGENT']);
        $bt = new HTML_QuickForm2_Element_BackgroundText();
        $bt->setBackgroundText('foo');
        $s = (string)$bt;
        $this->assertContains('"foo', $s);
        $this->assertNotContains('"foo"', $s);
    }

    public function testDetectBrokenBrowsersGoodAgent()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Opera/9.80 (X11; Linux i686; U; de)'
            . ' Presto/2.7.62 Version/11.00';
        $bt = new HTML_QuickForm2_Element_BackgroundText();
        $bt->setBackgroundText('foo');
        $s = (string)$bt;
        $this->assertContains('"foo', $s);
        $this->assertNotContains('"foo"', $s);
    }

    public function testDetectBrokenBrowsersBadAgents()
    {
        //IE6
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/4.0 (compatible; MSIE 6.0)';
        $bt = new HTML_QuickForm2_Element_BackgroundText();
        $bt->setBackgroundText('foo');
        $s = (string)$bt;
        $this->assertContains('"foo"', $s);

        //IE7
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/4.0 (compatible; MSIE 7.0;'
            . ' Windows NT 6.1; Trident/4.0; SLCC2; .NET CLR 2.0.50727;'
            . '.NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0;'
            . ' Tablet PC 2.0)';
        $bt = new HTML_QuickForm2_Element_BackgroundText();
        $bt->setBackgroundText('foo');
        $s = (string)$bt;
        $this->assertContains('"foo"', $s);

        //IE8
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/4.0 (compatible; MSIE 8.0;'
            . ' Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 1.1.4322;'
            . ' .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648)';
        $bt = new HTML_QuickForm2_Element_BackgroundText();
        $bt->setBackgroundText('foo');
        $s = (string)$bt;
        $this->assertContains('"foo"', $s);
    }

    /**
     * Test if deactivating the browser detection in the constructor works.
     */
    public function testDetectBrokenBrowsersDeactivate()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/4.0 (compatible; MSIE 8.0;'
            . ' Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 1.1.4322;'
            . ' .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648)';
        $bt = new HTML_QuickForm2_Element_BackgroundText(
            'test', null, array('detectBrokenBrowsers' => false)
        );
        $bt->setBackgroundText('foo');
        $s = (string)$bt;
        $this->assertContains('"foo', $s);
        $this->assertNotContains('"foo"', $s);
    }
}

?>