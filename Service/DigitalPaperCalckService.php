<?php


namespace App\Service;


use App\Entity\Calcs\DigitalPaperCalc;
use App\Repository\CuttingRepository;
use App\Repository\DigitalFormatListRepository;
use App\Repository\DigitalLaminationRepository;
use App\Repository\DigitalPaperListRepository;
use App\Repository\DigitalPrintColorRepository;
use App\Repository\DigitalProductRepository;
use App\Repository\DigitalServiceCreaseRepository;
use App\Repository\DigitalServiceFoldingRepository;
use App\Repository\DigitalServiceHoleRepository;
use App\Repository\DigitalServicesRepository;
use Symfony\Component\Security\Core\Security;

class DigitalPaperCalckService
{
    public const WIDTH_SRA3 = 440; //  450  440 размер запечатки
    public const HEIGHT_SRA3 = 310; //  320  310 размер запечатки
    public const OVERHANG = 3.5;  // по 1.75 mm с каждой стороны

    private Security $security;
    private DigitalPaperListRepository $digitalPaperListRepository;
    private DigitalPrintColorRepository $digitalPrintColorRepository;
    private DigitalLaminationRepository $digitalLaminationRepository;
    private DigitalProductRepository $digitalProductRepository;
    private DigitalFormatListRepository $digitalFormatListRepository;
    private DigitalServicesRepository $digitalServicesRepository;
    private DigitalServiceHoleRepository  $digitalServiceHoleRepository;
    private DigitalServiceFoldingRepository $digitalServiceFoldingRepository;
    private DigitalServiceCreaseRepository $digitalServiceCreaseRepository;
    private CuttingRepository $cuttingRepository;

    public function __construct(
        Security $security,
        DigitalPaperListRepository $digitalPaperListRepository,
        DigitalPrintColorRepository $digitalPrintColorRepository,
        DigitalLaminationRepository $digitalLaminationRepository,
        DigitalProductRepository $digitalProductRepository,
        DigitalFormatListRepository $digitalFormatListRepository,
        DigitalServicesRepository $digitalServicesRepository,
        DigitalServiceHoleRepository  $digitalServiceHoleRepository,
        DigitalServiceFoldingRepository $digitalServiceFoldingRepository,
        DigitalServiceCreaseRepository $digitalServiceCreaseRepository,
        CuttingRepository $cuttingRepository
    )
    {
        $this->security = $security;
        $this->digitalPaperListRepository = $digitalPaperListRepository;
        $this->digitalPrintColorRepository= $digitalPrintColorRepository;
        $this->digitalLaminationRepository = $digitalLaminationRepository;
        $this->digitalProductRepository = $digitalProductRepository;
        $this->digitalFormatListRepository = $digitalFormatListRepository;
        $this->digitalServicesRepository = $digitalServicesRepository;
        $this->digitalServiceHoleRepository =$digitalServiceHoleRepository;
        $this->digitalServiceFoldingRepository = $digitalServiceFoldingRepository;
        $this->digitalServiceCreaseRepository = $digitalServiceCreaseRepository;
        $this->cuttingRepository = $cuttingRepository;
    }
    public function DigitalPaperCalk($arr_data)
    {

        // количество SRA3 к входящему формату
        if (empty($arr_data['digitalFormat'])){
            $id_digitalFormat = $this->digitalFormatListRepository->findOneBy(['name'=>$arr_data['format']])->getId();
        } else{
            $id_digitalFormat = (int)$arr_data['digitalFormat'];
        }
        if (empty($arr_data['yourSizeWidth'])) {
            $digitalSizeWidth = (int)$this->digitalFormatListRepository->findBy(['id' => $id_digitalFormat])[0]->getWidth() + self::OVERHANG;
            $digitalSizeHeight = (int)$this->digitalFormatListRepository->findBy(['id' => $id_digitalFormat])[0]->getHeight() + self::OVERHANG;
        }

        else {
            $digitalSizeWidth = (int)$arr_data['yourSizeWidth']+self::OVERHANG ;
            $digitalSizeHeight = (int)$arr_data['yourSizeHeight']+self::OVERHANG;
        }

        $width_number_lists1  =  floor($digitalSizeWidth > $digitalSizeHeight ?  self::WIDTH_SRA3 /$digitalSizeWidth : self::WIDTH_SRA3 /$digitalSizeHeight);
        $height_number_lists1 =  floor($digitalSizeHeight < $digitalSizeWidth ? self::HEIGHT_SRA3 /$digitalSizeHeight : self::HEIGHT_SRA3 /$digitalSizeWidth);

        $width_number_lists2  =  floor($digitalSizeWidth < $digitalSizeHeight ?  self::WIDTH_SRA3 /$digitalSizeWidth : self::WIDTH_SRA3 /$digitalSizeHeight);
        $height_number_lists2 =  floor($digitalSizeHeight > $digitalSizeWidth ? self::HEIGHT_SRA3 /$digitalSizeHeight : self::HEIGHT_SRA3 /$digitalSizeWidth);

        $sum_paper_lists1 = $width_number_lists1 *  $height_number_lists1;
        $sum_paper_lists2 = $width_number_lists2 *  $height_number_lists2;
        $sum_paper_lists =  $sum_paper_lists1 > $sum_paper_lists2 ?  floor($sum_paper_lists1) : floor($sum_paper_lists2);


        // цена бумаги
        $id_digitalPaper = $arr_data['digitalPaper'];
        if ($this->security->isGranted('ROLE_PARTNER')) {
            $priceDigitalPaper = $this->digitalPaperListRepository->find($id_digitalPaper)->getPriceDiscounts();
        } else {
            $priceDigitalPaper = $this->digitalPaperListRepository->find($id_digitalPaper)->getPrice();
        }

        // Цена на цветность материала
        $id_printColor = $arr_data['printColor'];
        if ($this->security->isGranted('ROLE_PARTNER')){
            $pricePrintColor = $this->digitalPrintColorRepository->find($id_printColor)->getPriceDiscounts();
        } else {
            $pricePrintColor = $this->digitalPrintColorRepository->find($id_printColor)->getPrice();
        }


        // Цена на ламинацию
        $id_lamination = $arr_data['lamination'];
        if ($this->security->isGranted('ROLE_PARTNER')){
            $priceLamination = $this->digitalLaminationRepository->find($id_lamination)->getPriceDiscounts();
        } else {
            $priceLamination = $this->digitalLaminationRepository->find($id_lamination)->getPrice();
        }

        //Цена на услуги
        $id_servicesRounding = $arr_data['servicesRounding'] ;
        $id_servicesHole= $arr_data['servicesHole'];
        $id_servicesFolding =$arr_data['servicesFolding'];
        $id_servicesCrease =$arr_data['servicesCrease'];


        if ($this->security->isGranted('ROLE_PARTNER')){
            $servicesRounding = !empty( $id_servicesRounding) ? $this->digitalServicesRepository->find($id_servicesRounding)->getPriceDiscounts() : null;
            $servicesHole = !empty($id_servicesHole) ? $this->digitalServiceHoleRepository->find($id_servicesHole)->getPriceDiscounts() : null;
            $servicesFolding = !empty($id_servicesFolding) ? $this->digitalServiceFoldingRepository->find($id_servicesFolding)->getPriceDiscounts() : null;
            $servicesCrease = !empty($id_servicesCrease) ? $this->digitalServiceCreaseRepository->find($id_servicesCrease)->getPriceDiscounts(): null;
        } else {
            $servicesRounding = !empty( $id_servicesRounding) ? $this->digitalServicesRepository->find($id_servicesRounding)->getPrice() : null;
            $servicesHole = !empty($id_servicesHole) ? $this->digitalServiceHoleRepository->find($id_servicesHole)->getPrice() : null;
            $servicesFolding = !empty($id_servicesFolding) ? $this->digitalServiceFoldingRepository->find($id_servicesFolding)->getPrice() : null;
            $servicesCrease = !empty($id_servicesCrease) ? $this->digitalServiceCreaseRepository->find($id_servicesCrease)->getPrice(): null;
        }


        // Цена порезки по периметру
        $price_cutting = $id_digitalFormat != 1  ? $this->digitalPaperListRepository->find( $id_digitalPaper)->getPriceCutting(): null;
        $perimeter = ($digitalSizeWidth - self::OVERHANG + $digitalSizeHeight -  self::OVERHANG )*2;
        $factor = $sum_paper_lists < 10 ? 25 : 50;
        $sum_cutting = $price_cutting * $perimeter / $factor * $arr_data['sum'];


        $sum = ceil($arr_data['sum'] / ( $sum_paper_lists !== 0.0 ? $sum_paper_lists : 1) );

        $sumKit = $arr_data['sumKit'];
        $usd = $this->cuttingRepository->findAll()[1]->getPrice();
        $priceSum = $rez = ($usd * ((( $priceDigitalPaper + $pricePrintColor + $priceLamination ) * $sum  ) + $sum_cutting + (( $servicesRounding + $servicesHole + $servicesFolding + $servicesCrease ) * $arr_data['sum'] ))) * $sumKit;

        /*  dd([
              'высота х ширина' => $digitalSizeWidth.'x'. $digitalSizeHeight,
              'цена резки '=> $price_cutting,
              'периметр 1 шт'=>$perimeter,
              'сумма резки общая'=>$sum_cutting ,
              'цена бумаги'=>$priceDigitalPaper,
              'цена цвета'=>$pricePrintColor,
              'количество штук на 1ном листе СРА3'=>$sum_paper_lists,
              'коэфициент'=> $factor,
              'количество листов сра3'=>$sum,
              'комплекто'=>$sumKit,
              'доллар'=>$usd,

              'итого'=>$rez = ($usd * ((( $priceDigitalPaper + $pricePrintColor + $priceLamination ) * $sum  ) + $sum_cutting + (( $servicesRounding + $servicesHole + $servicesFolding + $servicesCrease ) * $arr_data['sum'] ))) * $sumKit,
              'формула'=>'('.$usd.' x '.'((('.$priceDigitalPaper . '+'.$pricePrintColor.'+'.$priceLamination.')x'.$sum.')+'.$sum_cutting.'+(('.$servicesRounding.'+'.$servicesHole.'+'.$servicesFolding.'+'.$servicesCrease.') x'.$arr_data['sum'].'))) x'.$sumKit.' = '.$rez,
          ]);*/




        return round((!empty($arr_data['markup']) ? ($priceSum * $arr_data['markup']/100) : 0)  + $priceSum, 2);
    }




}