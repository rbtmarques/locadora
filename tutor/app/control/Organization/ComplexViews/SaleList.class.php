<?php
/**
 * SaleList
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SaleList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        parent::setDatabase('samples');            // defines the database
        parent::setActiveRecord('Sale');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('date', '=', 'date', function($value) {
            return TDate::date2us($value);
        }); // filterField, operator, formField, transformFunction
        
        parent::addFilterField('customer_id', '=', 'customer_id', function($value) {
            return array_keys($value);
        }); // filterField, operator, formField, transformFunction
        
        // creates the form
        $this->form = new TQuickForm('form_search_Sale');
        $this->form->class = 'tform'; // change CSS class
        $this->form->setFormTitle('Sale');
        
        // create the form fields
        $id = new TEntry('id');
        $date = new TDate('date');
        $customer_id = new TDBMultiSearch('customer_id', 'samples', 'Customer', 'id', 'name');
        $customer_id->setMaxSize(1);
        $customer_id->setMinLength(1);
        $date->setMask( 'dd/mm/yyyy' );
        
        // add the fields
        $this->form->addQuickField('Id', $id,  '20%' );
        $this->form->addQuickField('Date', $date,  '50%' );
        $this->form->addQuickField('Customer', $customer_id,  '70%' );
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Sale_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $this->form->addQuickAction(_t('New'),  new TAction(array('SaleForm', 'onEdit')), 'bs:plus-sign green');
        
        // creates a DataGrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->style = 'width: 100%';
        
        // creates the datagrid columns
        $column_id       = new TDataGridColumn('id', 'Id', 'center', '10%');
        $column_date     = new TDataGridColumn('date', 'Date', 'center', '20%');
        $column_customer = new TDataGridColumn('customer->name', 'Customer', 'left', '50%');
        $column_total    = new TDataGridColumn('total', 'Total', 'right', '20%');
        
        // define format function
        $format_value = function($value) {
            if (is_numeric($value)) {
                return 'R$ '.number_format($value, 2, ',', '.');
            }
            return $value;
        };
        
        $column_total->setTransformer( $format_value );
        
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_date);
        $this->datagrid->addColumn($column_customer);
        $this->datagrid->addColumn($column_total);
        
        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_date = new TAction(array($this, 'onReload'));
        $order_date->setParameter('order', 'date');
        $column_date->setAction($order_date);
        
        // define the transformer method over image
        $column_date->setTransformer( function($value, $object, $row) {
            $date = new DateTime($value);
            return $date->format('d/m/Y');
        });

        // create EDIT action
        $action_edit = new TDataGridAction(array('SaleForm', 'onEdit'));
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-fw');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-fw');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);
        
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
}
