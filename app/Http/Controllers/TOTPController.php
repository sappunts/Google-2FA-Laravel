<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GooGleAuthController;
use App\Http\Requests;
use App\Models\User;

class TOTPController extends Controller
{
    public function index(){
        $secret = $this->CreateTOTP();
        User::where('id','1')->update(['2fa_token' => $secret]);
        return view('auth.totp', array('secret' => $secret));
    }
    
    public function CreateTOTP(){
        $ga = new GooGleAuthController();
        $secret = $ga->createSecret();
        return $secret;
    }

    public function GetQRCode(){
        $ga = new GooGleAuthController();
        $secret = User::where('id',1)->first();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl('2FA', $secret['2fa_token']);
        return $qrCodeUrl;
    }

    public function CheckCode($check){
        $ga = new GooGleAuthController();
        $secret = User::where('id',1)->first();
        $checkResult = $ga->verifyCode($secret['2fa_token'], $check, 2);
            if ($checkResult) {
                dd('OK');
            } else {
                dd('FALSE');
            }
    }
    public function ShowQRCode(){
        $secret = $this->GetQRCode();
        return view('auth.qr', array('secret' => $secret));
    }
}
