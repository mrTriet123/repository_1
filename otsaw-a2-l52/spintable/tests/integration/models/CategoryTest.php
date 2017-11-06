<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Category;
use App\Item;
use App\CategoryTranslation;


class CategoryTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseTransactions;
    protected $category;
    protected $categoryRepo;
    protected $dishRepo;

    public function setUp()
    {
        parent::setUp();
        $this->categoryRepo = new App\Repositories\Category\DbCategoryRepository(new Category());
        $this->dishRepo = new App\Repositories\Dish\DbDishRepository(new Item());
        $this->category = $this->createNewCategory();
    }

//    /** @test */
//    public function it_fetches_no_of_dishes()
//    {
//        factory(Item::class, 5)->create([ 'category_id' => $this->category->id ]);
//        $this->assertEquals(5,$this->category->countItem());
//    }

    /** @test */
    public function it_can_add_dishes()
    {
        $dish = $this->createNewItem($this->category->category_id);

        $this->assertEquals(3,$dish->countSizes());

    }

//    /** @test */
//    public function it_can_remove_dishes()
//    {
//        $dishes = factory(Item::class,2)->create([ 'category_id' => $this->category->id ]);
//        $this->category->addItem($dishes);
//        $this->category->removeItem($dishes[0]);
//        $this->assertEquals(1,$this->category->countItem());
//    }

//    /** @test */
//    public function it_can_remove_dishes_at_once()
//    {
//        $dishes = factory(Item::class,2)->create([ 'category_id' => $this->category->id ]);
//        $this->category->addItem($dishes);
//        $this->category->restart();
//        $this->assertEquals(0,$this->category->countItem());
//    }
    
    /** @test */
    public function it_can_add_languages()
    {
        $this->assertEquals(3,count($this->categoryRepo->getCategory($this->category)));
    }

    private function createNewCategory()
    {
        $categoryEnData = [
            'title' => 'categoryTitle',
            'description' => 'Description',
            'language_code' => 'en'
        ];
        $categoryZhData = [
            'title' => 'categoryTitle',
            'description' => 'Description',
            'language_code' => 'zh'
        ];
        $categoryJpData = [
            'title' => 'categoryTitle',
            'description' => 'Description',
            'language_code' => 'jp'
        ];

        $category = $this->categoryRepo->create($categoryEnData);

        $this->categoryRepo->addTranslationWithCategory($category,$categoryZhData);

        $this->categoryRepo->addTranslationWithCategory($category,$categoryJpData);

        return $category;
    }

    private function createNewItem($category_id = null)
    {
        $dishEnData = [
            'category_id' => $category_id,
            'name' => 'CurryChicken',
            'description' => 'Description',
            'language_code' => 'en'
        ];
        $smallSize = [
            'size' => 's',
            'price' => '10.0'
        ];
        $mediumSize = [
            'size' => 'm',
            'price' => '20.0'
        ];
        $largeSize = [
            'size' => 'l',
            'price' => '30.0',
            'is_default' => 1
        ];

        $dish = $this->dishRepo->create($dishEnData);

        $dish->addSize($smallSize);
        $dish->addSize($mediumSize);
        $dish->addSize($largeSize);

        return $dish;
    }
}

