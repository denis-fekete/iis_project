<?php

namespace App\Enums;

enum OrderBy : string {
    case Newest = 'Newest';
    case Oldest = 'Oldest';
    case Name = 'Name';
    case Price = 'Price';
}
