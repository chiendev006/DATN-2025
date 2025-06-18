<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product_comment;
use App\Models\Sanpham;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ProductCommentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Get all products and users
        $products = Sanpham::all();
        $users = User::where('role', 'user')->get(); // Only get regular users

        $comments = [];
        $startDate = Carbon::now()->subMonths(3);

        // Generate 3-5 comments for each product
        foreach ($products as $product) {
            $numberOfComments = $faker->numberBetween(3, 5);

            for ($i = 0; $i < $numberOfComments; $i++) {
                $commentDate = $startDate->copy()->addDays(rand(0, 90));
                $rating = $faker->numberBetween(3, 5); // Most ratings are positive

                // Generate realistic comments based on product category
                $comment = $this->generateComment($product->id_danhmuc, $rating, $faker);

                $comments[] = [
                    'product_id' => $product->id,
                    'user_id' => $users->random()->id,
                    'comment' => $comment,
                    'rating' => $rating,
                    'created_at' => $commentDate,
                    'updated_at' => $commentDate,
                ];
            }
        }

        // Insert all comments
        Product_comment::insert($comments);
    }

    private function generateComment($categoryId, $rating, $faker)
    {
        $positiveComments = [
            1 => [ // Cà phê
                'Cà phê đậm đà, thơm ngon đúng vị!',
                'Hương vị cà phê rất đặc trưng, rất thích.',
                'Cà phê ngon, đậm đà và thơm lừng.',
                'Vị cà phê rất đậm đà, đúng gu của tôi.',
                'Cà phê thơm ngon, đậm đà đúng chuẩn.'
            ],
            2 => [ // Trà sữa
                'Trà sữa ngon, topping nhiều và tươi.',
                'Vị trà sữa rất thơm, không quá ngọt.',
                'Trà sữa ngon, topping dẻo dai.',
                'Hương vị trà sữa rất đặc trưng.',
                'Trà sữa thơm ngon, đúng vị.'
            ],
            3 => [ // Nước ép
                'Nước ép tươi ngon, nguyên chất.',
                'Vị nước ép rất tự nhiên, không quá ngọt.',
                'Nước ép tươi mát, giải khát tốt.',
                'Hương vị nước ép rất thơm ngon.',
                'Nước ép tươi ngon, bổ dưỡng.'
            ],
            4 => [ // Sinh tố
                'Sinh tố ngon, mịn và bổ dưỡng.',
                'Vị sinh tố rất thơm ngon, không quá ngọt.',
                'Sinh tố tươi mát, giải khát tốt.',
                'Hương vị sinh tố rất đặc trưng.',
                'Sinh tố ngon, bổ dưỡng.'
            ]
        ];

        $neutralComments = [
            1 => [ // Cà phê
                'Cà phê ổn, có thể cải thiện thêm.',
                'Vị cà phê khá ổn.',
                'Cà phê tạm được.',
                'Cà phê bình thường.',
                'Cà phê đúng vị.'
            ],
            2 => [ // Trà sữa
                'Trà sữa ổn, topping vừa phải.',
                'Vị trà sữa khá ổn.',
                'Trà sữa tạm được.',
                'Trà sữa bình thường.',
                'Trà sữa đúng vị.'
            ],
            3 => [ // Nước ép
                'Nước ép ổn, có thể tươi hơn.',
                'Vị nước ép khá ổn.',
                'Nước ép tạm được.',
                'Nước ép bình thường.',
                'Nước ép đúng vị.'
            ],
            4 => [ // Sinh tố
                'Sinh tố ổn, có thể mịn hơn.',
                'Vị sinh tố khá ổn.',
                'Sinh tố tạm được.',
                'Sinh tố bình thường.',
                'Sinh tố đúng vị.'
            ]
        ];

        // Select comment based on rating
        if ($rating >= 4) {
            $commentPool = $positiveComments[$categoryId] ?? $positiveComments[1];
        } else {
            $commentPool = $neutralComments[$categoryId] ?? $neutralComments[1];
        }

        return $faker->randomElement($commentPool);
    }
}
