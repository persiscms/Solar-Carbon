<?php

/*
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carbon\Tests;

use Carbon\Carbon;

class DayOfWeekModifiersTest extends TestFixture
{
   public function testNext()
   {
      $d = Carbon::createFromDate(1975, 5, 21)->next();
      $this->assertCarbon($d, 1975, 5, 28, 0, 0, 0);
   }

   public function testNextMonday()
   {
      $d = Carbon::createFromDate(1975, 5, 21)->next(Carbon::MONDAY);
      $this->assertCarbon($d, 1975, 5, 26, 0, 0, 0);
   }

   public function testNextSaturday()
   {
      $d = Carbon::createFromDate(1975, 5, 21)->next(6);
      $this->assertCarbon($d, 1975, 5, 24, 0, 0, 0);
   }

   public function testNextTimestamp()
   {
      $d = Carbon::createFromDate(1975, 11, 14)->next();
      $this->assertCarbon($d, 1975, 11, 21, 0, 0, 0);
   }

   public function testLast()
   {
      $d = Carbon::createFromDate(1975, 5, 21)->last();
      $this->assertCarbon($d, 1975, 5, 14, 0, 0, 0);
   }

   public function testLastMonday()
   {
      $d = Carbon::createFromDate(1975, 5, 21)->last(Carbon::MONDAY);
      $this->assertCarbon($d, 1975, 5, 19, 0, 0, 0);
   }

   public function testLastSaturday()
   {
      $d = Carbon::createFromDate(1975, 5, 21)->last(6);
      $this->assertCarbon($d, 1975, 5, 17, 0, 0, 0);
   }

   public function testLastTimestamp()
   {
      $d = Carbon::createFromDate(1975, 11, 28)->last();
      $this->assertCarbon($d, 1975, 11, 21, 0, 0, 0);
   }

   public function testFirstDayOfMonth()
   {
      $d = Carbon::createFromDate(1975, 11, 21)->firstOfMonth();
      $this->assertCarbon($d, 1975, 11, 1, 0, 0, 0);
   }

   public function testFirstWednesdayOfMonth()
   {
      $d = Carbon::createFromDate(1975, 11, 21)->firstOfMonth(Carbon::WEDNESDAY);
      $this->assertCarbon($d, 1975, 11, 5, 0, 0, 0);
   }

   public function testFirstFridayOfMonth()
   {
      $d = Carbon::createFromDate(1975, 11, 21)->firstOfMonth(5);
      $this->assertCarbon($d, 1975, 11, 7, 0, 0, 0);
   }

   public function testLastDayOfMonth()
   {
      $d = Carbon::createFromDate(1975, 12, 5)->lastOfMonth();
      $this->assertCarbon($d, 1975, 12, 31, 0, 0, 0);
   }

   public function testLastTuesdayOfMonth()
   {
      $d = Carbon::createFromDate(1975, 12, 1)->lastOfMonth(Carbon::TUESDAY);
      $this->assertCarbon($d, 1975, 12, 30, 0, 0, 0);
   }

   public function testLastFridayOfMonth()
   {
      $d = Carbon::createFromDate(1975, 12, 5)->lastOfMonth(5);
      $this->assertCarbon($d, 1975, 12, 26, 0, 0, 0);
   }

   public function testNthOfMonthOutsideScope()
   {
      $this->assertFalse(Carbon::createFromDate(1975, 12, 5)->nthOfMonth(6, Carbon::MONDAY));
   }

   public function testNthOfMonthOutsideYear()
   {
      $this->assertFalse(Carbon::createFromDate(1975, 12, 5)->nthOfMonth(55, Carbon::MONDAY));
   }

   public function test2ndMondayOfMonth()
   {
      $d = Carbon::createFromDate(1975, 12, 5)->nthOfMonth(2, Carbon::MONDAY);
      $this->assertCarbon($d, 1975, 12, 8, 0, 0, 0);
   }

   public function test3rdWednesdayOfMonth()
   {
      $d = Carbon::createFromDate(1975, 12, 5)->nthOfMonth(3, 3);
      $this->assertCarbon($d, 1975, 12, 17, 0, 0, 0);
   }

   public function testFirstDayOfQuarter()
   {
      $d = Carbon::createFromDate(1975, 11, 21)->firstOfQuarter();
      $this->assertCarbon($d, 1975, 10, 1, 0, 0, 0);
   }

   public function testFirstWednesdayOfQuarter()
   {
      $d = Carbon::createFromDate(1975, 11, 21)->firstOfQuarter(Carbon::WEDNESDAY);
      $this->assertCarbon($d, 1975, 10, 1, 0, 0, 0);
   }

   public function testFirstFridayOfQuarter()
   {
      $d = Carbon::createFromDate(1975, 11, 21)->firstOfQuarter(5);
      $this->assertCarbon($d, 1975, 10, 3, 0, 0, 0);
   }

   public function testLastDayOfQuarter()
   {
      $d = Carbon::createFromDate(1975, 8, 5)->lastOfQuarter();
      $this->assertCarbon($d, 1975, 9, 30, 0, 0, 0);
   }

   public function testLastTuesdayOfQuarter()
   {
      $d = Carbon::createFromDate(1975, 8, 1)->lastOfQuarter(Carbon::TUESDAY);
      $this->assertCarbon($d, 1975, 9, 30, 0, 0, 0);
   }

   public function testLastFridayOfQuarter()
   {
      $d = Carbon::createFromDate(1975, 7, 5)->lastOfQuarter(5);
      $this->assertCarbon($d, 1975, 9, 26, 0, 0, 0);
   }

   public function testNthOfQuarterOutsideScope()
   {
      $this->assertFalse(Carbon::createFromDate(1975, 1, 5)->nthOfQuarter(20, Carbon::MONDAY));
   }

   public function testNthOfQuarterOutsideYear()
   {
      $this->assertFalse(Carbon::createFromDate(1975, 1, 5)->nthOfQuarter(55, Carbon::MONDAY));
   }

   public function test2ndMondayOfQuarter()
   {
      $d = Carbon::createFromDate(1975, 8, 5)->nthOfQuarter(2, Carbon::MONDAY);
      $this->assertCarbon($d, 1975, 7, 14, 0, 0, 0);
   }

   public function test3rdWednesdayOfQuarter()
   {
      $d = Carbon::createFromDate(1975, 8, 5)->nthOfQuarter(3, 3);
      $this->assertCarbon($d, 1975, 7, 16, 0, 0, 0);
   }

   public function testFirstDayOfYear()
   {
      $d = Carbon::createFromDate(1975, 11, 21)->firstOfYear();
      $this->assertCarbon($d, 1975, 1, 1, 0, 0, 0);
   }

   public function testFirstWednesdayOfYear()
   {
      $d = Carbon::createFromDate(1975, 11, 21)->firstOfYear(Carbon::WEDNESDAY);
      $this->assertCarbon($d, 1975, 1, 1, 0, 0, 0);
   }

   public function testFirstFridayOfYear()
   {
      $d = Carbon::createFromDate(1975, 11, 21)->firstOfYear(5);
      $this->assertCarbon($d, 1975, 1, 3, 0, 0, 0);
   }

   public function testLastDayOfYear()
   {
      $d = Carbon::createFromDate(1975, 8, 5)->lastOfYear();
      $this->assertCarbon($d, 1975, 12, 31, 0, 0, 0);
   }

   public function testLastTuesdayOfYear()
   {
      $d = Carbon::createFromDate(1975, 8, 1)->lastOfYear(Carbon::TUESDAY);
      $this->assertCarbon($d, 1975, 12, 30, 0, 0, 0);
   }

   public function testLastFridayOfYear()
   {
      $d = Carbon::createFromDate(1975, 7, 5)->lastOfYear(5);
      $this->assertCarbon($d, 1975, 12, 26, 0, 0, 0);
   }

   public function testNthOfYearOutsideScope()
   {
      $this->assertFalse(Carbon::createFromDate(1975, 1, 5)->nthOfYear(55, Carbon::MONDAY));
   }

   public function test2ndMondayOfYear()
   {
      $d = Carbon::createFromDate(1975, 8, 5)->nthOfYear(2, Carbon::MONDAY);
      $this->assertCarbon($d, 1975, 1, 13, 0, 0, 0);
   }

   public function test3rdWednesdayOfYear()
   {
      $d = Carbon::createFromDate(1975, 8, 5)->nthOfYear(3, 3);
      $this->assertCarbon($d, 1975, 1, 15, 0, 0, 0);
   }
}