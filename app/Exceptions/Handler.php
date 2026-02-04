<?php 
// app/Exceptions/Handler.php
use Illuminate\Auth\AuthenticationException;

protected function unauthenticated($request, AuthenticationException $exception)
{
    $guard = $exception->guards()[0] ?? null;

    if ($guard === 'customer') {
        return redirect()->route('login_app'); // route login khusus customer
    }

    return redirect()->guest(route('login')); // default Laravel
}



?>