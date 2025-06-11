<?php

// app/Http/Controllers/BarcodeController.php
namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeController extends Controller
{
    public function generate($id)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($id, $generator::TYPE_CODE_128);

        return response($barcode)
            ->header('Content-type', 'image/png');
    }
}

