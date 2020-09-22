<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/9/10
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\extend\spreadsheet;

use yii\base\BaseObject;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet as PhpSpreadsheet;

/**
 * 电子表格扩展
 * ```php
 *
 *      // xlsx 导出
 *      Extend::spreadsheet()->export(['name', 'age', 'sex'], [
 *          ['sany', '18', '女'],
 *          ['tom', '22', '男'],
 *      ], '例子');
 *
 * ```
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class Spreadsheet extends BaseObject
{
    /**
     * @var array
     * @since 1.0
     */
    private static $_contentTypes = [
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    /**
     * @var array
     * @since 1.0
     */
    private static $_extByWriterType = [
        'Xlsx' => 'xlsx',
    ];

    /**
     * Excel导出
     * @param array $title 标题
     * @param array $data 数据
     * @param string $filename 文件名
     * @param string $writerType 写入器类型
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function export(array $title, array $data, $filename, $writerType = 'Xlsx')
    {
        $this->setHeaders($filename, self::$_extByWriterType[$writerType]);

        $spreadsheet = $this->setCellValueByColumnsAndRows($title, $data);

        $writer = IOFactory::createWriter($spreadsheet, $writerType);
        $writer->save('php://output');

        exit(0);
    }

    /**
     * 设置响应头
     * @param string $filename 文件名
     * @param string $ext 扩展名
     * @return bool
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    private function setHeaders($filename, $ext = 'xlsx')
    {
        $contentType = self::$_contentTypes[$ext];
        header('X-Powered-By: ym/1.0');
        // Redirect output to a client’s web browser (default: Xlsx)
        header("Content-Type: {$contentType}");
        header("Content-Disposition: attachment;filename=\"{$filename}.{$ext}\"");

        // HTTP/1.1
        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        return true;
    }

    /**
     * Method 1
     * @param array $title 标题
     * @param array $data 数据
     * @return PhpSpreadsheet
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    private function setCellValueByColumnsAndRows(array $title, array $data)
    {
        // Create new Spreadsheet object
        $spreadsheet = new PhpSpreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title cell by method `setCellValueByColumnAndRow`
        foreach ($title as $key => $value) {
            $sheet->setCellValueByColumnAndRow($key + 1, 1, $value);
        }

        // Set body cell by method `setCellValueByColumnAndRow`
        $row = 2;
        foreach ($data as $item) {
            $column = 1;
            foreach ($item as $value) {
                $sheet->setCellValueByColumnAndRow($column, $row, $value);
                $column++;
            }

            $row++;
        }

        return $spreadsheet;
    }

    /**
     * Method 2
     * @param array $title 标题
     * @param array $data 数据
     * @return PhpSpreadsheet
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    private function setCellValues(array $title, array $data)
    {
        // Create new Spreadsheet object
        $spreadsheet = new PhpSpreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $titCol = 'A';
        foreach ($title as $key => $value) {
            $sheet->setCellValue($titCol . '1', $value);
            $titCol++;
        }

        $row = 2;
        foreach ($data as $item) {
            $dataCol = 'A';
            foreach ($item as $value) {
                $sheet->setCellValue($dataCol . $row, $value);
                $dataCol++;
            }

            $row++;
        }

        return $spreadsheet;
    }
}