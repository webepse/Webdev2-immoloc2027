<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate
{

    #[Assert\NotBlank(message: "Vous devez renseigner votre ancien mot de passe")]
    private $oldPassword;

     /**
     * Mot de passe brut temporaire (non stocké en base)
     */
    #[Assert\NotBlank(message: "Veuillez donner un mot de passe")]
    #[Assert\Length(
        min: 8,
        max: 4096, // Sécurité contre les attaques DoS sur l'algorithme de hashage
        minMessage: 'Votre mot de passe doit comporter au moins {{ limit }} caractères.',
    )]
    #[Assert\PasswordStrength(
        minScore: Assert\PasswordStrength::STRENGTH_MEDIUM,
        message: 'Votre mot de passe est trop facile à deviner. Ajoutez des chiffres, majuscules et symboles.'
    )]
    #[Assert\NotCompromisedPassword(
        message: 'Ce mot de passe a fuité dans une faille de données publique. Veuillez en choisir un autre.'
    )]
    private ?string $newPassword = null;

    #[Assert\EqualTo(propertyPath:"newPassword", message: "Vous n'avez pas correctement confirmé votre mot de passe")]
    public ?string $passwordConfirm = null;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;
        return $this;
    }

    
    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;
        return $this;
    }

    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;
        return $this;
    }


}
