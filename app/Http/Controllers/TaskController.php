<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Products;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{

    private function _readExcel($fileName, $returnData = false, $skipRows = null)
    {
        try {
            $return     = $sheetData = array();
            $file       =  storage_path('app/public/' . $fileName);
            $fileType   = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
            $reader     = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);

            $worksheetData = $reader->listWorksheetInfo($file);
            if (empty($worksheetData) || empty($worksheetData[0])) {
                $return['totalRows']    = 0;
                $return['headers']      = '';
                $return['fileName']     = $fileName;

                return $return;
            }

            $totalRows                  = $worksheetData[0]['totalRows'];
            $totalColumns               = $worksheetData[0]['totalColumns'];
            $lastColumnLetter           = $worksheetData[0]['lastColumnLetter'];

            if ($totalRows == 0 || $totalColumns == 0) {
                $return['totalRows']    = $totalRows;
                $return['headers']      = '';
                $return['fileName']     = $fileName;

                return $return;
            }

            $objPHPExcel    = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $sheetData      = $objPHPExcel->getActiveSheet()->rangeToArray('A1:' . $lastColumnLetter . $totalRows);
            $sheetData      = array_filter($sheetData);

            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);

            if (!empty($skipRows)) {
                $skipRowsArr = explode(",", $skipRows);
                foreach ($skipRowsArr as $skipRow) {
                    unset($sheetData[$skipRow - 1]);
                }
            }

            if (empty($sheetData)) {
                $return['totalRows']    = 0;
                $return['headers']      = '';
                $return['fileName']     = $fileName;
                return $return;
            }

            $sheetData      = array_values(array_filter($sheetData));
            $sheetData[0]   = array_filter($sheetData[0]);

            $return['totalRows']    = count($sheetData) - 1;
            $return['headers']      = (array) $sheetData[0];
            $return['fileName']     = $fileName;

            if ($returnData) {
                $return['fileData'] = $sheetData;
            } else {
                $return['headers']  = (array) array_values($sheetData[0]);
            }

            return $return;
        } catch (\Exception $exec) {
            echo "<pre>";
            print_r($exec->getMessage());
            exit;
        }
    }

    public function screen2(Request $request)
    {
        $FilePath       = getcwd() . "/public/uploads/ImportedExcel/";
        $excelFile      = $request->file;

        $fileName       = $excelFile->getClientOriginalName();
        $fileExtension  = $excelFile->getClientOriginalExtension();

        $fileName       = str_replace(' ', '_', $fileName);
        $fileName       = str_replace('.' . $fileExtension, '', $fileName);
        $fileName       = $fileName . '_' . time() . '.' . $fileExtension;

        $filePath       = $excelFile->storeAs('ImportedExcel', $fileName, 'public');

        if ($filePath) {
            $res            = $this->_readExcel($filePath, false);
            $res['code']    = 1;
            if ($res['totalRows'] == 0 || empty($res['headers'])) {
                $res['code']    = 2;
                $res['msg']     = 'There is no valid data to import.';
            }
            return view('screen.screen2', compact('res'));
        }
    }

    public function screen3(Request $request){
        $res = $this->_readExcel($request->fileName, true);

        $headerFileMap = array();
        if (!empty($res['headers'])) {
            foreach ($res['headers'] as $key => $headerCols) {
                $headerCol = trim($headerCols);
                $headerFileMap["$headerCol"] = $key;
            }
        }

        $request->map = array_filter($request->map);

        $saveData = array();
        $importedAssetsCount = 0;

        if (!empty($res['fileData'])) {
            CategoryProduct::truncate();

            foreach ($res['fileData'] as $key => $fileData) {
                if ($key == 0) {
                    continue;
                }
                if (!array_filter($fileData)) {
                    continue;
                }

                foreach ($request->map as $importHeader => $importCols) {
                    $saveData[$key][$importHeader] = $fileData[$headerFileMap[$importCols]];
                }

                $categoryIds    = [];
                if($importCols == 'Product categories'){
                    $category       = $fileData[$headerFileMap[$importCols]];
                    $categories     = explode("|",$category);
                    foreach($categories as $k => $category){
                        $categoryDetail     = Category::where('category',$category)->first();
                        if(empty($categoryDetail)){
                            $categoryDetail = Category::create(['category' => $category]);
                        }
                        $categoryIds[$k] = $categoryDetail['id'];
                    }
                    // $categoryIds                = implode("|",$categoryIds);
                    // $saveData[$key]['category'] = $categoryIds;
                } else{
                    $category           = $fileData[$headerFileMap[$importCols]];
                    $categoryDetail     = Category::where('category',$category)->first();
                    if(empty($categoryDetail)){
                        $categoryDetail = Category::create(['category' => $category]);
                    }
                    $categoryIds[]        = $categoryDetail['id'];
                }
                unset($saveData[$key]['category']);

                $product = Products::updateOrCreate(['sku' => $saveData[$key]['sku']], $saveData[$key]);
                foreach($categoryIds as $categoryId){
                    CategoryProduct::updateOrCreate(
                        [
                            'category_id'   => $categoryId,
                            'product_id'    => $product->id
                        ],
                        [
                            'category_id'   => $categoryId,
                            'product_id'    => $product->id
                        ],
                    );
                }
            }


            if (!empty($saveData)) {
                $importedAssetsCount += count($saveData);
            }

            Storage::disk('public')->delete($request->fileName);
            echo $importedAssetsCount.' products imported';

            $products = Products::with('CategoryProduct.Category')->get();
            return view('screen.screen3', compact('products'));
        }
    }

}
