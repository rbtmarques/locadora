<?php
/**
 * Product Form
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ProductForm extends TStandardForm
{
    protected $form;
    private   $frame;
    
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_Product');
        $this->form->class = 'tform'; // CSS class
        
        // defines the form title
        $this->form->setFormTitle('Product');
        
        // define the database and the Active Record
        parent::setDatabase('samples');
        parent::setActiveRecord('Product');
        
        // units
        $units = array('PC' => 'Pieces', 'GR' => 'Grain');
        
        // create the form fields
        $id                             = new TEntry('id');
        $description                    = new TEntry('description');
        $stock                          = new TEntry('stock');
        $sale_price                     = new TEntry('sale_price');
        $unity                          = new TCombo('unity');
        $photo_path                     = new TFile('photo_path');
        
        // complete upload action
        $photo_path->setCompleteAction(new TAction(array($this, 'onComplete')));
        
        $id->setEditable( FALSE );
        $unity->addItems( $units );
        $stock->setNumericMask(2, ',', '.', TRUE); // TRUE: process mask when editing and saving
        $sale_price->setNumericMask(2, ',', '.', TRUE); // TRUE: process mask when editing and saving
        
        // add the form fields
        $this->form->addQuickField('ID', $id,  '30%');
        $this->form->addQuickField('Description', $description,  '70%', new TRequiredValidator);
        $this->form->addQuickField('Stock', $stock,  '70%', new TRequiredValidator);
        $this->form->addQuickField('Sale Price', $sale_price,  '70%', new TRequiredValidator);
        $this->form->addQuickField('Unity', $unity,  '70%', new TRequiredValidator);
        $this->form->addQuickField('Photo Path', $photo_path,  '70%');
        
        $this->frame = new TElement('div');
        $this->frame->id = 'photo_frame';
        $this->frame->style = 'width:400px;height:auto;min-height:200px;border:1px solid gray;padding:4px;';
        $row = $this->form->addRow();
        $row->addCell('');
        $row->addCell($this->frame);
        

        // add the actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:save green');
        $this->form->addQuickAction(_t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addQuickAction(_t('List'), new TAction(array('ProductList', 'onReload')), 'fa:table blue');

        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', 'ProductList'));
        $vbox->add($this->form);

        parent::add($vbox);
    }
    
    /**
     * On complete upload
     */
    public static function onComplete($param)
    {
        new TMessage('info', 'Upload completed: '.$param['photo_path']);
        
        // refresh photo_frame
        TScript::create("$('#photo_frame').html('')");
        TScript::create("$('#photo_frame').append(\"<img style='width:100%' src='tmp/{$param['photo_path']}'>\");");
    }
    
    /**
     * Edit product
     */
    public function onEdit($param)
    {
        $object = parent::onEdit($param);
        if ($object)
        {
            $image = new TImage($object->photo_path);
            $image->style = 'width: 100%';
            $this->frame->add( $image );
        }
    }
    
    /**
     * Overloaded method onSave()
     * Executed whenever the user clicks at the save button
     */
    public function onSave()
    {
        // first, use the default onSave()
        $object = parent::onSave();
        
        // if the object has been saved
        if ($object instanceof Product)
        {
            $source_file   = 'tmp/'.$object->photo_path;
            $target_file   = 'images/' . $object->photo_path;
            $finfo         = new finfo(FILEINFO_MIME_TYPE);
            
            // if the user uploaded a source file
            if (file_exists($source_file) AND ($finfo->file($source_file) == 'image/png' OR $finfo->file($source_file) == 'image/jpeg'))
            {
                // move to the target directory
                rename($source_file, $target_file);
                try
                {
                    TTransaction::open($this->database);
                    // update the photo_path
                    $object->photo_path = 'images/'.$object->photo_path;
                    $object->store();
                    
                    TTransaction::close();
                }
                catch (Exception $e) // in case of exception
                {
                    new TMessage('error', $e->getMessage());
                    TTransaction::rollback();
                }
            }
            $image = new TImage($object->photo_path);
            $image->style = 'width: 100%';
            $this->frame->add( $image );
        }
    }
}
