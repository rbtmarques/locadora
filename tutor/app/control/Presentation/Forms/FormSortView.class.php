<?php
/**
 * FormSortView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormSortView extends TPage
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        $this->form = new TForm;
        
        $list1 = new TSortList('list1');
        $list2 = new TSortList('list2');
        
        $list1->addItems( array('1' => 'One', '2' => 'Two', '3' => 'Three') );
        $list2->addItems( array('a' => 'A', 'b' => 'B', 'c' => 'C') );
        
        $list1->setSize(200,100);
        $list2->setSize(200,100);
        
        $list1->connectTo($list2);
        $list2->connectTo($list1);
        
        // connect the change method
        $list1->setChangeAction(new TAction(array($this, 'onChangeAction')));
        
        // creates the action button
        $button1 = new TButton('action1');
        $button1->setAction(new TAction(array($this, 'onSend')), 'Send');
        $button1->setImage('fa:check-circle-o');
        
        $table = new TTable;
        $row = $table->addRow();
        $row->addCell($list1);
        $row->addCell($list2);
        $table->addRow()->addCell($button1);
        $this->form->setFields(array($list1, $list2, $button1));
        
        $this->form->add($table);
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
    }
    
    /**
     * Executed when a user change some item in list
     */
    public static function onChangeAction($param)
    {
        new TMessage('info', 'Change action<br>'.
                             'List1: ' . implode(',', $param['list1']) . '<br>' .
                             'List2: ' . implode(',', $param['list2']));
    }
    
    /**
     * Send data
     */
    public function onSend($param)
    {
        // get form data
        $data = $this->form->getData();
        
        // put the data back to the form
        $this->form->setData($data);
        
        // creates a string with the form element's values
        $message = 'List 1: '  . implode(',', $data->list1) . '<br>';
        $message.= 'List 2 : ' . implode(',', $data->list2) . '<br>';
        
        // show the message
        new TMessage('info', $message);
    }
}
?>