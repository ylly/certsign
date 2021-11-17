<?php

use PHPUnit\Framework\TestCase;
use YllyCertSign\Request\Request;

class RequestTest extends TestCase
{
    /**
     * @dataProvider setHolderData
     */
    public function testSetHolder($expectedFirstname, $expectedLastname, $firstname, $lastname)
    {
        if (null === $expectedFirstname) {
            $this->expectException(InvalidArgumentException::class);
        }

        $request = Request::create()->setHolder($firstname, $lastname, 'email', 'mobile');

        if (null !== $expectedFirstname) {
            self::assertEquals($expectedFirstname, $request->holder->firstname);
            self::assertEquals($expectedLastname, $request->holder->lastname);
        }
    }

    public function setHolderData()
    {
        return [
            ['firstname', 'lastname', 'firstname', 'lastname'],
            ['firstname', 'lastname',  '  firstname  ', '  lastname  '],
            ['firstname', 'firstname',  'firstname', null],
            ['firstname', 'firstname',  'firstname', ''],
            ['firstname', 'firstname',  'firstname', '    '],
            ['lastname', 'lastname',  null, 'lastname'],
            ['lastname', 'lastname',  '', 'lastname'],
            ['lastname', 'lastname',  '    ', 'lastname'],
            [null, null, null, null],
            [null, null, '', ''],
            [null, null, '    ', '    '],
        ];
    }
}
