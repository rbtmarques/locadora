<?php
/*
 * Massive Delete View
 * @author  Pablo Dall'Oglio
 * Copyright (c) 2006-2007 Pablo Dall'Oglio
 * <pablo@adianti.com.br>. All rights reserved.
 */
class MassiveDeleteView extends TStandardList
{
    protected $form;      // formulário de cadastro
    protected $datagrid;  // listagem
    protected $loaded;
    protected $pageNavigation;  // pagination component
    protected $activeRecord;
    protected $formgrid;
    protected $deleteAction;
    
    /*
     * método construtor
     * Cria a página, o formulário e a listagem
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('samples');
        parent::setActiveRecord('TrashItem');
        parent::addFilterField('content');
        parent::setLimit(10);

        $this->form = new TQuickForm('form_trash');
        $this->form->{'class'} = 'tform'; // CSS class
        $this->form->setFormTitle('Massive delete');
        
        // cria os campos do formulário
        $content = new TEntry('content');
        $this->form->addQuickField('Content', $content,  '80%');
        $this->form->addQuickAction(_t('Search'), new TAction(array($this, 'onSearch')), 'fa:search');
        
        // instancia objeto DataGrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->style = 'width: 100%';
        parent::setTransformer(array($this, 'onBeforeLoad'));
        
        // instancia as colunas da DataGrid
        $check     = new TDataGridColumn('check',    '',         'center', 40);
        $id        = new TDataGridColumn('id',       'ID',       'center', 100);
        $content   = new TDataGridColumn('content',  'Content',  'left');
        
        // adiciona as colunas à DataGrid
        $this->datagrid->addColumn($check);
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($content);
        
        // cria o modelo da DataGrid, montando sua estrutura
        $this->datagrid->createModel();
        
        // cria o paginador
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // put datagrid inside a form
        $this->formgrid = new TForm;
        $this->formgrid->add($this->datagrid);
        
        // creates the delete collection button
        $button = new TButton('delete_collection');
        $this->deleteAction = new TAction(array($this, 'onDeleteCollection'));
        $button->setAction($this->deleteAction, AdiantiCoreTranslator::translate('Delete selected'));
        $button->setImage('fa:remove red');
        $this->formgrid->addField($button);
        
        $gridpack = new TVBox;
        $gridpack->style = 'width: 100%';
        $gridpack->add($this->formgrid);
        $gridpack->add($button)->style = 'background:whiteSmoke;border:1px solid #cccccc; padding: 3px;padding: 5px;';
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
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
        $this->deleteAction->setParameters($param); // important!
        
        foreach ($objects as $object)
        {
            $object->check = new TCheckButton('check' . $object->{'id'});
            $object->check->setIndexValue('on');
            $this->formgrid->addField($object->check); // important
        }
    }
}
