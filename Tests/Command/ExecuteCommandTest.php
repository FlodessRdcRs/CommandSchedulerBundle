<?php

namespace Dukecity\CommandSchedulerBundle\Tests\Command;

use Dukecity\CommandSchedulerBundle\Fixtures\ORM\LoadScheduledCommandData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

/**
 * Class ExecuteCommandTest.
 */
class ExecuteCommandTest extends WebTestCase
{
    use FixturesTrait;

    /**
     * Test scheduler:execute without option.
     */
    public function testExecute()
    {
        // DataFixtures create 4 records
        $this->loadFixtures([LoadScheduledCommandData::class]);

        $output = $this->runCommand('scheduler:execute', [], true)->getDisplay();

        $this->assertStringStartsWith('Start : Execute all scheduled command', $output);
        $this->assertMatchesRegularExpression('/debug:container should be executed/', $output);
        $this->assertMatchesRegularExpression('/Execute : debug:container --help/', $output);
        $this->assertMatchesRegularExpression('/Immediately execution asked for : debug:router/', $output);
        $this->assertMatchesRegularExpression('/Execute : debug:router/', $output);

        $output = $this->runCommand('scheduler:execute')->getDisplay();
        $this->assertMatchesRegularExpression('/Nothing to do/', $output);
    }

    /**
     * Test scheduler:execute without option.
     */
    public function testExecuteWithNoOutput()
    {
        // DataFixtures create 4 records
        $this->loadFixtures([LoadScheduledCommandData::class]);

        $output = $this->runCommand(
            'scheduler:execute',
            [
                '--no-output' => true,
            ],
            true
        )->getDisplay();

        $this->assertEquals('', $output);

        $output = $this->runCommand('scheduler:execute')->getDisplay();
        $this->assertMatchesRegularExpression('/Nothing to do/', $output);
    }

    /**
     * Test scheduler:execute with --dump option.
     */
    public function testExecuteWithDump()
    {
        // DataFixtures create 4 records
        $this->loadFixtures([LoadScheduledCommandData::class]);

        $output = $this->runCommand(
            'scheduler:execute',
            [
                '--dump' => true,
            ],
            true
        )->getDisplay();

        $this->assertStringStartsWith('Start : Dump all scheduled command', $output);
        $this->assertMatchesRegularExpression('/Command debug:container should be executed/', $output);
        $this->assertMatchesRegularExpression('/Immediately execution asked for : debug:router/', $output);
    }
}
