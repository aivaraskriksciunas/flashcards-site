<?php 

namespace App\Enums;

enum CourseVisibility : string 
{
    case Private = 'private';
    case OrgAdmin = 'orgadmin';
    case OrgManager = 'orgmanager';
    case OrgEveryone = 'orgall';
    case Public = 'public';
}