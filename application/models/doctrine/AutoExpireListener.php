<?php
/**
 * AutoExpireListener
 *
 * @package     Doctrine
 * @subpackage  AutoExpire
 * @author      John Kramlich <john@manifestinteractive.com>
 */
class AutoExpireListener extends Doctrine_Record_Listener
{
    protected $_options = array();

    /**
     * __construct
     *
     * @param string $options 
     * @return void
     */
    public function __construct(array $options)
    {
        $this->_options = $options;
    }   
    
    /**
     * Implement preDqlSelect() hook and to remove records which shouldn't be seen 
     *
     * @param Doctrine_Event $event 
     * @return void
     */
    public function preDqlSelect(Doctrine_Event $event)
    {
		$publish_on_field = $params['alias'] . '.' . $this->_options['publish_on']['name'];
		$expire_on_field = $params['alias'] . '.' . $this->_options['expire_on']['name'];
		$query = $event->getQuery();
		
		// Add a Where clause to include only records that have a publish date equal to or greater to todays date
		$query->addWhere('(' . $publish_on_field . ' <= NOW() OR ' . $publish_on_field . ' IS NULL )');
		
		// Add a Where clause to include only records that have a expires date less than or equal to todays date
		$query->addWhere('(' . $expire_on_field . ' >= NOW() OR ' . $expire_on_field . ' IS NULL )');

    }
}