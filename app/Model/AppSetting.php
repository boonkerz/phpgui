<?php
declare(strict_types=1);

namespace App\Model;

class AppSetting
{

    private string $hetznerApiKey = "";

    public function getHetznerApiKey(): string
    {
        return $this->hetznerApiKey;
    }

    public function setHetznerApiKey(string $hetznerApiKey): void
    {
        $this->hetznerApiKey = $hetznerApiKey;
    }

}