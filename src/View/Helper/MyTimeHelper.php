<?php

namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\View\Helper\TimeHelper;

/**
 * FoodCoopShop - The open source software for your foodcoop
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since         FoodCoopShop 1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 * @author        Mario Rothauer <office@foodcoopshop.com>
 * @copyright     Copyright (c) Mario Rothauer, https://www.rothauer-it.com
 * @link          https://www.foodcoopshop.com
 */
class MyTimeHelper extends TimeHelper
{

    /**
     * @param $array
     * @return array
     */
    public function sortArrayByDate($array)
    {
        usort($array, function($a, $b) {
            return strtotime($a) - strtotime($b);
        });
        return $array;    
    }
    
    public function getTimeObjectUTC($time)
    {
        $timeObject = new Time($time);
        $timeObject->setTimezone('UTC');
        return $timeObject;
    }
    
    public function correctTimezone($timeObject)
    {
        return $timeObject->modify($this->getTimezoneDiffInSeconds($timeObject->toUnixString()) . ' seconds');
    }
    
    public function getTimezoneDiffInSeconds($timestamp)
    {
        $timezoneDiff = date('Z', $timestamp);
        return $timezoneDiff;
    }
    
    public function getI18Format($formatString)
    {
        return Configure::read('DateFormat.' . $formatString);
    }

    public function getLastDayOfGivenMonth($monthAndYear)
    {
        return date('t', strtotime($monthAndYear));
    }

    public function getYearFromDbDate($dbDate)
    {
        return date('Y', strtotime($dbDate));
    }
    
    public function getCurrentDateTimeForDatabase()
    {
        return date($this->getI18Format('DatabaseWithTimeAlt'));
    }
    
    public function getCurrentDateForDatabase()
    {
        return date($this->getI18Format('DatabaseAlt'));
    }
    
    public function getNthWeekdayBeforeWeekday($n, $weekday)
    {
        $beforeWeekday = $weekday - $n;
        if ($beforeWeekday < 0) {
            $beforeWeekday += 7;
        }
        return $beforeWeekday;
    }
    
    public function getSendOrderListsWeekday()
    {
        $sendOrderListsWeekday = Configure::read('appDb.FCS_WEEKLY_PICKUP_DAY') - Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA');
        if ($sendOrderListsWeekday < 0) {
            $sendOrderListsWeekday += 7;
        }
        return $sendOrderListsWeekday;
    }

    /**
     * should be called only once per request!
     * only implemented for requests on getWeekdaysBetweenOrderSendAndDelivery days
     * the rest of the days the date is nonsense!
     */
    public function recalcDeliveryDayDelta()
    {
        $sendOrderListsWeekday = $this->getSendOrderListsWeekday();
        switch (date('N')) {
            case $sendOrderListsWeekday: // today is $sendOrderListsWeekday
                $newDeliveryDelta = Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA');
                break;
            case $sendOrderListsWeekday + 1:
                $newDeliveryDelta = Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA') - 1;
                break;
            case $sendOrderListsWeekday + 2:
                $newDeliveryDelta = Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA') - 2;
                break;
            case $sendOrderListsWeekday + 3:
                $newDeliveryDelta = Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA') - 3;
                break;
        }
        if (isset($newDeliveryDelta)) {
            Configure::write('app.deliveryDayDelta', $newDeliveryDelta);
        }
    }

    public function getDeliveryDateByCurrentDayForDb()
    {
        $deliveryDate = self::getDeliveryDayByCurrentDay();
        $deliveryDate = date($this->getI18Format('DatabaseAlt'), $deliveryDate);
        return $deliveryDate;
    }
    
    public function getDateFormattedWithWeekday($date) {
        $date = $this->getWeekdayName($this->formatAsWeekday($date)) . ', ' . date($this->getI18Format('DateShortAlt'), $date);
        return $date;
    }
    
    public function getDeliveryDateByCurrentDayFormattedWithWeekday()
    {
        $deliveryDate = self::getDeliveryDayByCurrentDay();
        return $this->getDateFormattedWithWeekday($deliveryDate);
    }

    public function getDeliveryDayByCurrentDay()
    {
        return self::getDeliveryDay($this->getCurrentDay());
    }
    
    public function getNextDeliveryDays($maxDays=30) {
        $nextDeliveryDay = $this->getDeliveryDateByCurrentDayForDb();
        $nextDeliveryDays = [
            $nextDeliveryDay => $this->getDateFormattedWithWeekday(strtotime($nextDeliveryDay))
        ];
        $count = 1;
        while($count < $maxDays) {
            $nextCalculatedDeliveryDay = date(Configure::read('DateFormat.DatabaseAlt'), strtotime($nextDeliveryDay . ' + ' . $count * 7 . ' day'));
            $nextDeliveryDays[$nextCalculatedDeliveryDay] = $this->getDateFormattedWithWeekday(strtotime($nextCalculatedDeliveryDay));
            $count++;
        }
        return $nextDeliveryDays;
    }
    
    public function getDbFormattedPickupDayByDbFormattedDate($date)
    {
        $pickupDay = $this->getDeliveryDay(strtotime($date));
        $pickupDay = date(Configure::read('DateFormat.DatabaseAlt'), $pickupDay);
        return $pickupDay;
    }

    public function getDeliveryDateForSendOrderListsShell()
    {
        $formattedToday = date(Configure::read('DateFormat.DatabaseAlt'), $this->getCurrentDay());
        $deliveryDay = strtotime($formattedToday . '+' . Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA') . ' days');
        $deliveryDay = date($this->getI18Format('DatabaseAlt'), $deliveryDay);
        return $deliveryDay;
    }

    public function getDeliveryDay($orderDay)
    {
        $daysToAddToOrderPeriodLastDay = Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA') + 1;
        $deliveryDate = strtotime($this->getOrderPeriodLastDay($orderDay) . '+' . $daysToAddToOrderPeriodLastDay . ' days');
        
        $weekdayDeliveryDate = $this->formatAsWeekday($deliveryDate);
        $weekdayStringDeliveryDate = strtolower(date('l', $deliveryDate));
        
        $weekdayOrderDay = $this->formatAsWeekday($orderDay);
        
        if ($weekdayOrderDay >= $this->getSendOrderListsWeekday() && $weekdayOrderDay <= $weekdayDeliveryDate) {
            $preparedOrderDay = date($this->getI18Format('DateShortAlt'), $orderDay);
            $deliveryDate = strtotime($preparedOrderDay . '+ 1 week ' . $weekdayStringDeliveryDate);
        }
        
        return $deliveryDate;
    }

    public function getWeekdaysBetweenOrderSendAndDelivery($delta = 0)
    {
        $sendOrderListsWeekday = $this->getSendOrderListsWeekday();
        $weekdays = [];
        for ($i = $sendOrderListsWeekday; $i <= $sendOrderListsWeekday + Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA') + $delta; $i++) {
            $weekdays[] = $i;
        }
        return $weekdays;
    }

    public function getCurrentWeekday()
    {
        return $this->formatAsWeekday($this->getCurrentDay());
    }

    public function formatAsWeekday($day)
    {
        return date('N', $day);
    }

    public function getCurrentDay()
    {
        return time();
    }

    public function getDeliveryWeekday()
    {
        return Configure::read('appDb.FCS_WEEKLY_PICKUP_DAY');
    }
    
    public function getPreselectedDeliveryDayForOrderDetails($day)
    {
        $orderPeriodFirstDay = $this->getOrderPeriodFirstDay($day);
        return date($this->getI18Format('DateShortAlt'), $this->getDeliveryDay(strtotime($orderPeriodFirstDay)));
    }

    /**
     * see tests for implementations
     * @param $day
     * @return $day
     */
    public function getOrderPeriodFirstDay($day)
    {

        $currentWeekday = $this->formatAsWeekday($day);
        $dateDiff = 7 - $this->getSendOrderListsWeekday() + $currentWeekday;
        $date = strtotime('-' . $dateDiff . ' day ', $day);

        if ($currentWeekday > $this->getDeliveryWeekday()) {
            $date = strtotime('+7 day', $date);
        }

        $date = date($this->getI18Format('DateShortAlt'), $date);

        return $date;
    }

    /**
     * implemented for $this->sendOrderListsWeekday() == monday OR tuesday OR wednesday
     * @param $day
     * @return $day
     */
    public function getOrderPeriodLastDay($day)
    {

        $currentWeekday = $this->formatAsWeekday($day);

        if ($currentWeekday == 7) {
            $currentWeekday = 0;
        }

        if ($currentWeekday == $this->getDeliveryWeekday()) {
            $dateDiff = -1 - Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA');
        }
        if ($currentWeekday == ($this->getDeliveryWeekday() + 1) % 7) {
            $dateDiff = (Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA') * -1) + 5;
        }
        if ($currentWeekday == ($this->getDeliveryWeekday() + 2) % 7) {
            $dateDiff = (Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA') * -1) + 4;
        }
        if ($currentWeekday == ($this->getDeliveryWeekday() + 3) % 7) {
            $dateDiff = (Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA') * -1) + 3;
        }
        if ($currentWeekday == ($this->getDeliveryWeekday() + 4) % 7) {
            $dateDiff = (Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA') * -1) + 2;
        }
        if ($currentWeekday == ($this->getDeliveryWeekday() + 5) % 7) {
            $dateDiff = (Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA') * -1) + 1;
        }
        if ($currentWeekday == ($this->getDeliveryWeekday() + 6) % 7) {
            $dateDiff = Configure::read('appDb.FCS_DEFAULT_SEND_ORDER_LISTS_DAY_DELTA') * -1;
        }

        $date = date($this->getI18Format('DateShortAlt'), strtotime($dateDiff . ' day ', $day));

        return $date;
    }
    
    public function getWeekdaysWithSuffix($suffix)
    {
        $weekdays = $this->getWeekdays();
        $i = 0;
        foreach($weekdays as $weekday) {
            $weekdays[$i] .= $suffix;
            $i++;
        }
        return $weekdays;
    }

    public function getWeekdays()
    {
        $weekdays = [
          1 => __('Monday'),
          2 => __('Tuesday'),
          3 => __('Wednesday'),
          4 => __('Thursday'),
          5 => __('Friday'),
          6 => __('Saturday'),
          0 => __('Sunday')
        ];
        return $weekdays;
    }

    public function getMonths()
    {
        $months = [
            1 =>  __('January'),
            2 =>  __('February'),
            3 =>  __('March'),
            4 =>  __('April'),
            5 =>  __('May'),
            6 =>  __('June'),
            7 =>  __('July'),
            8 =>  __('August'),
            9 =>  __('September'),
            10 => __('October'),
            11 => __('November'),
            12 => __('December')
        ];
        return $months;
    }

    public function getAllMonthsForYear($year)
    {
        $months = $this->getMonths();
        $monthsForYear = [];
        foreach ($months as $key => $value) {
            $monthsForYear[$year.'-'.$key] = $value . ' ' . $year;
        }
        return $monthsForYear;
    }

    public function getWeekdayName($weekday)
    {
        $weekday = $weekday % 7;
        $weekdays = $this->getWeekdays();
        return $weekdays[$weekday];
    }

    public function getMonthName($month)
    {
        $months = $this->getMonths();
        return $months[$month];
    }

    public function getLastNDays($n, $startDate)
    {

        $startDate = strtotime($startDate);

        $days = [];
        for ($i=1; $i<=$n; $i++) {
            $deltaString = '-' . $i . ' days';
            $weekDay = date('w', strtotime($deltaString, $startDate));
            $days[date(Configure::read('DateFormat.DatabaseWithTimeAlt'), strtotime($deltaString, $startDate))] = $this->getWeekdayName($weekDay) . ', ' . date($this->getI18Format('DateShortAlt'), strtotime($deltaString, $startDate));
        }
        return $days;
    }
    
    public function getFirstDayOfThisYear()
    {
        return date($this->getI18Format('DateShortAlt'), strtotime('first day of january'));
    }

    public function getLastDayOfThisYear()
    {
        return date($this->getI18Format('DateShortAlt'), strtotime('last day of december'));
    }

    public function getLastMonthNameAndYear()
    {
        $previousMonthModifier = strtotime('first day of previous month');
        $lastMonthAndYearString = $this->getMonthName(date('n', $previousMonthModifier)) . ' ' . date('Y', $previousMonthModifier);
        return $lastMonthAndYearString;
    }

    public function getFirstDayOfLastMonth($date)
    {
        return date($this->getI18Format('DateShortAlt'), strtotime($date . ' first day of previous month'));
    }
    
    public function getLastDayOfLastMonth($date)
    {
        return date($this->getI18Format('DateShortAlt'), strtotime($date . ' last day of previous month'));
    }
    
    /**
     * considers windows and unix
     * @return boolean
     */
    public function isDatabaseDateNotSet($date)
    {
        return $date == '1970-01-01' || $date == '01.01.1970' || $date == '30.11.-0001' || $date == '0000-00-00' || $date == '1000-01-01' || $date == null;
    }

    public function prepareDbDateForDatepicker($date)
    {
        $preparedDate = $this->formatToDateShort($date);
        if ($this->isDatabaseDateNotSet($date)) {
            return '';
        } else {
            return $preparedDate;
        }
    }

    /**
     * formats a timestamp to a short date
     * @param integer $timestamp
     */
    public function formatToDateShort($dbString)
    {
        $timestamp = strtotime($dbString);
        if ($dbString == '') {
            return '';
        }
        return date($this->getI18Format('DateShortAlt'), $timestamp);
    }
    /**
      * @param $dateString
      */
    public function formatToDbFormatDate($dateString)
    {
        $timestamp = strtotime($dateString);
        $result = date(Configure::read('DateFormat.DatabaseAlt'), $timestamp);
        return $result;
    }

}
