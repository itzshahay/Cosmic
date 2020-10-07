<?php
namespace App\Middleware;

use App\Auth;
use App\Config;

use App\Models\Player;

use Core\Session;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class AuthMiddleware implements IMiddleware
{
    public function handle(Request $request) : void
    {
        if(!Session::exists('player_id')) {
            return;
        }

        $request->player = Player::getDataById(Session::get('player_id'));
        if($request->player == null) {
            return;
        }
      
        if (request()->getIp() != Session::get('ip_address') || $_SERVER['HTTP_USER_AGENT'] != Session::get('agent')) {
            Auth::logout();
        }
        
        $language = isset($_COOKIE['language']) ? $_COOKIE['language'] : 'en';
        if (!isset($_COOKIE['language'])) {
            setcookie('language', $language, time() + (10 * 365 * 24 * 60 * 60), '/');
        }
    }
}
