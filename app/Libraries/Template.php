<?php

namespace App\Libraries;

use App\Libraries\BundleConfig;

class Template
{
	public function render($view, $data = [])
	{
		$bundle = new BundleConfig();
		$css = array();
		$css = array_merge($css, $bundle->baseCss(), $bundle->pluginCss());

		$js = array();
		$js = array_merge($js, $bundle->baseJs(), $bundle->pluginJs());

		$data = array_merge($data, array('css' => $css, 'js' => $js));
		return view($view, $data);
	}
}
