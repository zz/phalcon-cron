<?php

namespace Sid\Phalcon\Cron\Tests\Job;

use Codeception\TestCase\Test;
use Sid\Phalcon\Cron\Manager;
use Sid\Phalcon\Cron\Job\System as SystemJob;

class SystemTest extends Test
{
    public function testRunInForeground()
    {
        $cronJob = new SystemJob(
            "* * * * *",
            "echo 'hello world'"
        );

        $output = $cronJob->runInForeground();

        $this->assertEquals(
            "* * * * *",
            $cronJob->getExpression()
        );

        $this->assertEquals(
            "echo 'hello world'",
            $cronJob->getCommand()
        );

        $this->assertEquals(
            "hello world\n",
            $output
        );
    }



    public function testSystemCronJobWithOutputToDevNull()
    {
        $systemCronJob = new SystemJob(
            "* * * * *",
            "echo 'hello world'",
            "/dev/null"
        );

        $this->assertEquals(
            "",
            $systemCronJob->runInForeground()
        );
    }

    public function testSystemCronJobWithOutputToFile()
    {
        $tmpName = tempnam(
            sys_get_temp_dir(),
            "PHALCONCRON"
        );

        $systemCronJob = new SystemJob(
            "* * * * *",
            "echo 'hello world'",
            $tmpName
        );
        
        $systemCronJob->runInForeground();

        $this->assertEquals(
            "hello world\n",
            file_get_contents($tmpName)
        );
    }
    
    
    
    public function testSystemCronJobsInForeground()
    {
        $cron = new Manager();
        
        $systemCronJob1 = new SystemJob(
            "* * * * *",
            "echo 'hello world 1'"
        );

        $systemCronJob2 = new SystemJob(
            "* * * * *",
            "echo 'hello world 2'"
        );

        $systemCronJob3 = new SystemJob(
            "* * * * *",
            "echo 'hello world 3'"
        );

        $cron->add($systemCronJob1);
        $cron->add($systemCronJob2);
        $cron->add($systemCronJob3);

        $this->assertEquals(
            [
                "hello world 1\n",
                "hello world 2\n",
                "hello world 3\n",
            ],
            $cron->runInForeground()
        );
    }
}