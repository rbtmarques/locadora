<?php
/**
 * FormDBAutoSelectionView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormDBAutoSelectionView extends TPage
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
        $this->form->setFormTitle('Automatic selections');
        
        // create the form fields (name, database, model, key, value)
        $radio  = new TDBRadioGroup('radio', 'samples', 'Category', 'id', 'name');
        $radio2 = new TDBRadioGroup('radio2','samples', 'Category', 'id', '{id} - {name}');
        $check  = new TDBCheckGroup('check', 'samples', 'Category', 'id', 'name');
        $check2 = new TDBCheckGroup('check2','samples', 'Category', 'id', '{id} - {name}');
        $combo  = new TDBCombo('combo', 'samples', 'Category', 'id', 'name');
        $combo2 = new TDBCombo('combo2', 'samples', 'Category', 'id', 'name');
        $select = new TDBSelect('select', 'samples', 'Category', 'id', 'name');
        $search = new TDBMultiSearch('search', 'samples', 'Category', 'id', 'name');
        $search2= new TDBMultiSearch('search2', 'samples', 'City', 'id', 'name');
        $autocomp = new TDBEntry('autocomplete', 'samples', 'Category', 'name');
        
        // add the fields to the form
        $this->form->addQuickField('TDBRadioGroup:', $radio );
        $this->form->addQuickField('TDBCheckGroup:',  $check );
        $this->form->addQuickField('TDBRadioGroup (use button):',  $radio2 );
        $this->form->addQuickField('TDBCheckGroup (use button):',  $check2 );
        $this->form->addQuickField('TDBCombo:',       $combo );
        $this->form->addQuickField('TDBCombo (as multisearch):',   $combo2 );
        $this->form->addQuickField('TDBSelect:',      $select );
        $this->form->addQuickField('TDBMultiSearch (minlen 1):', $search );
        $this->form->addQuickField('TDBMultiSearch (maxsize 1, minlen 1):',$search2 );
        $this->form->addQuickField('TDBEntry:', $autocomp );
        
        // default data:
        $radio->setValue(2);
        $radio2->setValue(2);
        $check->setValue(array(1,3));
        $check2->setValue(array(1,3));
        $combo->setValue(2);
        $combo2->setValue(2);
        $select->setValue(array(1,2));
        $search->setValue(array(1=>'Frequente'));
        
        // another properties
        $radio->setLayout('horizontal');
        $check->setLayout('horizontal');
        $radio2->setLayout('horizontal');
        $check2->setLayout('horizontal');
        $radio2->setUseButton();
        $check2->setUseButton();
        $combo->setSize(300);
        $combo2->setSize(300);
        $combo2->enableSearch();
        $select->setSize(300,50);
        $search->setSize(300,30);
        $search2->setSize(300);
        $autocomp->setSize(300);
        
        // multisearch specific options
        $search->setMinLength(1);
        $search2->setMinLength(1);
        $search2->setMaxSize(1);
        $search->setMask('{name} ({id})');
        $search2->setMask('({id}) {name} - {state->name}');
        $search->setOperator('like');
        
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
        $message.= 'Radio (button) : ' . $data->radio2 . '<br>';
        $message.= 'Check (button) : ' . print_r($data->check2, TRUE) . '<br>';
        $message.= 'Combo : ' . $data->combo . '<br>';
        $message.= 'Combo2 : ' . $data->combo2 . '<br>';
        $message.= 'Select : ' . print_r($data->select, TRUE) . '<br>';
        $message.= 'Search : ' . print_r($data->search, TRUE) . '<br>';
        $message.= 'Search2: '. print_r($data->search2, TRUE) . '<br>';
        $message.= 'Autocomplete: ' . $data->autocomplete;
        
        // show the message
        new TMessage('info', $message);
    }
}
