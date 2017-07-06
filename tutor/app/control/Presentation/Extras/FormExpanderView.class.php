<?php
/**
 * FormExpanderView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormExpanderView extends TPage
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
        $this->form = new TForm;
        $this->form->class = 'tform';
        $this->form->style = 'width:500px';
        
        $table = new TTable;
        $table->width = '100%';
        $this->form->add($table);
        
        $table->addRowSet(new TLabel('Title'),'')->class='tformtitle';
        
        // create the form fields
        $id          = new TEntry('id');
        $description = new TEntry('description');
        $input       = new TEntry('input');
         
        $subfield1 = new TEntry('subfield1');
        $subfield2 = new TEntry('subfield2');
        
        $expander = new TExpander('Click here to see!');
        $expander->setButtonProperty('class', 'btn btn-info btn-sm');
        
        $subtable = new TTable;
        $subtable->style = 'padding:5px';
        $expander->add($subtable);
        $subtable->addRowSet( new TLabel('Subfield1'), $subfield1 );
        $subtable->addRowSet( new TLabel('Subfield2'), $subfield2 );
        
        // add the fields inside the form
        $table->addRowSet( new TLabel('Id'),    $id);
        $table->addRowSet( new TLabel('Description'), $description);
        $table->addRowSet( new TLabel('Another fields'), $expander);
        $table->addRowSet( new TLabel('Input'), $input);
        
        $button = new TButton('save');
        $button->setAction(new TAction(array($this, 'onSave')), 'Save');
        $button->setImage('ico_save.png');
        $table->addRowSet( $button,'')->class='tformaction';
        
        $this->form->setFields(array($id, $description, $input, $subfield1, $subfield2, $button));
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
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
        $message.= 'Subfield1 : '   . $data->subfield1 . '<br>';
        $message.= 'Subfield2 : '   . $data->subfield2 . '<br>';
        $message.= 'Input : '       . $data->input . '<br>';
        
        // show the message
        new TMessage('info', $message);
    }
}
?>