<?php

namespace Minuz\BaseApi\Tools;

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



    public static function HaveValues(array $data, array $checklist): array
    {
        $markedChecklist = [];
        foreach( $checklist as $checklistItem) {
            $markedChecklist[$checklistItem] = isset($data[$checklistItem]) ? true : false;
        }
        
        return $markedChecklist;
    }



    public static function HaveNullVaLues(array $data): bool
    {
        foreach ( $data as $item ) {
            if ( $item == null ) { return true; }
        }

        return false;
    }
}