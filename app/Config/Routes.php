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
$routes->setDefaultController('Dashboard\Dashboard');
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
$routes->get('/', 'Dashboard/Dashboard::index');
$routes->get('/Dashboard', 'Dashboard/Dashboard::index');
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
$routes->add('/Asset/export', 'Master/Asset::export');
$routes->add('/Asset/exportCsv', 'Master/Asset::exportCsv');
$routes->add('/Asset/exportOds', 'Master/Asset::exportOds');
$routes->add('/Asset/add', 'Master/Asset::add');
$routes->add('/Asset/save', 'Master/Asset::save');
$routes->get('/Asset/detail/(:any)', 'Master\Asset::detail/$1');
// $routes->get('/Asset/detail/', 'Master/Asset::detail');
$routes->add('/Asset/getDetail/', 'Master/Asset::getDetail');
$routes->add('/Asset/datatable', 'Master/Asset::datatable');
$routes->add('/Asset/getDataImport', 'Master/Asset::getDataImport');
$routes->post('/Asset/insertExcel', 'Master/Asset::insertExcel');
$routes->post('/Asset/importexcel', 'Master/Asset::importexcel');
$routes->put('/Asset/update', 'Master/Asset::update');
$routes->add('/Asset/delete', 'Master/Asset::delete');
$routes->get('/Asset/download', 'Master/Asset::download');
$routes->get('/Asset/detailData', 'Master/Asset::detailData');
$routes->get('/Asset/detailData/(:any)', 'Master/Asset::detailData/$1');

$routes->post('/Asset/addParameter', 'Master/Asset::addParameter');
$routes->post('/Asset/editParameter', 'Master/Asset::editParameter');
$routes->post('/Asset/updateParameter', 'Master/Asset::updateParameter');
$routes->post('/Asset/deleteParameter', 'Master/Asset::deleteParameter');

$routes->add('/Location', 'Master/location::index');
$routes->post('/Location/datatable', 'Master/Location::datatable');
// $routes->add('/Location/detail', 'Master/location::detail');
$routes->add('/Location/detail/(:any)', 'Master\Location::detail/$1');
$routes->post('/Location/update', 'Master\Location::update');
$routes->add('/Location/delete', 'Master\Location::delete');

$routes->add('/Tag', 'Master/Tag::index');
$routes->post('/Tag/datatable', 'Master/Tag::datatable');
$routes->post('/Tag/add', 'Master/Tag::add');

$routes->add('/Notification', 'Setting/Notification::index');
$routes->add('/Application', 'Setting/Application::index');
$routes->add('/VersionApps', 'Setting/VersionApps::index');

$routes->get('/Transaction', 'Transaction/Transaction::index');
$routes->get('/Transaction/detail', 'Transaction/Transaction::detail');

$routes->get('/Finding', 'Finding/Finding::index');
$routes->get('/Finding/detailList', 'Finding/Finding::detailList');
$routes->get('/Finding/detail', 'Finding/Finding::detail');

$routes->get('/ReportingAsset', 'Reporting/Asset::index');
$routes->get('/ReportingAsset/detail', 'Reporting/Asset::detail');
$routes->add('/ReportingAsset/tableDetail', 'Reporting/Asset::tableDetail');
// $routes->get('/IncidentalReport', 'Transaction/IncidentalReport::index');
$routes->get('/LogActivity', 'Log/LogActivity::index');
$routes->get('/MediaLocation', 'Reporting/MediaLocation::index');
$routes->get('/Report', 'Reporting/Report::index');

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
