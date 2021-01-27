<?php

namespace ThaoHR\Tests\Unit\Listeners;

use ThaoHR\Events\Role\Created;
use ThaoHR\Events\Role\Deleted;
use ThaoHR\Events\Role\PermissionsUpdated;
use ThaoHR\Events\Role\Updated;

class RoleEventsSubscriberTest extends ListenerTestCase
{
    protected $role;

    protected function setUp(): void
    {
        parent::setUp();
        $this->role = factory(\ThaoHR\Role::class)->create();
    }

    /** @test */
    public function onCreate()
    {
        event(new Created($this->role));
        $this->assertMessageLogged("Created new role called {$this->role->display_name}.");
    }

    /** @test */
    public function onUpdate()
    {
        event(new Updated($this->role));
        $this->assertMessageLogged("Updated role with name {$this->role->display_name}.");
    }

    /** @test */
    public function onDelete()
    {
        event(new Deleted($this->role));
        $this->assertMessageLogged("Deleted role named {$this->role->display_name}.");
    }

    /** @test */
    public function onPermissionsUpdate()
    {
        event(new PermissionsUpdated());
        $this->assertMessageLogged("Updated role permissions.");
    }
}
