<?php
/**
 * SaleForm Registration
 * @author  <your name here>
 */
class SaleForm extends TPage
{
    protected $form; // form
    protected $formFields;
    protected $dt_venda;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form   = new TForm('form_Sale');
        $panel_master = new TPanelGroup( 'Sale' );
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $this->form->add($panel_master);
        $panel_master->add($vbox);
        
        $frame_general = new TFrame;
        $frame_general->class = 'tframe tframe-custom';
        $frame_general->setLegend('General data');
        $frame_general->style = 'background:whiteSmoke';
        $table_general = new TTable;
        $table_general->width = '100%';
        $frame_general->add($table_general);
        
        // products
        $frame_product = new TFrame;
        $frame_product->class = 'tframe tframe-custom';
        $frame_product->setLegend('Products');
        $table_product = new TTable;
        $frame_product->add($table_product);
        
        $vbox->add( $frame_general );
        $vbox->add( $frame_product );
        
        // master fields
        $id             = new TEntry('id');
        $date           = new TDate('date');
        $customer_id    = new TDBSeekButton('customer_id', 'samples', $this->form->getName(), 'Customer', 'name', 'customer_id', 'customer_name');
        $customer_name  = new TEntry('customer_name');
        $obs            = new TText('obs');
        
        // detail fields
        $product_id     = new TDBSeekButton('product_id', 'samples', $this->form->getName(), 'Product', 'description', 'product_id', 'product_name');
        $product_name   = new TEntry('product_name');
        $sale_price     = new TEntry('product_price');
        $amount         = new TEntry('product_amount');
        $discount       = new TEntry('product_discount');
        $total          = new TEntry('product_total');
        
        $id->setSize(40);
        $date->setSize(100);
        $obs->setSize(400, 50);
        $product_id->setSize(50);
        $customer_id->setSize(50);
        $id->setEditable(false);
        $product_name->setEditable(false);
        $customer_name->setEditable(false);
        $date->addValidation('Date', new TRequiredValidator);
        $customer_id->addValidation('Customer', new TRequiredValidator);
        $product_id->setExitAction(new TAction(array($this,'onProductChange')));

        // general fields
        $table_general->addRowSet( new TLabel('ID'), $id );
        $table_general->addRowSet( $label_date     = new TLabel('Date (*)'), $date );
        $table_general->addRowSet( $label_customer = new TLabel('Customer (*)'), array( $customer_id, $customer_name ) );
        $table_general->addRowSet( new TLabel('Obs'), $obs );
        $label_date->setFontColor('#FF0000');
        
        $add_product = new TButton('add_product');
        $action_product = new TAction(array($this, 'onProductAdd'));
        $add_product->setAction($action_product, 'Register');
        $add_product->setImage('fa:save');
        
        $table_product->addRowSet( $label_product    = new TLabel('Product (*)'), array($product_id,$product_name) );
        $table_product->addRowSet( $label_sale_price = new TLabel('Price (*)'), $sale_price );
        $table_product->addRowSet( $label_amount     = new TLabel('Amount(*)'), $amount );
        $table_product->addRowSet( new TLabel('Discount'), $discount );
        $table_product->addRowSet( $add_product );
        
        $label_product->setFontColor('#FF0000');
        $label_amount->setFontColor('#FF0000');
        $label_sale_price->setFontColor('#FF0000');
        
        $this->product_list = new TQuickGrid;
        $this->product_list->style = "margin-bottom: 10px";
        $this->product_list->disableDefaultClick();
        $this->product_list->addQuickColumn('', 'edit', 'left', 40);
        $this->product_list->addQuickColumn('', 'delete', 'left', 40);
        $this->product_list->addQuickColumn('ID', 'product_id', 'center', 40);
        $this->product_list->addQuickColumn('Product', 'product_name', 'left', 200);
        $this->product_list->addQuickColumn('Amount', 'product_amount', 'left', 30);
        $pr = $this->product_list->addQuickColumn('Price','product_price', 'right', 70);
        $ds = $this->product_list->addQuickColumn('Discount','product_discount', 'right', 60);
        $st = $this->product_list->addQuickColumn('Subtotal','={product_amount} * ( {product_price} - {product_discount} )', 'right', 80);
        $this->product_list->createModel();
        
        $vbox->add( $this->product_list );
        
        $format_value = function($value) {
            if (is_numeric($value)) {
                return 'R$ '.number_format($value, 2, ',', '.');
            }
            return $value;
        };
        
        $pr->setTransformer( $format_value );
        $ds->setTransformer( $format_value );
        $st->setTransformer( $format_value );
        
        $st->setTotalFunction( function($values) {
            return array_sum((array) $values);
        });
        
        // create an action button (save)
        $save_button = TButton::create('save', array($this, 'onSave'),  _t('Save'),  'fa:save green');
        $new_button  = TButton::create('new',  array($this, 'onClear'), _t('Clear'), 'fa:eraser red');
        
        // define form fields
        $this->formFields = array($id,$date, $customer_id, $customer_name, $obs, $product_id, $product_name, $sale_price, $amount, $discount, $total, $add_product, $save_button, $new_button);
        $this->form->setFields( $this->formFields );
        
        $panel_master->addFooter( THBox::pack($save_button, $new_button) );
        
        // create the page container
        $container = new TVBox;
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        parent::add($container);
    }
    
    /**
     * Pre load some data
     */
    public function onLoad($param)
    {
        $data = new stdClass;
        $data->customer_id   = $param['customer_id'];
        $data->customer_name = $param['customer_name'];
        $this->form->setData($data);
    }
    
    
    /**
     * On product change
     */
    static function onProductChange( $params )
    {
        if( isset($params['product_id']) && $params['product_id'] )
        {
            try
            {
                TTransaction::open('samples');
                
                $product = new Product($params['product_id']);
                $fill_data = new StdClass;
                $fill_data->product_price = $product->sale_price;
                TForm::sendData('form_Sale', $fill_data);
                TTransaction::close();
            }
            catch (Exception $e) // in case of exception
            {
                new TMessage('error', '<b>Error</b> ' . $e->getMessage());
                TTransaction::rollback();
            }
        }
    }
    
    
    /**
     * Clear form
     * @param $param URL parameters
     */
    function onClear($param)
    {
        $this->form->clear();
        TSession::setValue('sale_items', array());
        $this->onReload( $param );
    }
    
    /**
     * Add a product into item list
     * @param $param URL parameters
     */
    public function onProductAdd( $param )
    {
        try
        {
            TTransaction::open('samples');
            $data = $this->form->getData();
            
            if( (! $data->product_id) || (! $data->product_amount) || (! $data->product_price) )
                throw new Exception('The fields Product, Amount and Price are required');
            
            $product = new Product($data->product_id);
            
            $sale_items = TSession::getValue('sale_items');
            $key = (int) $data->product_id;
            $sale_items[ $key ] = array('product_id'       => $data->product_id,
                                        'product_name'     => $product->description,
                                        'product_amount'   => $data->product_amount,
                                        'product_price'    => $data->product_price,
                                        'product_discount' => $data->product_discount);
            
            TSession::setValue('sale_items', $sale_items);
            
            // clear product form fields after add
            $data->product_id = '';
            $data->product_name = '';
            $data->product_amount = '';
            $data->product_price = '';
            $data->product_discount = '';
            TTransaction::close();
            $this->form->setData($data);
            
            $this->onReload( $param ); // reload the sale items
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Edit a product from item list
     * @param $param URL parameters
     */
    public function onEditItemProduto( $param )
    {
        $data = $this->form->getData();
        
        // read session items
        $sale_items = TSession::getValue('sale_items');
        
        // get the session item
        $sale_item = $sale_items[ (int) $param['list_product_id'] ];
        
        $data->product_id = $param['list_product_id'];
        $data->product_name = $sale_item['product_name'];
        $data->product_amount = $sale_item['product_amount'];
        $data->product_price = $sale_item['product_price'];
        $data->product_discount = $sale_item['product_discount'];
        
        // fill product fields
        $this->form->setData( $data );
    
        $this->onReload( $param );
    }
    
    /**
     * Delete a product from item list
     * @param $param URL parameters
     */
    public function onDeleteItem( $param )
    {
        $data = $this->form->getData();
        
        $data->product_id = '';
        $data->product_name = '';
        $data->product_amount = '';
        $data->product_price = '';
        $data->product_discount = '';
        
        // clear form data
        $this->form->setData( $data );
        
        // read session items
        $sale_items = TSession::getValue('sale_items');
        
        // delete the item from session
        unset($sale_items[ (int) $param['list_product_id'] ] );
        TSession::setValue('sale_items', $sale_items);
        
        // reload sale items
        $this->onReload( $param );
    }
    
    /**
     * Reload the item list
     * @param $param URL parameters
     */
    public function onReload($param)
    {
        // read session items
        $sale_items = TSession::getValue('sale_items');
        
        $this->product_list->clear(); // clear product list
        $data = $this->form->getData();
        
        if ($sale_items)
        {
            $cont = 1;
            foreach ($sale_items as $list_product_id => $list_product)
            {
                $item_name = 'prod_' . $cont++;
                $item = new StdClass;
                
                // create action buttons
                $action_del = new TAction(array($this, 'onDeleteItem'));
                $action_del->setParameter('list_product_id', $list_product_id);
                
                $action_edi = new TAction(array($this, 'onEditItemProduto'));
                $action_edi->setParameter('list_product_id', $list_product_id);
                
                $button_del = new TButton('delete_product'.$cont);
                $button_del->class = 'btn btn-default btn-sm';
                $button_del->setAction( $action_del, '' );
                $button_del->setImage('fa:trash-o red fa-lg');
                
                $button_edi = new TButton('edit_product'.$cont);
                $button_edi->class = 'btn btn-default btn-sm';
                $button_edi->setAction( $action_edi, '' );
                $button_edi->setImage('fa:edit blue fa-lg');
                
                $item->edit = $button_edi;
                $item->delete = $button_del;
                
                $this->formFields[ $item_name.'_edit' ] = $item->edit;
                $this->formFields[ $item_name.'_delete' ] = $item->delete;
                
                $item->product_id = $list_product['product_id'];
                $item->product_name = $list_product['product_name'];
                $item->product_amount = $list_product['product_amount'];
                $item->product_price = $list_product['product_price'];
                $item->product_discount = $list_product['product_discount'];
                
                $row = $this->product_list->addItem( $item );
                $row->onmouseover = '';
                $row->onmouseout  = '';
            }
            
            $this->form->setFields( $this->formFields );
        }
        
        $this->loaded = TRUE;
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {
        try
        {
            TTransaction::open('samples');
            
            if (isset($param['key']))
            {
                $key = $param['key'];
                
                $object = new Sale($key);
                $sale_items = $object->getSaleItems();
                
                $session_items = array();
                foreach( $sale_items as $item )
                {
                    $session_items[$item->product_id] = $item->toArray();
                    $session_items[$item->product_id]['product_id'] = $item->product_id;
                    $session_items[$item->product_id]['product_name'] = $item->product->description;
                    $session_items[$item->product_id]['product_amount'] = $item->amount;
                    $session_items[$item->product_id]['product_price'] = $item->sale_price;
                    $session_items[$item->product_id]['product_discount'] = $item->discount;
                }
                TSession::setValue('sale_items', $session_items);
                
                $this->form->setData($object); // fill the form with the active record data
                $this->onReload( $param ); // reload sale items list
                TTransaction::close(); // close transaction
            }
            else
            {
                $this->form->clear();
                TSession::setValue('sale_items', null);
                $this->onReload( $param );
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Save the sale and the sale items
     */
    function onSave()
    {
        try
        {
            // open a transaction with database 'samples'
            TTransaction::open('samples');
            
            $sale = $this->form->getData('Sale');
            $this->form->validate(); // form validation
            
            // get session items
            $sale_items = TSession::getValue('sale_items');
            
            if( $sale_items )
            {
                $total = 0;
                foreach( $sale_items as $sale_item )
                {
                    $item = new SaleItem;
                    $item->product_id  = $sale_item['product_id'];
                    $item->sale_price  = $sale_item['product_price'];
                    $item->amount      = $sale_item['product_amount'];
                    $item->discount    = $sale_item['product_discount'];
                    $item->total       = ($sale_item['product_price'] * $sale_item['product_amount']) - $sale_item['product_amount'];
                    
                    $sale->addSaleItem($item);
                    $total += ($item->sale_price * $item->amount) - $item->discount;
                }
            }
            $sale->total = $total;
            $sale->store(); // stores the object
            $this->form->setData($sale); // keep form data
            TTransaction::close(); // close the transaction
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback();
        }
    }
    
    /**
     * Show the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') )
        {
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }
}
?>
