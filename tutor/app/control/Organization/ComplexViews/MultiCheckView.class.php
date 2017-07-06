<?php
/**
 * MultiCheckView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class MultiCheckView extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formDatagrid;
    protected $postAction;
    
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
        parent::setTransformer( array($this, 'onBeforeLoad') );
        
        // creates the form, with a table inside
        $this->form = new TQuickForm('form_search_Product');
        $this->form->class = 'tform';
        $this->form->style = 'width: 650px';
        $this->form->setFormTitle('Products');
        
        // create the form fields
        $description = new TEntry('description');
        
        // add a row for the filter field
        $this->form->addQuickField('Description', $description, 200);
        $this->form->setData( TSession::getValue('Product_filter_data') );
        $this->form->addQuickAction( _t('Find'), new TAction(array($this, 'onSearch')), 'ico_find.png');
        
        // create the datagrid form wrapper
        $this->formDatagrid = new TForm('datagrid_form');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TQuickGrid);
        $this->datagrid->setHeight(320);
        $this->formDatagrid->add($this->datagrid);
        
        // creates the datagrid columns
        $check       = $this->datagrid->addQuickColumn('Check', 'check', 'center', 40);
        $id          = $this->datagrid->addQuickColumn('ID', 'id', 'center', 40);
        $description = $this->datagrid->addQuickColumn('Description', 'description', 'left', 300);
        $stock       = $this->datagrid->addQuickColumn('Stock', 'stock', 'right', 70);
        $sale_price  = $this->datagrid->addQuickColumn('Sale Price', 'sale_price', 'right', 70);
        $unity       = $this->datagrid->addQuickColumn('Unit', 'unity', 'right', 60);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $this->postAction = new TAction(array($this, 'onPost'));
        $post = new TButton('post');
        $post->setAction($this->postAction);
        $post->setImage('ico_apply.png');
        $post->setLabel('Send');
        
        $this->formDatagrid->addField($post);
        
        // create the page container
        $container = new TVBox;
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($this->formDatagrid);
        $container->add($post);
        $container->add($this->pageNavigation);
        
        parent::add($container);
    }
    
    public function onReload( $param = NULL )
    {
        // update the post action parameters to pass
        // offset, limit, page and other info in
        // order to preserve the pagination after post
        $this->postAction->setParameters($param); // important!
        
        return parent::onReload( $param );
    }
    
    /**
     * Transform the objects before load them into the datagrid
     */
    public function onBeforeLoad( $objects )
    {
        foreach ($objects as $object)
        {
            $object->check = new TCheckButton('check_'.$object->id);
            $object->check->setIndexValue('on');
            $this->form->addField($object->check); // important!
        }
    }
    
    /**
     * Get post data and redirects to the next screen
     */
    public function onPost( $param )
    {
        $data = $this->form->getData();
        $this->form->setData($data);
        $selected_products = array();
        
        foreach ($this->form->getFields() as $name => $field)
        {
            if ($field instanceof TCheckButton)
            {
                $parts = explode('_', $name);
                $id = $parts[1];
                
                if ($field->getValue() == 'on')
                {
                    $selected_products[] = $id;
                }
            }
        }
        TSession::setValue('selected_products', $selected_products );
        TApplication::loadPage('MultiCheck2View');
    }
}
?>