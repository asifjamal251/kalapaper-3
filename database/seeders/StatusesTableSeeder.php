<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
           array('id' => '1','name' => 'Pending','status_badge' => '<span class="badge bg-warning">Pending</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '2','name' => 'In Progress','status_badge' => '<span class="badge bg-info">In Progress</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '3','name' => 'Completed','status_badge' => '<span class="badge bg-success">Completed</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '4','name' => 'Failed','status_badge' => '<span class="badge bg-danger">Failed</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '5','name' => 'Canceled','status_badge' => '<span class="badge bg-secondary">Canceled</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '6','name' => 'Approved','status_badge' => '<span class="badge bg-success">Approved</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '7','name' => 'Rejected','status_badge' => '<span class="badge bg-danger">Rejected</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '8','name' => 'On Hold','status_badge' => '<span class="badge bg-warning">On Hold</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '9','name' => 'Processing','status_badge' => '<span class="badge bg-info">Processing</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '10','name' => 'Shipped','status_badge' => '<span class="badge bg-primary">Shipped</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '11','name' => 'Delivered','status_badge' => '<span class="badge bg-success">Delivered</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '12','name' => 'Returned','status_badge' => '<span class="badge bg-danger">Returned</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '13','name' => 'Refunded','status_badge' => '<span class="badge bg-success">Refunded</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '14','name' => 'Active','status_badge' => '<span class="badge bg-success">Active</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '15','name' => 'Inactive','status_badge' => '<span class="badge bg-warning">Inactive</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '16','name' => 'Draft','status_badge' => '<span class="badge bg-secondary">Draft</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '17','name' => 'Expired','status_badge' => '<span class="badge bg-dark">Expired</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '18','name' => 'Partially Paid','status_badge' => '<span class="badge bg-info">Partially Paid</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '19','name' => 'Paid','status_badge' => '<span class="badge bg-success">Paid</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '20','name' => 'On Way','status_badge' => '<span class="badge bg-warning">On Way</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '21','name' => 'Received','status_badge' => '<span class="badge bg-info">Received</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '22','name' => 'In-stock','status_badge' => '<span class="badge bg-success">In-Stock</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '23','name' => 'Sold Out','status_badge' => '<span class="badge bg-danger">Sold Out</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '24','name' => 'Allocated','status_badge' => '<span class="badge bg-danger">Allocated</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25'),
  array('id' => '25','name' => 'Split','status_badge' => '<span class="badge bg-danger">Split</span>','created_at' => '2025-12-25 05:41:25','updated_at' => '2025-12-25 05:41:25')
        ]);
    }
}
