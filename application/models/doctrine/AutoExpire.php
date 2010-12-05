<?php
/**
 * AutoExpire
 *
 * @package     Doctrine
 * @subpackage  AutoExpire
 * @author      John Kramlich <john@manifestinteractive.com>
 */
class AutoExpire extends Doctrine_Template
{
    /**
     * Array of Timestampable options
     *
     * @var string
     */
    protected $_options = array(
		'publish_on' => array(
			'name' => 'publish_on',
			'alias' => NULL,
			'type' => 'timestamp',
			'format' => 'Y-m-d H:i:s',
			'disabled' => FALSE,
			'expression' => FALSE,
			'options' => array()
		),
		'expire_on' => array(
			'name' => 'expire_on',
			'alias' => NULL,
			'type' => 'timestamp',
			'format' => 'Y-m-d H:i:s',
			'disabled' => FALSE,
			'expression' => FALSE,
			'onInsert' => TRUE,
			'options' => array()
		)
	);

    /**
     * Set table definition for Timestampable behavior
     *
     * @return void
     */
    public function setTableDefinition()
    {
        if ( ! $this->_options['publish_on']['disabled'])
		{
            $name = $this->_options['publish_on']['name'];
            if ($this->_options['publish_on']['alias'])
			{
                $name .= ' as ' . $this->_options['publish_on']['alias'];
            }
			
            $this->hasColumn(
				$name, 
				$this->_options['publish_on']['type'], 
				NULL, 
				$this->_options['publish_on']['options']
			);
        }

        if ( ! $this->_options['expire_on']['disabled'])
		{
            $name = $this->_options['expire_on']['name'];
            if ($this->_options['expire_on']['alias'])
			{
                $name .= ' as ' . $this->_options['expire_on']['alias'];
            }
			
            $this->hasColumn(
				$name, 
				$this->_options['expire_on']['type'], 
				NULL, 
				$this->_options['expire_on']['options']
			);
        }

        $this->addListener(new AutoExpireListener($this->_options));
    }
}