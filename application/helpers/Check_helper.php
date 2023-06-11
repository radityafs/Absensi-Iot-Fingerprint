<?php

function check_logged()
{
    $ci = get_instance();
    if (!$ci->session->userdata('email')) {
        redirect('auth');
        exit();
    } else {

    }
}
