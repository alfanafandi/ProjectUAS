<?php
require_once __DIR__ . '/../domain_object/node_voucher.php';

class VoucherModel
{
    private $vouchers = [];
    private $next_id = 1;
    private $file_path = __DIR__ . '/../json/vouchers.json';

    public function __construct()
    {
        if (file_exists($this->file_path)) {
            $this->loadFromJson();
            $this->next_id = count($this->vouchers) + 1;
        } else {
            $this->initializeDefaultVoucher();
        }
    }

    private function saveToJson()
    {
        $data = array_map(function ($voucher) {
            return [
                'voucher_id' => $voucher->voucher_id,
                'kode' => $voucher->kode,
                'diskon' => $voucher->diskon,
            ];
        }, $this->vouchers);

        file_put_contents($this->file_path, json_encode($data, JSON_PRETTY_PRINT));
    }

    private function loadFromJson()
    {
        $data = json_decode(file_get_contents($this->file_path), true);
        $this->vouchers = array_map(function ($item) {
            return new \Voucher($item['voucher_id'], $item['kode'], $item['diskon']);
        }, $data);
    }

    public function addVoucher($kode, $diskon)
    {
        $voucher = new \Voucher($this->next_id++, $kode, $diskon);
        $this->vouchers[] = $voucher;
        $this->saveToJson();
    }

    public function initializeDefaultVoucher()
    {
        $this->addVoucher("0777", 20);
        $this->addVoucher("1234", 10);
        $this->addVoucher("4321", 15);
        $this->saveToJson();
    }

    public function getAllVouchers()
    {
        return $this->vouchers;
    }

    public function getVoucherById($voucher_id)
    {
        foreach ($this->vouchers as $voucher) {
            if ($voucher->voucher_id == $voucher_id) {
                return $voucher;
            }
        }
        return null;
    }

    public function updateVoucher($voucher_id, $voucher_kode, $voucher_diskon)
    {
        foreach ($this->vouchers as $voucher) {
            if ($voucher->voucher_id == $voucher_id) {
                $voucher->kode = $voucher_kode;
                $voucher->diskon = $voucher_diskon;
                $this->saveToJson();
                return true;
            }
        }
        return false;
    }

    public function getVoucherByCode($kode)
    {
        foreach ($this->vouchers as $voucher) {
            if ($voucher->kode == $kode) {
                return $voucher;
            }
        }
        return null;
    }

    public function deleteVoucher($voucher_id)
    {
        foreach ($this->vouchers as $key => $voucher) {
            if ($voucher->voucher_id == $voucher_id) {
                unset($this->vouchers[$key]);
                $this->vouchers = array_values($this->vouchers);
                $this->saveToJson();
                return true;
            }
        }
        return false;
    }
}
