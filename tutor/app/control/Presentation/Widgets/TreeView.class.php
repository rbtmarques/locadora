<?php
/**
 * TreeView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TreeView extends TPage
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // define the tree data
        $data = array();
        $data['Brazil']['RS'][10] = 'Lajeado';
        $data['Brazil']['RS'][20] = 'Cruzeiro do Sul';
        $data['Brazil']['RS'][30] = 'Porto Alegre';
        $data['Brazil']['SP'][40] = 'São Paulo';
        $data['Brazil']['SP'][50] = 'Osasco';
        $data['Brazil']['MG'][60] = 'Belo Horizonte';
        $data['Brazil']['MG'][70] = 'Ipatinga';
        
        // scroll around the treeview
        $scroll = new TScroll;
        $scroll->setSize(300, 200);
        
        // creates the treeview
        $treeview = new TTreeView;
        $treeview->setSize(300);
        $treeview->setItemIcon('ico_file.png');
        $treeview->setItemAction(new TAction(array($this, 'onSelect')));
        $treeview->fromArray($data); // fill the treeview
        $scroll->add($treeview);
        
        // creates a simple form
        $this->form = new TQuickForm('form_test');
        
        // creates the notebook around the form
        $notebook = new TNotebook(350, 100);
        $notebook->appendPage('Quick form component', $this->form);
        
        // creates the form fields
        $key   = new TEntry('key');
        $value = new TEntry('value');
        $this->form->addQuickField('Key',   $key,   50);
        $this->form->addQuickField('Value', $value, 170);
        
        // creates a table to wrap the treeview and the form
        $table = new TTable;
        $row = $table->addRow();
        $cell=$row->addCell($scroll)->valign='top';
        $cell=$row->addCell($notebook)->valign='top';
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($table);

        parent::add($vbox);
    }
    
    /**
     * Executed when the user clicks at a tree node
     * @param $param URL parameters containing key and value
     */
    public static function onSelect($param)
    {
        $obj = new StdClass;
        $obj->key = $param['key']; // get node key (index)
        $obj->value = $param['value']; // get node value (contend)
        
        // fill the form with this object attributes
        TForm::sendData('form_test', $obj);
    }
}
