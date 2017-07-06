<?php
/**
 * FormInlineBootstrapView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormInlineBootstrapView extends TPage
{
    private $form;
    private $alertBox;
    
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // create the form using TQuickForm class
        $this->form = new BootstrapFormWrapper( new TQuickForm, 'form-inline' );
        $this->form->setFormTitle('Quick form');
        
        // create the form fields
        $id          = new TEntry('id');
        $description = new TEntry('description');
        $list        = new TCombo('list');
        
        $combo_items = array();
        $combo_items['a'] ='Item a';
        $combo_items['b'] ='Item b';
        $combo_items['c'] ='Item c';
        
        $list->addItems($combo_items);
        
        // add the fields inside the form
        $this->form->addQuickField($l1=new TLabel('Id'),    $id,    40);
        $this->form->addQuickField($l2=new TLabel('Description'), $description, 180);
        $this->form->addQuickField($l3=new TLabel('List'), $list, 120);
        
        $l1->setSize(30);
        $l2->setSize(100);
        $l3->setSize(50);
        
        // define the form action
        $btn = $this->form->addQuickAction('Send', new TAction(array($this, 'onSend')), 'fa:check-circle-o');
        $btn->class = 'btn btn-success';
        
        $panel = new TPanelGroup('Bootstrap Inline Form');
        $panel->add($this->form);
        
        $this->alertBox = new TElement('div');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->alertBox);
        $vbox->add($panel);

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
        $message.= 'List : '        . $data->list . '<br>';
        
        // show the message
        new TMessage('info', $message);
    }
}
