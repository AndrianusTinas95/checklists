<?php

namespace App\Helpers;

use Illuminate\Support\Carbon as IlluminateCarbon;

class Carbon extends IlluminateCarbon
{
    protected static $years;
    protected static $months;
    protected static $weeks;
    protected static $days;
    protected static $hours;
    protected static $minutes;
    protected static $seconds;
    protected static $null;

    public static function chTime($timeStart,$timeEnd=null){
        $start  = Carbon::parse($timeStart);
        $end    = Carbon::parse($timeEnd ?? Carbon::now());

        self::$years    = $end->diffInYears($start);
        self::$months   = $end->diffInMonths($start);
        self::$weeks    = $end->diffInWeeks($start);
        self::$days     = $end->diffInDays($start);
        self::$hours    = $end->diffInHours($start);
        self::$minutes  = $end->diffInMinutes($start);
        self::$seconds  = $end->diffInSeconds($start);

        $unit =self::$years ? 'years' :(
            self::$months ? 'months' : (
                self::$weeks ? 'weeks' : (
                    self::$days ? 'days' : (
                        self::$hours ? 'hours' : (
                            self::$minutes ? 'minutes':(
                                self::$seconds ? 'seconds' : 'null'
                            )
                        )
                    )
                )
            )
        ); 

        return [
            'years'     => self::$years,
            'months'    => self::$months,
            'weeks'     => self::$weeks,
            'days'      => self::$days,
            'hours'     => self::$hours,
            'minutes'   => self::$minutes,
            'seconds'   => self::$seconds,
            'null'      => self::$null,
            'unit'      => $unit
        ];
    }

    public static function chDue($timeStart,$timeEnd=null){
        $time = self::chTime($timeStart,$timeEnd);

        return [
            'unit' => $time['unit'] != 'null' ? $time['unit'] : '',
            'interval'      => $time[$time['unit']],
        ];
    }

    public static function chTransform($interval,$unit){
        $start = Carbon::now();
        switch ($unit) {
            case 'minute':
                return $start->copy()
                            ->addMinutes($interval)
                            ->format('y-m-d H:i:s');
                break;

            case 'hour':
                return $start->copy()
                            ->addHours($interval)
                            ->format('y-m-d H:i:s');
                break;

            case 'day':
                return $start->copy()
                            ->addDays($interval)
                            ->format('y-m-d H:i:s');      
                break;

            case 'week':
                return $start->copy()
                            ->addWeeks($interval)
                            ->format('y-m-d H:i:s');      
                break;
            
            case 'month':
                return $start->copy()
                            ->addMonths($interval)
                            ->format('y-m-d H:i:s');      
                break;
            
            default:
                return null;
                break;
        }
    } 
}