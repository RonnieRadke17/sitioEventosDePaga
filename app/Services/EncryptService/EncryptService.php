<?php
namespace App\Services\EncryptService;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class EncryptService{

    public function Encrypt($data)
    {
        try {
            $data->setCollection(
                $data->getCollection()->transform(function ($item) {
                $item->encrypted_id = Crypt::encrypt($item->id);
                return $item;
                })
            );
        } catch (\Throwable $th) {
            return null;
        }
        return $data;
    }


    /* ecriptación de los selectores */
    public function Encryptselectors($data)
    {
        return $data->transform(function ($item) {
            $item->encrypted_id = Crypt::encrypt($item->id);
            return $item;
        });
    }


    public function decrypt(string $id){
        try {
            return Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return null;
        }
    }




}