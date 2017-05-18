<?php

class TestSpaceAroundControlStructures extends PHPUnit_Framework_TestCase
{

    /**
     * @var string Code to test
     */
    private $code = <<<CODE
<?php
foo();
if ('a' === 'a') {}
foreach (\$arr as \$val) {}
for (\$i = 0; \$i < 5; \$i++) {
	echo \$i;

	/**
	 * Hey there
	 */
	if ('a' === 'a') {

	}
	// Hello
	if ('a' === 'a') {

	}
}
?>
CODE;

    public function testEnabled()
    {
        $output = executeCommand(
            array(
                '--passes' => 'SpaceAroundControlStructures',
            ),
            $this->code
        );

        $this->assertContains(
            <<<CODE
foo();

if ('a' === 'a') {}

foreach (\$arr as \$val) {}

for (\$i = 0; \$i < 5; \$i++) {
	echo \$i;

	/**
	 * Hey there
	 */

	if ('a' === 'a') {

	}

	// Hello

	if ('a' === 'a') {

	}

}
CODE
            ,$output
        );
    }

    public function testDisabled()
    {
        $output = executeCommand(
            array(
                '--exclude' => 'SpaceAroundControlStructures',
            ),
            $this->code
        );

        $this->assertContains(
            <<<CODE
foo();
if ('a' === 'a') {}
foreach (\$arr as \$val) {}
for (\$i = 0; \$i < 5; \$i++) {
	echo \$i;

	/**
	 * Hey there
	 */
	if ('a' === 'a') {

	}
	// Hello
	if ('a' === 'a') {

	}
}
CODE
            ,$output
        );
    }

}
