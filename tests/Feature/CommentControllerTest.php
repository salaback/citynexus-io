<?php

namespace Tests\Feature;

use App\Client;
use App\PropertyMgr\Model\Comment;
use App\User;
use App\UserGroup;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentControllerTest extends TestCase
{

    protected $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = Client::where('domain', 'testclient.citynexus-io.app:8000')->first();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStoreNewComment()
    {
        $this->client->loginAsClient();
        $user = factory(User::class)->create();
        $user->addMembership($this->client->domain);
        $group = UserGroup::create(['name' => 'testGroup', 'permissions' => ['comment' => ['post' => true]]]);
        DB::table('user_user_group')->insert(['user_id' => $user->id, 'user_group_id' => $group->id]);
        $this->be($user);

        $this->post(route('comments.store'), [
                'title' => "Test Comment",
                'comment' => "Test Comment",
                'posted_by' => '1',
                'cn_commentable_id' => "1",
                'cn_commentable_type' => "App\\PropertyMgr\\Model\\Comment",

        ])->assertSee('<div class="comment-body">Test Comment</div>');
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDeleteCommentAsPoster()
    {
        $this->client->loginAsClient();
        $user = factory(User::class)->create();
        $user->addMembership($this->client->domain);
        $group = UserGroup::create(['name' => 'testGroup', 'permissions' => ['comment' => ['post' => true]]]);
        DB::table('user_user_group')->insert(['user_id' => $user->id, 'user_group_id' => $group->id]);
        $this->be($user);

        $comment = Comment::create([
            'title' => "Test Comment",
            'comment' => "Test Comment",
            'posted_by' => $user->id,
            'cn_commentable_id' => "1",
            'cn_commentable_type' => "App\\PropertyMgr\\Model\\Comment",

        ]);

        $this->post(route('comments.destroy', [$comment->id]), [
            '_method' => 'delete',
        ])->assertSee('deleted');

        $this->assertDatabaseHas('cn_comments', ['id' => $comment->id]);
        $this->assertDatabaseMissing('cn_comments', ['id' => $comment->id, 'deleted_at' => null]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDeleteCommentAsAdmin()
    {
        $this->client->loginAsClient();
        $user = factory(User::class)->create();
        $user->addMembership($this->client->domain);
        $group = UserGroup::create(['name' => 'testGroup', 'permissions' => ['admin' => ['delete-comments' => true]]]);
        DB::table('user_user_group')->insert(['user_id' => $user->id, 'user_group_id' => $group->id]);
        $this->be($user);

        $comment = Comment::create([
            'title' => "Test Comment",
            'comment' => "Test Comment",
            'posted_by' => 1,
            'cn_commentable_id' => "1",
            'cn_commentable_type' => "App\\PropertyMgr\\Model\\Comment",

        ]);

        $this->post(route('comments.destroy', [$comment->id]), [
            '_method' => 'delete',
        ])->assertSee('deleted');

        $this->assertDatabaseHas('cn_comments', ['id' => $comment->id]);
        $this->assertDatabaseMissing('cn_comments', ['id' => $comment->id, 'deleted_at' => null]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDeleteCommentUnauthorized()
    {
        $this->client->loginAsClient();
        $user = factory(User::class)->create();
        $user->addMembership($this->client->domain);
        $group = UserGroup::create(['name' => 'testGroup', 'permissions' => []]);
        DB::table('user_user_group')->insert(['user_id' => $user->id, 'user_group_id' => $group->id]);
        $this->be($user);

        $comment = Comment::create([
            'title' => "Test Comment",
            'comment' => "Test Comment",
            'posted_by' => 1,
            'cn_commentable_id' => "1",
            'cn_commentable_type' => "App\\PropertyMgr\\Model\\Comment",

        ]);

        $this->post(route('comments.destroy', [$comment->id]), [
            '_method' => 'delete',
        ])->assertSee('Not Authorized');

        $this->assertDatabaseHas('cn_comments', ['id' => $comment->id, 'deleted_at' => null]);
    }
}
