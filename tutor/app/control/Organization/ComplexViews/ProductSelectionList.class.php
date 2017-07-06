<?php
/**
 * ProductSelectionList
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ProductSelectionList extends TStandardList
{
    protected $form;     // search form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('samples');
        parent::setActiveRecord('Product');
        parent::setDefaultOrder('id', 'asc');
        // filter (filter field, operator, form field)
        parent::addFilterField('description', 'like', 'description');
        
        // creates the form
        $this->form = new TQuickForm('form_search_Product');
        $this->form->class = 'tform'; // change style
        $this->form->style = 'display: table;width:100%'; // change style
        $this->form->setFormTitle('Product');
        $description = new TEntry('description');
        $this->form->addQuickField('Description', $description,  '70%' );
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Product_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $this->form->addQuickAction( 'Show results', new TAction(array($this, 'showResults')), 'fa:check-circle-o green' );
        
        // creates a DataGrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->style = 'width: 100%';

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'left');
        $column_description = new TDataGridColumn('description', 'Description', 'left');
        $column_sale_price = new TDataGridColumn('sale_price', 'Sale Price', 'left');
        
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_description);
        $this->datagrid->addColumn($column_sale_price);

        $column_id->setTransformer(array($this, 'formatRow') );
        
        // creates the datagrid actions
        $action1 = new TDataGridAction(array($this, 'onSelect'));
        $action1->setUseButton(TRUE);
        $action1->setButtonClass('btn btn-default');
        $action1->setLabel(AdiantiCoreTranslator::translate('Select'));
        $action1->setImage('fa:check-circle-o blue');
        $action1->setField('id');
        
        // add the actions to the datagrid
        $this->datagrid->addAction($action1);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // vertical box container
        $container = new TVBox;
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($this->datagrid);
        $container->add($this->pageNavigation);
        
        parent::add($container);
    }
    
    /**
     * Save the object reference in session
     */
    public function onSelect($param)
    {
        // get the selected objects from session 
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        
        TTransaction::open('samples');
        $object = new Product($param['id']); // load the object
        if (isset($selected_objects[$object->id]))
        {
            unset($selected_objects[$object->id]);
        }
        else
        {
            $selected_objects[$object->id] = $object->toArray(); // add the object inside the array
        }
        TSession::setValue(__CLASS__.'_selected_objects', $selected_objects); // put the array back to the session
        TTransaction::close();
        
        // reload datagrids
        $this->onReload( func_get_arg(0) );
    }
    
    /**
     * Highlight the selected rows
     */
    public function formatRow($value, $object, $row)
    {
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        
        if ($selected_objects)
        {
            if (in_array( (int) $value, array_keys( $selected_objects ) ) )
            {
                $row->style = "background: #FFD965";
            }
        }
        
        return $value;
    }
    
    /**
     * Show selected records
     */
    public function showResults()
    {
        $datagrid = new BootstrapDatagridWrapper(new TQuickGrid);
        
        $datagrid->addQuickColumn('Id', 'id', 'left');
        $datagrid->addQuickColumn('Description', 'description', 'left');
        $datagrid->addQuickColumn('Sale Price', 'sale_price', 'left');
        
        // create the datagrid model
        $datagrid->createModel();
        
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        ksort($selected_objects);
        if ($selected_objects)
        {
            $datagrid->clear();
            foreach ($selected_objects as $selected_object)
            {
                $datagrid->addItem( (object) $selected_object );
            }
        }
        
        $win = TWindow::create('Results', 0.6, 0.6);
        $win->add($datagrid);
        $win->show();
    }
}
