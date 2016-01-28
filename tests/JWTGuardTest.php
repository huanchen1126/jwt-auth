<?php

/*
 * This file is part of jwt-auth
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tymon\JWTAuth\Test;

use Mockery;
use Tymon\JWTAuth\JWTGuard;
use Illuminate\Http\Request;

class JWTGuardTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->jwt = Mockery::mock('Tymon\JWTAuth\JWT');
        $this->provider = Mockery::mock('Illuminate\Contracts\Auth\UserProvider');
        $this->guard = new JWTGuard($this->jwt, $this->provider, Request::create('/foo', 'GET'));
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    public function it_should_get_the_authenticated_user()
    {
        $this->jwt->shouldReceive('getToken')->andReturn('foo.bar.baz');
        $this->jwt->shouldReceive('check')->andReturn(true);
        $this->jwt->shouldReceive('getPayload->get')->with('sub')->andReturn(1);

        $this->provider->shouldReceive('retrieveById')->with(1)->once()->andReturn((object) ['id' => 1]);
        $this->assertSame(1, $this->guard->user()->id);
    }

}