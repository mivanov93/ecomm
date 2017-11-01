<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Acl
 *
 * @author x0r
 */

namespace Ecomm\Acl;

use Zend\Permissions\Acl\Acl as ZendAcl;

class Acl extends ZendAcl {

    public function __construct() {
        // APPLICATION ROLES
        $this->addRole('guest');
        // member role "extends" guest, meaning the member role will get all of 
        // the guest role permissions by default
        $this->addRole('member', 'guest');

        // APPLICATION RESOURCES
        // Application resources == Slim route patterns
        $this->addResource('/');
        $this->addResource('/posts');
        $this->addResource('/post/add');
        $this->addResource('/post/edit');
        $this->addResource('/post/delete');
        $this->addResource('/login');
        $this->addResource('/register');
        $this->addResource('/logout');
        // APPLICATION PERMISSIONS
        // Now we allow or deny a role's access to resources. The third argument
        // is 'privilege'. We're using HTTP method as 'privilege'.
        $this->allow('guest', '/', 'GET');
        $this->allow('guest', '/login', array('GET', 'POST'));
        $this->allow('guest', '/logout', 'GET');

        $this->allow('member', '/post/*', 'GET');
    }

}
