<?php
/**
*
* Category Model
*
* @package	VirtueMart
* @subpackage Category
* @author Max Milbers
* @author jseros, RickG
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: category.php 10303 2020-04-22 13:27:59Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Model for product categories
 */
class VirtueMartModelCategory extends VmModel {

	private $_category_tree;
	public $_cleanCache = true ;
	static public $_optimisedCatSql = true;

	static $_validOrderingFields = array('category_name','c.ordering,category_name','category_description','c.category_shared','c.published');

	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('categories');

		$this->addvalidOrderingFieldName(self::$_validOrderingFields);

		$toCheck = VmConfig::get('browse_cat_orderby_field','category_name');
		if(!in_array($toCheck, $this->_validOrderingFieldName)){
			$toCheck = 'category_name';
		}
		$this->_selectedOrdering = $toCheck;
		$this->_selectedOrderingDir = VmConfig::get('cat_brws_orderby_dir', 'ASC');
		$this->setToggleName('shared');
		self::$_optimisedCatSql = VmConfig::get('optimisedCatSql', true);
	}


	public function checkIfCached($virtuemart_category_id,$childs=TRUE){
		$childs = (int)$childs;
		return !empty($this->_cache[$virtuemart_category_id][(int)$childs][VmLanguage::$currLangTag]);
	}

	/**
	 * Retrieve the detail record for the current $id if the data has not already been loaded.
	 *
	 * @author RickG, jseros, Max Milbers
	 */
	public function getCategory($virtuemart_category_id=0, $childs=TRUE, $fe = true){

		if(!empty($virtuemart_category_id)) $this->_id = (int)$virtuemart_category_id;
		$childs = (int)$childs;
		//vmdebug('getCategory '.$this->_id.' '.$childs);
		if (isset($this->_cache[$this->_id][$childs][VmLanguage::$currLangTag])) {
			vmdebug('Found cached cat');
			return clone($this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]);
		} else {

			if($childs and !empty($this->_cache[$this->_id][0][VmLanguage::$currLangTag])){
				$this->_cache[$this->_id][1][VmLanguage::$currLangTag] = clone($this->_cache[$this->_id][0][VmLanguage::$currLangTag]);
vmdebug('Found cached cat, but without children');
			} else if(!$childs and !empty($this->_cache[$this->_id][1][VmLanguage::$currLangTag])){
				$t = clone($this->_cache[$this->_id][1][VmLanguage::$currLangTag]);
				$t->children = false;
				$t->haschildren = null;
				$t->productcount = false;
				$t->parents = false;
				$this->_cache[$this->_id][0][VmLanguage::$currLangTag] = $t;
				vmdebug('Use already loaded category with children');
				return $t;

			} else {
				$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag] = $this->getTable('categories');
				if(!empty($this->_id)){
					$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->_langTag = VmConfig::$vmlang;
					$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->load($this->_id);

					$xrefTable = $this->getTable('category_medias');
					$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->virtuemart_media_id = $xrefTable->load((int)$this->_id);
				} else {
					$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->virtuemart_media_id = false;
				}

				//Fallbacks
				$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->categorytemplate = $this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->category_template;
				$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->categorylayout = $this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->category_layout;
				$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->productlayout = $this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->category_product_layout;

			}

			$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->children = false;

			$updateCategory = false;
			if(!isset($this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->has_children)){
				$updateCategory = true;
			}

			if(!isset($this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->has_children) or empty($virtuemart_category_id)){
				$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->has_children = 1;
			}
			$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->haschildren = &$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->has_children;

			$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->productcount = false;
			$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->parents = null;

			if($childs){
				//$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->haschildren = $this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->has_children;

				/* Get children if they exist */
				if ($this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->has_children) {
					//$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->children = $this->getCategories( true, $this->_id );
					if(empty($virtuemart_category_id) and empty($this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->virtuemart_vendor_id)){
						$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->virtuemart_vendor_id = 1;
					}
					$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->children = $this->getChildCategoryList($this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->virtuemart_vendor_id, $this->_id );
				}

				/* Get the product count */
				$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->productcount = $this->countProducts($this->_id);

				/* Get parent for breadcrumb */
				$this->_cache[$this->_id][$childs][VmLanguage::$currLangTag]->parents = $this->getParentsList($this->_id);
			}

			if($updateCategory){
				self::updateCategory($this->_id,$this->_db);
			}
		}

		return $this->_cache[$this->_id][$childs][VmLanguage::$currLangTag];
	}

	/**
	 * Get the list of child categories for a given category, is cached
	 *
	 * @param int $virtuemart_category_id Category id to check for child categories
	 * @return object List of objects containing the child categories
	 *
	 */
	public function getChildCategoryList($vendorId, $virtuemart_category_id,$selectedOrdering = null, $orderDir = null, $useCache = true) {

		if(empty($this) or get_class($this)!='VirtueMartModelCategory'){
			$useCache = false;
		} else {
			$useCache = VmConfig::get('UseCachegetChildCategoryList',false);
		}
		//$useCache = false;
		if($selectedOrdering===null){
			if($useCache){
				$selectedOrdering = $this->_selectedOrdering;
			} else {
				$selectedOrdering = VmConfig::get('browse_cat_orderby_field','category_name');
			}
		}

		if(trim($selectedOrdering) == 'c.ordering'){
			$selectedOrdering = 'c.ordering,category_name';
		}

		if(empty($selectedOrdering) or !in_array($selectedOrdering, self::$_validOrderingFields)){
			$selectedOrdering = 'c.ordering,category_name';
		}

		if($orderDir===null){
			if($useCache){
				$orderDir = $this->_selectedOrderingDir;
			} else {
				$orderDir = VmConfig::get('cat_brws_orderby_dir', 'ASC');
			}
		}

		$validOrderingDir = array('ASC','DESC');
		if(!in_array(strtoupper($orderDir), $validOrderingDir)){
			$orderDir = 'ASC';
		}

		static $_childCategoryList = array ();

		$onlyPublished = false;
		if(VmConfig::isSite() or !vmAccess::manager('category')){
			$onlyPublished = true;
		}
		//$key = (int)$vendorId.'_'.(int)$virtuemart_category_id.$selectedOrdering.$orderDir.VmLanguage::$currLangTag ;
		//We have here our internal key to preven calling of the cache
		//if (! array_key_exists ($key,$_childCategoryList)){
		vmSetStartTime('com_virtuemart_cat_childs');

		if($useCache){
			$cache = VmConfig::getCache('com_virtuemart_cat_childs','callback');
			$cache->setCaching(true);
			vmdebug('Calling cache getChildCategoryListObject');
			$cats = $cache->call( array( 'VirtueMartModelCategory', 'getChildCategoryListObject' ),$vendorId, $virtuemart_category_id, false, $onlyPublished, true, '', $selectedOrdering, $orderDir, 0, 0);
			vmTime('getChildCategoryList getChildCategoryListObject cached '.$virtuemart_category_id,'com_virtuemart_cat_childs');
			return $cats;
		} else {
			$cats = VirtueMartModelCategory::getChildCategoryListObject($vendorId, $virtuemart_category_id, false, $onlyPublished, true, '', $selectedOrdering, $orderDir, 0, 0);
			vmTime('getChildCategoryList getChildCategoryListObject '.$virtuemart_category_id,'com_virtuemart_cat_childs');
			return $cats;
		}

	}

	/**
	 * Be aware we need the lang to assure that the cache works properly. The cache needs all parameters
	 * in the function call to use the right hash
	 *
	 * @author Max Milbers
	 * @param $vendorId
	 * @param $virtuemart_category_id
	 * @param null $selectedOrdering
	 * @param null $orderDir
	 * @param $lang
	 * @return mixed
	 */
	static public function getChildCategoryListObject($vendorId, $virtuemart_category_id = 0, $childId = false, $onlyPublished = true, $media = true, $keyword = '', $selectedOrdering = null, $orderDir = null, $limitStart = 0, $limit = 0) {
		vmSetStartTime('getChildCategoryListObject');
		static $cats = array ();
		$h = (int)$vendorId.'_'.(int)$virtuemart_category_id.'_'.$childId.$selectedOrdering.(int)$onlyPublished.$orderDir.(int)$media.VmLanguage::$currLangTag.$limitStart.$keyword.$limit.$selectedOrdering.$orderDir ;
		if ( isset($cats[$h])){
			//vmdebug('getChildCategoryListObject return cached'.$h);
			return $cats[$h];
		}

		$langFields = array('category_name','category_description','metadesc','metakey','customtitle','slug');

		$join = ' FROM #__virtuemart_categories as c '.implode(' ',self::joinLangTables('#__virtuemart_categories','c','virtuemart_category_id'));

		if(self::$_optimisedCatSql){
			$select = ' c.category_parent_id, c.`ordering`';
		} else {
			$select = ' cx.category_parent_id, cx.`ordering`';
			$join .= ' INNER JOIN `#__virtuemart_category_categories` as cx on c.`virtuemart_category_id` = cx.`category_child_id` ';
		}
		$select .= ', c.virtuemart_category_id, c.category_parent_id, c.virtuemart_vendor_id, c.category_template, c.category_layout, c.category_product_layout, c.products_per_row, c.limit_list_step, c.limit_list_initial, c.hits, c.cat_params, c.metarobot, c.metaauthor, c.shared, c.`published`, c.has_children, c.has_medias, '.implode(', ',self::joinLangSelectFields($langFields));


		$where = array();

		if($childId){
			$where[]= ' c.`virtuemart_category_id` = ' . (int)$childId . ' ';
		} else if ( empty( $keyword )){
			if(self::$_optimisedCatSql){
				$orNull= '';
				if(empty($virtuemart_category_id)){
					$orNull = 'OR isNull(c.`category_parent_id`)';
				}
				$where[]= ' (c.`category_parent_id` = ' . (int)$virtuemart_category_id . ' '.$orNull.')';

			} else {
				$where[]= ' cx.`category_parent_id` = ' . (int)$virtuemart_category_id . ' ';
			}

		}

		if(VmConfig::get('multix')!='none'){
			if(empty($vendorId)){
				$where[]= ' c.`shared` = 1 ' ;
			} else {
				$where[]= ' (c.`virtuemart_vendor_id` = ' . (int)$vendorId .' OR c.`shared` = 1) ';
			}
		}

		if($onlyPublished) {
			$where[]= ' c.`published` = 1 ';
		}

		/*if(!empty($where)){
			$query .= 'WHERE '.implode(' AND ',$where);
		}/*/

		$whereOr = array();
		if( !empty( $keyword ) ) {
			$db = JFactory::getDBO();
			$keyword = $db->escape( $keyword, true );
			$keyword =  '"%' .str_replace(array(' ','-'),'%', $keyword). '%"';
			//$keyword = $db->escape( $keyword, true );
			$fields = self::joinLangLikeFields($langFields,$keyword);
			$whereOr = array_merge($whereOr, $fields);
		}

		$whereString = '';
		if (count($where) > 0 or count($whereOr)){
			$whereString = ' WHERE ';
			if (count($where) > 0){
				$whereString .= implode(' AND ', $where);
				if (count($whereOr) > 0){
					$whereString .= ' AND ';
				}
			}
			if (count($whereOr) > 0){
				$whereString .= '('.implode(' OR ', $whereOr).')';
			}
		} else {
			$whereString = '';
		}

		$query = 'SELECT '.$select.' '.$join.' '.$whereString;
		if(!empty($selectedOrdering)) {
			$query .= ' ORDER BY '.$selectedOrdering.' '.$orderDir;
		}

		if($limitStart and $limit){
			$query .= ' LIMIT '.$limitStart.','.$limit;
		} else if($limit){
			$query .= ' LIMIT '.$limit;
		}


		$db = JFactory::getDBO();
		$db->setQuery( $query );
		$childList = $db->loadObjectList();

		//vmdebug('getChildCategoryListObject',$query,$childList);

		if(!empty($childList)){
			if($media or !self::$_optimisedCatSql){
				$xrefTable = new TableCategory_medias($db);
				foreach($childList as $child){
					if(!self::$_optimisedCatSql){
						$child->has_medias = 1;
						$child->has_children = 1;
					}
					if(!isset($child->has_children) or !isset($child->has_medias) or !isset($child->category_parent_id)){
						self::updateCategory($child->virtuemart_category_id,$db);
					}
					if($child->has_medias){

						$xrefTable->_loaded = false;
						$xrefTable->virtuemart_category_id = 0;
						$xrefTable->virtuemart_media_id = array();
						$xrefTable->ordering = 0;
						$child->virtuemart_media_id = $xrefTable->load($child->virtuemart_category_id);
					} else {
						$child->virtuemart_media_id = false;
					}

				}
			}
		}

		//$count = count($childList);
		//vmdebug('getChildCategoryListObject count result '.$query,$count );
		$cats[$h] = $childList;
		vmTime('getChildCategoryListObject summed up','getChildCategoryListObject',false);
		return $childList;
	}


	public function getCategoryTree($parentId=0, $level = 2, $onlyPublished = true,$keyword = '', $limitStart = '',$limit = '', $tree = false){

		$sortedCats = array();

		if($limitStart === '' or $limit === ''){
			$limits = $this->setPaginationLimits();
			if($limitStart === '') $limitStart = $limits[0];
			if($limit === '') $limit = $limits[1];
		}

		$this->_noLimit = true;



		if($keyword!=''){
			//$sortedCats = self::getCategories($onlyPublished, false, false, $keyword);
			$vendorId = vmAccess::isSuperVendor();
			$sortedCats = $this->getChildCategoryListObject( $vendorId, $parentId, false, $onlyPublished, true, $keyword);
			if(!empty($sortedCats)){
				$siblingCount = count($sortedCats);
				foreach ($sortedCats as $key => &$category) {
					$category->siblingCount = $siblingCount;
				}
			}
			$this->_total = count($sortedCats);

		} else {
			$vendorId = vmAccess::isSuperVendor();
			//$sortedCats = self::getChildCategoryListObject($vendorId, $parentId, $this->_selectedOrdering,$this->_selectedOrderingDir);
			//$this->rekurseCats($parentId,$level,$onlyPublished,$keyword,$sortedCats, 0, $limit );
			$this->rekurseCategories($vendorId, $parentId, $sortedCats, $level, 0, $limitStart + $limit, $onlyPublished, $keyword, $this->_selectedOrdering,$this->_selectedOrderingDir, $tree);
			//vmdebug('My sorted cats ',$sortedCats);
			$this->_total = count($sortedCats);
		}

		$this->_noLimit = false;


		$this->_limitStart = $limitStart;
		$this->_limit = $limit;

		$this->getPagination();

		if(empty($limit)){
			//vmdebug('my $sortedCats sliced by  NO LIMIT',$sortedCats);
			return $sortedCats;
		} else {


			$sortedCats = array_slice($sortedCats, $limitStart,$limit);

			return $sortedCats;
		}

	}

	static public function rekurseCategories($vendorId, $parentId, &$cats, $level = 2, $limitStart = 0, $limit = 0, $onlyPublished = true, $keyword = '', $selectedOrdering = 'c.ordering, category_name', $selectedOrderingDir = 'ASC', $tree = false, $deep = 0, &$parentCategory = false){
		$media = true;

		if(($deep===0 or empty($level) or $deep <= $level) /*and (empty($limit) or count($cats)<$limit)*/){

			$children = self::getChildCategoryListObject($vendorId, $parentId, false, $onlyPublished, $media, $keyword, $selectedOrdering, $selectedOrderingDir, $limitStart, $limit);

			$siblingCount = count($children);

			if($tree and $parentCategory){
				$parentCategory->childs = &$children;
				$parentCategory->children = &$parentCategory->childs;
			}

			if($siblingCount){
				foreach ($children as $key => $category) {
					$category->level = $deep;
					$category->siblingCount = $siblingCount;
					if((!$tree or $deep===0) and !isset($cats[$category->virtuemart_category_id])){
						$cats[$category->virtuemart_category_id] = $category;
					}

					if($category->has_children){
						$deep++;
						self::rekurseCategories($vendorId, $category->virtuemart_category_id, $cats, $level, 0, $limit, $onlyPublished, $keyword, $selectedOrdering, $selectedOrderingDir, $tree, $deep, $category);
						$deep--;
					}
				}
			}
		} else {
			if($deep===0 or empty($level) or $deep <= $level){
				vmdebug('rekurseCategories stopped More cats ('.count($cats).') for $parentId = '.$parentId.' than limit '.$limit);
			} else {
				//vmdebug('rekurseCategories stopped reached Deepnees',$deep,$level);
			}

		}

	}

	public function rekurseCats($virtuemart_category_id, $level, $onlyPublished, $keyword, &$sortedCats, $deep=0, $limit = 0){

		if(($deep===0 or empty($level) or $deep <= $level) and $childs = $this->hasChildren($virtuemart_category_id) and (empty($limit) or count($sortedCats) < $limit) ){

			$childCats = self::getCategories($onlyPublished, $virtuemart_category_id, false, $keyword);

			if(!empty($childCats)){
				$siblingCount = count($childCats);
				foreach ($childCats as $key => $category) {
					$category->level = $deep;
					$category->siblingCount = $siblingCount;
					$sortedCats[] = $category;
					if($category->has_children){
						$deep++;
						$this->rekurseCats($category->virtuemart_category_id,$level,$onlyPublished,$keyword,$sortedCats, $deep, $limit);
						$deep--;
					}
				}
			}
		} else {
			vmdebug('rekurseCategories stopped ',$deep,$level,$limit,count($sortedCats));
		}
	}

	/**
	 * @deprecated use getChildCategoryListObject instead.
	 * @param bool $onlyPublished
	 * @param bool $parentId
	 * @param bool $childId
	 * @param string $keyword
	 * @param bool $vendorId
	 * @return mixed
	 */
	public function getCategories($onlyPublished = true, $parentId = false, $childId = false, $keyword = "", $vendorId = false) {

		//return $this->getChildCategoryListObject( $vendorId, $parentId, $childId, $onlyPublished, true, $keyword);
		static $cats = array();

		$select = ' c.`virtuemart_category_id`, c.`ordering`, c.`published`, c.`category_parent_id`, c.`shared`, c.`has_children` ';

		$joins = ' FROM `#__virtuemart_categories` as c ';

		$where = array();

		if( $onlyPublished ) {
			$where[] = " c.`published` = 1 ";
		}
		if( $parentId !== false ){
			$where[] = ' c.`category_parent_id` = '. (int)$parentId;
		}

		if( $childId !== false ){
			$where[] = ' c.`category_child_id` = '. (int)$childId;
		}

		if($vendorId===false){
			$vendorId = vmAccess::isSuperVendor();
		}

		if($vendorId!=1){
			if(VmConfig::isSite() and $vendorId==0){
				$vendorId = 1;
			}
			$where[] = ' (c.`virtuemart_vendor_id` = "'. (int)$vendorId. '" OR c.`shared` = "1") ';
		}

		$langFields = array('category_description','category_name');

		$select .= ', '.implode(', ',self::joinLangSelectFields($langFields));
		$joins .= implode(' ',self::joinLangTables($this->_maintable,'c','virtuemart_category_id'));

		$joins .= ' LEFT JOIN `#__virtuemart_category_categories` AS cx ON c.`virtuemart_category_id` = cx.`category_child_id`';


		$whereOr = array();
		if( !empty( $keyword ) ) {
			$db = JFactory::getDBO();
			$keyword = $db->escape( $keyword, true );
			$keyword =  '"%' .str_replace(array(' ','-'),'%', $keyword). '%"';
			//$keyword = $db->escape( $keyword, true );
			$fields = self::joinLangLikeFields($langFields,$keyword);
			$whereOr = array_merge($whereOr, $fields);
		}

		$whereString = '';
		if (count($where) > 0 or count($whereOr)){
			$whereString = ' WHERE ';
			if (count($where) > 0){
				$whereString .= implode(' AND ', $where);
				if (count($whereOr) > 0){
					$whereString .= ' AND ';
				}
			}
			if (count($whereOr) > 0){
				$whereString .= '('.implode(' OR ', $whereOr).')';
			}
		} else {
			$whereString = 'WHERE 1 ';
		}

		if(trim($this->_selectedOrdering) == 'c.ordering'){
			$this->_selectedOrdering = 'c.ordering, category_name';
		}
		$ordering = $this->_getOrdering();

		$hash = crc32($keyword.'.'.(int)$parentId.VmLanguage::$currLangTag.(int)$childId.$this->_selectedOrderingDir.(int)$vendorId.$this->_selectedOrdering);
		if(!isset($cats[$hash])){
			$cats[$hash] = $this->_category_tree = $this->exeSortSearchListQuery(0,$select,$joins,$whereString,'GROUP BY virtuemart_category_id',$ordering );
			$this->_total = count($cats[$hash]);
		}

		return $cats[$hash];

	}

	/**
	 * Gets the total number of entries per vendor
	 * @author Max Milbers
	 * @return int Total number of entries in the database
	 */
	public function getTotal() {

		if (empty($this->_total)) {
			$db = JFactory::getDbo();
			$vendorId = vmAccess::isSuperVendor();
			$venWhere = '';
			if(!empty($vendorId)){
				$venWhere = ' WHERE virtuemart_vendor_id = '.$vendorId.' ';
			}
			$query = 'SELECT `'.$this->_db->escape($this->_idName).'` FROM `'.$this->_db->escape($this->_maintable).'` '.$venWhere;;
			$db->setQuery( $query );
			if(!$db->execute()){
				if(empty($this->_maintable)) vmError('Model '.get_class( $this ).' has no maintable set');
				$this->_total = 0;
			} else {
				$this->_total = $db->getNumRows();
			}
		}

		return $this->_total;
	}

	/**
	 * count the products in a category
	 *
	 * @author Max Milbers
	 * @return array list of categories product is in
	 */
	public function countProducts($cat_id=0) {

		$db = JFactory::getDBO();
		$vendorId = 1;
		if ($cat_id > 0) {
			$q = 'SELECT count(`p`.virtuemart_product_id) AS total	
	  FROM `#__virtuemart_product_categories` as `pc`
	  LEFT JOIN `#__virtuemart_products` as `p` ON `pc`.virtuemart_product_id = `p`.virtuemart_product_id
	  WHERE `pc`.`virtuemart_category_id` = "'.(int)$cat_id.'"
	  AND `p`.`virtuemart_vendor_id` = "'.(int)$vendorId.'"
	  AND `p`.`published` = "1" ';
			$db->setQuery($q);
			$count = $db->loadResult();
		} else $count=0 ;

		return $count;
	}


	/**
	 * Order any category
	 *
	 * @author jseros
	 * @param  int $id category id
	 * @param  int $movement movement number
	 * @return bool
	 */
	public function orderCategory($id, $movement){
		//retrieving the category table object
		//and loading data
		$row = $this->getTable('categories');
		$row->load($id);

		if (!$row->move( $movement, 'category_parent_id = "'.(int)$row->category_parent_id.'"' )) {
			return false;
		}

		return true;
	}


	/**
	 * Order category group
	 *
	 * @author jseros
	 * @param  array $cats categories to order
	 * @return bool
	 */
	public function setOrder($cats, $order){

		$total		= count( $cats );
		$groupings	= array();
		$row = $this->getTable('categories');
		$rowLegacy = $this->getTable('category_categories');

		$query = 'SELECT `category_parent_id` FROM `#__virtuemart_categories` c
			      WHERE c.`virtuemart_category_id` = %s';

		$db = JFactory::getDBO();
		// update ordering values
		for( $i=0; $i < $total; $i++ ) {

			$row->load( $cats[$i] );
			$db->setQuery( sprintf($query,  (int)$cats[$i] ), 0 ,1 );
			$parent = $db->loadObject();

			$groupings[] = $parent->category_parent_id;
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->toggle('ordering',$row->ordering)) {
					return false;
				}
				/*$q = 'UPDATE #__virtuemart_category_categories SET ordering ="'.$row->ordering.'" WHERE category_child_id = "'.$cats[$i].'" ';
				$this->_db->setQuery($q);
				$this->_db->execute();*/

			}
		}

		// execute reorder for each parent group
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){

			$row->fixOrdering('category_parent_id = "'.(int)$group.'"');
			$row->synchroniseTableOrdering($group);
		}

		$this->clearCategoryRelatedCaches();

		return true;
	}

	/**
	 * Retrieve the detail record for the parent category of $categoryd
	 *
	 * @deprecated
	 * @author jseros
	 * @param int $categoryId Child category id
	 * @return JTable parent category data
	 */
	public function getParentCategory( $categoryId = 0 ){
		$data = $this->getRelationInfo( $categoryId );
		$parentId = isset($data->category_parent_id) ? $data->category_parent_id : 0;

		$parent = $this->getTable('categories');
		$parent->load((int) $parentId);

		return $parent;
	}


	/**
	 * Retrieve category child-parent relation record
	 *
	 * @deprecated
	 * @author jseros
	 * @param int $virtuemart_category_id
	 * @return object Record of parent relation
	 */
	public function getRelationInfo( $virtuemart_category_id = 0 ){

		$db = JFactory::getDBO();
		$query = 'SELECT `category_parent_id`, `ordering`
    			  FROM `#__virtuemart_category_categories`
    			  WHERE `category_child_id` = '. (int)$virtuemart_category_id;
		$db->setQuery($query);

		return $db->loadObject();
	}


	/**
	 * Bind the post data to the category table and save it
	 *
	 * @author jseros, Max Milbers
	 * @return int category id stored
	 */
	public function store(&$data) {

		vRequest::vmCheckToken();

		if(!vmAccess::manager('category.edit')){
			vmWarn('Insufficient permission to store category');
			return false;
		} else if( empty($data['virtuemart_category_id']) and !vmAccess::manager('category.create')){
			vmWarn('Insufficient permission to create category');
			return false;
		}

		$table = $this->getTable('categories');

		if ( !array_key_exists ('category_template' , $data ) ){
			$data['category_template'] = $data['category_layout'] = $data['category_product_layout'] = '' ;
		}

		$data['category_template'] = isset($data['categorytemplate'])? $data['categorytemplate']:null;
		$data['category_layout'] = isset($data['categorylayout'])? $data['categorylayout']:null;
		$data['category_product_layout'] = isset($data['productlayout'])? $data['productlayout']:null;

		if($data['category_parent_id'] == $data['virtuemart_category_id']){
			$data['category_parent_id'] = 0;
		}

		$data['has_children'] = 0;
		if(!empty($data['virtuemart_category_id'])){
			$data['has_children'] = (int)$this->hasChildren($data['virtuemart_category_id']);
		}


		$table->bindChecknStore($data);

		if(!empty($data['virtuemart_category_id'])){
			$xdata['category_child_id'] = (int)$data['virtuemart_category_id'];
			$xdata['category_parent_id'] = empty($data['category_parent_id'])? 0:(int)$data['category_parent_id'];
			$xdata['ordering'] = empty($data['ordering'])? 0: (int)$data['ordering'];

			$tableXref = $this->getTable('category_categories');

			$tableXref->bindChecknStore($xdata);

		}

		// Process the images
		$mediaModel = VmModel::getModel('Media');
		$file_id = $mediaModel->storeMedia($data,'category');

		$file_id = empty($file_id)? 0: 1;

		if($table->has_medias!=$file_id){
			$q = 'UPDATE #__virtuemart_categories SET `has_medias`='.(int)$file_id.'
	WHERE  `virtuemart_category_id`='.$data['virtuemart_category_id'].';';
			$db = JFactory::getDbo();
			$db->setQuery($q);
			$db->execute();
		}


		$this->clearCategoryRelatedCaches();

		if(!empty($data['category_parent_id'])){
			$q = 'UPDATE #__virtuemart_categories SET `has_children`= 1
	WHERE  `virtuemart_category_id`='.$data['category_parent_id'].';';
			$db = JFactory::getDbo();
			$db->setQuery($q);
			$db->execute();
		}

		return $data['virtuemart_category_id'] ;
	}

	/**
	 * Delete all categories selected
	 *
	 * @author jseros
	 * @param  array $cids categories to remove
	 * @return boolean if the item remove was successful
	 */
	public function remove($cids) {

		vRequest::vmCheckToken();

		if(!vmAccess::manager('category.delete')){
			vmWarn('Insufficient permissions to delete category');
			return false;
		}

		$table = $this->getTable('categories');

		foreach($cids as &$cid) {

			//Update Parent "has_children"
			$table->load($cid);
			if($table->category_parent_id){
				$has_children = $this->hasChildren($table->category_parent_id);
				$q = 'UPDATE #__virtuemart_categories SET `has_children`='.$has_children.'
	WHERE  `virtuemart_category_id`='.$table->category_parent_id.';';
				$db = JFactory::getDbo();
				$db->setQuery($q);
				$db->execute();
			}

			if (!$table->delete($cid)) {
				return false;
			}

			$db = JFactory::getDbo();
			$q = 'SELECT `virtuemart_customfield_id` FROM `#__virtuemart_product_customfields` as pc ';
			$q .= 'LEFT JOIN `#__virtuemart_customs`as c ON pc.`virtuemart_custom_id` = c.`virtuemart_custom_id` WHERE pc.`customfield_value` = "' . $cid . '" AND `field_type`= "Z"';
			$db->setQuery($q);
			$list = $db->loadColumn();

			if ($list) {
				$listInString = implode(',',$list);
				//Delete media xref
				$query = 'DELETE FROM `#__virtuemart_product_customfields` WHERE `virtuemart_customfield_id` IN ('. $listInString .') ';
				$db->setQuery($query);
				if(!$db->execute()){
					vmError( $db->getErrorMsg() );
				}
			}
		}

		$cidInString = implode(',',$cids);

		//Delete media xref
		$query = 'DELETE FROM `#__virtuemart_category_medias` WHERE `virtuemart_category_id` IN ('. $cidInString .') ';
		$db->setQuery($query);
		if(!$db->execute()){
			vmError( $db->getErrorMsg() );
		}

		//deleting product relations
		$query = 'DELETE FROM `#__virtuemart_product_categories` WHERE `virtuemart_category_id` IN ('. $cidInString .') ';
		$db->setQuery($query);

		if(!$db->execute()){
			vmError( $db->getErrorMsg() );
		}

		//deleting category relations
		$query = 'DELETE FROM `#__virtuemart_category_categories` WHERE `category_child_id` IN ('. $cidInString .') ';
		$db->setQuery($query);

		if(!$db->execute()){
			vmError( $db->getErrorMsg() );
		}

		//updating parent relations
		$query = 'UPDATE `#__virtuemart_category_categories` SET `category_parent_id` = 0 WHERE `category_parent_id` IN ('. $cidInString .') ';
		$db->setQuery($query);

		if(!$db->execute()){
			vmError( $db->getErrorMsg() );
		}

		$this->clearCategoryRelatedCaches();

		return true;
	}

	public function clearCategoryRelatedCaches(){

		$cache = VmConfig::getCache();
		$cache->clean('com_virtuemart_cats');
		$cache->clean('com_virtuemart_cat_childs');
		$cache->clean('mod_virtuemart_product');
		$cache->clean('mod_virtuemart_category');
	}

	/**
	 * Checks for children of the category $virtuemart_category_id
	 *
	 * @param int $virtuemart_category_id the category ID to check
	 * @return boolean true when the category has childs, false when not
	 */
	static public function hasChildren($virtuemart_category_id, $useXref = false) {

		static $hasChildrenCache=array();
		if(!isset($hasChildrenCache[$virtuemart_category_id])){
			$db = JFactory::getDBO();
			if(self::$_optimisedCatSql and !$useXref){
				$q = 'virtuemart_category_id FROM `#__virtuemart_categories`';
			} else {
				$q = '`category_child_id` FROM `#__virtuemart_category_categories`';
			}

			$db->setQuery('SELECT '. $q .' WHERE `category_parent_id` = "'.(int)$virtuemart_category_id.'"');
			$db->execute();
			$res = $db->loadResult();

			if ($res){
				$hasChildrenCache[$virtuemart_category_id] = true;
			} else {
				$hasChildrenCache[$virtuemart_category_id] = false;
			}
		}
		return $hasChildrenCache[$virtuemart_category_id];
	}

	/**
	 * Creates a bulleted of the childen of this category if they exist
	 *
	 * @todo Add vendor ID
	 * @param int $virtuemart_category_id the category ID to create the list of
	 * @return array containing the child categories
	 */
	public function getParentsList($virtuemart_category_id) {

		$db = JFactory::getDBO();
		$menu = JFactory::getApplication()->getMenu();
		$parents = array();
		$Itemid = vRequest::getInt('Itemid',false);
		if (empty($Itemid)) {
			$menuItem = $menu->getActive();
		} else {
			$menuItem = $menu->getItem($Itemid);
		}
		$menuCatid = (empty($menuItem->query['virtuemart_category_id'])) ? 0 : $menuItem->query['virtuemart_category_id'];
		if ($menuCatid == $virtuemart_category_id) return ;

		$this->categoryRecursed = 0;
		$tCats = $this->getCategoryRecurse($virtuemart_category_id,$menuCatid);
		if(!$tCats) return false;

		$parents_id = array_reverse($tCats);

		//$useFb = vmLanguage::getUseLangFallback();
		//$useFb2 = vmLanguage::getUseLangFallbackSecondary();

		$langFields = array('virtuemart_category_id','category_name');

		$select = 'SELECT '.implode(', ',self::joinLangSelectFields($langFields)).', published';
		$joins = 'FROM `#__virtuemart_categories` as c '.implode(' ',self::joinLangTables('#__virtuemart_categories','c','virtuemart_category_id'));

		$where = 'WHERE '.implode(', ',self::joinLangSelectFields(array('virtuemart_category_id'),false)).' = ';
		$q = $select.' '.$joins.' '.$where;

		foreach ($parents_id as $id ) {
			$db->setQuery($q.(int)$id);
			if($db->getErrorMsg()){
				vmError('Error in sql ',$db->getErrorMsg());
			}
			if($cat=$db->loadObject()){
				$parents[] = $cat;
			} else {
				if(VmConfig::$echoAdmin){
					vmWarn('category with id '.(int)$id.' is missing the main language ');
				}

			}
		}

		return $parents;
	}

	public $categoryRecursed = 0;

	public function getCategoryRecurse($virtuemart_category_id,$catMenuId,$idsArr=true ) {

		static $resId = array();

		if(empty($virtuemart_category_id)) return array();

		if($idsArr and !is_array($idsArr)){
			$idsArr = array();
			$this->categoryRecursed = 0;
		} else if($this->categoryRecursed>10){
			vmWarn('Stopped getCategoryRecurse after 10 rekursions');
			return false;
		}

		$hash = $virtuemart_category_id.'c'.$catMenuId;

		if(isset($resId[$hash])){
			$ids = $resId[$hash];
		} else if (!empty($virtuemart_category_id)){
			$db	= JFactory::getDBO();
			if(self::$_optimisedCatSql){
				$q = "SELECT `virtuemart_category_id` AS `child`, `category_parent_id` AS `parent`
				FROM  #__virtuemart_categories 
				WHERE `virtuemart_category_id`= ".(int)$virtuemart_category_id;
			} else {
				$q = "SELECT `category_child_id` AS `child`, `category_parent_id` AS `parent`
				FROM  #__virtuemart_category_categories AS `xref`
				WHERE `xref`.`category_child_id`= ".(int)$virtuemart_category_id;
			}

			$db->setQuery($q);
			$ids = $resId[$hash] = $db->loadObject();
		}

		if (isset ($ids->child)) {
			$idsArr[] = $ids->child;
			if($ids->parent != 0 and $catMenuId != $virtuemart_category_id and $catMenuId != $ids->parent) {
				$this->categoryRecursed++;
				$idsArr = $this->getCategoryRecurse($ids->parent,$catMenuId,$idsArr);
			}
		}
		return $idsArr ;
	}


	function toggle($field,$val = NULL, $cidname = 0,$tablename = 0, $view = false  ) {
		$result = parent::toggle($field,$val, $cidname, $tablename, $view );
		$this->clearCategoryRelatedCaches();
		return $result;
	}

	static function updateCategories(){

		$db = JFactory::getDbo();

		$q = 'SELECT * FROM #__virtuemart_categories WHERE has_children is NULL OR has_medias is NULL OR category_parent_id is NULL';
		$db->setQuery($q);
		$cats = $db->loadObjectlist();

		foreach($cats as $cat){
			self::updateCategory($cat->virtuemart_category_id,$db);
		}
	}

	static function updateCategory($catId,$db){

		if(empty($catId)) return;

		$has_children = self::hasChildren($catId, true);
		$q = 'SELECT category_parent_id, ordering FROM #__virtuemart_category_categories WHERE category_child_id = '.$catId.' ;';
		$db->setQuery($q);
		$xrefRes = $db->loadAssoc();

		$q = 'SELECT count(*) FROM #__virtuemart_category_medias WHERE virtuemart_category_id = '.$catId.' ;';
		$db->setQuery($q);
		$has_medias = $db->loadResult();

		$q = 'UPDATE #__virtuemart_categories SET `category_parent_id`='.(int)$xrefRes['category_parent_id'].', `ordering`='.(int)$xrefRes['ordering'].', `has_children`='.(int)$has_children.', `has_medias`='.(int)$has_medias.'
	WHERE  `virtuemart_category_id`='.$catId.';';
		$db->setQuery($q);
		$db->execute();
		//vmdebug('my updateCategory',$q);
	}
}