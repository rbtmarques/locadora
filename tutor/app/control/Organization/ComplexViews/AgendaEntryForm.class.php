<?php
/**
 * AgendaEntryForm Registration
 * @author  <your name here>
 */
class AgendaEntryForm extends TWindow
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct()
    {
        parent::__construct();
        parent::setSize(640, null);
        parent::setTitle('AgendaEntry');
        
        // creates the form
        $this->form = new TForm('form_Entry');
        $this->form->class = 'tform'; // CSS class
        $this->form->style = 'width: 600px';
        
        // add a table inside form
        $table = new TTable;
        $table-> width = '100%';
        $this->form->add($table);
        
        // add a row for the form title
        $row = $table->addRow();
        $row->class = 'tformtitle'; // CSS class
        $row->addCell( new TLabel('Entry') )->colspan = 2;
        
        $hours = array();
        $durations = array();
        for ($n=0; $n<24; $n++)
        {
            $hours[$n] = "$n:00";
            $durations[$n+1] = $n+1 . ' h';
        }
        array_pop($durations);
        // create the form fields
        $id                             = new TEntry('id');
        $entry_date                     = new TDate('entry_date');
        $start_hour                     = new TCombo('start_hour');
        $duration                       = new TCombo('duration');
        $title                          = new TEntry('title');
        $description                    = new TText('description');
        
        $start_hour->addItems($hours);
        $duration->addItems($durations);
        $id->setEditable(FALSE);
        // define the sizes
        $id->setSize(40);
        $entry_date->setSize(100);
        $start_hour->setSize(100);
        $duration->setSize(100);
        $title->setSize(400);
        $description->setSize(400, 50);


        // add one row for each form field
        $table->addRowSet( new TLabel('ID:'), $id );
        $table->addRowSet( new TLabel('Entry Date:'), $entry_date );
        $table->addRowSet( new TLabel('Start Hour:'), $start_hour );
        $table->addRowSet( new TLabel('Duration:'), $duration );
        $table->addRowSet( new TLabel('Title:'), $title );
        $table->addRowSet( new TLabel('Description:'), $description );

        // create an action button (save)
        $save_button=new TButton('save');
        $save_button->setAction(new TAction(array($this, 'onSave')), _t('Save'));
        $save_button->setImage('ico_save.png');

 
        // create an new button (edit with no parameters)
        $new_button=new TButton('new');
        $new_button->setAction(new TAction(array($this, 'onEdit')), _t('Clear'));
        $new_button->setImage('ico_new.png');

        $this->form->setFields(array($id,$entry_date,$start_hour,$duration,$title,$description,$save_button,$new_button));

        
        $buttons_box = new THBox;
        $buttons_box->add($save_button);
        $buttons_box->add($new_button);
        
        // add a row for the form action
        $row = $table->addRow();
        $row->class = 'tformaction'; // CSS class
        $row->addCell($buttons_box)->colspan = 2;
        
        parent::add($this->form);
    }

    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    public function onSave()
    {
        try
        {
            // open a transaction with database 'samples'
            TTransaction::open('samples');
            
            // get the form data into an active record Entry
            $object = $this->form->getData('AgendaEntry');
            
            $this->form->validate(); // form validation
            $object->store(); // stores the object
            $this->form->setData($object); // keep form data
            
            TTransaction::close(); // close the transaction
            $posAction = new TAction(array('AgendaView', 'reload'));
            // shows the success message
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'), $posAction);
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            $this->form->setData( $this->form->getData() ); // keep form data
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    public function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                
                // open a transaction with database 'samples'
                TTransaction::open('samples');
                
                // instantiates object AgendaEntry
                $object = new AgendaEntry($key);
                
                // fill the form with the active record data
                $this->form->setData($object);
                
                // close the transaction
                TTransaction::close();
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * method onStartEdit()
     * Start a new form filled
     */
    public function onStartEdit($param)
    {
        $this->form->clear();
        $param['duration'] = 1;
        $this->form->setData( (object) $param );
    }
}
?>
