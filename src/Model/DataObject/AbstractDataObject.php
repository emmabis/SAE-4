<?php
namespace App\SAE3\model\DataObject;

abstract class AbstractDataObject{
    public abstract function formatTableau(): array;
}