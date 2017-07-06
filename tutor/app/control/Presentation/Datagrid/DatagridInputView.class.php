<?php
/**
 * DatagridInputView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class DatagridInputView extends TPage
{
    private $form;
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->form = new TForm;
        
        // creates one datagrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->enablePopover('hint', 'Type address of <b>{name}</b>');
        $this->datagrid->disableDefaultClick();
        
        $this->form->add($this->datagrid);
        
        // add the columns
        $this->datagrid->addQuickColumn('Code',    'code',    'right', '10%');
        $this->datagrid->addQuickColumn('Name',    'name',    'left',  '30%');
        $this->datagrid->addQuickColumn('Address', 'address', 'left',  '30%');
        $this->datagrid->addQuickColumn('Phone',   'fone',    'left',  '30%');
        
        // creates the datagrid model
        $this->datagrid->createModel();
        
        // creates the action button
        $button1=new TButton('action1');
        // define the button action
        $button1->setAction(new TAction(array($this, 'onSave')), 'Save');
        $button1->setImage('fa:save green');
        
        $this->form->addField($button1);
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        $vbox->add($button1);

        parent::add($vbox);
    }
    
    /**
     * Load the data into the datagrid
     */
    function onReload()
    {
        $this->datagrid->clear();
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code     = '1';
        $item->name     = 'Fábio Locatelli';
        $item->address  = new TEntry('address1');
        $item->fone     = '1111-1111';
        $item->address->setValue('Rua Expedicionario');
        $this->datagrid->addItem($item);
        $this->form->addField($item->address); // important!
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code     = '2';
        $item->name     = 'Julia Haubert';
        $item->address  = new TEntry('address2');
        $item->fone     = '2222-2222';
        $item->address->setValue('Rua Expedicionario');
        $this->datagrid->addItem($item);
        $this->form->addField($item->address); // important!
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code     = '3';
        $item->name     = 'Carlos Ranzi';
        $item->address  = new TEntry('address3');
        $item->fone     = '3333-3333';
        $item->address->setValue('Rua Oliveira');
        $this->datagrid->addItem($item);
        $this->form->addField($item->address); // important!
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code     = '4';
        $item->name     = 'Daline DallOglio';
        $item->address  = new TEntry('address4');
        $item->fone     = '4444-4444';
        $item->address->setValue('Rua Oliveira');
        $this->datagrid->addItem($item);
        $this->form->addField($item->address); // important!
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
        
        $message = '';
        foreach ($this->form->getFields() as $name => $field)
        {
            if ($field instanceof TEntry)
            {
                $message .= " $name: " . $field->getValue() . '<br>';
            }
        }
        
        // show the message
        new TMessage('info', $message);
    }
    
    /**
     * shows the page
     */
    function show()
    {
        $this->onReload();
        parent::show();
    }
}
