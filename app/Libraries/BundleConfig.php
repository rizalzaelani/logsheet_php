<?php

namespace App\Libraries;

class BundleConfig
{
	public function baseJs()
	{
		$plugin = array(
			base_url('/vendors/axios/axios.min.js'),
			base_url('/vendors/lodash/lodash.min.js'),
			base_url('/vendors/moment/moment.min.js'),
			base_url('/vendors/moment/moment-with-locales.min.js'),
			base_url('/vendors/vue/vue.global.js'),
			base_url('/vendors/jquery/jquery.min.js'),
			base_url('/vendors/jquery-ui/jquery-ui.js'),
			base_url('/vendors/datatables/jquery.dataTables.js'),
			base_url('/vendors/datatables/dataTables.bootstrap4.min.js'),
			base_url('/vendors/daterangepicker/daterangepicker.js'),
			base_url('/vendors/echarts/echarts.min.js'),
			base_url('/vendors/select2/js/select2.full.js'),
			base_url('/vendors/filepond/filepond-plugin-image-preview.js'),
			base_url('/vendors/filepond/filepond-plugin-file-poster.js'),
			base_url('/vendors/filepond/filepond-plugin-file-validate-type.js'),
			base_url('/vendors/filepond/filepond.min.js'),
			base_url('/vendors/filepond/filepond.jquery.js'),
			base_url('/vendors/cropperjs/cropper.js'),
			base_url('/vendors/fullcalendar/core/js/main.js'),
			base_url('/vendors/mapbox/mapbox.js'),
			base_url('/vendors/mapbox/mapbox-gl.js'),
			base_url('/vendors/lightpick/lightpick.js'),
			base_url('/vendors/sweetalert2/sweetalert2.all.min.js'),

			base_url('/js/coreui.bundle.min.js'),
			base_url('/js/coreui-utils.js'),
			base_url('/js/custom-func.js'),
			base_url('/js/jquery.maskedinput.js'),
			base_url('/js/main.js'),
			base_url('/js/addon.js'),
			base_url('/js/string-manipulation.js'),
			base_url('/js/jquery.steps.min.js'),
			// base_url('/js/bd-wizard.js'),
		);
		return $plugin;
	}

	public function baseCss()
	{
		$plugin = array(
			base_url('/css/style.css'),
			base_url('/css/custom-style.css'),
			base_url('/icons/coreui/css/all.min.css'),

			base_url('/vendors/jquery-ui/jquery-ui.css'),
			base_url('/vendors/datatables/dataTables.bootstrap4.css'),
			base_url('/vendors/daterangepicker/daterangepicker.min.css'),
			base_url('/vendors/lightpick/lightpick.css'),
			base_url('/vendors/fontawesome/css/all.css'),
			base_url('/vendors/fullcalendar/css/main.css'),
			base_url('/vendors/select2/css/select2-coreui.min.css'),
			base_url('/vendors/select2/css/select2.min.css'),
			base_url('/vendors/filepond/filepond.css'),
			base_url('/vendors/filepond/filepond-plugin-image-preview.css'),
			base_url('/vendors/filepond/filepond-plugin-file-poster.css'),
			base_url('/vendors/sweetalert2/sweetalert2.all.min.js'),
			base_url('/vendors/mapbox/mapbox-gl.css'),
			base_url('/vendors/cropperjs/cropper.css'),
		);
		return $plugin;
	}

	public function pluginJs()
	{
		$plugin = array();
		return $plugin;
	}

	public function pluginCSS()
	{
		$plugin = array();

		return $plugin;
	}
}
