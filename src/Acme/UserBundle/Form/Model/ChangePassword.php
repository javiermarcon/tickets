<?php

namespace Acme\UserBundle\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "Wrong value for your current password"
     * )
     */
     protected $oldPassword;

    /**
     * @Assert\Length(
     *     min = 6,
     *     minMessage = "Password should by at least 6 chars long"
     * )
     */
     protected $newPassword;

     /**
     * @inheritDoc
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }
    
    /**
     * @inheritDoc
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * Set catid
     *
     * @param integer $oldPassword
     * @return ChangePassword
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }
    
    /**
     * Set catid
     *
     * @param integer $oldPassword
     * @return ChangePassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

}