<?php
/**
 * GeCidades Active Record
 * @author  <your-name-here>
 */
class GeCidades extends TRecord
{
    const TABLENAME = 'ge_cidades';
    const PRIMARYKEY= 'i_cidade';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $ge_estados;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('i_estado');
        parent::addAttribute('usuario');
        parent::addAttribute('dt_sistema');
    }

    
    /**
     * Method set_ge_estados
     * Sample of usage: $ge_cidades->ge_estados = $object;
     * @param $object Instance of GeEstados
     */
    public function set_ge_estados(GeEstados $object)
    {
        $this->ge_estados = $object;
        $this->i_cidade = $object->id;
    }
    
    /**
     * Method get_ge_estados
     * Sample of usage: $ge_cidades->ge_estados->attribute;
     * @returns GeEstados instance
     */
    public function get_ge_estados()
    {
        // loads the associated object
        if (empty($this->ge_estados))
            $this->ge_estados = new GeEstados($this->i_cidade);
    
        // returns the associated object
        return $this->ge_estados;
    }
    


}
