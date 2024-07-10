<?php 

namespace App\Enums;

enum CourseAccessLinkType : string 
{
    case Unrestricted = 'anonymous';
    case RequireName = 'require-name';
    case RequireAccount = 'require-account';
    case AssignedOnly = 'assigned-only';
}