<?php


namespace App\Service;


use Imagick;
use ImagickDraw;
use ImagickPixel;

class ImagickCheckService
{

    public function getCheck(string $articleNumber, array $material, string $size, array $sum ){

        $image = new Imagick();
        $draw = new ImagickDraw();
        $pixel = new ImagickPixel( 'white' );
        $image->newImage(638, 684, $pixel);// высота 58 x 54     // 319 x 342 150   // 638 x 684
        $image->setResolution('600', '600');
        $draw->setFillColor('black');
        $draw->setFont("times-bold.ttf");
        $draw->setResolution('600', '600');
        $draw->setFontSize( 6 );
        $image->annotateImage($draw, 220, 70, 0, 'Заказ №');
        $draw->setFontSize( 8 );
        $image->annotateImage($draw, 100, 130, 0, $articleNumber);
        $draw->setFont("times.ttf");
        $draw->setFontSize( 5 );
        if(!empty($material['name'])) {
            $image->annotateImage($draw, 40, 200, 0, $material['name']);
        }
        if(!empty($material['product'])) {
            $image->annotateImage($draw, 40, 250, 0, $material['product']);
        }
        if(!empty($material['resolution'])) {
            $image->annotateImage($draw, 40, 300, 0, $material['resolution']);
        }
        if(!empty($material['pocket'])) {
            $image->annotateImage($draw, 40, 300, 0, $material['pocket']);
        }
        if(!empty($material['selection'])) {
            $image->annotateImage($draw, 40, 300, 0, $material['selection']);
        }
        if(!empty($material['cringle'])){
            $image->annotateImage($draw, 40, 350, 0, $material['cringle']);
        }
        if(!empty($material['color'])){
            $image->annotateImage($draw, 40, 350, 0, $material['color']);
        }
        if(!empty($material['upturn'])){
            $image->annotateImage($draw, 40, 350, 0, $material['upturn']);
        }
        if(!empty($material['cutting'])){
            $image->annotateImage($draw, 40, 350, 0, $material['cutting']);
        }
        $image->annotateImage($draw, 40, 400, 0, $size);
        if(!empty($material['width_line'])) {
            $image->annotateImage($draw, 40, 450, 0, $material['width_line'].'m');
        }
        if(!empty($material['lamination'])) {
            $image->annotateImage($draw, 40, 450, 0, $material['lamination']);
        }
        $overlay = new Imagick('./imeg/check_print.png');
        $image->compositeImage($overlay, Imagick::COMPOSITE_DEFAULT, 10, 470);
       // $image->annotateImage($draw, 40, 480, 0, 'Дата:'.date('Y-m-d'));
        $image->annotateImage($draw, 280, 550, 0, 'Количество:'.$sum['sum']);
        $image->annotateImage($draw, 280, 600, 0, 'Комплектов:'.$sum['kit']);
        $image->annotateImage($draw, 280, 650, 0, 'Тираж:'.$sum['edition']);

        $image->setImageFormat('pdf');
       // header("Content-Disposition: attachment; filename=check_$articleNumber.pdf");
        //  $file = './uploads/print_file/' . $fileName;
       $image->writeImage('./uploads/print_file/check/check_'.$articleNumber.'.pdf');


       return $articleNumber;
    }


}