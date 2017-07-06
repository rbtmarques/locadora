<?php
/**
 * Tabular report
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TabularReportView extends TPage
{
    private $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_Customer_Report');
        $this->form->class = 'tform'; // CSS class
        $this->form->setFormTitle( 'Report filters' );
        
        // create the form fields
        $name         = new TEntry('name');
        $city_id      = new TDBSeekButton('city_id', 'samples', 'form_Customer_Report', 'City', 'name', 'city_id', 'city_name');
        $city_name    = new TEntry('city_name');
        $category_id  = new TDBCombo('category_id', 'samples', 'Category', 'id', 'name');
        $output_type  = new TRadioGroup('output_type');
        
        $options = array('html' => 'HTML', 'pdf' => 'PDF', 'rtf' => 'RTF');
        $output_type->addItems($options);
        $output_type->setValue('pdf');
        $output_type->setLayout('horizontal');
        
        $this->form->addQuickField( 'Name', $name );
        $this->form->addQuickFields( 'City', [$city_id, $city_name] );
        $this->form->addQuickField( 'Category', $category_id );
        $this->form->addQuickField( 'Output', $output_type );
        
        // define the sizes
        $name->setSize( 325 );
        $city_id->setSize(50);
        $city_name->setSize( 250 );
        $category_id->setSize( 325 );
        $city_name->setEditable(FALSE);
        $output_type->setUseButton();
        
        $this->form->addQuickAction( 'Generate', new TAction(array($this, 'onGenerate')), 'fa:download blue');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        
        parent::add($vbox);
    }

    /**
     * method onGenerate()
     * Executed whenever the user clicks at the generate button
     */
    function onGenerate()
    {
        try
        {
            // open a transaction with database 'samples'
            TTransaction::open('samples');
            
            // get the form data into an active record Customer
            $object = $this->form->getData();
            
            $repository = new TRepository('Customer');
            $criteria   = new TCriteria;
            if ($object->name)
            {
                $criteria->add(new TFilter('name', 'like', "%{$object->name}%"));
            }
            
            if ($object->city_id)
            {
                $criteria->add(new TFilter('city_id', '=', "{$object->city_id}"));
            }
            
            if ($object->category_id)
            {
                $criteria->add(new TFilter('category_id', '=', "{$object->category_id}"));
            }
           
            $customers = $repository->load($criteria);
            $format  = $object->output_type;
            
            if ($customers)
            {
                $widths = array(40, 150, 80, 120, 80);
                
                switch ($format)
                {
                    case 'html':
                        $tr = new TTableWriterHTML($widths);
                        break;
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths);
                        break;
                    case 'rtf':
                        if (!class_exists('PHPRtfLite_Autoloader'))
                        {
                            PHPRtfLite::registerAutoloader();
                        }
                        $tr = new TTableWriterRTF($widths);
                        break;
                }
                
                if (!empty($tr))
                {
                    // create the document styles
                    $tr->addStyle('title', 'Arial', '10', 'BI',  '#ffffff', '#407B49');
                    $tr->addStyle('datap', 'Arial', '10', '',    '#000000', '#869FBB');
                    $tr->addStyle('datai', 'Arial', '10', '',    '#000000', '#ffffff');
                    $tr->addStyle('header', 'Times', '16', 'BI', '#ff0000', '#FFF1B2');
                    $tr->addStyle('footer', 'Times', '12', 'BI', '#2B2B2B', '#B5FFB4');
                    
                    // add a header row
                    $tr->addRow();
                    $tr->addCell('Customers', 'center', 'header', 5);
                    
                    // add titles row
                    $tr->addRow();
                    $tr->addCell('Code',      'left', 'title');
                    $tr->addCell('Name',      'left', 'title');
                    $tr->addCell('Category',  'left', 'title');
                    $tr->addCell('Email',     'left', 'title');
                    $tr->addCell('Birthdate', 'left', 'title');
                    
                    // controls the background filling
                    $colour= FALSE;
                    
                    // data rows
                    foreach ($customers as $customer)
                    {
                        $style = $colour ? 'datap' : 'datai';
                        $tr->addRow();
                        $tr->addCell($customer->id,                 'left', $style);
                        $tr->addCell($customer->name,               'left', $style);
                        $tr->addCell($customer->category_name    ,  'left', $style);
                        $tr->addCell($customer->email,              'left', $style);
                        $tr->addCell($customer->birthdate,          'left', $style);
                        
                        $colour = !$colour;
                    }
                    
                    // footer row
                    $tr->addRow();
                    $tr->addCell(date('Y-m-d h:i:s'), 'center', 'footer', 5);
                    // stores the file
                    if (!file_exists("app/output/tabular.{$format}") OR is_writable("app/output/tabular.{$format}"))
                    {
                        $tr->save("app/output/tabular.{$format}");
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied') . ': ' . "app/output/tabular.{$format}");
                    }
                    
                    parent::openFile("app/output/tabular.{$format}");
                    
                    // shows the success message
                    new TMessage('info', 'Report generated. Please, enable popups in the browser (just in the web).');
                }
            }
            else
            {
                new TMessage('error', 'No records found');
            }
    
            // fill the form with the active record data
            $this->form->setData($object);
            
            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
}
?>