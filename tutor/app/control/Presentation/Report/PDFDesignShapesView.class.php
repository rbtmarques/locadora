<?php
/**
 * PDF Designed Shapes
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class PDFDesignShapesView extends TPage
{
    private $form; // form
    
    /**
     * Class constructor
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TForm('form_pdf_shapes');
        
        // creates a table
        $table = new TTable;
        
        // add the table inside the form
        $this->form->add($table);

        // create the form fields
        $name = new TEntry('name');
        $name->addValidation( 'Name', new TRequiredValidator );
        $label = new TLabel('Name' . ': ');
        $label->setFontColor('red');
        $table->addRowSet($label, $name);
        
        $save_button=new TButton('generate');
        $save_button->setAction(new TAction(array($this, 'onGenerate')), 'Generate');
        $save_button->setImage('ico_save.png');

        // add a row for the form action
        $table->addRowSet($save_button);

        // define wich are the form fields
        $this->form->setFields(array($name,$save_button));
        
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
            $data = $this->form->getData();
            $this->form->validate();
            
            $designer = new TPDFDesigner;
            $designer->fromXml('app/reports/forms.pdf.xml');
            $designer->replace('{name}', $data->name );
            $designer->generate();
            
            $designer->gotoAnchorXY('anchor1');
            $designer->SetFontColorRGB('#FF0000');
            $designer->SetFont('Arial', 'B', 18);
            $designer->Write(20, 'Dynamic text !');
            
            $file = 'app/output/pdf_shapes.pdf';
            
            if (!file_exists($file) OR is_writable($file))
            {
                $designer->save($file);
                parent::openFile($file);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $file);
            }
            
            new TMessage('info', 'Report generated. Please, enable popups in the browser (just in the web).');
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
        }
    }
}
?>