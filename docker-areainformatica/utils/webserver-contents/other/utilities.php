<?php
// Copyright (C) 2015 Antonio Daniele Gialluisi

// This file is part of "Area informatica"

// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program. If not, see <https://www.gnu.org/licenses/>.





/*
 * File con alcune funzioni utili
 */


/*
 * Funzione che traduce in italiano
 * i mesi, dato il loro numero
 */
function toMonth($n) {
    $st = "";
    
    if (is_string($n)) {
        $n = intval($n);
    }

    switch($n) {
        case 1: $st = "Gennaio"; break;
        case 2: $st = "Febbraio"; break;
        case 3: $st = "Marzo"; break;
        case 4: $st = "Aprile"; break;
        case 5: $st = "Maggio"; break;
        case 6: $st = "Giugno"; break;
        case 7: $st = "Luglio"; break;
        case 8: $st = "Agosto"; break;
        case 9: $st = "Settembre"; break;
        case 10: $st = "Ottobre"; break;
        case 11: $st = "Novembre"; break;
        case 12: $st = "Dicembre"; break;
    }
    
    
    return $st;
}



/*
 * Funzione che mostra la data in maniera
 * più facile da leggere
 */
function showDate($date) {
    $year = substr($date, 0, 4);
    $month = substr($date, 5, 7);
    $day = substr($date, 8);

    //var_dump($date);

    return sprintf("%02d %s %04d", $day, toMonth($month), $year);
}


