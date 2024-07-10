<?php 

namespace App\Enums;

enum UserType : string 
{
    case ADMIN = 'admin';
    case STUDENT = 'student';
    case ORG_ADMIN = 'orgadmin';
    case ORG_MANAGER = 'orgmanager';
    case ORG_MEMBER = 'orgmember';
    case ANONYMOUS = 'anonymous';
    case UNDEFINED = 'undefined';
}