<?php
/**
 * Taggable
 *
 * @package     Doctrine
 * @subpackage  Taggable
 * @author      John Kramlich <john@manifestinteractive.com>
 */
class Taggable extends Doctrine_Template
{
	protected $_options = array(
		'tagClass' => 'Tag',
		'tagAlias' => 'Tags',
		'className' => '%CLASS%Tag',
		'generateFiles' => TRUE,
		'table' => FALSE,
		'pluginTable' => FALSE,
		'children' => array(),
		'cascadeDelete' => TRUE,
		'appLevelDelete' => FALSE,
		'cascadeUpdate' => FALSE
	);

    public function __construct(array $options = array())
    {
        $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
        $this->_plugin = new TagGenerator($this->_options);
    }

    public function setUp()
    {
        $this->_plugin->initialize($this->_table);
    
        $className = $this->_table->getComponentName();
    
        Doctrine::getTable($this->_options['tagClass'])->bind(
			array(
				$className.' as '.$className.'s', 
				array(
				  'class' => $className,
				  'local' => 'tag_id',
				  'foreign' => 'id',
				  'refClass' => $this->_plugin->getTable()->getOption('name')
				)
			), 
			Doctrine_Relation::MANY
		);
    }
    

    /**
     * Get the Number of Tags Associated with This Item
     *
     * @return integer Number of tags
     */
    public function getNumberOfTags()
    {
        return $this->getInvoker()->get('Tags')->count();
    }
    
    /**
     * Does item have tags?
     *
     * @return boolean Whether the item has associated tags
     */
    public function hasTags()
    {
        return $this->getNumberOfTags() > 0;
    }

    /**
     * Get an array of this items tags
     *
     * @return array Item's tags
     */
    public function getTagNames()
    {
        $tagNames = array();
        foreach($this->getInvoker()->get('Tags') as $tag)
		{
            $tagNames[] = $tag->get('name');
        }
        
        return $tagNames;
    }


    public function getTagsString($seperator = ', ')
    {
        return implode($seperator, $this->getTagNames());
    }
    
    /**
     * Get all item's tags as a string
     *
     * @param string $seperator The seperator to use
     * @return string The string of tags
     */
    public function getTagsAsString ($seperator = ', ')
    {
        return $this->getTagsString($seperator);
    }

    /**
     * Set an items tags, overwrite any existing tags
     *
     * @param array $tags An array of tags
     */
    public function setTags($tags)
    {
        if(empty($tags))
		{
            $tags = array();
        }
    
        $tagIds = $this->getTagIds($tags);
        $this->getInvoker()->unlink('Tags');
        $this->getInvoker()->link('Tags', $tagIds);
        
        return $this->getInvoker();
    }
    
    /**
     * Add tags to an item
     *
     * @param array $tags An array of tags
     */
    public function addTags($tags)
    {
        $this->getInvoker()->link('Tags', $this->getTagIds($tags));
    
        return $this->getInvoker();
    }
    
    /**
     * Remove tags from an item
     *
     * @param array $tags An array of tags
     */
    public function removeTags($tags)
    {
        $this->getInvoker()->unlink('Tags', $this->getTagIds($tags));
        
        return $this->getInvoker();
    }
    
    /**
     * Remove all tags from an item
     *
     */
    public function removeAllTags()
    {
        $this->getInvoker()->unlink('Tags');
        
        return $this->getInvoker();
    }
    
    /**
     * Get all records that have related tags
     *
     */
    public function getRelatedRecords($hydrationMode = Doctrine::HYDRATE_RECORD)
    {
        return $this->getRelatedRecordsQuery()->execute(array(), $hydrationMode);
    }
    
    /**
     * Check if Item has Related Items based on tags
     *
     * @return boolean Whether this item has records that share similiar tags
     */
    public function hasRelatedRecords($hydrationMode = Doctrine::HYDRATE_RECORD)
    {
        return $this->getRelatedRecordsQuery()->count();
    }
    
    public function getRelatedRecordsQuery()
    {
        return $this->getInvoker()->getTable()
        ->createQuery('a')
        ->leftJoin('a.Tags t')
        ->whereIn('t.id', $this->getCurrentTagIds())
        ->andWhere('a.id != ?', $this->getInvoker()->get('id'));
    }
    
    /**
     * Get all tag ids for this item
     *
     */
    public function getCurrentTagIds()
    {
        $tagIds = array();
        
        foreach ($this->getInvoker()->get('Tags') as $tag)
		{
            $tagIds[] = $tag->get('id');
        }
    
        return $tagIds;
    }

    public function getTagIds($tags)
    {
		if(is_string($tags))
		{
			$tagNames = array_unique(array_filter(array_map('trim', explode(',', $tags))));
			$tagsList = array();
		
			if(!empty($tagNames))
			{
				$existingTagQuery = Doctrine::getTable($this->_options['tagClass'])
				->createQuery('t')
				->select('t.id')
				->where('t.lc_name = ?')
				->limit(1);
		
				foreach ($tagNames as $tagName)
				{
					//check if tag is existing in db
					$lowerCaseTagName = strtolower($tagName);
					
					// Search the database for an existing tag
					$_existingTag = $existingTagQuery->execute(array($lowerCaseTagName), DOCTRINE::HYDRATE_NONE);
					
					//if tag is not in db, insert tag 
					if (empty($_existingTag))
					{
						$tag = new $this->_options['tagClass']();
						$tag->set('name', $tagName);
						$tag->set('lc_name', $lowerCaseTagName);
						$tag->save();
						$tagsList[] = $tag->get('id');
					}
					else
					{
						$tagsList[] = $_existingTag[0][0];
					}
				}
			}
		
			return $tagsList;
		} 
		elseif(is_array($tags))
		{
			if (is_numeric(current($tags)))
			{
				return $tags;
			}
			else
			{
				return $this->getTagIds(implode(',', $tags));
			}
		}
		elseif ($tags instanceof Doctrine_Collection)
		{
			return $tags->getPrimaryKeys();
		}
		else
		{
			throw new Doctrine_Exception('Invalid $tags data provided. Data provided must be a string of tags, an array of tag ids, or a Doctrine_Collection of tag records.');
		}
    }    
}
