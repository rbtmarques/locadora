<?php
/**
 * FormStaticSelectionView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormStaticSelectionView extends TPage
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
        $this->form->setFormTitle('Static selections');
        
        // create the form fields
        $radio    = new TRadioGroup('radio');
        $radio2   = new TRadioGroup('radio2');
        $check    = new TCheckGroup('check');
        $check2   = new TCheckGroup('check2');
        $combo    = new TCombo('combo');
        $combo2   = new TCombo('combo2');
        $select   = new TSelect('select');
        $search   = new TMultiSearch('search');
        $search2  = new TMultiSearch('search2');
        $autocomp = new TEntry('autocomplete');
        
        $radio->setLayout('horizontal');
        $radio2->setLayout('horizontal');
        $check->setLayout('horizontal');
        $check2->setLayout('horizontal');
        $radio2->setUseButton();
        $check2->setUseButton();
        $combo2->enableSearch();
        $search2->setMaxSize(1);
        $search->setMinLength(1);
        $search2->setMinLength(1);
        
        $items = array();
        $items['a'] ='Item a';
        $items['b'] ='Item b';
        $items['c'] ='Item c';
        
        $radio->addItems($items);
        $check->addItems($items);
        $radio2->addItems($items);
        $check2->addItems($items);
        $combo->addItems($items);
        $combo2->addItems($items);
        $select->addItems($items);
        $search->addItems($items);
        $search2->addItems($items);
        
        $autocomp->setCompletion( array_values( $items ));
        
        foreach ($radio2->getLabels() as $key => $label)
        {
            $label->setTip("Radio $key");
        }
        foreach ($check2->getLabels() as $key => $label)
        {
            $label->setTip("Check $key");
        }
        
        // define default values
        $radio->setValue('b');
        $radio2->setValue('b');
        $check->setValue( array('a', 'c'));
        $check2->setValue( array('a', 'c'));
        $combo->setValue('b');
        $combo2->setValue('b');
        $select->setValue(array('a', 'b'));
        $search->setValue(array('b'=>'Item b'));
        
        $this->form->addQuickField('TRadioGroup:', $radio );
        $this->form->addQuickField('TCheckGroup:', $check );
        $this->form->addQuickField('TRadioGroup (use button):', $radio2 );
        $this->form->addQuickField('TCheckGroup (use button):', $check2 );
        $this->form->addQuickField('TCombo:',      $combo, 300 );
        $this->form->addQuickField('TCombo (as multisearch):', $combo2, 300);
        $this->form->addQuickField('TSelect:',     $select );
        $this->form->addQuickField('TMultiSearch (minlen 1):',$search );
        $this->form->addQuickField('TMultiSearch (maxsize 1, minlen 1):',$search2, 300 );
        $this->form->addQuickField('Autocomplete:',$autocomp, 300 );
        
        $this->form->addQuickAction('Send', new TAction(array($this, 'onSend')), 'fa:check-circle-o green');
        
        $select->setSize(300,50);
        $search->setSize(300,30);
        
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
        $message.= 'Radio (button) : ' . $data->radio2 . '<br>';
        $message.= 'Check (button) : ' . print_r($data->check2, TRUE) . '<br>';
        $message.= 'Combo : ' . $data->combo . '<br>';
        $message.= 'Combo2 : '. $data->combo2 . '<br>';
        $message.= 'Select: ' . print_r($data->select, TRUE) . '<br>';
        $message.= 'Search: ' . print_r($data->search, TRUE) . '<br>';
        $message.= 'Search2: '. print_r($data->search2, TRUE) . '<br>';
        $message.= 'Autocomplete: ' . $data->autocomplete;
        
        // show the message
        new TMessage('info', $message);
    }
}
