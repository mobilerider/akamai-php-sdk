<?php

namespace Akamai\Sdk\Model\MSL\v3;

class CpCodeMSLv3 extends \Mr\Bootstrap\Model\BaseModel
{
    /**
     * Type defining purpose of this cpCode
     *
     * @var string
     */
    protected $type;

    /**
     * @inheritDoc
     */
    public static function getResource()
    {
        return 'cpcode';
    }

    /**
     * Returns configuration reporting cp code
     *
     * @return int
     */
    public function getType()
    {
        return $this->{'__type'};
    }

    /**
     * Returns cpCode name
     *
     * @return string
     */
    public function getName()
    {
        return $this->{'cpcode-name'};
    }

    /**
     * Returns cpCode code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->{'cpcode'};
    }
}