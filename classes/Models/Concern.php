<?php
namespace Models;
use \Illuminate\Database\Eloquent\Model;

class Concern extends Model
{
  protected $table = 'publicConcern';
  protected $primaryKey = 'publicConcernId';
  // protected $connection=$container['settings']['db'];

}
