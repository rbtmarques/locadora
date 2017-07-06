<?php
/**
 * Product List
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ProductList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('samples');                // defines the database
        parent::setActiveRecord('Product');            // defines the active record
        parent::setDefaultOrder('id', 'asc');          // defines the default order
        parent::addFilterField('description', 'like'); // add a filter field
        parent::addFilterField('unity', '=');          // add a filter field
        
        // creates the form, with a table inside
        $this->form = new TQuickForm('form_search_Product');
        $this->form->class = 'tform';
        $this->form->setFormTitle('Products');
        $units = array('PC' => 'Pieces', 'GR' => 'Grain');
        
        // create the form fields
        $description = new TEntry('description');
        $unit        = new TCombo('unity');
        $unit->addItems( $units );
        
        // add a row for the filter field
        $this->form->addQuickField('Description', $description, '70%');
        $this->form->addQuickField('Unit', $unit, '70%');
        
        $this->form->setData( TSession::getValue('Product_filter_data') );
        
        $this->form->addQuickAction( _t('Find'), new TAction(array($this, 'onSearch')), 'fa:search blue');
        $this->form->addQuickAction( _t('New'),  new TAction(array('ProductForm', 'onEdit')), 'fa:plus green');
        
        // creates a DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->enablePopover('Image', "<img src='{photo_path}'>");

        // creates the datagrid columns
        $id          = $this->datagrid->addQuickColumn('ID', 'id', 'center', '10%');
        $description = $this->datagrid->addQuickColumn('Description', 'description', 'left', '45%');
        $stock       = $this->datagrid->addQuickColumn('Stock', 'stock', 'right', '15%');
        $sale_price  = $this->datagrid->addQuickColumn('Sale Price', 'sale_price', 'right', '15%');
        $unity       = $this->datagrid->addQuickColumn('Unit', 'unity', 'right', '15%');
        
        // create the datagrid actions
        $edit_action   = new TDataGridAction(array('ProductForm', 'onEdit'));
        $delete_action = new TDataGridAction(array($this, 'onDelete'));
        
        // add the actions to the datagrid
        $this->datagrid->addQuickAction(_t('Edit'), $edit_action, 'id', 'fa:edit blue');
        $this->datagrid->addQuickAction(_t('Delete'), $delete_action, 'id', 'fa:trash red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // create the page container
        $container = new TVBox;
        $container->add(new TXMLBreadCrumb('menu.xml', 'ProductList'));
        $container->add($this->form);
        $container->add($this->datagrid);
        $container->add($this->pageNavigation);
        parent::add($container);
    }
}
