<?php

namespace Tests\Feature;

use App\DataStore\Importer;
use App\DataStore\Jobs\ProcessUpload;
use App\DataStore\Model\DataSet;
use App\DataStore\Model\Upload;
use App\DataStore\Model\Uploader;
use App\DataStore\Store;
use App\PropertyMgr\Model\Entity;
use App\PropertyMgr\Model\Property;
use App\User;
use App\UserGroup;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProcessUploadTest extends TestCase
{
    protected $store;
    protected $importer;
    protected $client;

    use DatabaseTransactions;

    protected  $connectionsToTransact = [
        'public',
        'tenant'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->client->logInAsClient();
        $this->store = new Store();
        $this->importer = new Importer();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStoreCSV()
    {

        $upload_map = \GuzzleHttp\json_decode('{"objectid":{"show":"on","name":"Objectid","key":"objectid","type":"integer"},"cama_id":{"show":"on","name":"Cama_id","key":"cama_id","type":"integer"},"prop_id":{"show":"on","name":"Prop_id","key":"prop_id","type":"string"},"map":{"show":"on","name":"Map","key":"map","type":"integer"},"lot":{"show":"on","name":"Lot","key":"lot","type":"integer"},"map_par_id":{"show":"on","name":"Map_par_id","key":"map_par_id","type":"string"},"pid":{"show":"on","name":"Pid","key":"pid","type":"string"},"town_id":{"show":"on","name":"Town_id","key":"town_id","type":"integer"},"bldg_val":{"show":"on","name":"Bldg_val","key":"bldg_val","type":"integer"},"land_val":{"show":"on","name":"Land_val","key":"land_val","type":"integer"},"total_val":{"show":"on","name":"Total_val","key":"total_val","type":"integer"},"fy":{"show":"on","name":"Fy","key":"fy","type":"integer"},"lot_size":{"show":"on","name":"Lot_size","key":"lot_size","type":"integer"},"lot_units":{"show":"on","name":"Lot_units","key":"lot_units","type":"string"},"ls_date":{"show":"on","name":"Ls_date","key":"ls_date","type":"integer"},"ls_price":{"show":"on","name":"Ls_price","key":"ls_price","type":"integer"},"use_code":{"show":"on","name":"Use_code","key":"use_code","type":"integer"},"site_addr":{"show":"on","name":"Site_addr","key":"site_addr","type":"string"},"addr_num":{"show":"on","name":"Addr_num","key":"addr_num","type":"integer"},"full_str":{"show":"on","name":"Full_str","key":"full_str","type":"string"},"city":{"show":"on","name":"City","key":"city","type":"string"},"owner1":{"show":"on","name":"Owner1","key":"owner1","type":"string"},"own_addr":{"show":"on","name":"Own_addr","key":"own_addr","type":"string"},"own_city":{"show":"on","name":"Own_city","key":"own_city","type":"string"},"own_state":{"show":"on","name":"Own_state","key":"own_state","type":"string"},"own_zip":{"show":"on","name":"Own_zip","key":"own_zip","type":"string"},"ls_book":{"show":"on","name":"Ls_book","key":"ls_book","type":"integer"},"ls_page":{"show":"on","name":"Ls_page","key":"ls_page","type":"integer"},"zoning":{"show":"on","name":"Zoning","key":"zoning","type":"string"},"year_built":{"show":"on","name":"Year_built","key":"year_built","type":"integer"},"bld_area":{"show":"on","name":"Bld_area","key":"bld_area","type":"integer"},"res_area":{"show":"on","name":"Res_area","key":"res_area","type":"integer"},"style":{"show":"on","name":"Style","key":"style","type":"string"},"stories":{"show":"on","name":"Stories","key":"stories","type":"float"},"num_rooms":{"show":"on","name":"Num_rooms","key":"num_rooms","type":"integer"},"bedroom":{"show":"on","name":"Bedroom","key":"bedroom","type":"integer"},"fullbath":{"show":"on","name":"Fullbath","key":"fullbath","type":"integer"},"grade":{"show":"on","name":"Grade","key":"grade","type":"string"},"condition":{"show":"on","name":"Condition","key":"condition","type":"string"},"loc_id":{"show":"on","name":"Loc_id","key":"loc_id","type":"string"},"other_val":{"show":"on","name":"Other_val","key":"other_val","type":"integer"},"owner2":{"show":"on","name":"Owner2","key":"owner2","type":"string"},"own_co":{"show":"on","name":"Own_co","key":"own_co","type":"string"},"lot_cut":{"show":"on","name":"Lot_cut","key":"lot_cut","type":"integer"},"halfbath":{"show":"on","name":"Halfbath","key":"halfbath","type":"integer"}}');

        $dataset = factory(DataSet::class)->create(['schema' => $upload_map]);

        $uploader = Uploader::create([
           'dataset_id' => $dataset->id,
            'name' => random_int(100,900000000) . ' Test',
            'type' => 'csv',
            'map' => $upload_map,
        ]);

        $upload = Upload::create([
            'uploader_id' => $uploader->id,
            'source' => '/test_data/assessors_test.csv',
            'size' => 0,
            'user_id' => 1,
            'file_type' => 'text/csv'
        ]);

        $this->importer->fromUpload($upload);

        $this->assertDatabaseHas($dataset->table_name, ['objectid' => 1]);
        $this->assertDatabaseHas($dataset->table_name, ['cama_id' => 4497]);
        $this->assertDatabaseHas($dataset->table_name, ['map' => 100]);
        $this->assertDatabaseHas($dataset->table_name, ['lot' => 13]);
        $this->assertDatabaseHas($dataset->table_name, ['lot_cut' => 1]);
        $this->assertDatabaseHas($dataset->table_name, ['map_par_id' => '100-14']);
        $this->assertDatabaseHas($dataset->table_name, ['pid' => '0100  0000  0014  A']);
        $this->assertDatabaseHas($dataset->table_name, ['town_id' => 149]);
        $this->assertDatabaseHas($dataset->table_name, ['bldg_val' => 169900]);
        $this->assertDatabaseHas($dataset->table_name, ['land_val' => 56900]);

        DB::table($dataset->table_name)->truncate();

    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCastingInt()
    {

        $upload_map = \GuzzleHttp\json_decode('{"objectid":{"show":"on","name":"Objectid","key":"objectid","type":"integer"},"cama_id":{"show":"on","name":"Cama_id","key":"cama_id","type":"integer"},"prop_id":{"show":"on","name":"Prop_id","key":"prop_id","type":"string"},"map":{"show":"on","name":"Map","key":"map","type":"integer"},"lot":{"show":"on","name":"Lot","key":"lot","type":"integer"},"map_par_id":{"show":"on","name":"Map_par_id","key":"map_par_id","type":"string"},"pid":{"show":"on","name":"Pid","key":"pid","type":"string"},"town_id":{"show":"on","name":"Town_id","key":"town_id","type":"integer"},"bldg_val":{"show":"on","name":"Bldg_val","key":"bldg_val","type":"integer"},"land_val":{"show":"on","name":"Land_val","key":"land_val","type":"integer"},"total_val":{"show":"on","name":"Total_val","key":"total_val","type":"integer"},"fy":{"show":"on","name":"Fy","key":"fy","type":"integer"},"lot_size":{"show":"on","name":"Lot_size","key":"lot_size","type":"integer"},"lot_units":{"show":"on","name":"Lot_units","key":"lot_units","type":"string"},"ls_date":{"show":"on","name":"Ls_date","key":"ls_date","type":"integer"},"ls_price":{"show":"on","name":"Ls_price","key":"ls_price","type":"integer"},"use_code":{"show":"on","name":"Use_code","key":"use_code","type":"integer"},"site_addr":{"show":"on","name":"Site_addr","key":"site_addr","type":"string"},"addr_num":{"show":"on","name":"Addr_num","key":"addr_num","type":"integer"},"full_str":{"show":"on","name":"Full_str","key":"full_str","type":"string"},"city":{"show":"on","name":"City","key":"city","type":"string"},"owner1":{"show":"on","name":"Owner1","key":"owner1","type":"string"},"own_addr":{"show":"on","name":"Own_addr","key":"own_addr","type":"string"},"own_city":{"show":"on","name":"Own_city","key":"own_city","type":"string"},"own_state":{"show":"on","name":"Own_state","key":"own_state","type":"string"},"own_zip":{"show":"on","name":"Own_zip","key":"own_zip","type":"string"},"ls_book":{"show":"on","name":"Ls_book","key":"ls_book","type":"integer"},"ls_page":{"show":"on","name":"Ls_page","key":"ls_page","type":"integer"},"zoning":{"show":"on","name":"Zoning","key":"zoning","type":"string"},"year_built":{"show":"on","name":"Year_built","key":"year_built","type":"integer"},"bld_area":{"show":"on","name":"Bld_area","key":"bld_area","type":"integer"},"res_area":{"show":"on","name":"Res_area","key":"res_area","type":"integer"},"style":{"show":"on","name":"Style","key":"style","type":"string"},"stories":{"show":"on","name":"Stories","key":"stories","type":"float"},"num_rooms":{"show":"on","name":"Num_rooms","key":"num_rooms","type":"integer"},"bedroom":{"show":"on","name":"Bedroom","key":"bedroom","type":"integer"},"fullbath":{"show":"on","name":"Fullbath","key":"fullbath","type":"integer"},"grade":{"show":"on","name":"Grade","key":"grade","type":"string"},"condition":{"show":"on","name":"Condition","key":"condition","type":"string"},"loc_id":{"show":"on","name":"Loc_id","key":"loc_id","type":"string"},"other_val":{"show":"on","name":"Other_val","key":"other_val","type":"integer"},"owner2":{"show":"on","name":"Owner2","key":"owner2","type":"string"},"own_co":{"show":"on","name":"Own_co","key":"own_co","type":"string"},"lot_cut":{"show":"on","name":"Lot_cut","key":"lot_cut","type":"integer"},"halfbath":{"show":"on","name":"Halfbath","key":"halfbath","type":"integer"}}');

        $dataset = factory(DataSet::class)->create(['schema' => $upload_map]);

        $uploader = Uploader::create([
            'dataset_id' => $dataset->id,
            'name' => random_int(100,900000000) . ' Test',
            'type' => 'csv',
            'map' => $upload_map,
        ]);

        $upload = Upload::create([
            'uploader_id' => $uploader->id,
            'source' => '/test_data/int_errors.csv',
            'size' => 0,
            'user_id' => 1,
            'file_type' => 'text/csv'
        ]);

        $this->importer->fromUpload($upload);

        $this->assertDatabaseHas($dataset->table_name, ['objectid' => 1]);
        $this->assertDatabaseHas($dataset->table_name, ['cama_id' => 4497]);
        $this->assertDatabaseHas($dataset->table_name, ['map' => 100]);
        $this->assertDatabaseHas($dataset->table_name, ['lot' => 13]);
        $this->assertDatabaseHas($dataset->table_name, ['lot_cut' => 1]);
        $this->assertDatabaseHas($dataset->table_name, ['map_par_id' => '100-14']);
        $this->assertDatabaseHas($dataset->table_name, ['pid' => '0100  0000  0014  A']);
        $this->assertDatabaseHas($dataset->table_name, ['town_id' => 149]);
        $this->assertDatabaseHas($dataset->table_name, ['bldg_val' => 169900]);
        $this->assertDatabaseHas($dataset->table_name, ['land_val' => 56900]);

        DB::table($dataset->table_name)->truncate();

    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStoreExcel()
    {

        $upload_map = \GuzzleHttp\json_decode('{"objectid":{"show":"on","name":"Objectid","key":"objectid","type":"integer"},"cama_id":{"show":"on","name":"Cama_id","key":"cama_id","type":"integer"},"prop_id":{"show":"on","name":"Prop_id","key":"prop_id","type":"string"},"map":{"show":"on","name":"Map","key":"map","type":"integer"},"lot":{"show":"on","name":"Lot","key":"lot","type":"integer"},"map_par_id":{"show":"on","name":"Map_par_id","key":"map_par_id","type":"string"},"pid":{"show":"on","name":"Pid","key":"pid","type":"string"},"town_id":{"show":"on","name":"Town_id","key":"town_id","type":"integer"},"bldg_val":{"show":"on","name":"Bldg_val","key":"bldg_val","type":"integer"},"land_val":{"show":"on","name":"Land_val","key":"land_val","type":"integer"},"total_val":{"show":"on","name":"Total_val","key":"total_val","type":"integer"},"fy":{"show":"on","name":"Fy","key":"fy","type":"integer"},"lot_size":{"show":"on","name":"Lot_size","key":"lot_size","type":"integer"},"lot_units":{"show":"on","name":"Lot_units","key":"lot_units","type":"string"},"ls_date":{"show":"on","name":"Ls_date","key":"ls_date","type":"integer"},"ls_price":{"show":"on","name":"Ls_price","key":"ls_price","type":"integer"},"use_code":{"show":"on","name":"Use_code","key":"use_code","type":"integer"},"site_addr":{"show":"on","name":"Site_addr","key":"site_addr","type":"string"},"addr_num":{"show":"on","name":"Addr_num","key":"addr_num","type":"integer"},"full_str":{"show":"on","name":"Full_str","key":"full_str","type":"string"},"city":{"show":"on","name":"City","key":"city","type":"string"},"owner1":{"show":"on","name":"Owner1","key":"owner1","type":"string"},"own_addr":{"show":"on","name":"Own_addr","key":"own_addr","type":"string"},"own_city":{"show":"on","name":"Own_city","key":"own_city","type":"string"},"own_state":{"show":"on","name":"Own_state","key":"own_state","type":"string"},"own_zip":{"show":"on","name":"Own_zip","key":"own_zip","type":"string"},"ls_book":{"show":"on","name":"Ls_book","key":"ls_book","type":"integer"},"ls_page":{"show":"on","name":"Ls_page","key":"ls_page","type":"integer"},"zoning":{"show":"on","name":"Zoning","key":"zoning","type":"string"},"year_built":{"show":"on","name":"Year_built","key":"year_built","type":"integer"},"bld_area":{"show":"on","name":"Bld_area","key":"bld_area","type":"integer"},"res_area":{"show":"on","name":"Res_area","key":"res_area","type":"integer"},"style":{"show":"on","name":"Style","key":"style","type":"string"},"stories":{"show":"on","name":"Stories","key":"stories","type":"float"},"num_rooms":{"show":"on","name":"Num_rooms","key":"num_rooms","type":"integer"},"bedroom":{"show":"on","name":"Bedroom","key":"bedroom","type":"integer"},"fullbath":{"show":"on","name":"Fullbath","key":"fullbath","type":"integer"},"grade":{"show":"on","name":"Grade","key":"grade","type":"string"},"condition":{"show":"on","name":"Condition","key":"condition","type":"string"},"loc_id":{"show":"on","name":"Loc_id","key":"loc_id","type":"string"},"other_val":{"show":"on","name":"Other_val","key":"other_val","type":"integer"},"owner2":{"show":"on","name":"Owner2","key":"owner2","type":"string"},"own_co":{"show":"on","name":"Own_co","key":"own_co","type":"string"},"lot_cut":{"show":"on","name":"Lot_cut","key":"lot_cut","type":"integer"},"halfbath":{"show":"on","name":"Halfbath","key":"halfbath","type":"integer"}}');

        $dataset = factory(DataSet::class)->create(['schema' => $upload_map]);

        $uploader = Uploader::create([
            'dataset_id' => $dataset->id,
            'name' => random_int(100,900000000) . ' Test',
            'type' => 'csv',
            'map' => $upload_map,
        ]);

        $upload = Upload::create([
            'uploader_id' => $uploader->id,
            'source' => '/test_data/assessors_test.xlsx',
            'size' => 0,
            'user_id' => 1,
            'file_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);

        $user = factory(User::class)->create();
        $user->addMembership($this->client->domain);
        $group = UserGroup::create(['name' => 'testGroup', 'permissions' => ['datasets' => ['upload' => true]]]);
        DB::table('user_user_group')->insert(['user_id' => $user->id, 'user_group_id' => $group->id]);
        $this->be($user);

        $this->importer->fromUpload($upload);

        $this->assertDatabaseHas($dataset->table_name, ['objectid' => 1]);
        $this->assertDatabaseHas($dataset->table_name, ['cama_id' => 4497]);
        $this->assertDatabaseHas($dataset->table_name, ['map' => 100]);
        $this->assertDatabaseHas($dataset->table_name, ['lot' => 13]);
        $this->assertDatabaseHas($dataset->table_name, ['lot_cut' => 1]);
        $this->assertDatabaseHas($dataset->table_name, ['map_par_id' => '100-14']);
        $this->assertDatabaseHas($dataset->table_name, ['pid' => '0100  0000  0014  A']);
        $this->assertDatabaseHas($dataset->table_name, ['town_id' => 149]);
        $this->assertDatabaseHas($dataset->table_name, ['bldg_val' => 169900]);
        $this->assertDatabaseHas($dataset->table_name, ['land_val' => 56900]);

        DB::table($dataset->table_name)->truncate();

        $this->get(route('upload.process', [$upload->id]))->assertSee('queued');

        $this->assertDatabaseHas($dataset->table_name, ['objectid' => 1]);
        $this->assertDatabaseHas($dataset->table_name, ['cama_id' => 4497]);
        $this->assertDatabaseHas($dataset->table_name, ['map' => 100]);
        $this->assertDatabaseHas($dataset->table_name, ['lot' => 13]);
        $this->assertDatabaseHas($dataset->table_name, ['lot_cut' => 1]);
        $this->assertDatabaseHas($dataset->table_name, ['map_par_id' => '100-14']);
        $this->assertDatabaseHas($dataset->table_name, ['pid' => '0100  0000  0014  A']);
        $this->assertDatabaseHas($dataset->table_name, ['town_id' => 149]);
        $this->assertDatabaseHas($dataset->table_name, ['bldg_val' => 169900]);
        $this->assertDatabaseHas($dataset->table_name, ['land_val' => 56900]);

        DB::table($dataset->table_name)->truncate();

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEntityPropertySyncs()
    {

        $upload_map = \GuzzleHttp\json_decode('{"objectid":{"show":"on","name":"Objectid","key":"objectid","type":"integer"},"cama_id":{"show":"on","name":"Cama_id","key":"cama_id","type":"integer"},"prop_id":{"show":"on","name":"Prop_id","key":"prop_id","type":"string"},"map":{"show":"on","name":"Map","key":"map","type":"integer"},"lot":{"show":"on","name":"Lot","key":"lot","type":"integer"},"map_par_id":{"show":"on","name":"Map_par_id","key":"map_par_id","type":"string"},"pid":{"show":"on","name":"Pid","key":"pid","type":"string"},"town_id":{"show":"on","name":"Town_id","key":"town_id","type":"integer"},"bldg_val":{"show":"on","name":"Bldg_val","key":"bldg_val","type":"integer"},"land_val":{"show":"on","name":"Land_val","key":"land_val","type":"integer"},"total_val":{"show":"on","name":"Total_val","key":"total_val","type":"integer"},"fy":{"show":"on","name":"Fy","key":"fy","type":"integer"},"lot_size":{"show":"on","name":"Lot_size","key":"lot_size","type":"integer"},"lot_units":{"show":"on","name":"Lot_units","key":"lot_units","type":"string"},"ls_date":{"show":"on","name":"Ls_date","key":"ls_date","type":"integer"},"ls_price":{"show":"on","name":"Ls_price","key":"ls_price","type":"integer"},"use_code":{"show":"on","name":"Use_code","key":"use_code","type":"integer"},"site_addr":{"show":"on","name":"Site_addr","key":"site_addr","type":"string"},"addr_num":{"show":"on","name":"Addr_num","key":"addr_num","type":"integer"},"full_str":{"show":"on","name":"Full_str","key":"full_str","type":"string"},"city":{"show":"on","name":"City","key":"city","type":"string"},"owner1":{"show":"on","name":"Owner1","key":"owner1","type":"string"},"own_addr":{"show":"on","name":"Own_addr","key":"own_addr","type":"string"},"own_city":{"show":"on","name":"Own_city","key":"own_city","type":"string"},"own_state":{"show":"on","name":"Own_state","key":"own_state","type":"string"},"own_zip":{"show":"on","name":"Own_zip","key":"own_zip","type":"string"},"ls_book":{"show":"on","name":"Ls_book","key":"ls_book","type":"integer"},"ls_page":{"show":"on","name":"Ls_page","key":"ls_page","type":"integer"},"zoning":{"show":"on","name":"Zoning","key":"zoning","type":"string"},"year_built":{"show":"on","name":"Year_built","key":"year_built","type":"integer"},"bld_area":{"show":"on","name":"Bld_area","key":"bld_area","type":"integer"},"res_area":{"show":"on","name":"Res_area","key":"res_area","type":"integer"},"style":{"show":"on","name":"Style","key":"style","type":"string"},"stories":{"show":"on","name":"Stories","key":"stories","type":"float"},"num_rooms":{"show":"on","name":"Num_rooms","key":"num_rooms","type":"integer"},"bedroom":{"show":"on","name":"Bedroom","key":"bedroom","type":"integer"},"fullbath":{"show":"on","name":"Fullbath","key":"fullbath","type":"integer"},"grade":{"show":"on","name":"Grade","key":"grade","type":"string"},"condition":{"show":"on","name":"Condition","key":"condition","type":"string"},"loc_id":{"show":"on","name":"Loc_id","key":"loc_id","type":"string"},"other_val":{"show":"on","name":"Other_val","key":"other_val","type":"integer"},"owner2":{"show":"on","name":"Owner2","key":"owner2","type":"string"},"own_co":{"show":"on","name":"Own_co","key":"own_co","type":"string"},"lot_cut":{"show":"on","name":"Lot_cut","key":"lot_cut","type":"integer"},"halfbath":{"show":"on","name":"Halfbath","key":"halfbath","type":"integer"}}');

        $dataset = factory(DataSet::class)->create(['schema' => $upload_map]);

        $uploader = Uploader::create([
            'dataset_id' => $dataset->id,
            'name' => random_int(100,900000000) . ' Test',
            'type' => 'csv',
            'map' => $upload_map,
            'syncs' => [
                [
                    'class' => 'address',
                    'type' => 'unparsed',
                    'full_address' => 'site_addr',
                    'city' => null,
                    'default_city' => 'Lawrence',
                    'state' => 'null',
                    'default_state' => 'MA',
                    'postcode' => null,
                    'default_postcode' => null
                ],
                [
                    'class' => 'entity',
                    'type' => 'unparsed',
                    'full_name' => 'owner1',
                    'format' => 'LastFirstM',
                    'role' => 'owner',
                    'address' => [
                        'type' => 'unparsed',
                        'full_address' => 'own_addr',
                        'city' => 'own_city',
                        'default_city' => null,
                        'state' => 'own_state',
                        'default_state' => null,
                        'postcode' => 'own_zip',
                        'default_postcode' => null
                    ]
                ]
            ]
        ]);

        $upload = Upload::create([
            'uploader_id' => $uploader->id,
            'source' => '/test_data/assessors_test.csv',
            'size' => 0,
            'user_id' => 1,
            'file_type' => 'text/csv',
        ]);


        DB::table("cn_entities")->truncate();
        DB::table("cn_raw_entities")->truncate();
        DB::table("cn_properties")->truncate();
        DB::table("cn_addresses")->truncate();
        DB::table("entity_entity_address")->truncate();
        DB::table("cn_entity_addresses")->truncate();
        DB::table("cn_entitables")->truncate();

        $this->importer->fromUpload($upload);

        $this->assertDatabaseHas('cn_properties', ['address' => '52 FARNHAM ST']);
        $this->assertDatabaseHas('cn_entities', ['first_name' => 'ASHLEY', 'last_name' => 'GUTIERREZ']);
        $this->assertDatabaseHas('cn_properties', ['address' => '51 FARNHAM ST']);
        $this->assertDatabaseHas('cn_entities', ['first_name' => 'JOHN', 'last_name' => 'DREW']);

        DB::table($dataset->table_name)->truncate();

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEntityPropertyTagSyncsWithExistingModels()
    {

        $upload_map = \GuzzleHttp\json_decode('{"objectid":{"show":"on","name":"Objectid","key":"objectid","type":"integer"},"cama_id":{"show":"on","name":"Cama_id","key":"cama_id","type":"integer"},"prop_id":{"show":"on","name":"Prop_id","key":"prop_id","type":"string"},"map":{"show":"on","name":"Map","key":"map","type":"integer"},"lot":{"show":"on","name":"Lot","key":"lot","type":"integer"},"map_par_id":{"show":"on","name":"Map_par_id","key":"map_par_id","type":"string"},"pid":{"show":"on","name":"Pid","key":"pid","type":"string"},"town_id":{"show":"on","name":"Town_id","key":"town_id","type":"integer"},"bldg_val":{"show":"on","name":"Bldg_val","key":"bldg_val","type":"integer"},"land_val":{"show":"on","name":"Land_val","key":"land_val","type":"integer"},"total_val":{"show":"on","name":"Total_val","key":"total_val","type":"integer"},"fy":{"show":"on","name":"Fy","key":"fy","type":"integer"},"lot_size":{"show":"on","name":"Lot_size","key":"lot_size","type":"integer"},"lot_units":{"show":"on","name":"Lot_units","key":"lot_units","type":"string"},"ls_date":{"show":"on","name":"Ls_date","key":"ls_date","type":"integer"},"ls_price":{"show":"on","name":"Ls_price","key":"ls_price","type":"integer"},"use_code":{"show":"on","name":"Use_code","key":"use_code","type":"integer"},"site_addr":{"show":"on","name":"Site_addr","key":"site_addr","type":"string"},"addr_num":{"show":"on","name":"Addr_num","key":"addr_num","type":"integer"},"full_str":{"show":"on","name":"Full_str","key":"full_str","type":"string"},"city":{"show":"on","name":"City","key":"city","type":"string"},"owner1":{"show":"on","name":"Owner1","key":"owner1","type":"string"},"own_addr":{"show":"on","name":"Own_addr","key":"own_addr","type":"string"},"own_city":{"show":"on","name":"Own_city","key":"own_city","type":"string"},"own_state":{"show":"on","name":"Own_state","key":"own_state","type":"string"},"own_zip":{"show":"on","name":"Own_zip","key":"own_zip","type":"string"},"ls_book":{"show":"on","name":"Ls_book","key":"ls_book","type":"integer"},"ls_page":{"show":"on","name":"Ls_page","key":"ls_page","type":"integer"},"zoning":{"show":"on","name":"Zoning","key":"zoning","type":"string"},"year_built":{"show":"on","name":"Year_built","key":"year_built","type":"integer"},"bld_area":{"show":"on","name":"Bld_area","key":"bld_area","type":"integer"},"res_area":{"show":"on","name":"Res_area","key":"res_area","type":"integer"},"style":{"show":"on","name":"Style","key":"style","type":"string"},"stories":{"show":"on","name":"Stories","key":"stories","type":"float"},"num_rooms":{"show":"on","name":"Num_rooms","key":"num_rooms","type":"integer"},"bedroom":{"show":"on","name":"Bedroom","key":"bedroom","type":"integer"},"fullbath":{"show":"on","name":"Fullbath","key":"fullbath","type":"integer"},"grade":{"show":"on","name":"Grade","key":"grade","type":"string"},"condition":{"show":"on","name":"Condition","key":"condition","type":"string"},"loc_id":{"show":"on","name":"Loc_id","key":"loc_id","type":"string"},"other_val":{"show":"on","name":"Other_val","key":"other_val","type":"integer"},"owner2":{"show":"on","name":"Owner2","key":"owner2","type":"string"},"own_co":{"show":"on","name":"Own_co","key":"own_co","type":"string"},"lot_cut":{"show":"on","name":"Lot_cut","key":"lot_cut","type":"integer"},"halfbath":{"show":"on","name":"Halfbath","key":"halfbath","type":"integer"}}');

        $dataset = factory(DataSet::class)->create(['schema' => $upload_map]);


        $uploader = Uploader::create([
            'dataset_id' => $dataset->id,
            'name' => random_int(100,900000000) . ' Test',
            'type' => 'csv',
            'map' => $upload_map,
            'syncs' => [
                [
                    'class' => 'address',
                    'type' => 'unparsed',
                    'full_address' => 'site_addr',
                    'city' => null,
                    'default_city' => 'Lawrence',
                    'state' => 'null',
                    'default_state' => 'MA',
                    'postcode' => null,
                    'default_postcode' => null
                ],
                [
                    'class' => 'entity',
                    'type' => 'unparsed',
                    'full_name' => 'owner1',
                    'format' => 'LastFirstM',
                    'role' => 'owner',
                    'address' => [
                        'type' => 'unparsed',
                        'full_address' => 'own_addr',
                        'city' => 'own_city',
                        'default_city' => null,
                        'state' => 'own_state',
                        'default_state' => null,
                        'postcode' => 'own_zip',
                        'default_postcode' => null
                    ]
                ],
                [
                    'class' => 'tag',
                    'dataPoint' => 'zoning',
                    'method' => 'value'
                ]
            ]
        ]);

        $upload = Upload::create([
            'uploader_id' => $uploader->id,
            'source' => '/test_data/assessors_test.csv',
            'size' => 0,
            'user_id' => 1,
            'file_type' => 'text/csv',
        ]);


        DB::table("cn_entities")->truncate();
        DB::table("cn_raw_entities")->truncate();
        DB::table("cn_properties")->truncate();
        DB::table("cn_addresses")->truncate();
        DB::table("entity_entity_address")->truncate();
        DB::table("cn_entity_addresses")->truncate();
        DB::table("cn_entitables")->truncate();

        $property = Property::create(['address' => '52 FARNHAM ST', 'city' => 'LAWRENCE', 'state' => 'MA', 'country' => 'USA', 'is_building' => true]);
        $entity1 = Entity::create(['first_name' => 'ASHLEY', 'last_name' => 'GUTIERREZ']);
        Entity::create(['first_name' => 'GERALD', 'last_name' => 'WHITE', 'middle_name' => 'F']);

        $this->importer->fromUpload($upload);

        $this->assertDatabaseHas('cn_entitables', ['entity_id' => $entity1->id, 'entitables_id' => $property->id]);
        $this->assertSame(DB::table('cn_entities')->where('first_name', 'GERALD')->where('last_name', 'WHITE')->count(), 1);

        DB::table($dataset->table_name)->truncate();

    }



    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPropertySyncsWithZeroAddress()
    {

        $upload_map = \GuzzleHttp\json_decode('{"objectid":{"show":"on","name":"Objectid","key":"objectid","type":"integer"},"cama_id":{"show":"on","name":"Cama_id","key":"cama_id","type":"integer"},"prop_id":{"show":"on","name":"Prop_id","key":"prop_id","type":"string"},"map":{"show":"on","name":"Map","key":"map","type":"integer"},"lot":{"show":"on","name":"Lot","key":"lot","type":"integer"},"map_par_id":{"show":"on","name":"Map_par_id","key":"map_par_id","type":"string"},"pid":{"show":"on","name":"Pid","key":"pid","type":"string"},"town_id":{"show":"on","name":"Town_id","key":"town_id","type":"integer"},"bldg_val":{"show":"on","name":"Bldg_val","key":"bldg_val","type":"integer"},"land_val":{"show":"on","name":"Land_val","key":"land_val","type":"integer"},"total_val":{"show":"on","name":"Total_val","key":"total_val","type":"integer"},"fy":{"show":"on","name":"Fy","key":"fy","type":"integer"},"lot_size":{"show":"on","name":"Lot_size","key":"lot_size","type":"integer"},"lot_units":{"show":"on","name":"Lot_units","key":"lot_units","type":"string"},"ls_date":{"show":"on","name":"Ls_date","key":"ls_date","type":"integer"},"ls_price":{"show":"on","name":"Ls_price","key":"ls_price","type":"integer"},"use_code":{"show":"on","name":"Use_code","key":"use_code","type":"integer"},"site_addr":{"show":"on","name":"Site_addr","key":"site_addr","type":"string"},"addr_num":{"show":"on","name":"Addr_num","key":"addr_num","type":"integer"},"full_str":{"show":"on","name":"Full_str","key":"full_str","type":"string"},"city":{"show":"on","name":"City","key":"city","type":"string"},"owner1":{"show":"on","name":"Owner1","key":"owner1","type":"string"},"own_addr":{"show":"on","name":"Own_addr","key":"own_addr","type":"string"},"own_city":{"show":"on","name":"Own_city","key":"own_city","type":"string"},"own_state":{"show":"on","name":"Own_state","key":"own_state","type":"string"},"own_zip":{"show":"on","name":"Own_zip","key":"own_zip","type":"string"},"ls_book":{"show":"on","name":"Ls_book","key":"ls_book","type":"integer"},"ls_page":{"show":"on","name":"Ls_page","key":"ls_page","type":"integer"},"zoning":{"show":"on","name":"Zoning","key":"zoning","type":"string"},"year_built":{"show":"on","name":"Year_built","key":"year_built","type":"integer"},"bld_area":{"show":"on","name":"Bld_area","key":"bld_area","type":"integer"},"res_area":{"show":"on","name":"Res_area","key":"res_area","type":"integer"},"style":{"show":"on","name":"Style","key":"style","type":"string"},"stories":{"show":"on","name":"Stories","key":"stories","type":"float"},"num_rooms":{"show":"on","name":"Num_rooms","key":"num_rooms","type":"integer"},"bedroom":{"show":"on","name":"Bedroom","key":"bedroom","type":"integer"},"fullbath":{"show":"on","name":"Fullbath","key":"fullbath","type":"integer"},"grade":{"show":"on","name":"Grade","key":"grade","type":"string"},"condition":{"show":"on","name":"Condition","key":"condition","type":"string"},"loc_id":{"show":"on","name":"Loc_id","key":"loc_id","type":"string"},"other_val":{"show":"on","name":"Other_val","key":"other_val","type":"integer"},"owner2":{"show":"on","name":"Owner2","key":"owner2","type":"string"},"own_co":{"show":"on","name":"Own_co","key":"own_co","type":"string"},"lot_cut":{"show":"on","name":"Lot_cut","key":"lot_cut","type":"integer"},"halfbath":{"show":"on","name":"Halfbath","key":"halfbath","type":"integer"}}');

        $dataset = factory(DataSet::class)->create(['schema' => $upload_map]);

        $uploader = Uploader::create([
            'dataset_id' => $dataset->id,
            'name' => random_int(100,900000000) . ' Test',
            'type' => 'csv',
            'map' => $upload_map,
            'syncs' => [
                [
                    'class' => 'address',
                    'type' => 'unparsed',
                    'full_address' => 'site_addr',
                    'city' => null,
                    'default_city' => 'Lawrence',
                    'state' => 'null',
                    'default_state' => 'MA',
                    'postcode' => null,
                    'default_postcode' => null
                ],
            ]
        ]);

        $upload = Upload::create([
            'uploader_id' => $uploader->id,
            'source' => '/test_data/zeroaddresses.csv',
            'size' => 0,
            'user_id' => 1,
            'file_type' => 'text/csv',
        ]);

        DB::table("cn_entities")->truncate();
        DB::table("cn_raw_entities")->truncate();
        DB::table("cn_properties")->truncate();
        DB::table("cn_addresses")->truncate();
        DB::table("entity_entity_address")->truncate();
        DB::table("cn_entity_addresses")->truncate();
        DB::table("cn_entitables")->truncate();

        $this->importer->fromUpload($upload);

        $_20130320 = DB::table($dataset->table_name)->where('ls_date', '20130320')->first();
        $_20131011 = DB::table($dataset->table_name)->where('ls_date', '20131011')->first();

        $this->assertTrue($_20130320->property_id == null);
        $this->assertTrue($_20131011->property_id == null);

        DB::table($dataset->table_name)->truncate();
    }


    /**
     * Very large test that takes about 2 minutes to process.
     * Keep hidden unless it needs changes.
     */
    public function testStoreLargeCSV()
    {
        DB::disconnect();

        DB::table("cn_entities")->truncate();
        DB::table("cn_raw_entities")->truncate();
        DB::table("cn_properties")->truncate();
        DB::table("cn_addresses")->truncate();
        DB::table("entity_entity_address")->truncate();
        DB::table("cn_entity_addresses")->truncate();
        DB::table("cn_entitables")->truncate();
        DB::table("cn_tags")->truncate();
        DB::table("cn_tagables")->truncate();

        $schema = \GuzzleHttp\json_decode('{"objectid":{"show":"on","name":"Objectid","key":"objectid","type":"integer"},"cama_id":{"show":"on","name":"Cama_id","key":"cama_id","type":"integer"},"prop_id":{"show":"on","name":"Prop_id","key":"prop_id","type":"string"},"map":{"show":"on","name":"Map","key":"map","type":"integer"},"lot":{"show":"on","name":"Lot","key":"lot","type":"integer"},"map_par_id":{"show":"on","name":"Map_par_id","key":"map_par_id","type":"string"},"pid":{"show":"on","name":"Pid","key":"pid","type":"string"},"town_id":{"show":"on","name":"Town_id","key":"town_id","type":"integer"},"bldg_val":{"show":"on","name":"Bldg_val","key":"bldg_val","type":"integer"},"land_val":{"show":"on","name":"Land_val","key":"land_val","type":"integer"},"total_val":{"show":"on","name":"Total_val","key":"total_val","type":"integer"},"fy":{"show":"on","name":"Fy","key":"fy","type":"integer"},"lot_size":{"show":"on","name":"Lot_size","key":"lot_size","type":"float"},"lot_units":{"show":"on","name":"Lot_units","key":"lot_units","type":"string"},"ls_date":{"show":"on","name":"Ls_date","key":"ls_date","type":"integer"},"ls_price":{"show":"on","name":"Ls_price","key":"ls_price","type":"integer"},"use_code":{"show":"on","name":"Use_code","key":"use_code","type":"integer"},"site_addr":{"show":"on","name":"Site_addr","key":"site_addr","type":"string"},"addr_num":{"show":"on","name":"Addr_num","key":"addr_num","type":"string"},"full_str":{"show":"on","name":"Full_str","key":"full_str","type":"string"},"city":{"show":"on","name":"City","key":"city","type":"string"},"owner1":{"show":"on","name":"Owner1","key":"owner1","type":"string"},"own_addr":{"show":"on","name":"Own_addr","key":"own_addr","type":"string"},"own_city":{"show":"on","name":"Own_city","key":"own_city","type":"string"},"own_state":{"show":"on","name":"Own_state","key":"own_state","type":"string"},"own_zip":{"show":"on","name":"Own_zip","key":"own_zip","type":"string"},"ls_book":{"show":"on","name":"Ls_book","key":"ls_book","type":"integer"},"ls_page":{"show":"on","name":"Ls_page","key":"ls_page","type":"integer"},"zoning":{"show":"on","name":"Zoning","key":"zoning","type":"string"},"year_built":{"show":"on","name":"Year_built","key":"year_built","type":"integer"},"bld_area":{"show":"on","name":"Bld_area","key":"bld_area","type":"integer"},"res_area":{"show":"on","name":"Res_area","key":"res_area","type":"integer"},"style":{"show":"on","name":"Style","key":"style","type":"string"},"stories":{"show":"on","name":"Stories","key":"stories","type":"float"},"num_rooms":{"show":"on","name":"Num_rooms","key":"num_rooms","type":"integer"},"bedroom":{"show":"on","name":"Bedroom","key":"bedroom","type":"integer"},"fullbath":{"show":"on","name":"Fullbath","key":"fullbath","type":"integer"},"grade":{"show":"on","name":"Grade","key":"grade","type":"string"},"condition":{"show":"on","name":"Condition","key":"condition","type":"string"},"loc_id":{"show":"on","name":"Loc_id","key":"loc_id","type":"string"},"other_val":{"show":"on","name":"Other_val","key":"other_val","type":"integer"},"owner2":{"show":"on","name":"Owner2","key":"owner2","type":"string"},"own_co":{"show":"on","name":"Own_co","key":"own_co","type":"string"},"lot_cut":{"show":"on","name":"Lot_cut","key":"lot_cut","type":"string"},"halfbath":{"show":"on","name":"Halfbath","key":"halfbath","type":"integer"},"own_addr2":{"show":"on","name":"Own_addr2","key":"own_addr2","type":"string"}}');
        $upload_map = \GuzzleHttp\json_decode('{"objectid":{"show":"on","name":"Objectid","key":"objectid","type":"integer"},"cama_id":{"show":"on","name":"Cama_id","key":"cama_id","type":"integer"},"prop_id":{"show":"on","name":"Prop_id","key":"prop_id","type":"string"},"map":{"show":"on","name":"Map","key":"map","type":"integer"},"lot":{"show":"on","name":"Lot","key":"lot","type":"integer"},"map_par_id":{"show":"on","name":"Map_par_id","key":"map_par_id","type":"string"},"pid":{"show":"on","name":"Pid","key":"pid","type":"string"},"town_id":{"show":"on","name":"Town_id","key":"town_id","type":"integer"},"bldg_val":{"show":"on","name":"Bldg_val","key":"bldg_val","type":"integer"},"land_val":{"show":"on","name":"Land_val","key":"land_val","type":"integer"},"total_val":{"show":"on","name":"Total_val","key":"total_val","type":"integer"},"fy":{"show":"on","name":"Fy","key":"fy","type":"integer"},"lot_size":{"show":"on","name":"Lot_size","key":"lot_size","type":"float"},"lot_units":{"show":"on","name":"Lot_units","key":"lot_units","type":"string"},"ls_date":{"show":"on","name":"Ls_date","key":"ls_date","type":"integer"},"ls_price":{"show":"on","name":"Ls_price","key":"ls_price","type":"integer"},"use_code":{"show":"on","name":"Use_code","key":"use_code","type":"integer"},"site_addr":{"show":"on","name":"Site_addr","key":"site_addr","type":"string"},"addr_num":{"show":"on","name":"Addr_num","key":"addr_num","type":"string"},"full_str":{"show":"on","name":"Full_str","key":"full_str","type":"string"},"city":{"show":"on","name":"City","key":"city","type":"string"},"owner1":{"show":"on","name":"Owner1","key":"owner1","type":"string"},"own_addr":{"show":"on","name":"Own_addr","key":"own_addr","type":"string"},"own_city":{"show":"on","name":"Own_city","key":"own_city","type":"string"},"own_state":{"show":"on","name":"Own_state","key":"own_state","type":"string"},"own_zip":{"show":"on","name":"Own_zip","key":"own_zip","type":"string"},"ls_book":{"show":"on","name":"Ls_book","key":"ls_book","type":"integer"},"ls_page":{"show":"on","name":"Ls_page","key":"ls_page","type":"integer"},"zoning":{"show":"on","name":"Zoning","key":"zoning","type":"string"},"year_built":{"show":"on","name":"Year_built","key":"year_built","type":"integer"},"bld_area":{"show":"on","name":"Bld_area","key":"bld_area","type":"integer"},"res_area":{"show":"on","name":"Res_area","key":"res_area","type":"integer"},"style":{"show":"on","name":"Style","key":"style","type":"string"},"stories":{"show":"on","name":"Stories","key":"stories","type":"float"},"num_rooms":{"show":"on","name":"Num_rooms","key":"num_rooms","type":"integer"},"bedroom":{"show":"on","name":"Bedroom","key":"bedroom","type":"integer"},"fullbath":{"show":"on","name":"Fullbath","key":"fullbath","type":"integer"},"grade":{"show":"on","name":"Grade","key":"grade","type":"string"},"condition":{"show":"on","name":"Condition","key":"condition","type":"string"},"loc_id":{"show":"on","name":"Loc_id","key":"loc_id","type":"string"},"other_val":{"show":"on","name":"Other_val","key":"other_val","type":"integer"},"owner2":{"show":"on","name":"Owner2","key":"owner2","type":"string"},"own_co":{"show":"on","name":"Own_co","key":"own_co","type":"string"},"lot_cut":{"show":"on","name":"Lot_cut","key":"lot_cut","type":"string"},"halfbath":{"show":"on","name":"Halfbath","key":"halfbath","type":"integer"},"own_addr2":{"show":"on","name":"Own_addr2","key":"own_addr2","type":"string"}}');
        $syncs = \GuzzleHttp\json_decode('[{"class":"address","type":"unparsed","full_address":"site_addr","city":null,"default_city":"Lawrence","state":null,"default_state":"MA","postcode":null,"default_postcode":null},{"class":"entity","type":"unparsed","address":{"type":"unparsed","full_address":"own_addr","city":"own_city","default_city":null,"state":"own_state","default_state":"AL","postcode":"own_zip","default_postcode":null},"full_name":"owner1","format":"LastFirstM","role":"owner"},{"class":"entity","type":"unparsed","address":{"type":"unparsed","full_address":"own_addr","city":"own_city","default_city":null,"state":"own_state","default_state":"AL","postcode":"own_zip","default_postcode":null},"full_name":"owner2","format":"LastFirstM","role":"owner"},{"class":"tag","dataPoint":"style","method":"value"},{"class":"tag","dataPoint":"zoning","method":"value"}]');
        $dataset = factory(DataSet::class)->create(['schema' => $schema]);

        $uploader = Uploader::create([
            'dataset_id' => $dataset->id,
            'name' => random_int(100,900000000) . ' Test',
            'type' => 'csv',
            'map' => $upload_map,
            'syncs' => $syncs
        ]);

        $upload = Upload::create([
            'uploader_id' => $uploader->id,
            'source' => '/test_data/large_file.csv',
            'size' => 0,
            'user_id' => 1,
            'file_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);

        $this->importer->fromUpload($upload);

        $this->assertDatabaseHas($dataset->table_name, ['objectid' => 1]);
        $this->assertDatabaseHas($dataset->table_name, ['cama_id' => 4497]);
        $this->assertDatabaseHas($dataset->table_name, ['map' => 100]);
        $this->assertDatabaseHas($dataset->table_name, ['lot' => 13]);
        $this->assertDatabaseHas($dataset->table_name, ['lot_cut' => 1]);
        $this->assertDatabaseHas($dataset->table_name, ['map_par_id' => '100-14']);
        $this->assertDatabaseHas($dataset->table_name, ['pid' => '0100  0000  0014  A']);
        $this->assertDatabaseHas($dataset->table_name, ['town_id' => 149]);
        $this->assertDatabaseHas($dataset->table_name, ['bldg_val' => 169900]);
        $this->assertDatabaseHas($dataset->table_name, ['land_val' => 56900]);

    }
}
