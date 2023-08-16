<?php 

namespace App\Services\Importing;

abstract class SetImporter {

    public abstract function parse_set( string $set ): array;

}