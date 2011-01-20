<?php
/**
 * HTML_QuickForm2_BackgroundText package.
 *
 * PHP version 5
 *
 * @category HTML
 * @package  HTML_QuickForm2_BackgroundText
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.gnu.org/copyleft/lesser.html LGPL License
 * @version  SVN: $Id: InputText.php 294057 2010-01-26 21:10:28Z avb $
 * @link     http://pear.php.net/package/HTML_QuickForm2_BackgroundText
 */

require_once 'HTML/QuickForm2/Element/InputText.php';

/**
 * Text input element with pre-set text that vanishes when
 * the user focuses it. Setting a special class is also supported.
 *
 * Example:
 * before:
 *   Name:   [John Do|   ]
 *   E-Mail: [Please type your email address]
 *
 * after:
 *   Name:   [John Doe   ]
 *   E-Mail: [|                             ]
 *
 *  The pre-set "background text" is distinguished from user-inputted text
 * with a special invisible character that gets appended to it.
 * Even if the user writes the same text in the field as the background
 * text, it gets detected as user input because of this special character.
 *  Unfortunately, some broken browsers display this invisible char
 * which does not look nice. The class automatically detects such broken
 * browsers and removes the invisible character. To deactivate
 * the browser detection, pass array('detectBrokenBrowsers' => false)
 * as data parameter on element creation.
 *
 * @category HTML
 * @package  HTML_QuickForm2_BackgroundText
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.gnu.org/copyleft/lesser.html LGPL License
 * @link     http://pear.php.net/package/HTML_QuickForm2_BackgroundText
 */
class HTML_QuickForm2_Element_BackgroundText
    extends HTML_QuickForm2_Element_InputText
{
    /**
     * Background text to use
     *
     * @var string
     */
    protected $btText  = null;

    /**
     * Element class to use when background text is active
     *
     * @var string
     */
    protected $btClass = null;

    /**
     * Invisible character to distiguish user content from
     * our default background text.
     *
     * @var string
     */
    protected $btInvisibleChar = "\342\201\243";

    /**
     * Class constructor
     *
     * @param string $name       Element name
     * @param mixed  $attributes Attributes (either a string or an array)
     * @param array  $data       Element data (label, options and data
     *                           used for element creation).
     *                           Set 'detectBrokenBrowsers' to "false" to prevent
     *                           automatic detection of broken browsers.
     */
    public function __construct($name = null, $attributes = null, $data = null)
    {
        $bDetect = true;
        if (isset($data['detectBrokenBrowsers'])) {
            $bDetect = (bool)$data['detectBrokenBrowsers'];
            unset($data['detectBrokenBrowsers']);
        }
        parent::__construct($name, $attributes, $data);

        if ($bDetect) {
            $this->detectBrokenBrowsers();
        }

        $this->addFilter(array($this, 'filterBackgroundTextFromValue'));
    }



    /**
     * Filters the background text from the value.
     *
     * @param string $value Raw value from i.e. POST data
     *
     * @return string Filtered value without background text.
     */
    public function filterBackgroundTextFromValue($value)
    {
        if ($value === $this->btText) {
            $value = '';
        }
        return $value;
    }



    /**
     * Sets the background text to show when the text element is
     * empty and not focused
     *
     * @param string $text Background text to set
     *
     * @return HTML_QuickForm2_BackgroundText This object
     */
    public function setBackgroundText($text)
    {
        var_dump('set');
        //clear out old background text from value
        if (isset($this->attributes['value'])
            && $this->attributes['value'] == $this->btText
        ) {
            $this->attributes['value'] = '';
            $this->attributes['class'] = '';
        }

        //we add a invisible separator character to distiguish
        // user content from our default text
        if ($text != '') {
            $this->btText = $text . $this->btInvisibleChar;
        } else {
            $this->btText = null;
        }

        return $this;
    }



    /**
     * Set the invisible char that is used to distinguish
     * between text written by users and the background text.
     *
     * Some browsers display the invisible char, so you might want
     * to disable it.
     *
     * @param string $char Invisible char
     *
     * @return HTML_QuickForm2_Element_BackgroundText This object
     */
    public function setInvisibleChar($char)
    {
        $btText = substr($this->btText, 0, -strlen($this->btInvisibleChar));
        $this->btInvisibleChar = $char;
        if ($btText) {
            $this->setBackgroundText($btText);
        }
    }



    /**
     * Sets the HTML class to use when the text element is
     * empty and not focused
     *
     * @param string $class HTML class to set when the element
     *                      is not focused
     *
     * @return HTML_QuickForm2_BackgroundText This object
     */
    public function setBackgroundClass($class)
    {
        $this->btClass = $class;

        return $this;
    }



    /**
     * Updates the attributes array before rendering to prepare
     * for the rendering process.
     *
     * @return void
     */
    protected function btUpdateAttributes()
    {
        if ($this->btText == '') {
            //deactivate it
            unset($this->attributes['onfocus']);
            unset($this->attributes['onblur']);
            return;
        }

        $jBtText   = json_encode((string)$this->btText);
        $jBtClass  = json_encode($this->btClass);
        $jOldClass = json_encode('');
        if (isset($this->attributes['class'])) {
            $jOldClass = json_encode($this->attributes['class']);
        }

        $this->attributes['onfocus']
            = 'if (this.value == ' . $jBtText . ') {'
            . 'this.value = "";'
            . 'this.className = ' . $jOldClass . ';'
            . '}';
        $this->attributes['onblur']
            = 'if (this.value == "") {'
            . 'this.value = ' . $jBtText . ';'
            . 'this.className = ' . $jBtClass . ';'
            . '}';

        //default when loading the form
        if (!isset($this->attributes['value'])
            || !$this->attributes['value']
        ) {
            $this->attributes['value'] = $this->btText;
        }

        if ($this->attributes['value'] == $this->btText) {
            $this->attributes['class'] = $this->btClass;
        }
    }



    /**
     * Some browsers like the IE6, IE7 and IE8 display the invisible character.
     * We simply remove the invisible char for them.
     *
     * @return void
     */
    protected function detectBrokenBrowsers()
    {
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            return;
        }
        if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.')
            || false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.')
            || false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.')
        ) {
            $this->setInvisibleChar(null);
        }
    }



    /**
     * Returns the rendered HTML element.
     * Updates the background text attributes before rendering.
     *
     * @return string HTML code
     */
    public function __toString()
    {
        $this->btUpdateAttributes();
        return parent::__toString();
    }
}
?>