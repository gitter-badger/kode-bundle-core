<?php

namespace KodeCms\KodeBundle\Core\Util;

use DateTime;

class Time
{
    /**
     * @var DateTime
     */
    private $date;

    public function getNow(): DateTime
    {
        if ($this->date instanceof DateTime) {
            return clone $this->date;
        }

        return new DateTime();
    }

    public function setNow(DateTime $date): Time
    {
        $this->date = $date;

        return $this;
    }
}
