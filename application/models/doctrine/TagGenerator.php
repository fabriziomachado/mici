<?php
/**
 * TagGenerator
 *
 * @package     Doctrine
 * @subpackage  Taggable
 * @author      John Kramlich <john@manifestinteractive.com>
 */
class TagGenerator extends Doctrine_Record_Generator
{
    protected $_options = array();
    
    public function __construct(array $options = array())
    {
        $this->_options = $options;
    }
    
    public function initOptions()
    {
        $builderOptions = array(
			'suffix' => '.php',
			'baseClassesDirectory' => 'generated',
			'generateBaseClasses' => TRUE,
			'generateTableClasses' => FALSE,
			'baseClassName' => 'Doctrine_Record'
		);
        
        $this->setOption('builderOptions', $builderOptions);
        $this->setOption('className', '%CLASS%Tag');
        $this->setOption('generateFiles', TRUE);
		$this->setOption('generatePath', APPPATH.DIRECTORY_SEPARATOR.'doctrine'.DIRECTORY_SEPARATOR.'dev_local'.DIRECTORY_SEPARATOR.'models');
    }
    
    public function setTableDefinition()
    {
        $this->hasColumn('tag_id', 'integer', NULL, array('primary' => TRUE));    
    }
    
    public function generateClass(array $definition = array())
    {
        $definition['inheritance']['extends'] = 'Doctrine_Record';
    
        return parent::generateClass($definition);
    }
    
    public function buildRelation()
    {
        $this->_table->bind(
			array(
				$this->_options['tagClass'],
				array(
        			'local' => 'tag_id',
        			'foreign' => 'id',
        			'onDelete' => 'CASCADE'
        		)
			), 
			Doctrine_Relation::ONE
		);
        
        $this->getOption('table')->bind(
			array(
				$this->_options['tagClass'] . ' as ' . $this->_options['tagAlias'], 
				array(
					'local' => 'id',
					'foreign' => 'tag_id',
					'refClass' => $this->_table->getComponentName()
				)
			), 
			Doctrine_Relation::MANY
		);
    
        parent::buildRelation();
    }
    
    public function setUp()
    {
    }
}