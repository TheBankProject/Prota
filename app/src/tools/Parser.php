<?php

namespace Minuz\Prota\Tools;

class Parser
{
    public static function HydrateNulls(array &$data, mixed $filling): void
    {
        $filler = function ($item) use ($filling) {
            return is_null($item) ? $filling : $item;
        };

        $data = array_map($filler, $data);
        return;
    }



    public static function HaveValues(array|null|false &$data): void
    {
        if ( ! $data ) {
            $data = false;
            return;
        }
        foreach( $data as $index => $item) {
            $clearedItem = trim($item);
            if ( ! $clearedItem ) {
                $data[$index] = false;
            }
        }
        
        return;
    }



    public static function HaveEmptyVaLues(array $data): bool
    {
        foreach ( $data as $item ) {
            if ( $item == null ) {
                return true;
            }

            $itemCleaned = trim($item);
            if ( $itemCleaned == '' ) {
                return true;
            }
        }

        return false;
    }
}