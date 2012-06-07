<?php
/**
 * HTML_QuickForm2_BackgroundText example.
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
header('Content-Type: application/xhtml+xml; charset=utf-8');
?>
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>HTML_QuickForm2_Element_BackgroundText demo</title>
  <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
  <style type="text/css">
.inacttext {
  color: #888;
}
  </style>
 </head>
 <body>
<?php
require_once 'HTML/QuickForm2.php';
require_once 'HTML/QuickForm2/Renderer.php';
require_once __DIR__ . '/../../HTML/QuickForm2/Element/BackgroundText.php';

HTML_QuickForm2_Factory::registerElement(
    'backgroundtext',
    'HTML_QuickForm2_Element_BackgroundText'
);

$form = new HTML_QuickForm2(
    'search', 'post',
    array(),
    true
);

$form->addElement(
    'backgroundtext', 'search',
    array(
        'id'   => 'search',
        'size' => 40
    )
)
->setBackgroundText('Type your search terms here')
->setBackgroundClass('inacttext');

$form->addElement(
    'submit', 'submitted',
    array('id' => 'submit', 'value' => 'Search')
);

if ($form->validate()) {
    echo '<pre>';
    var_dump($form->getValue());
    echo '</pre>';
}

$renderer = HTML_QuickForm2_Renderer::factory('default');
echo $form->render($renderer);
?>
  <p>
   Try to type in the exact text and submit it - it will still be distinguished
   from the default one: 'Type your search terms here'.
  </p>
 </body>
</html>