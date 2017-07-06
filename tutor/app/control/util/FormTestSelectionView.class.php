<?php
/**
 * FormTestSelectionView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormTestSelectionView extends TPage
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
        $this->form = new TForm;
        $this->form->class = 'tform';
        
        // creates the form field container
        $table = new TTable;
        $table->width = '100%';
        $this->form->add($table);
        
        // title row
        $table->addRowSet( new TLabel('Static selections'), '' )->class='tformtitle';
        
        // create the form fields
        $entry    = new TEntry('entry');
        $radio    = new TRadioGroup('radio');
        $check    = new TCheckGroup('check');
        $combo    = new TCombo('combo');
        $select   = new TSelect('select');
        $search   = new TMultiSearch('search');
        $autocomp = new TEntry('autocomplete');
        $date     = new TDate('date');
        $file     = new TFile('file');
        $text     = new TText('text');
        $spinner  = new TSpinner('spinner');
        $result   = new THtmlEditor('result');
        
        $spinner->setRange(1,100,1);
        
        $entry->setExitAction(new TAction(array($this, 'onExitAction')));
        $radio->setChangeAction(new TAction(array($this, 'onChangeAction')));
        $check->setChangeAction(new TAction(array($this, 'onChangeAction')));
        $combo->setChangeAction(new TAction(array($this, 'onChangeAction')));
        $select->setChangeAction(new TAction(array($this, 'onChangeAction')));
        $search->setChangeAction(new TAction(array($this, 'onChangeAction')));
        $autocomp->setExitAction(new TAction(array($this, 'onChangeAction')));
        $date->setExitAction(new TAction(array($this, 'onChangeAction')));
        $file->setCompleteAction(new TAction(array($this, 'onChangeAction')));
        $text->setExitAction(new TAction(array($this, 'onChangeAction')));
        $spinner->setExitAction(new TAction(array($this, 'onChangeAction')));
        
        $entry->setTip('Type: a,c');
        $radio->setLayout('horizontal');
        $check->setLayout('horizontal');
        $combo->setSize(100);
        $select->setSize(200,70);
        $search->setSize(300,40);
        $text->setSize(300,40);
        $result->setSize(500,200);
        
        $items = array();
        $items['a'] ='Item a';
        $items['b'] ='Item b';
        $items['c'] ='Item c';
        
        $radio->addItems($items);
        $check->addItems($items);
        $combo->addItems($items);
        $select->addItems($items);
        $search->addItems($items);
        $autocomp->setCompletion( array_values( $items ));
        
        foreach ($radio->getLabels() as $key => $label)
        {
            $label->setTip("Radio $key");
        }
        foreach ($check->getLabels() as $key => $label)
        {
            $label->setTip("Check $key");
        }
        
        // define default values
        $search->setMinLength(3);
        
        // add the fields to the table
        $table->addRowSet(new TLabel('Entry:'),       $entry );
        $table->addRowSet(new TLabel('TRadioGroup:'), $radio );
        $table->addRowSet(new TLabel('TCheckGroup:'), $check );
        $table->addRowSet(new TLabel('TCombo:'),      $combo );
        $table->addRowSet(new TLabel('TSelect:'),     $select );
        $table->addRowSet(new TLabel('TMultiSearch:'),$search );
        $table->addRowSet(new TLabel('Autocomplete:'),$autocomp );
        $table->addRowSet(new TLabel('Date:'),        $date );
        $table->addRowSet(new TLabel('Text:'),        $text );
        $table->addRowSet(new TLabel('Spinner:'),     $spinner );
        $table->addRowSet(new TLabel('File:'),        $file );
        $table->addRowSet(new TLabel('Result:'),      $result );
        
        // define wich are the form fields
        $this->form->setFields(array($entry, $radio, $check, $combo, $select, $search, $autocomp, $date, $file, $text, $spinner, $result));
        
        parent::add($this->form);
    }
    
    public static function onExitAction($param)
    {
        $value = $param['entry'];
        $obj = new stdClass;
        $obj->radio = substr($value,0,1);
        $obj->check = $value;
        $obj->combo = substr($value,0,1);
        $obj->select = $value;
        $obj->autocomplete = $value;
        // $obj->search = array(substr($value,0,1) => $value);
        $obj->date = date('Y-m-d');
        $obj->text = $value;
        
        TForm::sendData('my_form', $obj);
    }
    
    public static function onChangeAction($param)
    {
        unset($param['result']);
        
        $output = '';
        foreach ($param as $key => $value)
        {
            if (is_string($value))
            {
                $output .= "<b>$key</b> => $value <br>";
            }
            else
            {
                $svalue = json_encode($value);
                $output .= "<b>$key</b> => $svalue <br>";
            }
        }
        
        $obj = new stdClass;
        $obj->result = $output;
        TForm::sendData('my_form', $obj);
    }
}
