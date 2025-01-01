<?php
class Voucher
{
    public $voucher_id;
    public $kode;
    public $diskon;

    function __construct($voucher_id, $kode, $diskon)
    {
        $this->voucher_id = $voucher_id;
        $this->kode = $kode;
        $this->diskon = $diskon;
    }
}
