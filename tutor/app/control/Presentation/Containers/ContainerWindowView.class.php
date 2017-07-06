<?php
/**
 * ContainerWindowView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ContainerWindowView extends TWindow
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        parent::setTitle('New window');
        
        // sample values:
        // 600, 400 (absolute size)
        // 0.6, 0.4 (relative size 60%, 40%)
        parent::setSize(500, 300); // (600, 400)
        
        // absolute left, top
        // parent::setPosition( 100, 100 );
        
        // create the form using TQuickForm class
        $this->form = new BootstrapFormWrapper(new TQuickForm);
        $this->form->style = 'width: 90%';
        
        // create the form fields
        $id          = new TEntry('id');
        $description = new TEntry('description');
        $date        = new TDate('date');
        $text        = new TText('text');
        
        // add the fields inside the form
        $this->form->addQuickField('Id',    $id,    40);
        $this->form->addQuickField('Description', $description, 300);
        $this->form->addQuickField('Date', $date, 100);
        $this->form->addQuickField('Text', $text, 300);
        
        $text->setSize(300,100);
        
        // define the form action 
        $this->form->addQuickAction('Save', new TAction(array($this, 'onSave')), 'fa:check-circle-o green');
        
        // add the form inside the page
        parent::add($this->form);
    }
    
    /**
     * Simulates an save button
     * Show the form content
     */
    public function onSave($param)
    {
        $data = $this->form->getData(); // optional parameter: active record class
        
        // put the data back to the form
        $this->form->setData($data);
        
        // creates a string with the form element's values
        $message = 'Id: '           . $data->id . '<br>';
        $message.= 'Description : ' . $data->description . '<br>';
        $message.= 'Date : '        . $data->date . '<br>';
        $message.= 'Text : '        . $data->text . '<br>';
        
        // show the message
        new TMessage('info', $message);
    }
}
