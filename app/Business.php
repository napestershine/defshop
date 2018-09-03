<?php

namespace App;

/**
 * Class Business
 * @package App
 */
class Business extends Application
{

    /**
     * @var string
     */
    protected $_table = 'business';

    /**
     *
     */
    const BUSINESS_ID = 1;

    /**
     * @return mixed
     */
    public function getVatRate()
    {
        $business = $this->getOne(self::BUSINESS_ID);
        return $business['vat_rate'];
    }

}