<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth\Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Auth/Login::index');
$routes->get('/Login', 'Auth/Login::index');
$routes->post('/Login/auth', 'Auth/Login::auth');
$routes->get('/logout', 'Auth/Login::logout');

$routes->get('/register', 'Auth/Register::index');
$routes->post('/register/doRegister', 'Auth/Register::doRegister');

$routes->get('/Wizard', 'Wizard/Wizard::index');
$routes->post('/Wizard/getInvoice', 'Wizard/Wizard::getInvoice');
$routes->add('/Wizard/Invoice/(:any)', 'Wizard\Wizard::invoice/$1');
$routes->post('/Wizard/Invoice/download', 'Wizard/Wizard::download');

$routes->get('/Dashboard', 'Dashboard/Dashboard::index');
// $routes->get('/Dashboard', 'Dashboard/Dashboard::index');
$routes->get('/Company', 'Master/Company::index');
$routes->get('/AdminData', 'Master/AdminData::index');
$routes->get('/Operation', 'Master/Operation::index');
$routes->get('/Users', 'Master/Users::index');

$routes->get('/Api', 'Api/Api::index');

$routes->get('/Asset', 'Master/Asset::index');
$routes->add('/Asset/dataTag', 'Master/Asset::dataTag');
$routes->add('/Asset/getParam', 'Master/Asset::getParam');
$routes->add('/Asset/domPdf', 'Master/Asset::domPdf');
$routes->get('/Asset/deletedData', 'Master/Asset::deletedData');
$routes->get('/Asset/waitingApproved', 'Master/Asset::waitingApproved');
$routes->get('/Asset/getData', 'Master/Asset::getData');
$routes->add('/Asset/import', 'Master/Asset::import');
$routes->post('/Asset/importAsset', 'Master/Asset::importAsset');
$routes->add('/Asset/export', 'Master/Asset::export');
$routes->add('/Asset/exportCsv', 'Master/Asset::exportCsv');
$routes->add('/Asset/exportOds', 'Master/Asset::exportOds');
$routes->add('/Asset/add', 'Master/Asset::add');
$routes->add('/Asset/save', 'Master/Asset::save');
$routes->add('/Asset/addAsset', 'Master/Asset::addAsset');
$routes->get('/Asset/detail/(:any)', 'Master\Asset::detail/$1');
$routes->get('/Asset/detail2/(:any)', 'Master\Asset::detail2/$1');
// $routes->get('/Asset/detail/', 'Master/Asset::detail');
$routes->add('/Asset/getDetail/', 'Master/Asset::getDetail');
$routes->add('/Asset/datatable', 'Master/Asset::datatable');
$routes->add('/Asset/getDataImport', 'Master/Asset::getDataImport');
$routes->post('/Asset/insertExcel', 'Master/Asset::insertExcel');
$routes->post('/Asset/importexcel', 'Master/Asset::importexcel');
$routes->post('/Asset/update', 'Master\Asset::update');
$routes->add('/Asset/delete', 'Master\Asset::delete');
$routes->get('/Asset/download', 'Master/Asset::download');
$routes->get('/Asset/downloadSampleAsset', 'Master/Asset::downloadSampleAsset');
$routes->get('/Asset/detailData', 'Master/Asset::detailData');
$routes->get('/Asset/detailData/(:any)', 'Master/Asset::detailData/$1');

$routes->post('/Asset/addTag', 'Master\Asset::addTag');
$routes->post('/Asset/updateTag', 'Master\Asset::updateTag');
$routes->post('/Asset/updateTagLocation', 'Master\Asset::updateTagLocation');
$routes->post('/Asset/addTagLocation', 'Master\Asset::addTagLocation');
$routes->post('/Asset/updateOperation', 'Master\Asset::updateOperation');
$routes->post('/Asset/updateTagging', 'Master\Asset::updateTagging');
$routes->add('/Asset/uploadFile', 'Master\Asset::uploadFile');
$routes->post('Asset/insertParameter', 'Master\Asset::insertParameter');
$routes->post('/Asset/addParameter', 'Master/Asset::addParameter');
$routes->post('/Asset/editParameter', 'Master/Asset::editParameter');
$routes->post('/Asset/updateParameter', 'Master/Asset::updateParameter');
$routes->post('/Asset/deleteParameter', 'Master/Asset::deleteParameter');

$routes->post('/Asset/saveSetting', 'Master/Asset::saveSetting');
$routes->post('/Asset/sortingParameter', 'Master/Asset::sortingParameter');

$routes->add('/Location', 'Master/Location::index');
$routes->post('/Location/datatable', 'Master/Location::datatable');
// $routes->add('/Location/detail', 'Master/location::detail');
$routes->add('/Location/detail/(:any)', 'Master\Location::detail/$1');
$routes->add('/Location/detail/(:any)', 'Master\Location::detail/$1');
$routes->add('/Location/add', 'Master\Location::add');
$routes->add('/Location/addTagLocation', 'Master\Location::addTagLocation');
$routes->post('/Location/update', 'Master\Location::update');
$routes->add('/Location/delete', 'Master\Location::delete');
$routes->add('/Location/download', 'Master\Location::download');
$routes->add('/Location/uploadFile', 'Master\Location::uploadFile');
$routes->add('/Location/insertLocation', 'Master\Location::insertLocation');

$routes->add('/Tag', 'Master/Tag::index');
$routes->post('/Tag/datatable', 'Master/Tag::datatable');
$routes->post('/Tag/add', 'Master/Tag::add');
$routes->post('/Tag/edit', 'Master/Tag::edit');
$routes->post('/Tag/update', 'Master/Tag::update');
$routes->post('/Tag/deleteTag', 'Master/Tag::deleteTag');
$routes->add('/Tag/download', 'Master\Tag::download');
$routes->add('/Tag/uploadFile', 'Master\Tag::uploadFile');
$routes->add('/Tag/insertTag', 'Master\Tag::insertTag');

$routes->add('/Notification', 'Setting/Notification::index');

$routes->get('/Transaction', 'Transaction/Transaction::index');
$routes->get('/Transaction/detail', 'Transaction/Transaction::detail');
$routes->post('/Transaction/datatable', 'Transaction/Transaction::datatable');
$routes->post('/Transaction/approveTrx', 'Transaction/Transaction::approveTrx');

$routes->get('/ScheduleTrx/generateSchedule', 'Transaction/ScheduleTrx::generateSchedule');
$routes->get('/ScheduleTrx/generateManual', 'Transaction/ScheduleTrx::generateManual');

$routes->get('/Finding', 'Finding/Finding::index');
$routes->get('/Finding/detailList', 'Finding/Finding::detailList');
$routes->get('/Finding/detail', 'Finding/Finding::detail');
$routes->get('/Finding/issue', 'Finding/Finding::issue');
$routes->get('/Finding/getFindingLog', 'Finding/Finding::getFindingLog');
$routes->post('/Finding/datatable', 'Finding/Finding::datatable');
$routes->post('/Finding/addFindingLog', 'Finding/Finding::addFindingLog');
$routes->post('/Finding/closeFinding', 'Finding/Finding::closeFinding');

$routes->get('/ReportingAsset', 'Reporting/Asset::index');
$routes->get('/ReportingAsset/detail', 'Reporting/Asset::detail');
$routes->add('/ReportingAsset/tableDetail', 'Reporting/Asset::tableDetail');
$routes->post('/ReportingAsset/datatable', 'Reporting/Asset::datatable');
// $routes->get('/IncidentalReport', 'Transaction/IncidentalReport::index');
$routes->get('/LogActivity', 'Log/LogActivity::index');
$routes->get('/MediaLocation', 'Reporting/MediaLocation::index');
$routes->get('/Report', 'Reporting/Report::index');

$routes->add('/VersionApps', 'Setting/VersionApps::index');
$routes->add('/VersionApps/datatable', 'Setting/VersionApps::datatable');
$routes->post('/VersionApps/new', 'Setting/VersionApps::new');
$routes->post('/VersionApps/detail', 'Setting/VersionApps::detail');
$routes->post('/VersionApps/edit', 'Setting/VersionApps::edit');
$routes->post('/VersionApps/update', 'Setting/VersionApps::update');
$routes->post('/VersionApps/delete', 'Setting/VersionApps::delete');
$routes->post('/VersionApps/download', 'Setting/VersionApps::download');
$routes->add('/VersionApps/download/(:any)', 'Setting\VersionApps::download/$1');

$routes->add('/Application', 'Setting/Application::index');
$routes->post('/Application/saveSetting', 'Setting/Application::saveSetting');
$routes->post('/Application/saveAssetStatus', 'Setting/Application::saveAssetStatus');
$routes->post('/Application/deleteAssetStatus', 'Setting/Application::deleteAssetStatus');

$routes->add('/Schedule', 'Setting/Schedule::index');
$routes->add('/Schedule/getDataByMonth', 'Setting/Schedule::getDataByMonth');
$routes->post('/Schedule/addScheduleAM', 'Setting/Schedule::addScheduleAM');
$routes->post('/Schedule/importSchedule', 'Setting/Schedule::importSchedule');

$routes->add('/Schedule/datatable', 'Setting/Schedule::datatable');
$routes->post('/Schedule/updateSchedule', 'Setting/Schedule::updateSchedule');
$routes->post('/Schedule/schJson', 'Setting/Schedule::schJson');
$routes->post('/Schedule/checkAssetId', 'Setting/Schedule::checkAssetId');



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
