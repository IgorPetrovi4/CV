<?php


namespace App\Service;


use App\Repository\OrdersAllRepository;
use Imagick;

class ImagickPreviewService
{
    public const PUNCT_MM = 0.352775;
    public const PIXEL_MM = 0.084666812;
    public const MiB_Mb = 1.048576;
    public const Kb_Mb =0.000001;
    public const HZ_MM = 0.169319336;
    public const INCH = 25.4;

    public function imagickPreview($fileName){
        // create Imagick object
       $file = './uploads/print_file/' . $fileName;
        $img = new \Imagick();
        $img->readImage($file);
        $imageInfo = $img->identifyImage();

       // перевод из мемабайтов в мегабайты
        if ( $img->getImageFormat() == 'PDF'){
            $mB = filesize($file)*self::Kb_Mb;
        }
        if ( $img->getImageFormat() == 'JPEG'){
            $mB = $img->getImageLength()*self::Kb_Mb;
                  }
        if ( $img->getImageFormat() == 'TIFF'){
            $mB = preg_replace('/[^0-9 .]/', '', $imageInfo['fileSize'])*self::MiB_Mb;
        }
       if( $img->getImageFormat() == 'EPT'){
           $mB = filesize($file)*self::Kb_Mb;
       }

        $fileSize = round($mB, 2);

       if ($img->getImageFormat() == 'PDF' || $img->getImageFormat() == 'EPT' ){
            $widthMm = round($imageInfo['geometry']['width']*self::PUNCT_MM);
            $heightMm = round($imageInfo['geometry']['height']*self::PUNCT_MM);
       }
       else{
           $resolutionX = $imageInfo['resolution']['x'];
           $resolutionY = $imageInfo['resolution']['y'];
           $widthMm = round(self::INCH*$imageInfo['geometry']['width']/$resolutionX);
           $heightMm = round(self::INCH*$imageInfo['geometry']['height']/$resolutionY);
          /* $widthMm = round(getImageSize($file)['0']*self::PIXEL_MM);
           $heightMm = round(getImageSize($file)['1']*self::PIXEL_MM);*/
        }

        // получаем разрешение для файлов без разрешения
        if (!empty($imageInfo['resolution']['x']) <= 1){
            $resolution = round($imageInfo['geometry']['width']*2.54/$widthMm*10);
        }
        $resolution = $imageInfo['resolution']['x'];


        // отрисовка рамок
        $draw = new \ImagickDraw();
        $strokeColor = new \ImagickPixel('red');
        $fillColor = new \ImagickPixel('transparent');
        $draw->setStrokeColor($strokeColor);
        $draw->setFillColor($fillColor);
        $draw->setStrokeOpacity(1);
        $draw->setStrokeWidth(2);
        // Px = D*S/2.54
        $pixelX = $resolution * 0.2 / 2.54;
        $draw->rectangle($pixelX, $pixelX, $imageInfo['geometry']['width'] - $pixelX, $imageInfo['geometry']['height'] - $pixelX);
        //TODO нужен if для CRA3 435x310 (если с плотерная резка  с низу 60мм по бокам и сверху по 15мм)  лист 450 x 320     2 и 5 мм
        $strokeColor = new \ImagickPixel('green');
        $draw->setStrokeColor($strokeColor);
        $pixelX2 = $resolution * 0.5 / 2.54;
        $draw->rectangle($pixelX2, $pixelX2, $imageInfo['geometry']['width'] - $pixelX2, $imageInfo['geometry']['height'] - $pixelX2);
        $img->drawImage($draw);

        // записываем изображение для превью
        $filePreview = '/uploads/print_file/' . $fileName . '.jpg';
        $img->adaptiveResizeImage($imageInfo['geometry']['height'], 1200, true);
        $img->writeImage('.' . $filePreview);


        return $dataImagick = [
            'filePreview'=>$filePreview,
            'widthMm'=>$widthMm,
            'heightMm'=>$heightMm,
            'resolution'=>$resolution,
            'imageInfo'=>$imageInfo,
            'fileSize'=>$fileSize,
            'imageFormat'=>$img->getImageFormat(),
            ];
    }

}