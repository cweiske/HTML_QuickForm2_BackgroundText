<?xml version="1.0" encoding="utf-8"?>
<html>
 <head>
  <title>HTML_QuickForm2_Element_BackgroundText demo</title>
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
require_once 'BackgroundText.php';

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
  <ul>
   <li><a href="BackgroundText.phps">BackgroundText source</a></li>
   <li><a href="demo-backgroundtext.phps">demo source</a></li>
  </ul>
  <p>
   Try to type in the exact text and submit it - it will still be distinguished
   from the default one: 'Type your search terms here'.
  </p>
 </body>
</html>