<?php

namespace Easyconn\Phalcon\Cron\Job;

use Easyconn\Phalcon\Cron\Job;

class Callback extends Job
{
    /**
     * @var callable
     */
    protected $callback;
    
    

    public function __construct(string $expression, callable $callback)
    {
        parent::__construct($expression);
        
        $this->callback = $callback;
    }
    
    
    
    public function getCallback() : callable
    {
        return $this->callback;
    }
    
    
    
    /**
     * @return mixed
     */
    public function runInForeground()
    {
        $contents = call_user_func($this->callback);
        
        return $contents;
    }
}
