<?php
/**
 * Template View pattern implementation
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TemplateViewAdvancedView extends TPage
{
    private $quickform;
    
    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        
        // load the styles
        TPage::include_css('app/resources/styles.css');
        
        $link1 = new TActionLink('Action 1', new TAction(array($this, 'onAction1')), 'green', 10, null, 'fa:search');
        $link2 = new TActionLink('Action 2', new TAction(array($this, 'onAction2')), 'blue', 10, null, 'fa:search');
        $link1->class = 'btn btn-default';
        $link2->class = 'btn btn-default';
        
        $hbox_actions = THBox::pack($link1, $link2);
        
        try
        {
            // create the HTML Renderer
            $this->html = new THtmlRenderer('app/resources/content.html');
    
            // define replacements for the main section
            $replace = array();
            $replace['name']    = 'Test name';
            $replace['address'] = 'Test address';
            
            // replace the main section variables
            $this->html->enableSection('main', $replace);
            
            // Table wrapper (form and HTML)
            $table = new TTable;
            $table->addRow()->addCell(new TXMLBreadCrumb('menu.xml', __CLASS__));
            $table->addRow()->addCell($hbox_actions);
            $table->addRow()->addCell($this->html);
            parent::add($table);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Executed when the user clicks at the action1 button
     */
    public function onAction1()
    {
        // create a quickform
        $this->quickform = new TQuickForm('form1');
        $this->quickform->addQuickField('Test1', new TEntry('test1'));
        $this->quickform->addQuickField('Test2', new TEntry('test2'));
        $this->quickform->addQuickAction('Show data', new TAction(array($this, 'onForm1')), 'ico_edit.png');
        
        $replace = array();
        $replace['object']  = $this->quickform;
        $replace['name']    = 'TQuickForm';
        
        // replace the object section variables
        $this->html->enableSection('object', $replace);
        
    }
    
    /**
     * Executed when the user clicks at the show data button
     */
    public function onForm1()
    {
        $this->onAction1();
        $data = $this->quickform->getData();
        $this->quickform->setData($data);
        new TMessage('info', json_encode($data));
    }
    
    /**
     * Executed when the user clicks at the action2 button
     */
    public function onAction2()
    {
        $datagrid = new TQuickGrid;
        $datagrid->setHeight(320);
        
        // add the columns
        $datagrid->addQuickColumn('Code',    'code',    'right', 100);
        $datagrid->addQuickColumn('Name',    'name',    'left',  200);
        
        // creates the datagrid model
        $datagrid->createModel();
        
        $object = new StdClass;
        $object->code = '001';
        $object->name = 'Test 001';
        $datagrid->addItem($object);
        
        $object = new StdClass;
        $object->code = '002';
        $object->name = 'Test 002';
        $datagrid->addItem($object);
        
        $replace = array();
        $replace['object']  = $datagrid;
        $replace['name']    = 'TQuickGrid';
        
        // replace the object section variables
        $this->html->enableSection('object', $replace);
    }
}
