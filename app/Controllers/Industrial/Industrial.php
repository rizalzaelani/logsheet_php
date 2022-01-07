<?php

namespace App\Controllers\Industrial;

use App\Controllers\Api\Asset;
use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\AssetStatusModel;
use App\Models\AssetTaggingModel;
use App\Models\AssetTagLocationModel;
use App\Models\AssetTagModel;
use App\Models\CategoryModel;
use App\Models\ParameterModel;
use App\Models\TagLocationModel;
use App\Models\TagModel;
use App\Models\TemplateModel;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Exception;

class Industrial extends BaseController
{
    public function index()
    {
        $categoryModel = new CategoryModel();

        $category = $categoryModel->findAll();

        $data = array(
            'title' => 'Select Industrial | Logsheet Digital',
            'subtitle' => 'Logsheet Digital',
        );
        $data['category'] = $category;

        return $this->template->render('Industrial/index', $data);
    }

    public function doGenerate()
    {
        $categoryModel  = new CategoryModel();
        $templateModel  = new TemplateModel();
        $assetModel     = new AssetModel();
        $parameterModel = new ParameterModel();
        $tagModel       = new TagModel();
        $tagLocationModel = new TagLocationModel();
        $assetTagModel  = new AssetTagModel();
        $assetTagLocationModel = new AssetTagLocationModel();
        $assetStatusModel = new AssetStatusModel();
        $assetTaggingModel = new AssetTaggingModel();

        $categoryId = $this->request->getVar('selected');
        $adminId = $this->session->get('adminId');

        $template = $templateModel->getAll(['categoryIndustryId' => $categoryId]);

        if (empty($template)) {
            return $this->response->setJSON(array(
                'status' => 404,
                'message' => 'Sorry, data not available at this time.',
                'data' => []
            ));
        }
        try {
            foreach ($template as $key => $value) {
                $pathFile = $value['path'];
                $strPath = str_replace(base_url() . '/', "", $pathFile);
                if (file_exists($strPath)) {
                    $reader = ReaderEntityFactory::createXLSXReader();
                    $reader->open($strPath);
                    $dataAsset = [];
                    $parameter = [];
                    $desc = [];
                    foreach ($reader->getSheetIterator() as $sheet) {
                        $rowAsset = 1;
                        if ($sheet->getName() == 'Asset') {
                            foreach ($sheet->getRowIterator() as $row) {
                                if ($rowAsset > 1) {
                                    $dataAsset[] = array(
                                        'assetName'     => $row->getCellAtIndex(0)->getValue(),
                                        'assetNumber'   => $row->getCellAtIndex(1)->getValue(),
                                        'description'   => $row->getCellAtIndex(2)->getValue(),
                                        'tagLocation'   => $row->getCellAtIndex(3)->getValue(),
                                        'tag'           => $row->getCellAtIndex(4)->getValue(),
                                        'schManual'     => $row->getCellAtIndex(5)->getValue(),
                                        'schType'       => $row->getCellAtIndex(6)->getValue(),
                                        'schFrequency'  => $row->getCellAtIndex(7)->getValue(),
                                        'schWeeks'      => $row->getCellAtIndex(10)->getValue(),
                                        'schWeekDays'   => $row->getCellAtIndex(6)->getValue() === "Monthly" ? $row->getCellAtIndex(11)->getValue() : $row->getCellAtIndex(8)->getValue(),
                                        'schDays'       => $row->getCellAtIndex(9)->getValue(),
                                        'rfid'          => $row->getCellAtIndex(12)->getValue(),
                                        'coordinat'     => str_replace("'", '', $row->getCellAtIndex(13)->getValue()),
                                        'assetStatus'   => $row->getCellAtIndex(14)->getValue()
                                    );
                                }
                                $rowAsset++;
                            }
                        }
                        $rowDescription = 1;
                        if ($sheet->getIndex() == 1) {
                            $description = [];
                            $special = [];
                            foreach ($sheet->getRowIterator() as $row) {
                                if ($rowDescription > 2) {
                                    $assetNumber = $row->getCellAtIndex(0)->getValue();
                                    $key = $row->getCellAtIndex(1)->getValue();
                                    $value = $row->getCellAtIndex(2)->getValue();
                                    $description[] = array("assetNumber" => $assetNumber, "key" => $key, "value" => $value);
                                }
                                $rowDescription++;
                            }
                            foreach ($dataAsset as $i => $items) {
                                $descAssetAll = [];
                                $descAsset = [];
                                foreach ($description as $index => $val) {
                                    $assetNumber = $val['assetNumber'];
                                    $key = $val['key'];
                                    $value = $val['value'];
                                    if ($assetNumber == "all") {
                                        $descAssetAll[] = array("key" => $key, "value" => $value);
                                    } else {
                                        $cekAssetNumber = array_filter($dataAsset, function ($items) use ($assetNumber) {
                                            return $items['assetNumber'] = $assetNumber;
                                        });
                                        if ($cekAssetNumber) {
                                            $descAsset[] = array('assetNumber' => $assetNumber, "key" => $key, "value" => $value);
                                        }
                                    }
                                }
                                foreach ($description as $index => $rows) {
                                    $assetNumber = $rows['assetNumber'];
                                    if ($dataAsset[$i]['assetNumber'] != $assetNumber) {
                                        if ($dataAsset[$i]['description'] == "") {
                                            $dataAsset[$i]['description'] = $descAssetAll;
                                        }
                                    }
                                }
                            }
                            foreach ($descAsset as $descKey => $descValue) {
                                foreach ($dataAsset as $a => $assetVal) {
                                    $cekIsString = is_string($dataAsset[$a]['description']);
                                    if ($descValue['assetNumber'] == $assetVal['assetNumber']) {
                                        $valkey = $descValue['key'];
                                        $valVal = $descValue['value'];
                                        if (!$cekIsString) {
                                            array_push($dataAsset[$a]['description'], array("key" => $valkey, "value" => $valVal));
                                        }
                                    }
                                }
                            }
                            foreach ($dataAsset as $b => $bVal) {
                                $isString = is_string($bVal['description']);
                                if (!$isString) {
                                    $dataAsset[$b]['description'] = json_encode($dataAsset[$b]['description']);
                                }
                            }
                        }

                        $rowParameter = 1;
                        if ($sheet->getIndex() == 2) {
                            foreach ($sheet->getRowIterator() as $row) {
                                if ($rowParameter > 1) {
                                    $parameter[] = array(
                                        // 'parameterId' => uuidv4(),
                                        'sortId' => $row->getCellAtIndex(0)->getValue(),
                                        'parameterName' => $row->getCellAtIndex(1)->getValue(),
                                        'description' => $row->getCellAtIndex(2)->getValue(),

                                        'max' => $row->getCellAtIndex(3)->getValue() ? $row->getCellAtIndex(3)->getValue() : null,
                                        'min' => $row->getCellAtIndex(4)->getValue() ? $row->getCellAtIndex(4)->getValue() : null,
                                        'normal' => $row->getCellAtIndex(5)->getValue() ? $row->getCellAtIndex(5)->getValue() : "",
                                        'abnormal' => $row->getCellAtIndex(6)->getValue() ? $row->getCellAtIndex(6)->getValue() : "",
                                        'option' => $row->getCellAtIndex(8)->getValue() ? $row->getCellAtIndex(8)->getValue() : "",
                                        'uom' => $row->getCellAtIndex(7)->getValue() ? $row->getCellAtIndex(7)->getValue() : "",

                                        'inputType' => $row->getCellAtIndex(9)->getValue(),
                                        'showOn' => $row->getCellAtIndex(10)->getValue(),
                                    );
                                }
                                $rowParameter++;
                            }
                        }
                    }
                    $reader->close();



                    foreach ($dataAsset as $rowAsset => $valAsset) {
                        $dataInsert = array(
                            'assetId' => uuidv4(),
                            'userId' => $this->session->get("adminId"),
                            'assetName' => $valAsset['assetName'],
                            'assetNumber' => $valAsset['assetNumber'],
                            'description' => $valAsset['description'],
                            'schManual' => $valAsset['schManual'] == "Automatic" ? 0 : 1,
                            'schType' => $valAsset['schType'],
                            'schFrequency' => $valAsset['schFrequency'],
                            'schWeekDays' => $valAsset['schWeekDays'],
                            'schWeeks' => $valAsset['schType'] == 'Monthly' ? $valAsset['schWeeks'] : '',
                            'schDays' => $valAsset['schType'] == 'Monthly' ? $valAsset['schDays'] : '',
                        );

                        // asset status
                        $status = $valAsset['assetStatus'];
                        $dataStatus = $assetStatusModel->getAll(['userId' => $adminId, 'assetStatusName' => $status, 'deletedAt' => null]);
                        if (empty($dataStatus)) {
                            $newStatus = array(
                                'assetStatusId' => uuidv4(),
                                'userId' => $adminId,
                                'assetStatusName' => $status,
                            );
                            $assetStatusModel->insert($newStatus);
                            $dataInsert['assetStatusId'] = $newStatus['assetStatusId'];
                        } else {
                            $dataInsert['assetStatusId'] = $dataStatus[0]['assetStatusId'];
                        }
                        $assetModel->insert($dataInsert);

                        //parameter
                        foreach ($parameter as $keyParam => $valParam) {
                            $arrParameter = $valParam;
                            $dataParameter['parameterId'] = uuidv4();
                            $arrParameter['assetId'] = $dataInsert['assetId'];
                            $parameterModel->insert($arrParameter);
                        }
                        // tag
                        $tag = $valAsset['tag'];
                        $arrTag = explode(",", $tag);
                        foreach ($arrTag as $keyTag => $valTag) {
                            $dataTag = $tagModel->getAll(['userId' => $adminId, 'tagName' => $valTag]);
                            if (empty($dataTag)) {
                                $newTag = array(
                                    'tagId' => uuidv4(),
                                    'userId' => $this->session->get("adminId"),
                                    'tagName' => $valTag,
                                    'description' => ''
                                );
                                $tagModel->insert($newTag);
                                $insertNewAssetTag = array(
                                    'assetTagId' => uuidv4(),
                                    'assetId' => $dataInsert['assetId'],
                                    'tagId' => $newTag['tagId']
                                );
                                $assetTagModel->insert($insertNewAssetTag);
                            } else {
                                $tagId = $dataTag[0]['tagId'];
                                $insertAssetTag = array(
                                    'assetTagId' => uuidv4(),
                                    'assetId' => $dataInsert['assetId'],
                                    'tagId' => $tagId
                                );
                                $assetTagModel->insert($insertAssetTag);
                            }
                        }
                        // tag location
                        $tagLocation = $valAsset['tagLocation'];
                        $arrTagLocation = explode(",", $tagLocation);
                        foreach ($arrTagLocation as $keyTagLoc => $valTagLoc) {
                            $dataTagLocation = $tagLocationModel->getAll(['userId' => $adminId, 'tagLocationName' => $valTagLoc]);
                            if (empty($dataTagLocation)) {
                                $newTagLocation = array(
                                    'tagLocationId' => uuidv4(),
                                    'userId' => $this->session->get("adminId"),
                                    'tagLocationName' => $valTagLoc,
                                    'latitude' => '',
                                    'longitude' => '',
                                    'description' => '',
                                );
                                $tagLocationModel->insert($newTagLocation);
                                $insertNewAssetTagLocation = array(
                                    'assetTagLocationId' => uuidv4(),
                                    'assetId' => $dataInsert['assetId'],
                                    'tagLocationId' => $newTagLocation['tagLocationId']
                                );
                                $assetTagLocationModel->insert($insertNewAssetTagLocation);
                            } else {
                                $tagLocationId = $dataTagLocation[0]['tagLocationId'];
                                $insertAssetTagLocation = array(
                                    'assetTagLocationId' => uuidv4(),
                                    'assetId' => $dataInsert['assetId'],
                                    'tagLocationId' => $tagLocationId
                                );
                                $assetTagLocationModel->insert($insertAssetTagLocation);
                            }
                        }

                        // asset tagging
                        $rfid = $valAsset['rfid'];
                        if ($rfid != "") {
                            $dataRFID = array(
                                'assetTaggingId' => uuidv4(),
                                'assetId' => $dataInsert['assetId'],
                                'assetTaggingValue' => $rfid,
                                'assetTaggingtype' => 'rfid'
                            );
                            $assetTaggingModel->insert($dataRFID);
                        }
                        $coordinat = $valAsset['coordinat'];
                        if ($coordinat != "") {
                            $dataCoordinat = array(
                                'assetTaggingId' => uuidv4(),
                                'assetId' => $dataInsert['assetId'],
                                'assetTaggingValue' => $coordinat,
                                'assetTaggingtype' => 'coordinat'
                            );
                            $assetTaggingModel->insert($dataCoordinat);
                        }
                    }
                }
            }
            return $this->response->setJSON(array(
                'status' => 200,
                'message' => 'Success generate template',
                'data' => $template
            ));
        } catch (Exception $e) {
            $this->response->setJSON(array(
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => []
            ));
        }
    }
}
