<?php
namespace App\Services\EncryptService;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class EncryptService{
    public function encryptAll($data)
    {
        $data->getCollection()->transform(function ($item) {
                $item->encrypted_id = Crypt::encrypt($item->id);
                return $item;
            });
    }

    public function find(string $id){
        try {
            return Crypt::decryptString($id);
        } catch (DecryptException $e) {
            // Manejo de errores si es necesario
            return null;
        }
    }




}