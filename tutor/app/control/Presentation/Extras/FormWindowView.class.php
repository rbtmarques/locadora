<?php
/**
 * FormWindowView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormWindowView extends TPage
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // create the form using TQuickForm class
        $this->form = new TQuickForm;
        $this->form->class = 'tform';
        $this->form->setFormTitle('Form open window');
        
        // create the form fields
        $id          = new TEntry('id');
        $description = new TEntry('description');
        $date1       = new TDate('date1');
        $date2       = new TDate('date2');
        
        $date1->setSize(100);
        $date2->setSize(100);
        
        // add the fields inside the form
        $this->form->addQuickField('Id',    $id,    40);
        $this->form->addQuickField('Description', $description, 280);
        $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2));
        
        // define the form action 
        $this->form->addQuickAction('Send', new TAction(array($this, 'onSend')), 'fa:check-circle-o');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
    }
    
    /**
     * Send data
     */
    public function onSend($param)
    {
        $data = $this->form->getData(); // optional parameter: active record class
        
        // put the data back to the form
        $this->form->setData($data);
        
        // creates a string with the form element's values
        $message = 'Id: '           . $data->id . '<br>';
        $message.= 'Description : ' . $data->description . '<br>';
        $message.= 'Date1: '        . $data->date1 . '<br>';
        $message.= 'Date2: '        . $data->date2 . '<br>';
        
        $window = TWindow::create('Title', 0.5, 0.5);
        $window->add($message);
        $window->show();
    }
}
