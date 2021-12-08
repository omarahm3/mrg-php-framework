<?php

namespace app\core;

interface ModelInterface
{
  public function loadData(array $data);

  public function validate(): bool;
}