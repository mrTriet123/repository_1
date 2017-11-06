<?php
namespace App;

use Carbon\Carbon;
class Export extends \Maatwebsite\Excel\Files\NewExcelFile {

public function getFilename()
{
    return Carbon::now();
}
}