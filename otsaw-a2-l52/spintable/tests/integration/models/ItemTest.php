<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Item;
use App\Category;

class ItemTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
//    use DatabaseTransactions;
    /** @test */
    public function an_item_has_a_name()
    {
        // Given
        $category = factory(Category::class)->create();

        // When
        $item = factory(Item::class)->create( ['category_id' => $category->id, 'name' => 'CurryChicken'] );

        // Then
        $this->assertEquals('CurryChicken', $item->name);
    }

    /** @test */
    public function it_must_have_at_least_one_language_to_create()
    {
        $itemTranslation = factory(App\ItemTranslation::class)->create(['language_code'=>'en']);

        factory(App\ItemTranslation::class)->create(['language_code'=>'zh','item_id'=>$itemTranslation->item_id]);
        factory(App\ItemTranslation::class)->create(['language_code'=>'jp','item_id'=>$itemTranslation->item_id]);

        $this->assertEquals(3,count(App\ItemTranslation::all()));

    }

}
