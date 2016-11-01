<?php


class AuthenticationMiddleware
{
   

    /**
     * @var CI_Controller|object
     */
    private $ci;

    private $whitelists = array(
        'user/login',
        'user/set_session'
    );


    public function __construct()
    {
        $this->ci = & get_instance();

    }

    public function authentify()
    {

        if( !$this->ci->session->userdata('token') || $this->ci->session->userdata('role') != 'OUTLET' )
        {

            if( !in_array(uri_string(), $this->whitelists ) )
            {
                redirect(base_url('user/login'));
            }
            
        }

    }

}



?>
