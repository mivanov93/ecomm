<?php

namespace Ecomm\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Description of UserRepository
 *
 * @author x0r
 */
class UserRepository extends EntityRepository {

    public function registerUser($params) {
        $user = new \Ecomm\Entity\User;
        $user->setEmail($params['email']);
        $user->setFullName($params['fullName']);
        $user->setPassword(password_hash($params['password'], PASSWORD_BCRYPT));
        $user->setUsername($params['username']);
        $user->setRole($params['role']);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

}
