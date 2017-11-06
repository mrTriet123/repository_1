<?php
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Company;
use App\Outlet;

class CompanyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseTransactions;
    protected $company;

    public function setUp()
    {
        parent::setUp();
        $this->company = factory(Company::class)->create();
    }
    
    /** @test */
    public function it_fetches_no_of_outlets()
    {
        factory(Outlet::class, 3)->create([ 'company_id' => $this->company->id ]);
        $this->assertEquals(3,$this->company->countOutlet());
    }

    /** @test */
    public function it_can_add_outlets()
    {
        $outlet = factory(Outlet::class)->create([ 'company_id' => $this->company->id ]);
        $outlet2 = factory(Outlet::class)->create([ 'company_id' => $this->company->id ]);

        $this->company->addOutlet($outlet);
        $this->company->addOutlet($outlet2);

        $this->assertEquals(2,$this->company->countOutlet());
    }

    /** @test */
    public function it_can_add_outlets_at_once()
    {
        $outlets = factory(Outlet::class , 5)->create([ 'company_id' => $this->company->id ]);
        $this->company->addOutlet($outlets);
        $this->assertEquals(5,$this->company->countOutlet());
    }
    
    /** @test */
    public function it_can_remove_outlets()
    {
        $outlets = factory(Outlet::class,5)->create([ 'company_id' => $this->company->id ]);
        $this->company->addOutlet($outlets);
        $this->company->removeOutlet($outlets[0]);
        $this->assertEquals(4,$this->company->countOutlet());
    }

    /** @test */
    public function it_can_remove_outlets_at_once()
    {
        $outlets = factory(Outlet::class,5)->create([ 'company_id' => $this->company->id ]);
        $this->company->addOutlet($outlets);
        $this->company->restart();
        $this->assertEquals(0,$this->company->countOutlet());
    }
}
