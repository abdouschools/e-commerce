<?php
//src/Entity/ChangePassword.php
namespace App\Entity;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class ChangePassword
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "Vous avez indiqué un mauvais mot de passe"
     * )
     */
    private $password;
    private $newPassword;
    private $plainPassword;
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    public function setNewPassword($password)
    {
        $this->password = $password;
    }
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}
