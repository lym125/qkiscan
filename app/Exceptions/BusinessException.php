<?php

namespace App\Exceptions;

use Exception;

class BusinessException extends Exception
{
    /**
    * 用于响应的 HTTP 状态码
    *
    * @var int
    */
    public $status = 400;

    /**
     * 设置用于响应的 HTTP 状态码
     *
     * @param int $status
     * @return $this
     */
    public function status($status)
    {
        $this->status = $status;

        return $this;
    }
}
