<?php

namespace jDate;

use Carbon\Carbon;

/**
 * Class jDate
 * @package jDate
 * @property string solarDay
 * @property string solarDayName
 * @property string solarMonth
 * @property string solarYear
 * @property string monthName
 */

Class jDate extends Carbon{

    private $daySolar = 0;
    private $monthSolar = 0;
    private $yearSolar = 0;
    private $isLeapYearSolar = 0;

    /**
     * Create a new jDate instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     *
     * @param string              $time
     * @param DateTimeZone|string $tz
     */
    public function __construct($time = null, $tz = null)
    {
        parent::__construct($time, $tz);
        $this->sync();
    }

    public function fromSolar($year, $month, $day, $hour = 0, $minute = 1)
    {
        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

        $jy = $year - 979;
        $jm = $month - 1;
        $jd = $day - 1;

        $j_day_no = 365*$jy + $this->div($jy, 33)*8 + $this->div($jy%33+3, 4);
        for ($i=0; $i < $jm; ++$i) $j_day_no += $j_days_in_month[$i];
        $j_day_no += $jd; $g_day_no = $j_day_no+79;
        $gy = 1600 + 400*$this->div($g_day_no, 146097);

        /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
        $g_day_no = $g_day_no % 146097; $leap = true;
        if ($g_day_no >= 36525) {
            /* 36525 = 365*100 + 100/4 */
            $g_day_no--; $gy += 100*$this->div($g_day_no, 36524);

            /* 36524 = 365*100 + 100/4 - 100/100 */
            $g_day_no = $g_day_no % 36524; if ($g_day_no >= 365) $g_day_no++; else $leap = false;
        }
        $gy += 4*$this->div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */ $g_day_no %= 1461;
        if ($g_day_no >= 366) {
            $leap = false; $g_day_no--; $gy += $this->div($g_day_no, 365); $g_day_no = $g_day_no % 365;
        }
        for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++) $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
        $gm = $i+1; $gd = $g_day_no+1;

        $this->setDateTime($gy, $gm, $gd, $hour, $minute);
        $this->daySolar = $day;
        $this->monthSolar = $month;
        $this->yearSolar = $year;
        $this->isLeapYearSolar = $leap;

        return $this;
    }

    public function toSolar($format = "Y/m/d", $convertNumbers = true)
    {
        $this->sync();

        $result = "";
        for ($i = 0; $i < strlen($format); $i++) {
            $subtype=substr($format,$i,1);
            switch ($subtype)
            {
                case "A":
                    $result .= $this->farsiAmPm(true);
                    break;
                case "a":
                    $result .= $this->farsiAmPm();
                    break;
                case "d":
                    $result.= str_pad($this->daySolar, 2, "0", STR_PAD_LEFT);
                    break;
                case "j":
                    $result.= $this->convertNumberToWord($this->daySolar, true);
                    break;
                case "D":
                    $result .= $this->farsiDayName();
                    break;
                case"F":
                    $result.= $this->farsiMonthName(true);
                    break;
                case "M":
                    $result.= $this->farsiMonthName();
                    break;
                case "l":
                    $result .= $this->farsiDayName(true);
                    break;
                case "m":
                    $result.= str_pad($this->monthSolar, 2, "0", STR_PAD_LEFT);
                    break;
                case "n":
                    $result.= $this->monthSolar;
                    break;
                case "S":
                    $result.= "م";
                    break;
                case "t":
                    $result.= $this->farsiDaysInMonth();
                    break;
                case "w":
                    $result .= $this->farsiDayOfWeek();
                    break;
                case "y":
                    $result .= substr($this->yearSolar, 2);
                    break;
                case "Y":
                    $result .= $this->yearSolar;
                    break;
                case "Z" :
                    $result.= $this->farsiDayOfYear();
                    break;
                case "L" :
                    $result .= $this->isLeapYearSolar;
                    break;
                default:
                    $result.= $this->format($subtype);
            }
        }

        return $convertNumbers ? $this->convertNumbers($result) : $result;

        /*$output = $this->yearSolar."/".str_pad($this->monthSolar,2,'0',STR_PAD_LEFT)."/".str_pad($this->daySolar,2,'0',STR_PAD_LEFT);
        if($showTime) $output .= date("-h:i:s", $this->timestamp);
        return $this->convertNumbers($output);*/
    }

    public function ago(Carbon $other = null, $absolute = false)
    {
        $diff = parent::diffForHumans($other, $absolute);
        $pattern = array("years", "months", "days", "weeks", "hours", "minutes", "seconds", "year", "month", "day", "week", "hour", "minute", "second", "from now", "ago", "after", "before");
        $replace = array("سال", "ماه", "روز", "هقته", "ساعت", "دقیقه", "ثانیه", "سال", "ماه", "روز", "هقته", "ساعت", "دقیقه", "ثانیه", "از الان", "پیش", "بعد", "قبل" );
        return str_replace($pattern, $replace, $diff);
    }

    public function convertNumbers($matches, $reverse = false)
    {
        $farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
        $english_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");

        $output = str_replace($english_array, $farsi_array, $matches);
        if($reverse) $output = str_replace($farsi_array, $english_array, $matches);

        return $output;
    }

    public static function parseFromSolar($input = null)
    {
        $self = new self();
        $parsed = date_parse($self->convertNumbers($input, true));
        if($parsed['year'] != false) {
            $self->fromSolar($parsed['year'], $parsed['month'], $parsed['day'], $parsed['hour'], $parsed['minute']);
            return $self;
        }

        return null;
    }

    public function __get($word)
    {
        switch ($word) {
            case "solarDay":
                return $this->daySolar;
                break;
            case "solarDayName":
                return $this->farsiDayName();
                break;
            case "solarMonth":
                return $this->monthSolar;
                break;
            case "solarYear":
                return $this->yearSolar;
                break;
            case "monthName":
                return $this->format("F");
                break;
            case "solarMonthName":
                return $this->farsiMonthName();
                break;
            default:
                return parent::__get($word);
                break;
        }
    }

    private function div($a,$b) { return (int) ($a / $b); }

    private function convertNumberToWord($num, $farsi = false)
    {
        $num = (int) $num;
        $words = array();
        if ($farsi) {
            $list1 = array('صفر', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه', 'ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده');
            $list2 = array('', 'ده', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود', 'صد');
            $list3 = array('', 'هزار', 'میلیون', 'میلیارد', 'تریلیون', 'کادریلیون', 'کوینتریلیون', 'سکستریلیون', 'سپتریلیون', 'اکتریلیون', 'نونیلیون', 'دسیلیون');
            $glue = " و";
        } else {
            $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven','twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
            );
            $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
            $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
                'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion','quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion');
            $glue = " and";
        }

        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);

        foreach ($num_levels as $i => $num_level) {
            $levels--;
            $hundreds = (int) ($num_level / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' ' . $list2[10] . $glue . " " : '');

            $tens = (int) ($num_level % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_level % 10);
                $singles = $list1[$singles] . ' ';
            }

            $meme = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_level ) ) ? ' ' . $list3[$levels] : '' );
            $words[] = $meme;

        }
        return implode($glue, $words);
    }

    private function farsiDayName($full = false)
    {
        $feed = ($full) ? $this->format("l") : $this->format("D");
        switch ( strtolower($feed) ) {
            case 'sat': $ret = 'ش'; break;
            case 'sun': $ret = 'ی'; break;
            case 'mon': $ret = 'د'; break;
            case 'tue': $ret = 'س'; break;
            case 'wed': $ret = 'چ'; break;
            case 'thu': $ret = 'پ'; break;
            case 'fri': $ret = 'ج'; break;
            case 'saturday': $ret = 'شنبه'; break;
            case 'sunday': $ret = 'یک شنبه'; break;
            case 'monday': $ret = 'دو شنبه'; break;
            case 'tuesday': $ret = 'سه شنبه'; break;
            case 'wednesday': $ret = 'چهار شنبه'; break;
            case 'thursday': $ret = 'پنج شنبه'; break;
            case 'friday': $ret = 'جمعه'; break;
            default: $ret = "جمعه"; break;
        }
        return $ret;
    }

    private function farsiMonthName()
    {
        switch ( $this->monthSolar ) {
            case '1': $ret = 'فروردین'; break;
            case '2': $ret = 'اردیبهشت'; break;
            case '3': $ret = 'خرداد'; break;
            case '4': $ret = 'تیر'; break;
            case '5': $ret = 'امرداد'; break;
            case '6': $ret = 'شهریور'; break;
            case '7': $ret = 'مهر'; break;
            case '8': $ret = 'آبان'; break;
            case '9': $ret = 'آذر'; break;
            case '10': $ret = 'دی'; break;
            case '11': $ret = 'بهمن'; break;
            case '12': $ret = 'اسفند'; break;
            default : $ret = 'اسفند'; break;
        }
        return $ret;
    }

    private function farsiAmPm($full = false)
    {
        $feed = ($full) ? $this->format("A") : $this->format("a");
        switch ( $feed ) {
            case 'am': $ret = 'ق ظ'; break;
            case 'pm': $ret = 'ب ظ'; break;
            case 'AM': $ret = 'قبل از ظهر'; break;
            case 'PM': $ret = 'بعد از ظهر'; break;
            default : $ret = 'ق ظ'; break;
        }
        return $ret;
    }

    private function farsiDaysInMonth()
    {
        $array = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
        return $array[$this->monthSolar - 1];
    }

    private function farsiDayOfWeek()
    {
        $array = array('sat' => 1, 'sun' => 2, 'mon' => 3, 'tue' => 4, 'wed' => 5, 'thu' => 6, 'fri' => 7);
        return $array[strtolower($this->format("D"))];
    }

    private function farsiDayOfYear()
    {
        $result = 0;
        if ($this->monthSolar == "01") return $this->daySolar;

        $array = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
        for ($i = 1; $i < $this->monthSolar || $i == 12; $i++) {
            $result += $array[$i];
        }
        return $result + $this->daySolar;
    }

    private function sync()
    {
        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

        $gy = date("Y", $this->timestamp) - 1600;
        $gm = date("n", $this->timestamp) - 1;
        $gd = date("j", $this->timestamp) - 1;

        $g_day_no = 365*$gy+$this->div($gy+3,4)-$this->div($gy+99,100)+$this->div($gy+399,400);

        for ($i=0; $i < $gm; ++$i) $g_day_no += $g_days_in_month[$i];

        if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0))) $g_day_no++; /* leap and after Feb */

        $g_day_no += $gd; $j_day_no = $g_day_no-79; $j_np = $this->div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
        $j_day_no = $j_day_no % 12053; $jy = 979+33*$j_np+4*$this->div($j_day_no,1461); /* 1461 = 365*4 + 4/4 */
        $j_day_no %= 1461; if($j_day_no >= 366) { $jy += $this->div($j_day_no-1, 365); $j_day_no = ($j_day_no-1)%365; }

        for($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) $j_day_no -= $j_days_in_month[$i];
        $jm = $i + 1;
        $jd = $j_day_no + 1;

        $this->yearSolar = $jy;
        $this->monthSolar = $jm;
        $this->daySolar = $jd;
        $this->isLeapYearSolar = ($g_day_no > 365) ? true : false;
    }
}
