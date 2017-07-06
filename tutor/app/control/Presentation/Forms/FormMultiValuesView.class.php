<?php
/**
 * FormMultiValuesView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormMultiValuesView extends TPage
{
    private $form;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        // create form and table container
        $this->form = new TForm;
        $table = new TTable;
        $table->addSection('thead');
        $table->addRowSet( new TLabel('Multisearch'), new TLabel('Entry mask'), new TLabel('Date'), new TLabel('Combo') );
        
        $this->form->add($table);
        
        $table->addSection('tbody');
        for ($n=1; $n<=5; $n++)
        {
            $search = new TMultiSearch('search[]');
            $search->setMaxSize(1);
            $search->setMinLength(1);
            $search->addItems( ['1'=>'Item a', '2' => 'Item b', '3'=>'Item c', '4' => 'Item d', '5'=> 'Item e'] );
            
            $entry = new TEntry('entry[]');
            $entry->setNumericMask(2,',','.', true);
            $entry->setSize(120);
            
            $date = new TDate('date[]');
            $date->setSize(120);
            
            $combo = new TCombo('combo[]');
            $combo->addItems([ '1'=>'One', '2'=>'Two', '3' => 'Three', '4' => 'Four', '5' => 'Five' ]);
            $combo->setSize(120);
            
            $this->form->addField($search);
            $this->form->addField($entry);
            $this->form->addField($date);
            $this->form->addField($combo);
            
            // create delete button
            $del = new TImage('fa:trash-o red');
            $del->onclick = 'ttable_remove_row(this)';
            $table->addRowSet( $search, $entry, $date, $combo, $del );
        }
        
        // create add button
        $add = new TButton('clone');
        $add->setLabel('Add');
        $add->setImage('fa:plus-circle green');
        $add->addFunction('ttable_clone_previous_row(this)');
        
        // create save button
        $save = TButton::create('save', array($this, 'onSave'), 'Save', 'fa:save blue');
        $this->form->addField($save);
        
        // add buttons in table
        $table->addRowSet([$add, $save]);
        
        $panel = new TPanelGroup('Multivalues');
        $panel->add($this->form);
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($panel);

        parent::add($vbox);
    }
    
    /**
     * Test save
     */
    public static function onSave($param)
    {
        // show form values inside a window
        $win = TWindow::create('test', 0.6, 0.8);
        $win->add( '<pre>'.str_replace("\n", '<br>', print_r($param, true) ).'</pre>'  );
        $win->show();
    }
}
