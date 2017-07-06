<?php
/**
 * FormDBManualSelectionView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormDBManualSelectionView extends TPage
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // create the form
        $this->form = new TQuickForm;
        $this->form->class = 'tform';
        $this->form->setFormTitle('Manual selections');
        
        // create the form fields
        $radio = new TRadioGroup('radio');
        $check = new TCheckGroup('check');
        $combo = new TCombo('combo');
        $select = new TSelect('select');
        $search = new TMultiSearch('search');
        $autocomp = new TEntry('autocomplete');
        
        $search->setMinLength(1);
        $radio->setLayout('horizontal');
        $check->setLayout('horizontal');
        
        // open database transaction
        TTransaction::open('samples');

        // load items from repository
        $collection = Category::all();
        
        // add the combo items
        $items = array();
        foreach ($collection as $object)
        {
            $items[$object->id] = $object->name;
        }
        TTransaction::close();
        
        $radio->addItems($items);
        $check->addItems($items);
        $combo->addItems($items);
        $select->addItems($items);
        $search->addItems($items);
        $autocomp->setCompletion( array_values( $items ));
        
        // add the fields to the form
        $this->form->addQuickField('TRadioGroup:', $radio );
        $this->form->addQuickField('TCheckGroup:', $check );
        $this->form->addQuickField('TCombo:',      $combo, 300 );
        $this->form->addQuickField('TSelect:',     $select );
        $this->form->addQuickField('TMultiSearch (minlen 1):',$search );
        $this->form->addQuickField('Autocomplete:',$autocomp, 300 );
        
        $select->setSize(300,70);
        $search->setSize(300,50);
        
        $this->form->addQuickAction('Send', new TAction(array($this, 'onSend')), 'fa:check-circle-o green');
        
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
        $message = 'Radio : ' . $data->radio . '<br>';
        $message.= 'Check : ' . print_r($data->check, TRUE) . '<br>';
        $message.= 'Combo : ' . $data->combo . '<br>';
        $message.= 'Select : ' . print_r($data->select, TRUE) . '<br>';
        $message.= 'MultiSearch: ' . print_r($data->search, TRUE) . '<br>';
        $message.= 'Autocomplete: ' . $data->autocomplete;
        
        // show the message
        new TMessage('info', $message);
    }
}
