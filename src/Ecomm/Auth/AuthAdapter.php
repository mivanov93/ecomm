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

    //put your code here
    public function authenticate() {
//        return new AuthenticationResult(
//                AuthenticationResult::FAILURE_CREDENTIAL_INVALID, array(), array('Invalid username or password provided')
//        );
        $user = new User();
        $user->name = "gosho";
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
