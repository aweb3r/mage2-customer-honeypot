<?php

namespace Arcmedia\CustomerHoneyPot\Helper;

use Exception;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    private $firstname;
    private $lastname;
    private $email;

    public function __construct(
        Context $context
    )
    {
        parent::__construct($context);
        $this->firstname = $_POST["firstname"];
        $this->lastname = $_POST["lastname"];
        $this->email = $_POST["email"];
    }

    public function spamCheck()
    {
        $this->checkNameLenght();
        $this->checkMailAddress();
        $this->checkNames();
    }

    protected function checkNameLenght()
    {
        // todo: make length configurable
        if (
            (strlen($this->firstname) > 30)
            ||
            (strlen($this->lastname) > 30)
        ) {
            throw new Exception('fistname is too long');
        }
    }

    /**
     * @throws Exception
     */
    protected function checkNames()
    {
        // TODO: make this matches configurable
        if (
            preg_match("/\p{Han}+/u", $this->firstname)
            ||
            preg_match("/\p{Han}+/u", $this->lastname)
            ||
            preg_match('/[А-Яа-яЁё]/u', $this->firstname)
            ||
            preg_match('/[А-Яа-яЁё]/u', $this->lastname)
        ) {
            // TODO: make this configurable
            throw new Exception(
                'unlawful signs characters in ' . $this->firstname . ' or ' . $this->lastname
            );
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function checkMailAddress()
    {
        // TODO: refactor: make mail domains configurable
        if (strpos($this->email, "@mail.ru")) {
            throw new Exception('e-mail provider are not permitted');
        }
        return array($this->firstname, $this->lastname);
    }

}
