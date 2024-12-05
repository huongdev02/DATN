<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AutoDeleteInactiveProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:auto-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Lấy danh sách sản phẩm thỏa mãn điều kiện
        $products = Product::where('is_active', 0)
            ->where('updated_at', '<=', now()->subDays(7))
            ->get();

        foreach ($products as $product) {
            // Xóa avatar
            Storage::disk('public')->delete($product->avatar);

            // Xóa gallery
            foreach ($product->galleries as $gallery) {
                Storage::disk('public')->delete($gallery->image_path);
                $gallery->delete();
            }

            // Xóa sản phẩm
            $product->delete();
        }

        $this->info('Inactive products deleted successfully.');
    }
}
