<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zp extends Model
{

    use HasFactory;

    const MRP = 2917;
    const MZP = 42500;

    public function calculate($request) {
        $correction = 0;
        $zp = $request->oklad;
        $opv = $zp * 0.1;
        $vosms = $zp * 0.02;
        $osms = $zp * 0.02;
        $so = ($zp - $opv) * 0.035;
        if ($zp < 25 * self::MRP) {
            $correction = ($zp - $opv - self::MZP - $vosms) * 0.9;
        }
        $ipn = ($zp - $opv - ($request->hasNalog ? self::MZP : 0) - $vosms - $correction) * 0.1;
        if ($request->isPensioner && $request->isInvalid) {
            $na_ruki = $zp;
            $ipn = $opv = $osms = $vosms = $so = 0;
        } else if ($request->isPensioner || ($request->isInvalid && $zp > 882 * self::MRP)) {
            $na_ruki = $zp - $ipn;
            $opv = $osms = $vosms = $so = 0;
        } else if ($request->isInvalid && in_array($request->invalidGroup, [1, 2])) {
            $na_ruki = $zp - $so;
            $ipn = $opv = $osms = $vosms = 0;
        } else if ($request->isInvalid && $request->invalidGroup == 3) {
            $na_ruki = $zp - $so - $opv;
            $ipn = $osms = $vosms = 0;
        } else {
            $na_ruki = $zp - $ipn - $opv - $osms - $vosms - $so;
        }

        if ($request->method() == "POST") {
            $this->store(array('ipn' => $ipn, 'opv' => $opv, 'osms' => $osms, 'vosms' => $vosms, 'na_ruki' => $na_ruki));
        }

        return [
            'Индивидуальный подоходный налог' => $ipn,
            'Обязательные пенсионные взносы' => $opv,
            'Обязательное социальное медицинское страхование' => $osms,
            'Взносы на обязательное социальное медицинское страхование' => $vosms,
            'Социальные отчисления' => $so,
            'зарплата на руки' => $na_ruki
        ];
    }

    public function store(array $data)
    {
        $this->ipn = $data['ipn'];
        $this->osms = $data['osms'];
        $this->vosms = $data['vosms'];
        $this->opv = $data['opv'];
        $this->na_ruki = $data['na_ruki'];
        $this->save();
    }
}
