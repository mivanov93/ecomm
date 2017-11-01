<?php

/**
 * Description of Acl
 *
 * @author x0r
 */

namespace Ecomm\Auth;

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
        $this->addResource('/myposts');
        $this->addResource('/post/add');
        $this->addResource('/post/edit');
        $this->addResource('/post/delete');
        $this->addResource('/login');
        $this->addResource('/register');
        $this->addResource('/logout');
        $this->addResource('/unauthorized');
        // APPLICATION PERMISSIONS
        // Now we allow or deny a role's access to resources. The third argument
        // is 'privilege'. We're using HTTP method as 'privilege'.
        $this->allow('guest', '/', 'GET');
        $this->allow('guest', '/posts', ['GET', 'POST']);
        $this->allow('guest', '/unauthorized', ['GET', 'POST']);
        $this->allow('guest', '/login', ['GET', 'POST']);
        $this->allow('guest', '/logout', 'GET');

        $this->allow('member', '/post/add', 'GET');
        $this->allow('guest', '/myposts', ['GET', 'POST']);
    }

}
