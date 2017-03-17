<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserGroupsTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAddUserToGroup()
    {
        $group = factory(\App\UserGroup::class)->create();

        $user = factory(\App\User::class)->create();

        $group->users()->attach($user);

    }

    public function testGetUserPermissions()
    {
        $group = factory(\App\UserGroup::class)->create();

        $user = factory(\App\User::class)->create();

        $group->users()->attach($user);

        $old = $group->permissions;

        $new = $user->getGroupPermissions();

        $this->assertSame($old, $new);

    }


    public function testGetCombinedUserPermissions()
    {
        $group1 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => true], ]
        ]);

        $group2 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['second' => true]]
        ]);

        $user = factory(\App\User::class)->create();

        $group1->users()->attach($user);
        $group2->users()->attach($user);

        $old = ['test' => ['first' => true, 'second' => true]];

        $new = $user->getGroupPermissions();

        $this->assertSame($old, $new);

    }

    public function testGetCombinedUserPermissionsWithNegitive()
    {
        $group1 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => true], ]
        ]);

        $group2 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => false, 'second' => true]]
        ]);

        $group3 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => true], ]
        ]);

        $user = factory(\App\User::class)->create();

        $group1->users()->attach($user);
        $group2->users()->attach($user);
        $group3->users()->attach($user);

        $old = ['test' => ['first' => false, 'second' => true]];

        $new = $user->getGroupPermissions();

        $this->assertSame($old, $new);

    }

    public function testUserIsAllowed()
    {
        $group1 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => true], ]
        ]);

        $group2 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => false, 'second' => true]]
        ]);

        $group3 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => true], ]
        ]);

        $user = factory(\App\User::class)->create();

        $group1->users()->attach($user);
        $group2->users()->attach($user);
        $group3->users()->attach($user);

        $old = ['test' => ['first' => false, 'second' => true]];

        $this->assertTrue($user->allowed('test', 'second'));

    }

    public function testUserIsNotAllowed()
    {
        $group1 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => true], ]
        ]);

        $group2 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => false, 'second' => true]]
        ]);

        $group3 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => true], ]
        ]);

        $user = factory(\App\User::class)->create();

        $group1->users()->attach($user);
        $group2->users()->attach($user);
        $group3->users()->attach($user);

        $old = ['test' => ['first' => false, 'second' => true]];

        $this->assertFalse($user->allowed('test', 'first'));

    }

    public function testUserIsDisallowed()
    {
        $group1 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => true], ]
        ]);

        $group2 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => false, 'second' => true]]
        ]);

        $group3 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => true], ]
        ]);

        $user = factory(\App\User::class)->create();

        $group1->users()->attach($user);
        $group2->users()->attach($user);
        $group3->users()->attach($user);

        $old = ['test' => ['first' => false, 'second' => true]];

        $this->assertTrue($user->disallowed('test', 'first'));

    }

    public function testUserIsNotDisallowed()
    {
        $group1 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => true], ]
        ]);

        $group2 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => false, 'second' => true]]
        ]);

        $group3 = factory(\App\UserGroup::class)->create([
            'permissions' => ['test' => ['first' => true], ]
        ]);

        $user = factory(\App\User::class)->create();

        $group1->users()->attach($user);
        $group2->users()->attach($user);
        $group3->users()->attach($user);

        $this->assertFalse($user->disallowed('test', 'second'));

    }
}
