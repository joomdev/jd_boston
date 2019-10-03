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
         <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAM8AAAAoCAYAAABZwK5lAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MDI1RkI0MTdBMzAyMTFFOUFBQTFBNDI1RkY2NTEyNUQiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MDI1RkI0MThBMzAyMTFFOUFBQTFBNDI1RkY2NTEyNUQiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDowMjVGQjQxNUEzMDIxMUU5QUFBMUE0MjVGRjY1MTI1RCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDowMjVGQjQxNkEzMDIxMUU5QUFBMUE0MjVGRjY1MTI1RCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PtQXuvQAAAtySURBVHja7F19cFxV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" alt="logo" />
         <div class="install-message">
            <h3>JD Salon - Joomla Template for Beauty, Spa & Hair Salon <span>v1.0</span>
            </h3>
          </div>
         <div class="astroid-install-actions">
            <a href="index.php?option=com_templates" class="btn btn-default">Get started</a>
         </div>
         <div class="astroid-support-link">
            <a href="https://docs.joomdev.com/category/astroid-user-manual/" target="_blank">Documentation</a> <span>|</span> <a href="https://www.joomdev.com/forum/jd-salon" target="_blank">Forum</a> <span>|</span> <a href="https://www.youtube.com/playlist?list=PLv9TlpLcSZTBBVpJqe3SdJ34A6VvicXqM" target="_blank">Tutorials</a> <span>|</span> <a href="https://www.joomdev.com/about-us" target="_blank">Credits</a>
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
