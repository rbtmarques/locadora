<?php
/**
 * GeClientes Active Record
 * @author  <your-name-here>
 */
class GeClientes extends TRecord
{
    const TABLENAME = 'ge_clientes';
    const PRIMARYKEY= 'i_cliente';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $ge_bairros;
    private $ge_cidades;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('i_empresa');
        parent::addAttribute('i_classe');
        parent::addAttribute('nome');
        parent::addAttribute('sexo');
        parent::addAttribute('endereco');
        parent::addAttribute('complemento');
        parent::addAttribute('i_bairro');
        parent::addAttribute('i_cidade');
        parent::addAttribute('cep');
        parent::addAttribute('e_mail');
        parent::addAttribute('rg');
        parent::addAttribute('cpf');
        parent::addAttribute('ddd_res');
        parent::addAttribute('fone_res');
        parent::addAttribute('ddd_com');
        parent::addAttribute('fone_com');
        parent::addAttribute('ddd_cel');
        parent::addAttribute('fone_cel');
        parent::addAttribute('dt_nasc');
        parent::addAttribute('nome_pai');
        parent::addAttribute('nome_mae');
        parent::addAttribute('observacao');
        parent::addAttribute('estado_civil');
        parent::addAttribute('conjuge');
        parent::addAttribute('situacao');
        parent::addAttribute('motivo');
        parent::addAttribute('vl_limite');
        parent::addAttribute('foto');
        parent::addAttribute('empresa');
        parent::addAttribute('endereco_empresa');
        parent::addAttribute('complemento_empresa');
        parent::addAttribute('i_bairro_empresa');
        parent::addAttribute('i_cidade_empresa');
        parent::addAttribute('cep_empresa');
        parent::addAttribute('numero_fidelizacao');
        parent::addAttribute('dt_cadastro');
        parent::addAttribute('usuario');
        parent::addAttribute('dt_sistema');
    }

    
    /**
     * Method set_ge_bairros
     * Sample of usage: $ge_clientes->ge_bairros = $object;
     * @param $object Instance of GeBairros
     */
    public function set_ge_bairros(GeBairros $object)
    {
        $this->ge_bairros = $object;
        $this->i_cliente = $object->id;
    }
    
    /**
     * Method get_ge_bairros
     * Sample of usage: $ge_clientes->ge_bairros->attribute;
     * @returns GeBairros instance
     */
    public function get_ge_bairros()
    {
        // loads the associated object
        if (empty($this->ge_bairros))
            $this->ge_bairros = new GeBairros($this->i_cliente);
    
        // returns the associated object
        return $this->ge_bairros;
    }
    
    
    /**
     * Method set_ge_cidades
     * Sample of usage: $ge_clientes->ge_cidades = $object;
     * @param $object Instance of GeCidades
     */
    public function set_ge_cidades(GeCidades $object)
    {
        $this->ge_cidades = $object;
        $this->i_cliente = $object->id;
    }
    
    /**
     * Method get_ge_cidades
     * Sample of usage: $ge_clientes->ge_cidades->attribute;
     * @returns GeCidades instance
     */
    public function get_ge_cidades()
    {
        // loads the associated object
        if (empty($this->ge_cidades))
            $this->ge_cidades = new GeCidades($this->i_cliente);
    
        // returns the associated object
        return $this->ge_cidades;
    }
    


}
