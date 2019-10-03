<?php
/**
 * @package   Astroid Framework
 * @author    JoomDev https://www.joomdev.com
 * @copyright Copyright (C) 2009 - 2019 JoomDev.
 * @license   GNU/GPLv2 and later
 */
// no direct access
defined('_JEXEC') or die;

class pkg_astroidInstallerScript {

   /**
    * 
    * Function to run before installing the component	 
    */
   public function preflight($type, $parent) {
      
   }

   /**
    *
    * Function to run when installing the component
    * @return void
    */
   public function install($parent) {
      $this->getJoomlaVersion();
      $this->displayAstroidWelcome($parent);
   }

   /**
    *
    * Function to run when un-installing the component
    * @return void
    */
   public function uninstall($parent) {
      
   }

   /**
    * 
    * Function to run when updating the component
    * @return void
    */
   function update($parent) {
      $this->displayAstroidWelcome($parent);
   }

   /**
    * 
    * Function to update database schema
    */
   public function updateDatabaseSchema($update) {
      
   }

   public function getJoomlaVersion() {
      $version = new \JVersion;
      $version = $version->getShortVersion();
      $version = substr($version, 0, 1);
      define('ASTROID_JOOMLA_VERSION', $version);
   }

   /**
    * 
    * Function to display welcome page after installing
    */
   public function displayAstroidWelcome($parent) {
      ?>
      <style>
         .astroid-install {
            margin: 20px 0;
            padding: 40px 0;
            text-align: center;
            border-radius: 0px;
            position: relative;
            border: 1px solid #f8f8f8;
            background:#fff url(<?php echo JURI::root(); ?>media/astroid/assets/images/moon-surface.png); 
            background-repeat: no-repeat; 
            background-position: bottom;
         }
         .astroid-install p {
            margin: 0;
            font-size: 16px;
            line-height: 1.5;
         }
         .astroid-install .install-message {
            width: 90%;
            max-width: 800px;
            margin: 50px auto;
         }
         .astroid-install .install-message h3 {
            display: block;
            font-size: 20px;
            line-height: 27px;
            margin: 25px 0;
         }
         .astroid-install .install-message h3 span {
            display: block;
            color: #7f7f7f;
            font-size: 13px;
            font-weight: 600;
            line-height: normal;
         }
         .astroid-install-actions .btn {
            color: #fff;
            overflow: hidden;
            font-size: 18px;
            box-shadow: none;
            font-weight: 400;
            padding: 15px 50px;
            border-radius: 50px;
            background: transparent linear-gradient(to right, #8E2DE2, #4A00E0) repeat scroll 0 0 !important;
            line-height: normal;
            border: none;
            font-weight: bold;
            position: relative;
            box-shadow:0px 0px 30px #b0b7e2;
            transition: linear 0.1s;
         }
         .astroid-install-actions .btn:after{
            top: 50%;
            width: 20px;
            opacity: 0;
            content:"";
            right: 80px;
            height: 17px;
            display: block;
            position: absolute;
            transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            -webkit-transform: translateY(-50%);
            background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAARCAYAAADdRIy+AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OERDRjlBMjY0OTIzMTFFODkyQTI4MzYzN0ZGQ0Y1NTMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OERDRjlBMjc0OTIzMTFFODkyQTI4MzYzN0ZGQ0Y1NTMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo4RENGOUEyNDQ5MjMxMUU4OTJBMjgzNjM3RkZDRjU1MyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4RENGOUEyNTQ5MjMxMUU4OTJBMjgzNjM3RkZDRjU1MyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PvXGU3IAAADpSURBVHjarNShCsJAHMfxO1iyyEw+gsFoEIMKA5uoxSfwKQSjYWASq4JVEKPggwgmi2ASFcMUdefvj3/QsMEdd3/4lO32Zey2SaWU0JwsLOEGndRVFNTkw0N9Z562ziRIAog4OnMRJDV4c3TsIki6EHM0dBEkbfWbgYsgqcKRo3065mGjm1CHM0ihP084wA7yMIRIokonPOFoKNjjO4zptTS4ltZuoQUVPhbaPsMC7PkZjmw3pQx3jk1sdzn4i01t38MiXDi2sP1SSnDl2Mr2W87BiWNryCStkwb/Qx82/D9swCtp0UeAAQDi4gvA12LkbAAAAABJRU5ErkJggg==') no-repeat;
            -webkit-transition: all 0.4s;
            -moz-transition: all 0.4s;
            transition: all 0.4s;
         }
         .astroid-install-actions .btn:hover{
            transition: linear 0.1s;
            box-shadow:0px 0px 30px #4b57d9;
         }
         .astroid-install-actions .btn:hover:after{
            opacity: 1;
            right: 20px;
            margin-left: 0;
         }
         .astroid-support-link{
            color: #8E2DE2;
            padding: 30px 0 10px;
         }
         .astroid-support-link a{
            color: #8E2DE2;
            text-decoration: none;
         }
         .astroid-support-link a:hover {
            text-decoration: underline;
         }
         .astroid-poweredby{
            right: 20px;
            width: 150px;
            height: 25px;
            bottom: 20px;
            position: absolute;
            background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAAAZCAYAAADT59fvAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NjBBMzcwNEU0N0YzMTFFOEE0ODFCNkJDMkNBMDVFNDIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NjBBMzcwNEY0N0YzMTFFOEE0ODFCNkJDMkNBMDVFNDIiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo2MEEzNzA0QzQ3RjMxMUU4QTQ4MUI2QkMyQ0EwNUU0MiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo2MEEzNzA0RDQ3RjMxMUU4QTQ4MUI2QkMyQ0EwNUU0MiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgETteYAAA5xSURBVHja7Ft5eBRV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') no-repeat 0 0;
         }
         .astroid-poweredby a{
            bottom: 0;
            display: block;
            font-size: 0;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
         }
         .astroid-poweredby span{
            font-size: 0;
         }
      </style>
      <div class="astroid-install">
         <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAAA3CAYAAABJnAVSAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NDg0NDQ3QTU5NDZGMTFFN0FDMTFFNTlCNTc2RDFCNzkiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NDg0NDQ3QTY5NDZGMTFFN0FDMTFFNTlCNTc2RDFCNzkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo0ODQ0NDdBMzk0NkYxMUU3QUMxMUU1OUI1NzZEMUI3OSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo0ODQ0NDdBNDk0NkYxMUU3QUMxMUU1OUI1NzZEMUI3OSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PjMZUqoAAA2ASURBVHja7F0JdFXF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" alt="logo" />
         <div class="install-message">
            <h3>JD Boston - Free Business & eCommerce Joomla Template<span>v3.0</span>
            </h3>
          </div>
         <div class="astroid-install-actions">
            <a href="index.php?option=com_templates" class="btn btn-default">Get started</a>
         </div>
         <div class="astroid-support-link">
            <a href="https://docs.joomdev.com/category/astroid-user-manual/" target="_blank">Documentation</a> <span>|</span> <a href="https://www.joomdev.com/forum/jd-boston" target="_blank">Forum</a> <span>|</span> <a href="https://www.youtube.com/playlist?list=PLv9TlpLcSZTBBVpJqe3SdJ34A6VvicXqM" target="_blank">Tutorials</a> <span>|</span> <a href="https://www.joomdev.com/about-us" target="_blank">Credits</a>
         </div>
         <div class="astroid-poweredby">
            <a href="https://www.joomdev.com" target="_blank">
               <span>JoomDev</span>
            </a>
         </div>
      </div>
      <?php
   }

   /**
    * 
    * Function to run after installing the component	 
    */
   public function postflight($type, $parent) {
      
   }

}
