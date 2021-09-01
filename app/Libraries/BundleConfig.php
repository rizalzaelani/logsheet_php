<?php

namespace App\Libraries;

class BundleConfig
{
	public function baseJs()
	{
		$plugin = array(
			base_url('/vendors/jquery/jquery.min.js'),
			base_url('/js/tooltips.js'),
			base_url('/js/coreui.bundle.min.js'),
			base_url('/js/coreui-utils.js'),
			// base_url('/js/jquery.slim.min.js'),
			base_url('/js/jquery.maskedinput.js'),
			base_url('/vendors/moment/moment-with-locales.min.js'),
			base_url('/vendors/datatables/jquery.dataTables.js'),
			base_url('/vendors/datatables/dataTables.bootstrap4.min.js'),
			base_url('/vendors/datatables/datatables.min.js'),
			base_url('/vendors/daterangepicker/daterangepicker.js'),
			base_url('/vendors/echarts/echarts.min.js'),
			base_url('/vendors/select2/js/select2.full.js'),
			base_url('/vendors/filepond/filepond-plugin-file-validate-type.js'),
			base_url('/vendors/filepond/filepond.min.js'),
			base_url('/vendors/filepond/filepond.jquery.js'),
			base_url('/js/tooltips.js'),
			base_url('/js/advanced-forms.js'),
			// base_url('/js/app.min.js'),
			// base_url('/js/app.js'),

		);
		return $plugin;
	}

	public function baseCss()
	{
		$plugin = array(
			base_url('/css/style.css'),
			base_url('/css/custom-style.css'),
			base_url('/css/style.min.css.map'),
			base_url('/icons/coreui/css/all.min.css'),
			// base_url('/vendors/datatables/datatables.min.css'),
			base_url('/vendors/datatables/dataTables.bootstrap5.min.css'),
			base_url('/vendors/daterangepicker/daterangepicker.css'),
			base_url('/vendors/fontawesome/css/fontawesome.css'),
			base_url('/vendors/select2/css/select2-coreui.min.css'),
			base_url('/vendors/select2/css/select2.min.css'),
			base_url('/vendors/filepond/filepond.css')
			// base_url('/css/select2-coreui.css')
		);
		return $plugin;
	}

	public function pluginJs()
	{
		$plugin = array(
			base_url('/vendors/sweetalert2/sweetalert2.all.min.js')
		);
		return $plugin;
	}

	public function pluginCSS()
	{
		$plugin = array(
			base_url('/vendors/sweetalert2/sweetalert2.min.css')
		);

		return $plugin;
	}
}
