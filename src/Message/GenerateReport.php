<?php
// src/Message/GenerateReport.php

namespace App\Message;

class GenerateReport
{
    /** @var string $firstName */
    private $firstName;

    /** @var string $phoneNumber */
    private $phoneNumber;

    public function __construct(string $firstName, string $phoneNumber)
    {
        $this->firstName = $firstName;
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }
}