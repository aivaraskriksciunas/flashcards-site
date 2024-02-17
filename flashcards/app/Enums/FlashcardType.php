<?php 

namespace App\Enums;

enum FlashcardType : string 
{
    case Text = 'text';
    case List = 'list';
    case Math = 'math';
}