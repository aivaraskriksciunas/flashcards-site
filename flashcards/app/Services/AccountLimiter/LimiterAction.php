<?php

namespace App\Services\AccountLimiter;

enum LimiterAction: string 
{
    case View = 'canView';
    case Create = 'canCreate';
    case Undelete = 'canUndelete';
}