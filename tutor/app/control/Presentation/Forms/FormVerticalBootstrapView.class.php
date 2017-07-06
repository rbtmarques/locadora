<?php
/**
 * FormVerticalBootstrapView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormVerticalBootstrapView extends TPage
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
        $this->form = new BootstrapFormWrapper( new TQuickForm, 'form-vertical' ); // HERE CHANGE CLASS
        
        // Fields by row
        $this->form->setFieldsByRow(1);
        $this->form->setFormTitle('Quick form');
        
        // create the form fields
        $id          = new TEntry('id');
        $description = new TEntry('description');
        $date        = new TDate('date');
        $color       = new TColor('color');
        $list        = new TCombo('list');
        $text        = new TText('text');
        
        $combo_items = array();
        $combo_items['a'] ='Item a';
        $combo_items['b'] ='Item b';
        $combo_items['c'] ='Item c';
        
        $list->addItems($combo_items);
        
        // add the fields inside the form
        $this->form->addQuickField('Id',    $id,    40, new TRequiredValidator);
        $this->form->addQuickField('Description', $description, 200, new TRequiredValidator);
        $this->form->addQuickField('Date', $date, 100);
        $this->form->addQuickField('Color', $color, 100);
        $this->form->addQuickField('List', $list, 120);
        $this->form->addQuickField('Text', $text, 120);
        $text->setSize(200, 50);
        
        // define the form action
        $btn = $this->form->addQuickAction('Send', new TAction(array($this, 'onSend')), 'fa:check-circle-o');
        $btn->class = 'btn btn-success';
        
        $panel = new TPanelGroup('Bootstrap Vertical Form');
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
        try
        {
            $data = $this->form->getData(); // optional parameter: active record class
            
            $this->form->validate();
            
            // put the data back to the form
            $this->form->setData($data);
            
            // creates a string with the form element's values
            $message = 'Id: '           . $data->id . '<br>';
            $message.= 'Description : ' . $data->description . '<br>';
            $message.= 'Date1: '        . $data->date . '<br>';
            $message.= 'Color : '       . $data->color . '<br>';
            $message.= 'List : '        . $data->list . '<br>';
            $message.= 'Text : '        . $data->text . '<br>';
            
            // show the message
            new TMessage('info', $message);
        }
        catch (Exception $e)
        {
            $this->alertBox->add( new TAlert('danger', $e->getMessage()) );
        }
    }
}
