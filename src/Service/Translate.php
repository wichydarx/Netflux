<?php

namespace App\Service;


use Symfony\Component\Translation\LocaleSwitcher;

class Translate 
{
    public function __construct(
        private LocaleSwitcher $localeSwitcher,
    ) {
    }

    public function trans($locale): void
    {

        $currentLocale = $this->localeSwitcher->getLocale();

        if ($currentLocale !== $locale){
            $this->localeSwitcher->setLocale($locale);
            dd($this->localeSwitcher->getLocale());
        }
    }
}