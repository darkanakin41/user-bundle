<?php

namespace PLejeune\UserBundle\Nomenclature;


use PLejeune\CoreBundle\Nomenclature\AbstractNomenclature;

class UserRoleNomenclature extends AbstractNomenclature
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_BACKEND = 'ROLE_BACKEND';
    const ROLE_COMPETITION = 'ROLE_COMPETITION';
    const ROLE_ADMIN = 'ROLE_ADMIN';
}