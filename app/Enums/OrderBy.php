<?php

namespace App\Enums;

enum OrderBy : string {
    case Name = 'Name';
    case Price = 'Price';
    case Newest = 'Newest';
    case Oldest = 'Oldest';
}
