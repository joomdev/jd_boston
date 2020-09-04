<?php
/**
*
* Product table
*
* @package	VirtueMart
* @subpackage Category
* @author jseros
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: categories.php 10297 2020-04-07 22:19:33Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Category table class
 * The class is is used to table-level abstraction for Categories.
 *
 * @package	VirtueMart
 * @subpackage Category
 * @author jseros
 */
class TableCategories extends VmTable {

	/** @var int Primary key */
	var $virtuemart_category_id	= null;

	var $category_parent_id = null;
	/** @var integer Product id */
	var $virtuemart_vendor_id		= 0;
	/** @var string Category name */
	var $category_name		=  '';
	var $slug		=  '';
	/** @var string Category description */
	var $category_description		= '';

	/** @var string Category browse page layout */
	var $category_template = null;
	/** @var string Category browse page layout */
	var $category_layout = null;
	/** @var int Category flypage */
	var $category_product_layout		= null;

	/** @var integer Products to show per row  */
	var $products_per_row		= '';
	/** @var int Category order */
	var $ordering		= 0;

	var $shared 		= 0;
	var $cat_params = '';
	/** @var int category limit step*/
	var $limit_list_step 	 = 0;
	/** @var int category limit initial */
	var $limit_list_initial	= 0;
	/** @var string Meta description */
	var $metadesc	= '';
	/** @var string custom title */
	var $customtitle	= '';
	/** @var string Meta keys */
	var $metakey	= '';
	/** @var string Meta robot */
	var $metarobot	= '';
	/** @var string Meta author */
	var $metaauthor	= '';
        /** @var integer Category publish or not */
	var $published			= 0;

	var $has_children = null;
	var $has_medias = null;

	/**
	 * Class contructor
	 *
	 * @author Max Milbers
	 * @param $db database connector object
	 */
	public function __construct($db) {
		parent::__construct('#__virtuemart_categories', 'virtuemart_category_id', $db);

		//In a VmTable the primary key is the same as the _tbl_key and therefore not needed
// 		$this->setPrimaryKey('virtuemart_category_id');
		$this->setObligatoryKeys('category_name');
		$this->setLoggable();
		$this->setTranslatable(array('category_name','category_description','metadesc','metakey','customtitle'));

		$varsToPushParam = array(
					'show_store_desc' => array('','int'),
					'showcategory_desc' => array('','int'),
					'showcategory' => array('','int'),
					'categories_per_row' => array('','int'),
					'showproducts' => array('','int'),
					'omitLoaded' => array('','int'),
					'showsearch' => array('','int'),
					'productsublayout' => array('','char'),
					/*'categorylayout' => array('','char'),
					'productlayout' => array('','char'),*/
					'featured' => array('','int'),
					'featured_rows' => array('','int'),
					'omitLoaded_featured' => array('','int'),
					'discontinued' => array('','int'),
					'discontinued_rows' => array('','int'),
					'omitLoaded_discontinued' => array('','int'),
					'latest' => array('','int'),
					'latest_rows' => array('','int'),
					'omitLoaded_latest' => array('','int'),
					'topten' => array('','int'),
					'topten_rows' => array('','int'),
					'omitLoaded_topten' => array('','int'),
					'recent' => array('','int'),
					'recent_rows' => array('','int'),
					'omitLoaded_recent' => array('','int')
		);
		$this->setParameterable('cat_params',$varsToPushParam);
		$this->setSlug('category_name');
		$this->setTableShortCut('c');
		$this->setOrderable();
	}

	public function check(){

		$csValue = $this->limit_list_step;
		if(!empty($csValue)){
			$sequenceArray = explode(',', $csValue);
			foreach($sequenceArray as &$csV){
				$csV = (int)trim($csV);
			}
			$this->limit_list_step = implode(',',$sequenceArray);
		}

		return parent::check();
	}

	public function move( $dirn, $where = 0, $orderingKey=0, $cid = 0 ){

		$res = parent::move($dirn, 'category_parent_id = "'.(int)$this->category_parent_id.'"', $orderingKey);
		$this->synchroniseTableOrdering($this->category_parent_id);
	}

	public function synchroniseTableOrdering($category_parent_id){

		$orderingKey = 'ordering';
		$q = 'SELECT virtuemart_category_id,'.$orderingKey.' FROM #__virtuemart_categories WHERE category_parent_id = "'.(int)$category_parent_id.'" ';
		$this->_db->setQuery($q);
		$res = $this->_db->loadAssocList('virtuemart_category_id',$orderingKey);
		foreach($res as $id=>$ordering){
			$q = 'UPDATE #__virtuemart_category_categories SET '.$orderingKey.'="'.$ordering.'" WHERE category_child_id = "'.$id.'" ';
			$this->_db->setQuery($q);
			$this->_db->execute();
		}
	}

	/**
	 * Overwrite method
	 *
	 * @author jseros
	 * @param $dirn movement number
	 * @param $parent_id category parent id
	 * @param $where sql WHERE clausule
	 */
/*	public function move( $dirn, $parent_id = 0, $where='' )
	{
		if (!in_array( 'ordering',  array_keys($this->getProperties())))
		{
			vmError( get_class( $this ).' does not support ordering' );
			return false;
		}

		$k = $this->_tbl_key;

		if(VmConfig::get('optimisedCatSql', false)){
			$prefix = 'c';
		} else {
			$prefix = 'cx';
		}

		$sql = 'SELECT '.$this->_tablePreFix.$this->_tbl_key.', '.$this->_tablePreFix.$this->_orderingKey.' FROM '.$this->_tbl.' as c ';


		if(!VmConfig::get('optimisedCatSql', false)){

			$sql .= ' LEFT JOIN #__virtuemart_category_categories as cx
				ON c.virtuemart_category_id = cx.category_child_id';
		}

		$condition = $prefix.'.category_parent_id = '. $this->_db->Quote($parent_id);

		$where .= !empty($where) ? ' AND '.$condition : $condition;

		if ($dirn < 0)
		{
			$sign = ' < ';
			$orderDir = 'DESC';
		}
		else if ($dirn > 0)
		{
			$sign = ' > ';
			$orderDir = '';
		}
		else
		{
			$sign = ' = ';
			$orderDir = '';
		}

		$sql .= 'WHERE '.$prefix.'.'.$this->_orderingKey.$sign.(int) $this->ordering;
		$sql .= !empty($where) ? ' AND '.$where : '';
		$sql .= ' ORDER BY '.$prefix.'.'.$this->_orderingKey.' '.$orderDir;
		$this->_db->setQuery( $sql, 0, 1 );


		$row = null;
		$row = $this->_db->loadObject();

		//vmdebug('VmTable Category move my sql and row',$sql,$row);

		if (isset($row))
		{
			$query = 'UPDATE '. $this->_tbl
			. ' SET ordering = '. (int) $row->ordering
			. ' WHERE '. $this->_tbl_key .' = '. $this->_db->Quote($this->{$k})
			;
			$this->_db->setQuery( $query );

			if (!$this->_db->execute())
			{
				$err = $this->_db->getErrorMsg();
				vmError( 'TableCategories move isset row this->k '.$err, 'TableCategories move isset row this->k ' );
			}

			$query = 'UPDATE '.$this->_tbl
			. ' SET ordering = '.(int) $this->ordering
			. ' WHERE '.$this->_tbl_key.' = '.$this->_db->Quote($row->{$k})
			;
			$this->_db->setQuery( $query );

			if (!$this->_db->execute())
			{
				$err = $this->_db->getErrorMsg();
				vmError( 'TableCategories move isset row this->k '.$err, 'TableCategories move isset row $row->{$k} ' );
			}

			$this->ordering = $row->ordering;
		}
		else
		{
			$query = 'UPDATE '. $this->_tbl
			. ' SET ordering = '.(int) $this->ordering
			. ' WHERE '. $this->_tbl_key .' = '. $this->_db->Quote($this->{$k})
			;
			$this->_db->setQuery( $query );

			if (!$this->_db->execute())
			{
				$err = $this->_db->getErrorMsg();
				vmError('TableCategories move update '.$err );
			}
		}
		return true;
	}
*/

	/**
	 * Overwrite method
	 * Compacts the ordering sequence of the selected records
	 * @author jseros
	 *
	 * @param $parent_id category parent id
	 * @param string Additional where query to limit ordering to a particular subset of records
	 */
	function reorder( $parent_id = 0, $where='' )
	{
		$k = $this->_tbl_key;

		if (!in_array( 'ordering', array_keys($this->getProperties() ) ))
		{
			vmError( get_class( $this ).' does not support ordering');
			return false;
		}

		$order2 = '';
		$query = 'SELECT c.'.$this->_tbl_key.', c.ordering'
		. ' FROM '. $this->_tbl . ' c'
		. ' LEFT JOIN #__virtuemart_category_categories cx'
		. ' ON c.virtuemart_category_id = cx.category_child_id'
		. ' WHERE c.ordering >= 0' . ( $where ? ' AND '. $where : '' )
		. ' AND cx.category_parent_id = '. $parent_id
		. ' ORDER BY c.ordering'.$order2;

		$this->_db->setQuery( $query );
		if (!($orders = $this->_db->loadObjectList()))
		{
			vmError($this->_db->getErrorMsg());
			return false;
		}
		// compact the ordering numbers
		for ($i=0, $n=count( $orders ); $i < $n; $i++)
		{
			if ($orders[$i]->ordering >= 0)
			{
				if ($orders[$i]->ordering != $i+1)
				{
					$orders[$i]->ordering = $i+1;
					$query = 'UPDATE '.$this->_tbl
					. ' SET ordering = '. (int) $orders[$i]->ordering
					. ' WHERE '. $k .' = '. $this->_db->Quote($orders[$i]->{$k})
					;
					$this->_db->setQuery( $query);
					$this->_db->execute();
				}
			}
		}

	return true;
	}
}
