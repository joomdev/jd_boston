<?php
/**
 * @package Helix3 Framework - fixed Accessibility
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

class Helix3FeatureMenu {

	private $helix3;

	public function __construct($helix3){
		$this->helix3 = $helix3;
		$this->position = 'menu';
	}

	public function renderFeature() {

		$menu_type = $this->helix3->getParam('menu_type');

		ob_start();

		if($menu_type == 'mega_offcanvas') { ?>
			<div class='sp-megamenu-wrapper'>
				<a id="offcanvas-toggler" href="#" aria-label="Menu"><i class="fa fa-bars" aria-hidden="true" title="Menu"></i></a>
				<?php $this->helix3->loadMegaMenu('hidden-sm hidden-xs'); ?>
			</div>
		<?php } else if ($menu_type == 'mega') { ?>
			<div class='sp-megamenu-wrapper'>
				<a id="offcanvas-toggler" class="visible-sm visible-xs" aria-label="Menu" href="#"><i class="fa fa-bars" aria-hidden="true" title="Menu"></i></a>
				<?php $this->helix3->loadMegaMenu('hidden-sm hidden-xs'); ?>
			</div>
		<?php } else { ?>
			<a id="offcanvas-toggler" aria-label="Menu" href="#"><i class="fa fa-bars" title="Menu"></i></a>
		<?php }

		return ob_get_clean();
	}
}
