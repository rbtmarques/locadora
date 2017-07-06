<?php
/**
 * GeClientesList Listing
 * @author  <your name here>
 */
class GeClientesList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('filmes');            // defines the database
        parent::setActiveRecord('GeClientes');   // defines the active record
        parent::setDefaultOrder('i_cliente', 'asc');         // defines the default order
        // parent::setCriteria($criteria) // define a standard filter

        parent::addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        parent::addFilterField('i_cidade', 'like', 'i_cidade'); // filterField, operator, formField
        parent::addFilterField('cep', 'like', 'cep'); // filterField, operator, formField
        parent::addFilterField('e_mail', 'like', 'e_mail'); // filterField, operator, formField
        parent::addFilterField('cpf', 'like', 'cpf'); // filterField, operator, formField
        
        // creates the form
        $this->form = new TQuickForm('form_search_GeClientes');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        $this->form->setFormTitle('GeClientes');
        

        // create the form fields
        $nome = new TEntry('nome');
        $i_cidade = new TEntry('i_cidade');
        $cep = new TEntry('cep');
        $e_mail = new TEntry('e_mail');
        $cpf = new TEntry('cpf');


        // add the fields
        $this->form->addQuickField('Nome', $nome,  200 );
        $this->form->addQuickField('Cidade', $i_cidade,  200 );
        $this->form->addQuickField('CEP', $cep,  200 );
        $this->form->addQuickField('E-mail', $e_mail,  200 );
        $this->form->addQuickField('CPF', $cpf,  200 );

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('GeClientes_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $this->form->addQuickAction(_t('New'),  new TAction(array('GeClientesForm', 'onEdit')), 'bs:plus-sign green');
        
        // creates a DataGrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_check = new TDataGridColumn('check', '', 'center');
        $column_i_cliente = new TDataGridColumn('i_cliente', 'Cód.', 'left');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_endereco = new TDataGridColumn('endereco', 'Endereço', 'left');
        $column_complemento = new TDataGridColumn('complemento', 'Complemento', 'left');
        $column_i_bairro = new TDataGridColumn('i_bairro', 'Bairro', 'right');
        $column_i_cidade = new TDataGridColumn('i_cidade', 'Cidade', 'right');
        $column_cep = new TDataGridColumn('cep', 'CEP', 'center');
        $column_e_mail = new TDataGridColumn('e_mail', 'E-mail', 'left');
        $column_cpf = new TDataGridColumn('cpf', 'CPF', 'center');
        $column_fone_res = new TDataGridColumn('fone_res', 'Telefone(s)', 'center');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_check);
        $this->datagrid->addColumn($column_i_cliente);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_endereco);
        $this->datagrid->addColumn($column_complemento);
        $this->datagrid->addColumn($column_i_bairro);
        $this->datagrid->addColumn($column_i_cidade);
        $this->datagrid->addColumn($column_cep);
        $this->datagrid->addColumn($column_e_mail);
        $this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_fone_res);


        // creates the datagrid column actions
        $order_i_cliente = new TAction(array($this, 'onReload'));
        $order_i_cliente->setParameter('order', 'i_cliente');
        $column_i_cliente->setAction($order_i_cliente);
        
        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $column_nome->setAction($order_nome);
        
        $order_i_cidade = new TAction(array($this, 'onReload'));
        $order_i_cidade->setParameter('order', 'i_cidade');
        $column_i_cidade->setAction($order_i_cidade);
        


        // inline editing
        $nome_edit = new TDataGridAction(array($this, 'onInlineEdit'));
        $nome_edit->setField('i_cliente');
        $column_nome->setEditAction($nome_edit);
        
        $endereco_edit = new TDataGridAction(array($this, 'onInlineEdit'));
        $endereco_edit->setField('i_cliente');
        $column_endereco->setEditAction($endereco_edit);
        
        $complemento_edit = new TDataGridAction(array($this, 'onInlineEdit'));
        $complemento_edit->setField('i_cliente');
        $column_complemento->setEditAction($complemento_edit);
        
        $cep_edit = new TDataGridAction(array($this, 'onInlineEdit'));
        $cep_edit->setField('i_cliente');
        $column_cep->setEditAction($cep_edit);
        
        $fone_res_edit = new TDataGridAction(array($this, 'onInlineEdit'));
        $fone_res_edit->setField('i_cliente');
        $column_fone_res->setEditAction($fone_res_edit);
        

        
        // create EDIT action
        $action_edit = new TDataGridAction(array('GeClientesForm', 'onEdit'));
        $action_edit->setUseButton(TRUE);
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('i_cliente');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setUseButton(TRUE);
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('i_cliente');
        $this->datagrid->addAction($action_del);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $this->datagrid->disableDefaultClick();
        
        // put datagrid inside a form
        $this->formgrid = new TForm;
        $this->formgrid->add($this->datagrid);
        
        // creates the delete collection button
        $this->deleteButton = new TButton('delete_collection');
        $this->deleteButton->setAction(new TAction(array($this, 'onDeleteCollection')), AdiantiCoreTranslator::translate('Delete selected'));
        $this->deleteButton->setImage('fa:remove red');
        $this->formgrid->addField($this->deleteButton);
        
        $gridpack = new TVBox;
        $gridpack->style = 'width: 100%';
        $gridpack->add($this->formgrid);
        $gridpack->add($this->deleteButton)->style = 'background:whiteSmoke;border:1px solid #cccccc; padding: 3px;padding: 5px;';
        
        $this->transformCallback = array($this, 'onBeforeLoad');


        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Title', $this->form));
        $container->add($gridpack);
        $container->add($this->pageNavigation);
        
        parent::add($container);
    }
    
    /**
     * Transform datagrid objects
     * Create the checkbutton as datagrid element
     */
    public function onBeforeLoad($objects, $param)
    {
        // update the action parameters to pass the current page to action
        // without this, the action will only work for the first page
        $deleteAction = $this->deleteButton->getAction();
        $deleteAction->setParameters($param); // important!
        
        $gridfields = array( $this->deleteButton );
        
        foreach ($objects as $object)
        {
            $object->check = new TCheckButton('check' . $object->i_cliente);
            $object->check->setIndexValue('on');
            $gridfields[] = $object->check; // important
        }
        
        $this->formgrid->setFields($gridfields);
    }

}
