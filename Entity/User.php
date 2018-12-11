<?php

namespace PLejeune\UserBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 * @UniqueEntity(fields="email", message="Email déjà pris")
 * @UniqueEntity(fields="username", message="Username déjà pris")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var boolean
     */
    private $enable;


    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $titre;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var \DateTime
     */
    private $dateNaissance;

    /**
     * @var \DateTime
     */
    private $dateRegistration;

    /**
     * @var \DateTime|null
     */
    private $dateValidation;

    /**
     * @var string|null
     */
    private $facebook;

    /**
     * @var string|null
     */
    private $twitter;

    /**
     * @var string|null
     */
    private $instagram;

    /**
     * @var string|null
     */
    private $youtube;

    /**
     * @var string|null
     */
    private $twitch;

    /**
     * @var string|null
     */
    private $avatar;


    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $token_type;


    /**
     * @var \DateTime
     */
    private $token_date;

    /**
     * User constructor.
     * @param int $id
     */
    public function __construct()
    {
        $this->setEnable(false);
        $this->setRoles(['ROLE_USER']);
        $this->setDateRegistration(new \DateTime("now"));
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set roles.
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles.
     *
     * @return array
     */
    public function getRoles()
    {
        $roles = $this->roles;
        // Afin d'être sûr qu'un user a toujours au moins 1 rôle
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    /**
     * Set firstname.
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname.
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set dateNaissance.
     *
     * @param \DateTime $dateNaissance
     *
     * @return User
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance.
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set dateRegistration.
     *
     * @param \DateTime $dateRegistration
     *
     * @return User
     */
    public function setDateRegistration($dateRegistration)
    {
        $this->dateRegistration = $dateRegistration;

        return $this;
    }

    /**
     * Get dateRegistration.
     *
     * @return \DateTime
     */
    public function getDateRegistration()
    {
        return $this->dateRegistration;
    }

    /**
     * Set dateValidation.
     *
     * @param \DateTime|null $dateValidation
     *
     * @return User
     */
    public function setDateValidation($dateValidation = null)
    {
        $this->dateValidation = $dateValidation;

        return $this;
    }

    /**
     * Get dateValidation.
     *
     * @return \DateTime|null
     */
    public function getDateValidation()
    {
        return $this->dateValidation;
    }

    /**
     * Set facebook.
     *
     * @param string|null $facebook
     *
     * @return User
     */
    public function setFacebook($facebook = null)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook.
     *
     * @return string|null
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set twitter.
     *
     * @param string|null $twitter
     *
     * @return User
     */
    public function setTwitter($twitter = null)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter.
     *
     * @return string|null
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set instagram.
     *
     * @param string|null $instagram
     *
     * @return User
     */
    public function setInstagram($instagram = null)
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * Get instagram.
     *
     * @return string|null
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * Set youtube.
     *
     * @param string|null $youtube
     *
     * @return User
     */
    public function setYoutube($youtube = null)
    {
        $this->youtube = $youtube;

        return $this;
    }

    /**
     * Get youtube.
     *
     * @return string|null
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * Set twitch.
     *
     * @param string|null $twitch
     *
     * @return User
     */
    public function setTwitch($twitch = null)
    {
        $this->twitch = $twitch;

        return $this;
    }

    /**
     * Get twitch.
     *
     * @return string|null
     */
    public function getTwitch()
    {
        return $this->twitch;
    }

    /**
     * Set avatar.
     *
     * @param string|null $avatar
     *
     * @return User
     */
    public function setAvatar($avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return string|null
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return NULL;
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => FALSE]);
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function generateToken(?string $type): self
    {
        $now = new \DateTime("now");
        $this->setTokenType($type);
        $this->setTokenDate($now);
        $this->setToken(md5($this->getUsername() . $type . $now->getTimestamp()));

        return $this;
    }

    public function getTokenType(): ?string
    {
        return $this->token_type;
    }

    public function setTokenType(?string $token_type): self
    {
        $this->token_type = $token_type;

        return $this;
    }

    public function getTokenDate(): ?\DateTimeInterface
    {
        return $this->token_date;
    }

    public function setTokenDate(?\DateTimeInterface $token_date): self
    {
        $this->token_date = $token_date;

        return $this;
    }

    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }
}
