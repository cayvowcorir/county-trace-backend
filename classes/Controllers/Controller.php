<?php

namespace Controllers;


use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class Controller {

    protected $ci;
    protected $logger;
    protected $table;

    public function __construct($c,LoggerInterface $logger, Builder $table ) {
      $this->ci=$c;
      $this->logger = $logger;
      $this->table = $table;
    }
  }
