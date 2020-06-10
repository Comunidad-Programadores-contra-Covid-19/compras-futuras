<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OtpService;
use App\Otp;
use App\Store;
use App\User;
use App\Offer;
use Mail;
use Illuminate\Support\Facades\DB;
use App\UserMailSend;
use App\Notifications\UserMailSendConfirmed;
use App\Mail\HeroeCodigoEmail;
class OtpController extends Controller
{
    public function create($idstore, $idclient, $offer_id)
    {
        $otpClient = Otp::where('user_id', $idclient)
            ->orderBy('otp_timestamp', 'ASC')->first();
        if ($otpClient == null) {
             $offer=DB::table('offers')
            ->select('name_offer')
            ->where('id', '=' , $offer_id)
            ->get();
            $store=DB::table('stores')
            ->select('name', 'address')
            ->where('id', '=' , $idstore)
            ->get();
            $otp = $this->store($idstore, $idclient, $offer_id);
            return view('otps.buy', ['otp' => $otp, 'offer' => $offer, 'store' => $store]);
        } else {
            $OTP_VALID = 1440; // Equivale a 24 horas.
            $validTill = strtotime($otpClient->otp_timestamp) + ($OTP_VALID * 60);
            if (strtotime("now") > $validTill) {
                $otpClient = $this->store($idstore, $idclient, $offer_id);



                return view('otps.buy', ['otp' => $otp]);
            } else {
                return back()->with('info', 'Ya pediste o consumiste un beneficio!');
            }
        }
    }

    public function store($store_id, $client_id, $offer_id)
    {
        $otpService = new OtpService();
        $otp = $otpService->generateOtp($client_id, $store_id, $offer_id);

        $clientEmail = User::findOrFail($client_id);
        $offerEmail = Offer::findOrFail($offer_id);
        $params= array('offer'=>$offerEmail->name_offer,'otp'=>$otp);

        return $otp;
    }



    public function destroy(Request $request, $idstore)
    {
        $OTP_VALID = 60;
        $store = Store::where('user_id', $idstore)->first();
        $pass = $request->codigo;
        $otp = Otp::where('otp_pass', $pass)->first();
        $message = "";
        $success = false;
        if ($otp != null && $store != null) {
            if ($otp->store_id == $store->id) {
                if ($otp->is_used == 0) {
                    $validTill = strtotime($otp->otp_timestamp) + ($OTP_VALID * 60);
                    if (strtotime("now") > $validTill) {
                        $message = "El codigo expiro!";
                    } else {
                        $this->discountOffer($otp->offer_id);
                        $otp->is_used = 1;
                        $otp->save();
                        $message = "Codigo ingresado correctamente!";
                        $success = true;
                        $recipient= UserMailSend::where('offer_id', $otp->offer_id)->orderBy('created_at','desc')->first();
                        if ($recipient==true) {
                            $recipient->notify(new UserMailSendConfirmed);
                            $recipient->delete();
                        }
                        return response()->json([
                            'success' => $success,
                            'info' => $message,
                            'offerId' => $otp->offer_id,
                            'buyerId' => $otp->user_id
                        ]);
                    }
                } else {
                    $message = "Ya usaste este codigo!";
                }
            } else {
                $message = "Ups! El codigo o el comercio es incorrecto";
            }
        } else {
            $message = "Ups! El codigo o el comercio es incorrecto";
        }
        return response()->json([
            'success' => $success,
            'info' => $message
        ]);



    }
    public function clientCancel($idOtp){
        $message="se cancelo tu pedido";
        $otp = Otp::where('id', $idOtp)->first();
        $otp->delete();
        return back()->with('info', $message);
    }


    public function discountOffer($idOffer)
    {
        $offer = Offer::where('id', $idOffer)->first();
        //var_dump($offer->id);
        $offer->amount = $offer->amount - 1;
        $offer->total_amount = $offer->total_amount + 1;
        $offer->save();
        $store = Store::find($offer->store_id);
        $store->gifts = $store->gifts - 1;
        $store->save();
    }
}
