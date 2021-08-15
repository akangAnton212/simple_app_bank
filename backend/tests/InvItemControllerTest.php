<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class InvItemControllerTest extends TestCase
{
    public function test_store_data()
    {
        //TODO: code inside here --Created by Kiddy
    }

    /**
     * @test
     */
    public function it_stores_data()
    {
        //Membuat objek user yang otomatis menambahkannya ke database.
        $user = factory(MUser::class)->create();

        //Membuat objek category yang otomatis menambahkannya ke database.
        $invItem = factory(MInvItem::class)->create();

        //Acting as berfungsi sebagai autentikasi, jika kita menghilangkannya maka akan error.
        $response = $this->actingAs($user)
        //Hit post ke method add, fungsinya ya akan lari ke fungsi add.
        ->post(route('inv_item.add'), [
            //isi parameter sesuai kebutuhan request
            'item_code'     => $this->faker->words(3, true),
            'name'          => $this->faker->words(5, true),
            'uid_inv_unit'  => "35732ff6-f98d-435a-8b84-82d92b249f2a", //uid
            'alias_code'    => $this->faker->words(5, true),
            'alias_name'    => $this->faker->words(5, true),
            'uid'           => ""    
        ]);

        //Tuntutan status 302, yang berarti redirect status code.
        $response->assertStatus(302);
    }
}
