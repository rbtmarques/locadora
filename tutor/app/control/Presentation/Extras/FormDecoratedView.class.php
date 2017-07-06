<?php
/**
 * FormDecoratedView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormDecoratedView extends TPage
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // load the styles
        TPage::include_css('app/resources/formdecorator.css');
        
        // create the HTML Renderer
        $html = new THtmlRenderer('app/resources/formdecorator.html');
        
        // create the form using TQuickForm class
        $this->form = new TQuickForm;
        
        // create the form fields
        $id          = new TEntry('id');
        $description = new TEntry('description');
        $date        = new TDate('date');
        $time        = new TEntry('time');
        $number      = new TEntry('number');
        $text        = new TText('text');
        $description->setTip('Type the description here...');
        
        $date->setMask('dd/mm/yyyy'); // define date mask
        $time->setMask('99:99');
        $number->setNumericMask(2, ',', '.', TRUE); // define numeric input
        
        // add the fields inside the form
        $this->form->addQuickField('Id',    $id,    40);
        $this->form->addQuickField('Description', $description, 200);
        $this->form->addQuickField('Date (dd/mm/yyyy)', $date, 80);
        $this->form->addQuickField('Time (99:99)', $time, 60);
        $this->form->addQuickField('Numeric Input (9.999,99)', $number, 100);
        $this->form->addQuickField('Text', $text, 120);
        $text->setSize(200,100);
        // define the form action 
        $this->form->addQuickAction('Save', new TAction(array($this, 'onSave')), 'ico_save.png');
        
        // replace the main section variables
        $replace = array('form'=> $this->form);
        $html->enableSection('main', $replace);
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($html);

        parent::add($vbox);
    }
    
    /**
     * Show the form content
     */
    public function onSave($param)
    {
        $data = $this->form->getData();
        
        $this->form->setData($data); // put the data back to the form
        
        // creates a string with the form element's values
        $message = 'Id: '           . $data->id . '<br>';
        $message.= 'Description : ' . $data->description . '<br>';
        $message.= 'Date : '        . $data->date . '<br>';
        $message.= 'Time : '        . $data->time . '<br>';
        $message.= 'Number : '      . $data->number . '<br>';
        $message.= 'Text : '        . $data->text . '<br>';
        
        // show the message
        new TMessage('info', $message);
    }
}
?>