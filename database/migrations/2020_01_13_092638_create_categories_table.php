<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Category;

class CreateCategoriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('categories', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('name', 50);
      $table->bigInteger('category_id')->unsigned()->nullable();
      $table->timestamps();
    });

    Schema::table('categories', function (Blueprint $table) {
      $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
     });

    //  $categories = ['Cleaning Service','Child Care','Elderly Care','Electrician','Food Truck','Caterer','Baker','Gardener','Landscaping Service','Pet grooming Service','Home Staging','Home Painting','Handyman','Printing','Direct Mailer','Personal Shopper','Event Planner','Errand Service','Food Delivery','Florist','Appliance Repair','Coaching ','Courier',
    //   'Learning','Decoration','Disposal','Plumbing','DJing','Furniture / Design/Assembly/ Removal','Academic Writing','Tutor','Dog Walker','Mobile Retail Boutique','Car Wash ',' Farming','Tour Guide','Beautify','Barber ','Security Service','Artist','Home decor','Home Chores ','Massage Therapy','Personal Trainer','Dance Classes','Music Lessons','Moving Service',
    //   'Tax Preparation','Hair Salon - ladies and gents','Hair Stylist','Health Care','Photography/Videography ','Research','Legal Practitioner','Local Job','Achitech ','Reseller','Retail Sales','Scripting','Shipping','Engineering','Training','Voice Artist'
    // ];
    //
    // foreach ($categories as $cat) {
    //   Category::create([
    //     'name' => $cat,
    //   ]);
    // }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('categories');
  }
}
