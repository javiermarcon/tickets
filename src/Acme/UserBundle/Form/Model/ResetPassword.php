<?php

namespace Acme\UserBundle\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPassword
{

    /**
     * @Assert\Length(
     *     min = 6,
     *     minMessage = "Password should by at least 6 chars long"
     * )
     */
     protected $newPassword;

     /**
     * @Assert\Length(
     *     min = 6,
     *     minMessage = "Password should by at least 6 chars long"
     * )
     */
     protected $codigo;

    /**
     * @inheritDoc
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @inheritDoc
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set catid
     *
     * @param integer $oldPassword
     * @return ResetPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * Set catid
     *
     * @param string $codigo
     * @return ResetPassword
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }
}