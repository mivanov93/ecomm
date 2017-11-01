<?php

namespace Ecomm\Auth;

use Ecomm\Entity\User;
use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result as AuthenticationResult;

/**
 * Description of AuthAdapter
 *
 * @author x0r
 */
class AuthAdapter extends AbstractAdapter {

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $userRepo;

    public function __construct(\Doctrine\ORM\EntityRepository $userRepo) {

        $this->userRepo = $userRepo;
    }

    //put your code here
    public function authenticate() {
        $username = $this->getIdentity();
        $password = $this->getCredential();
        if (!is_string($username) || !is_string($password)) {
            return new AuthenticationResult(
                    AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND, array(), array('User not found.')
            );
        }
        $user = $this->userRepo->findOneBy(['username' => $username]);
        if ($user === NULL) {
            return new AuthenticationResult(
                    AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND, array(), array('User not found.')
            );
        }
        if (!password_verify($password, $user->getPassword())) {
            return new AuthenticationResult(
                    AuthenticationResult::FAILURE_CREDENTIAL_INVALID, array(), array('User not found.')
            );
        }

        return new AuthenticationResult(AuthenticationResult::SUCCESS, $user, array());
    }

// $user = $this->findUser();
//
//        if ($user === false) {
//            return new AuthenticationResult(
//                AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND,
//                array(),
//                array('User not found.')
//            );
//        }
//
//        $validationResult = $this->passwordValidator->isValid(
//            $this->credential, $user[$this->credentialColumn], $user[$this->identityColumn]
//        );
//
//        if ($validationResult->isValid()) {
//            // Don't store password in identity
//            unset($user[$this->getCredentialColumn()]);
//
//            return new AuthenticationResult(AuthenticationResult::SUCCESS, $user, array());
//        }
//
//        return new AuthenticationResult(
//            AuthenticationResult::FAILURE_CREDENTIAL_INVALID,
//            array(),
//            array('Invalid username or password provided')
//        );
}
