<?php
/**
 * Created by PhpStorm.
 * User: roboticsexpert
 * Date: 8/8/18
 * Time: 6:31 PM
 */

namespace Roboticsexpert\PaymentGateways\Helpers;

class NumberHelper
{
    public static function formatMoney($money, $price = ' تومان')
    {
        if (!$money) {
            $money = 0;
        }
        return self::formatNumber($money) . $price;
    }

    public static function formatNumber(int $number, bool $thousandSeparator = true, $mod = 'fa')
    {
        return StringHelper::englishToPersian(number_format($number, 0, '', $thousandSeparator ? ',' : ''));
    }

    public static function formatMoneyWithFree($money, $price = ' تومان')
    {
        if ($money == 0 || $money == null) {
            return 'رایگان';
        }
        return self::formatNumber($money) . $price;
    }

    public static function formatDiscount($money, $price = ' تومان')
    {
        if ($money == 0 || $money == null) {
            return 'ندارد';
        }
        return self::formatNumber($money) . $price;
    }

    public static function formatDownloadCount(int $number)
    {

        if ($number < 10) {
            return self::formatNumber($number);
        }
        return '+' . self::formatNumber(round($number, max(-(strlen($number) - 2), -3), PHP_ROUND_HALF_DOWN));
    }

    public static function roundPrice(int $price)
    {
        return round($price, -2);
    }

    public static function validateNationalId(string $nationalId)
    {
        if (strlen($nationalId) != 10) {
            return false;
        }


        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += $nationalId[$i] * (10 - $i);
        }

        $c = $sum - ((int)($sum / 11)) * 11;


        if (($c == 0 || $c == 1) && $c == $nationalId[9]) {
            return true;
        }

        if ($c > 1 && ((11 - $c) == $nationalId[9])) {
            return true;
        }

        return false;
    }


    /**
     * Validate IBAN (Sheba) account number
     *
     * @param $account
     *
     * @return bool
     */
    public static function validateIban($account)
    {
        if (strlen($account) != 26 || strtolower(substr($account, 0, 2)) != "ir") {
            return false;
        }

        $account = substr($account, 2, 24);

        if (!is_numeric($account) || strlen($account) != 24) {
            return false;
        }

        $ibanNumber = substr($account, 2) . '1827' . substr($account, 0, 2);
        $check_digits = bcmod($ibanNumber, 97);


        return (int)$check_digits === 1 ? true : false;
    }

    public static function validateLegalId($code)
    {
        if (!preg_match('/^[0-9]{10}$/', $code)) {
            return false;
        }
        for ($i = 0; $i < 10; $i++) {
            if (preg_match('/^' . $i . '{10}$/', $code)) {
                return false;
            }
        }
        for ($i = 0, $sum = 0; $i < 9; $i++) {
            $sum += ((10 - $i) * intval(substr($code, $i, 1)));
        }
        $ret = $sum % 11;
        $parity = intval(substr($code, 9, 1));
        if (($ret < 2 && $ret == $parity) || ($ret >= 2 && $ret == 11 - $parity)) {
            return true;
        }
        return false;
    }

    public static function validatePostalCode($postalcode)
    {
        return preg_match('/^([0-9]{5})([0-9]{5})?$/i', $postalcode);
    }


    public static function convertNumbersToEnglish($string)
    {
        $numbers = self::numbersToEnglishMap();
        return str_replace(array_keys($numbers), array_values($numbers), $string);
    }

    /**
     * @return array
     */
    public static function numbersToEnglishMap()
    {
        return [
            "\u{06F0}" => "\u{0030}",
            "\u{0660}" => "\u{0030}",
            "\u{06F1}" => "\u{0031}",
            "\u{0661}" => "\u{0031}",
            "\u{06F2}" => "\u{0032}",
            "\u{0662}" => "\u{0032}",
            "\u{06F3}" => "\u{0033}",
            "\u{0663}" => "\u{0033}",
            "\u{06F4}" => "\u{0034}",
            "\u{0664}" => "\u{0034}",
            "\u{06F5}" => "\u{0035}",
            "\u{0665}" => "\u{0035}",
            "\u{06F6}" => "\u{0036}",
            "\u{0666}" => "\u{0036}",
            "\u{06F7}" => "\u{0037}",
            "\u{0667}" => "\u{0037}",
            "\u{06F8}" => "\u{0038}",
            "\u{0668}" => "\u{0038}",
            "\u{06F9}" => "\u{0039}",
            "\u{0669}" => "\u{0039}",
        ];
    }

}
