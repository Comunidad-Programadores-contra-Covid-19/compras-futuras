<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use Auth;
use App\Otp;
use App\Offer;
use App\Credentials;
use App\RatingStore;
use Illuminate\Support\Facades\DB;
use willvincent\Rateable\Rating;
use App\Mail\registroComercioEmail;
use Mail;
use App\User;
class StoreController extends Controller
{

    public function renderPerfil()
    {
        if(Auth::user()){
            $usuario=Auth::user();
            if($usuario->rol=='store' && $usuario->email_verified_at && $usuario->email_send == '0'){
                   // Mail::to($usuario->email)->send(new registroComercioEmail($usuario->store->name));
                    $user = User::findOrFail($usuario->id);
                    $user->email_send ='1';
                    $user->update();
            }
        }

        return view('stores.miPerfil');
    }
    public function renderVentas()
    {

        $storeId = Auth::user()->store->id;

        $storeOffers = Offer::where('store_id', $storeId)->orderBy('created_at', 'desc')->get();

        return view('stores.misVentas', compact('storeOffers'));
    }
    public function renderProductos()
    {

        $commerceEmail = Auth::user()->store->id;

        $storeOffers = Offer::where('store_id', $commerceEmail)->orderBy('created_at', 'desc')->get();

        return view('stores.misProductos', compact('storeOffers'));
    }

    public function index(Request $request)
    {
        $name = $request->searchName;
        $stores = DB::table('stores')
            ->join('credentials', 'stores.id', '=', 'credentials.store_id')
            ->where('name', 'LIKE', "%$name%")
            ->select('stores.*', 'credentials.store_id')
            ->get();
        return view('welcome', ['stores' => $stores]);
    }

    /*     public function store(Request $request)
    {
        $store = new Store;
        $store->name = $request->name;
        $store->save();
        return redirect('stores');
    } */

    /*     public function edit($id)
    {
        $store = Store::find($id);
        return view('stores.edit', ['store' => $store]);
    } */
    public function update(Request $request, $id)
    {
        $storeUpdate = Store::findOrFail($id);
        /* ['name','user_id','description','adress','sector','avatar','facebook','instagram','horarios','category','phone'] */

        $storeUpdate->name = $request->name;
        $storeUpdate->description = $request->description;
        $storeUpdate->address = $request->address;
        $storeUpdate->sector = $request->sector;
        $storeUpdate->facebook = $request->facebook;
        $storeUpdate->instagram = $request->instagram;
        $storeUpdate->horarios = $request->horarios;
        $storeUpdate->category = $request->category;
        $storeUpdate->phone = $request->phone;

        $storeUpdate->update();

        return redirect('stores/misProductos')->with('success', 'Tu perfil se modificado Correctamente, por favor ahora publica un producto');
    }
    public function updateImage(Request $request, $id)
    {
        $storeUpdate = Store::findOrFail($id);
        /* ['name','user_id','description','adress','sector','avatar','facebook','instagram','horarios','category','phone'] */

        if ($request->has('avatar')) {
            \Storage::delete($storeUpdate->avatar);
            $storeUpdate->avatar = $request->file('avatar')->store('public');
        } else {
            $storeUpdate->avatar = $storeUpdate->avatar;
        }


        $storeUpdate->update();

        return redirect('stores/misProductos')->with('success', 'Tu perfil se modificado Correctamente, por favor ahora publica un producto');
    }

    public function registerTwo(Request $request, $id)
    {
        $storeUpdate = Store::findOrFail($id);
        /* ['name','user_id','description','adress','sector','avatar','facebook','instagram','horarios','category','phone'] */

        $storeUpdate->name = $request->name;
        $storeUpdate->address = $request->address;
        $storeUpdate->category = $request->category;
        $storeUpdate->phone = $request->phone;

        $storeUpdate->update();

        return redirect('stores/misProductos')->with('success', 'Tu perfil se modificado correctamente, por favor ahora publica un producto');
    }
    public function show($id)
    {
        $store = Store::find($id);
        $otp='';
        if(Auth::user()){
            if(Auth::user()->rol == "client"){
                $userId=Auth::user()->id;
                $otp = Otp::where('user_id',  $userId)->first();
                if($otp){
                    $OTP_VALID = 60;
                    $validTill = strtotime($otp->otp_timestamp) + ($OTP_VALID * 60);
                    if (strtotime("now") > $validTill) {
                        $otp->delete();
                    }
                }
            }
        }

        $credencialStore = Credentials::where('store_id', $id)->get();
        $credentials = $credencialStore[0];
        return view('stores.index_profile', ['store' => $store, 'credentials' => $credentials,'otp'=> $otp]);
    }
    public function destroy($id)
    {
        $store = Store::find($id);
        \Storage::delete($store->avatar);
        $store->delete();
        return back();
    }

    public function setPuntuacion(Request $request, $id)
    {

        if ($request->estrellas>=1) {
        $rating = new RatingStore();
        $rating->rateStore($id, $request->estrellas);
        $otps=Otp::where('id', $request->otps)->first();
        $otps->is_rating= 1;
        $otps->save();

        return back()->with('success', 'Has calificado al comercio correctamente');

        } else {
            return back()->with('success', 'Debes elegir una calificación');
        }





    }

    public function getPuntuacion($storeId)
    {
        $rating = new RatingStore();
        $rate = $rating->getAverageRating($storeId);
        return $rate;
    }

}
