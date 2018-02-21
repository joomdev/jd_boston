<?php
/*------------------------------------------------------------------------
# Plg_vmcloudzoom : Joomla Virtuemart images product zoom Plugin
# ------------------------------------------------------------------------
# author: Lamvt Vinaora Team
# copyright Copyright (C) 2012 joomquery.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomquery.com
# Technical Support: http://joomquery.com/en/home/our-products/8215-vt-jqzoom-virtuemart-joomla-plugin.html
-------------------------------------------------------------------------*/

defined('JPATH_BASE') or die;

jimport('joomla.application.component.helper');
class PlgSystemVmCloudZoom extends JPlugin{

	public function onBeforeRender() {
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$view = JRequest::getVar('view');
		if ($app->isAdmin()){
					return true;
				}
			if ($view !='productdetails'){
				return true;
			}
		$pluginLivePath = JURI::root(true).'/plugins/system/vmcloudzoom/';
		$show_jquery = $this->params->get('show_jquery',1);
			if($show_jquery==1){
				$doc->addScript($pluginLivePath.'js/jquery-1.6.js');
			}
		
			$doc->addScript($pluginLivePath.'js/cloud-zoom.1.0.2.js');
			$doc->addStyleSheet($pluginLivePath.'css/cloud-zoom.css');	
	}
	public function onAfterRender() {
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$view = JRequest::getVar('view');
		if ($app->isAdmin()){
				return true;
			}
		if ($view !='productdetails'){
			return true;
		}
						
		$buffer = JResponse::getBody();		
		$regx = '/<div class="main-image">([^`]*?)<\/div>/';
		$regx2 = '/<div class="floatleft">([^`]*?)<\/div>/';
		$regx3 = '/<div class="additional-images">/';
		$getMainImage = $this->getMainImage();
		$getAddImages = $this->getAddImages();
		
		
		$buffer = preg_replace($regx2,'',$buffer);		
		$buffer = preg_replace($regx3,$getAddImages,$buffer);		
		$buffer = preg_replace($regx,$getMainImage,$buffer);
			$turn_link = $this->params->get('turn_link',1);
			if($turn_link == 1){
				$authorlink = '<a style="font-size:0.5px;" href="http://joomquery.com" title="joomla template free">JoomQuery</a>';
				$link_reg = '/<\/body>/';
				$buffer = preg_replace($link_reg,$authorlink,$buffer);
			}
		JResponse::setBody($buffer);

		return true;
	}
	
	function getCustomJs(){
		$zoomWidth = 		trim($this->params->get('zoomWidth',250));
		$zoomHeight = 		trim($this->params->get('zoomHeight',250));
		$xOffset = 			trim($this->params->get('xOffset',10));
		$yOffset = 			trim($this->params->get('yOffset',0));
		$position = 		$this->params->get('position','right');
		$tint = 			$this->params->get('tint','#aa00aa');
		$tintOpacity = 		$this->params->get('tintOpacity',0.5);
		$lensOpacity = 		$this->params->get('lensOpacity',0.5);
		$softFocus = 		$this->params->get('softFocus',1);
							if($softFocus==1){$softFocus ="true";}else{$softFocus ="false";}
		$smoothMove = 		$this->params->get('smoothMove',3);					
		$showTitle = 		$this->params->get('showTitle',1);					
							if($showTitle==1){$showTitle ="true";}else{$showTitle ="false";}		
		$titleOpacity = 		trim($this->params->get('titleOpacity',0.5));
		
		
		$custome_js  = "";
		$custome_js  .= "zoomWidth: ".$zoomWidth.",";
        $custome_js  .= "zoomHeight: ".$zoomHeight.",";
        $custome_js  .= "position: '".$position."',";
        $custome_js  .= "tint: '".$tint."',";
        $custome_js  .= "tintOpacity: ".$tintOpacity.",";
        $custome_js  .= "lensOpacity: ".$lensOpacity.",";
        $custome_js  .= "softFocus: ".$softFocus.",";
        $custome_js  .= "smoothMove: ".$smoothMove.",";
        $custome_js  .= "showTitle: ".$showTitle.",";
        $custome_js  .= "titleOpacity: ".$titleOpacity.",";
        $custome_js  .= "adjustX: ".$xOffset.",";
        $custome_js  .= "adjustY: ".$yOffset;
	
	return $custome_js;
		
	}
	
	function getMainImage(){
		if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
		$config = VmConfig::loadConfig();
		$pluginLivePath = JURI::root(true).'/plugins/system/vmcloudzoom/';
		$product_model = VmModel::getModel('product');
		$virtuemart_product_id = JRequest::getInt('virtuemart_product_id', 0);
		$product = $product_model->getProduct($virtuemart_product_id);
		$images = $product->images;
		
		$main_image_url = $images[0]->file_url;// [file_title][file_description][file_meta]
		$main_image_title = $images[0]->file_title;// [file_title][file_description][file_meta]
		$main_image_description = $images[0]->file_description;// [file_title][file_description][file_meta]
		$main_image_alt = $images[0]->file_meta;// [file_title][file_description][file_meta]

		$j = count($images);
		$imageWidth = 		trim($this->params->get('imageWidth',350));
		$imageHeight = 		trim($this->params->get('imageHeight',200));
		
		//creat thumb
		$this->createThumbs($images[0]->file_url_folder, $images[0]->file_url_folder_thumb, $imageWidth);
		
		//re_write _URL
		$main_image_url_thumb = JURI::base().$images[0]->file_url_folder_thumb.$images[0]->file_name.".".$images[0]->file_extension;
		
		//add HTML

		$html = "";
		
		$html .= "<div class=\"clearfix\">";
		$html .= "<div class=\"main-image\">";
		$html .= "<a id='zoom1' href=\"$main_image_url\" class=\"cloud-zoom\" rel=\"".$this->getCustomJs()."\"  title=\"$main_image_title \" >";
		$html .= "<img width=\"$imageWidth \" height=\"$imageHeight \" src=\"$main_image_url_thumb\"  title=\"$main_image_title \"  style=\"border: 4px solid #666;\" alt=\"$main_image_title\" />";
		$html .= "</a>";
		$html .= "</div>";
		$html .= "<br/>";
		$html .= "</div>";		
    return $html;
	}
	
	
	function getAddImages(){
		if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
		$config = VmConfig::loadConfig();
		$pluginLivePath = JURI::root(true).'/plugins/system/vmcloudzoom/';
		$product_model = VmModel::getModel('product');
		$virtuemart_product_id = JRequest::getInt('virtuemart_product_id', 0);
		$product = $product_model->getProduct($virtuemart_product_id);
		$images = $product->images;
		$main_image_url = JURI::root(true). $images[0]->file_url;// [file_title][file_description][file_meta]
		$main_image_title = $images[0]->file_title;// [file_title][file_description][file_meta]
		$main_image_description = $images[0]->file_description;// [file_title][file_description][file_meta]
		$main_image_alt = $images[0]->file_meta;// [file_title][file_description][file_meta]
		$main_image_url_thumb = $images[0]->file_url_folder_thumb."/".$images[0]->file_name.".".$images[0]->file_extension;
		
		$j = count($images);
		$thumbimageWidth = 		trim($this->params->get('thumbimageWidth',75));
		if($thumbimageWidth==''){$thumbimageWidth = 'auto';}
		$thumbimageHeight = 		trim($this->params->get('thumbimageHeight',75));
		if($thumbimageHeight==''){$thumbimageHeight = 'auto';}
		
		
		//add HTML
		
		$html = "";
		
		$html .= "<div class=\"clearfix\">";	
		$html .= "<div class=\"additional-images\">";		
		$html .= "<div class=\"floatleft\"><a href='".JURI::base().$images[0]->file_url."' class='cloud-zoom-gallery' title='Thumbnail 1' rel=\"useZoom: 'zoom1', smallImage: '".JURI::base().$main_image_url_thumb."' \"><img style=\"width:".$thumbimageWidth."px !important;height:".$thumbimageHeight."px !important;\" src='".JURI::base().$images[0]->file_url_thumb."' alt = \"Thumbnail 1\"/></a></div>";
		if($j >1){
			for($i=1; $i<$j; $i++){
				$main_image_url_thumb_i = JURI::base().$images[$i]->file_url_folder_thumb.$images[$i]->file_name.".".$images[$i]->file_extension;
		
				$html .= "<div class=\"floatleft\"><a href='".JURI::base().$images[$i]->file_url."' class='cloud-zoom-gallery' title='Thumbnail 1' rel=\"useZoom: 'zoom1', smallImage: '".$main_image_url_thumb_i."' \"><img style=\"width:".$thumbimageWidth."px !important;height:".$thumbimageHeight."px !important;\" src='".JURI::base().$images[$i]->file_url_thumb."' alt = \"$main_image_url_thumb_i\"/></a></div>";
			}	
		}		
		$html .= "</div>";
		$html .= "</div>";
			
    return $html;
	}
	
	
	

function createThumbs( $pathToImages, $pathToThumbs, $thumbWidth )
{
  // open the directory
  $dir = opendir( $pathToImages );

  // loop through it, looking for any/all JPG files:
  while (false !== ($fname = readdir( $dir ))) {
    // parse path for the extension
	$thum_exist_files = "{$pathToThumbs}{$fname}";
	
if (!file_exists($thum_exist_files)){
		//echo $thum_exist_files.'<br />';
		$info = pathinfo($pathToImages . $fname);
		// continue only if this is a JPEG image
		$type = pathinfo($pathToImages . $fname, PATHINFO_EXTENSION);
		//$type = strtolower($info['extension']);
		
		
		switch($type){

			case "gif":
				
				
					$orig_img = imagecreatefromgif("{$pathToImages}{$fname}");
					
					// load image and get image size
					$img = imagecreatefromjpeg( "{$pathToImages}{$fname}" );
					
					$width = imagesx( $img );
					$height = imagesy( $img );

					  // calculate thumbnail size
					$new_width = $thumbWidth;
					$new_height = floor( $height * ( $thumbWidth / $width ) );

					  // create a new temporary image
					$tmp_img = imagecreatetruecolor( $new_width, $new_height );

					  // copy and resize old image into new image
					imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

					  // save thumbnail into a file
					imagegif( $tmp_img, "{$pathToThumbs}{$fname}" );
				
				break;
			case "jpg":
				
					$orig_img = imagecreatefromjpeg("{$pathToImages}{$fname}");
					//
					// load image and get image size
					$img = imagecreatefromjpeg( "{$pathToImages}{$fname}" );
					$width = imagesx( $img );
					$height = imagesy( $img );

					  // calculate thumbnail size
					$new_width = $thumbWidth;
					$new_height = floor( $height * ( $thumbWidth / $width ) );

					  // create a new temporary image
					$tmp_img = imagecreatetruecolor( $new_width, $new_height );

					  // copy and resize old image into new image
					imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

					  // save thumbnail into a file
					imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}" );
				
				break;
			case "png":
				
					$orig_img = imagecreatefrompng("{$pathToImages}{$fname}");
					//
					// load image and get image size
					$img = imagecreatefromjpeg( "{$pathToImages}{$fname}" );
					$width = imagesx( $img );
					$height = imagesy( $img );

					  // calculate thumbnail size
					$new_width = $thumbWidth;
					$new_height = floor( $height * ( $thumbWidth / $width ) );

					  // create a new temporary image
					$tmp_img = imagecreatetruecolor( $new_width, $new_height );

					  // copy and resize old image into new image
					imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

					  // save thumbnail into a file
					imagepng( $tmp_img, "{$pathToThumbs}{$fname}" );
				
				break;

		}
	}
		
    
  }
  // close the directory
  closedir( $dir );
 
}
	
	
	
	
	
	
}
