<?php
/**
 * GeClientesForm Registration
 * @author  <your name here>
 */
class GeClientesForm extends TPage
{
    protected $form; // form
    
    use Adianti\Base\AdiantiStandardFormTrait; // Standard form methods
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('filmes');              // defines the database
        $this->setActiveRecord('GeClientes');     // defines the active record
        
        // creates the form
        $this->form = new TQuickForm('form_GeClientes');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        
        // define the form title
        $this->form->setFormTitle('GeClientes');
        


        // create the form fields
        $nome = new TEntry('nome');
        $sexo = new TCombo('sexo');
        $endereco = new TEntry('endereco');
        $complemento = new TEntry('complemento');
        $i_bairro = new TCombo('i_bairro');
        $i_cidade = new TSeekButton('i_cidade');
        $cep = new TEntry('cep');
        $e_mail = new TEntry('e_mail');
        $rg = new TEntry('rg');
        $cpf = new TEntry('cpf');
        $ddd_res = new TEntry('ddd_res');
        $fone_res = new TEntry('fone_res');
        $dt_nasc = new TDate('dt_nasc');
        $nome_pai = new TEntry('nome_pai');
        $nome_mae = new TEntry('nome_mae');
        $observacao = new TText('observacao');
        $estado_civil = new TSelect('estado_civil');
        $conjuge = new TEntry('conjuge');
        $situacao = new TEntry('situacao');
        $motivo = new TEntry('motivo');
        $vl_limite = new TEntry('vl_limite');
        
        //add opções sexo
        $sexo->addItems(['M'=>'Masculino', 'F'=>'Feminino']);
        $sexo->setValue('M');
        
        // add itens opc bairros
        TTransaction::open('filmes');
        $collection = GeBairros::all();
        $itemsBairro = array();
        foreach ($collection as $object)
        {
            $itemsBairro[$object->i_bairro] = $object->nome;
        }
        TTransaction::close();
        
        $i_bairro->addItems($itemsBairro);
        
        $btAddBairro = TButton::create('btAddBairro',array($this,'onEdit'),'bairros','fa:plus');

        // add the fields
        //$this->form->addQuickField('Nome', $nome,  200 , new TRequiredValidator);
        $this->form->addQuickFields('Nome', array($nome, new TLabel('Sexo'), $sexo), new TRequiredValidator);
        //$this->form->addQuickField('Sexo', $sexo,  200 , new TRequiredValidator);
        $this->form->addQuickField('Endereco', $endereco,  450 );
        $this->form->addQuickField('Complemento', $complemento,  200 );
        $this->form->addQuickFields('Bairro', array($i_bairro, $btAddBairro));
        $this->form->addQuickField('Cidade', $i_cidade,  100 , new TRequiredValidator);
        $this->form->addQuickField('CEP', $cep,  10 , new TRequiredValidator);
        $this->form->addQuickField('E-mail', $e_mail,  200 , new TRequiredValidator);
        $this->form->addQuickField('RG', $rg,  200 , new TRequiredValidator);
        $this->form->addQuickField('CPF', $cpf,  200 , new TRequiredValidator);
        $this->form->addQuickField('DDD', $ddd_res,  5 );
        $this->form->addQuickField('Telefone', $fone_res,  200 , new TRequiredValidator);
        $this->form->addQuickField('Data de Nascimento', $dt_nasc,  100 , new TRequiredValidator);
        $this->form->addQuickField('Nome Pai', $nome_pai,  200 );
        $this->form->addQuickField('Nome Mae', $nome_mae,  200 );
        $this->form->addQuickField('Observacao', $observacao,  200 );
        $this->form->addQuickField('Estado Civil', $estado_civil,  200 );
        $this->form->addQuickField('Conjuge', $conjuge,  200 );
        $this->form->addQuickField('Situacao', $situacao,  200 );
        $this->form->addQuickField('Motivo', $motivo,  200 );
        $this->form->addQuickField('Valor Limite', $vl_limite,  200 );



        
        if (!empty($i_cliente))
        {
            $i_cliente->setEditable(FALSE);
        }
        
        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
         
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onEdit')), 'bs:plus-sign green');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Title', $this->form));
        
        parent::add($container);
    }
    
    public function onEdit(){}
}
