<?php


namespace App\Service\Calcs;


use App\Repository\CuttingRepository;
use App\Repository\UserMarkupRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DigitalPaperCalckService
{
    public const WIDTH_SRA3 = 440; //  450  440 размер запечатки
    public const HEIGHT_SRA3 = 310; //  320  310 размер запечатки
    public const OVERHANG = 3.5;  // по 1.75 mm с каждой стороны


    private CuttingRepository $cutting;
    private UserMarkupRepository $markupRepository;
    private FormInterface $form;
    private UserInterface $select_user;

    public function __construct(
        CuttingRepository $cutting,
        UserMarkupRepository $markupRepository
    )
    {
        $this->cutting = $cutting;
        $this->markupRepository = $markupRepository;
    }


    /**
     * @param FormInterface $form
     * @return $this
     */
    public function setForm(FormInterface $form): self
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @param UserInterface $select_user
     * @return $this
     */
    public function setSelectUser(UserInterface $select_user): self
    {
        $this->select_user = $select_user;
        return $this;
    }

    public function getSize(): array
    {
        if (!empty($this->form->getData()->getWidth())) {
            $width = $this->form->getData()->getWidth() + self::OVERHANG;;
            $height = $this->form->getData()->getHeight() + self::OVERHANG;;
        } else {
            $width = $this->form->getData()->getDigitalFormat()->getWidth() + self::OVERHANG;;
            $height = $this->form->getData()->getDigitalFormat()->getHeight() + self::OVERHANG;;
        }
        return [
            'width' => $width,
            'height' => $height
        ];
    }

    public function getSumPaperLists()
    {
        // количество изделий на одном SRA3 к входящему размеру

        $width_number_lists1 = floor($this->getSize()['width'] > $this->getSize()['height'] ? self::WIDTH_SRA3 / $this->getSize()['width'] : self::WIDTH_SRA3 / $this->getSize()['height']);
        $height_number_lists1 = floor($this->getSize()['height'] < $this->getSize()['width'] ? self::HEIGHT_SRA3 / $this->getSize()['height'] : self::HEIGHT_SRA3 / $this->getSize()['width']);

        $width_number_lists2 = floor($this->getSize()['width'] < $this->getSize()['height'] ? self::WIDTH_SRA3 / $this->getSize()['width'] : self::WIDTH_SRA3 / $this->getSize()['height']);
        $height_number_lists2 = floor($this->getSize()['height'] > $this->getSize()['width'] ? self::HEIGHT_SRA3 / $this->getSize()['height'] : self::HEIGHT_SRA3 / $this->getSize()['width']);

        $sum_paper_lists1 = $width_number_lists1 * $height_number_lists1;
        $sum_paper_lists2 = $width_number_lists2 * $height_number_lists2;
        $sum_paper_lists = $sum_paper_lists1 > $sum_paper_lists2 ? floor($sum_paper_lists1) : floor($sum_paper_lists2);

        return $sum_paper_lists;
    }

    public function getPricePaper(): ?float
    {
        if ($this->select_user->getRoles()[0] === 'ROLE_PARTNER') {
            $price = $this->form->getData()->getDigitalPaper()->getPriceDiscounts();
        } else {
            $price = $this->form->getData()->getDigitalPaper()->getPrice();
        }
        return $price;

    }

    public function getPrintColor()
    {
        // Цена на цветность материала
        if ($this->select_user->getRoles()[0] === 'ROLE_PARTNER') {
            $price = $this->form->getData()->getPrintColor()->getPriceDiscounts();
        } else {
            $price = $this->form->getData()->getPrintColor()->getPrice();
        }
        return $price;
    }

    public function getCutting()
    {
        // Цена порезки по периметру
        if ($this->form->getData()->getDigitalFormat()->getId() != 1) {
            $price = $this->form->getData()->getDigitalPaper()->getPriceCutting();
        } else {
            $price = null;
        }
        $perimeter = ($this->getSize()['width'] - self::OVERHANG + $this->getSize()['height'] - self::OVERHANG) * 2;
        $factor = $this->getSumPaperLists() < 10 ? 25 : 50;
        $cutting = $price * $perimeter / $factor * $this->form->getData()->getSum();

        return $cutting;
    }

    public function getLamination()
    {
        if ($this->select_user->getRoles()[0] === 'ROLE_PARTNER') {
            $price = $this->form->getData()->getLamination()->getPriceDiscounts();
        } else {
            $price = $this->form->getData()->getLamination()->getPrice();
        }
        return $price;
    }

    public function getServices(): array
    {
        //Цена на услуги
        if ($this->select_user->getRoles()[0] === 'ROLE_PARTNER') {
            $servicesRounding = $this->form->getData()->getServices()->getPriceDiscounts();
            $servicesHole = $this->form->getData()->getServiceHole()->getPriceDiscounts();
            $servicesFolding = $this->form->getData()->getServiceFolding()->getPriceDiscounts();
            $servicesCrease = $this->form->getData()->getServiceCrease()->getPriceDiscounts();
        } else {
            $servicesRounding = $this->form->getData()->getServices()->getPrice();
            $servicesHole = $this->form->getData()->getServiceHole()->getPrice();
            $servicesFolding = $this->form->getData()->getServiceFolding()->getPrice();
            $servicesCrease = $this->form->getData()->getServiceCrease()->getPrice();
        }

        return [
            'rounding' => $servicesRounding,
            'hole' => $servicesHole,
            'folding' => $servicesFolding,
            'crease' => $servicesCrease,
        ];
    }

    public function getMarkup()
    {
        if ($this->select_user->getRoles()[0] === 'ROLE_PARTNER') {
            if (!empty($this->markupRepository->findBy(['user' => $this->select_user])[0])) {
                $markup = $this->markupRepository->findBy(['user' => $this->select_user])[0]->getDigitalPaperKalc();
            } else {
                $markup = 0;
            }
        } else {
            $markup = 0;
        }
        return $markup;
    }

    public function getSum(): float
    {
        $usd = $this->cutting->findAll()[1]->getPrice();
        $sum = ceil($this->form->getData()->getSum() / ($this->getSumPaperLists() !== 0.0 ? $this->getSumPaperLists() : 1));
        $sumKit = $this->form->getData()->getSumKit();
        $price = ($usd * ((($this->getPricePaper() + $this->getPrintColor() + $this->getLamination()) * $sum) + $this->getCutting() + (($this->getServices()['rounding'] + $this->getServices()['hole'] + $this->getServices()['folding'] + $this->getServices()['crease']) * $this->form->getData()->getSum()))) * $sumKit;
        $result = round($price, 2);
        return $result;
    }


}