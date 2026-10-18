<?php

use App\Libraries\CIAuth;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\SocialMedia;
use App\Models\SubCategory;
use App\Models\User;
use Config\Services;
use CodeIgniter\I18n\Time;

if (!function_exists('get_user')) {
    function get_user()
    {
        if (CIAuth::check()) {
            $user = new User();
            return $user->asObject()->where('id', CIAuth::id())->first();
        } else {
            return null;
        }
    }
}

if (!function_exists('get_settings')) {
    function get_settings()
    {
        $settings = new Setting();
        $settings_data = $settings->asObject()->first();
        if (!$settings_data) {
            $data = array(
                'blog_title' => 'CI4Blog',
                'blog_email' => 'info@ci4blog.test',
                'blog_phone' => null,
                'blog_meta_keywords' => null,
                'blog_meta_description' => null,
                'blog_logo' => null,
                'blog_favicon' => null
            );

            $settings->save($data);
            $new_settings_data = $settings->asObject()->first();
            return $new_settings_data;
        } else {
            return $settings_data;
        }
    }
}

if (!function_exists('get_social_media')) {
    function get_social_media()
    {
        $social_media = new SocialMedia();
        $social_media_data = $social_media->asObject()->first();

        if (!$social_media_data) {
            $data = array(
                'facebook_url' => null,
                'twitter_url' => null,
                'instagram_url' => null,
                'youtube_url' => null,
                'tiktok_url' => null,
                'linkedin_url' => null
            );
            $social_media->save($data);
            $new_social_media_data = $social_media->asObject()->first();
            return $new_social_media_data;
        } else {
            return $social_media_data;
        }
    }
}

if (!function_exists('current_route_name')) {
    function current_route_name()
    {
        $router = Services::router();
        $route_name = $router->getMatchedRouteOptions()['as'];
        return $route_name;
    }
}


// 
// HELPER FRONTEND 
// 

if (!function_exists('get_parent_categories')) {
    function get_parent_categories()
    {
        $category = new Category();
        $categories_data = $category->asObject()->orderBy('ordering', 'asc')->findAll();
        return $categories_data;
    }
}

if (!function_exists('get_parent_subcategories')) {
    function get_parent_subcategories($category_id)
    {
        $subCategory = new SubCategory();
        $subCategory_data = $subCategory->asObject()->where('parent_cat', $category_id)->orderBy('ordering', 'asc')->findAll();
        return $subCategory_data;
    }
}

if (!function_exists('get_dependent_subcategories')) {
    function get_dependent_subcategories()
    {
        $subCategory = new SubCategory();
        $subCategory_data = $subCategory->asObject()->where('parent_cat', 0)->findAll();
        return $subCategory_data;
    }
}

if (!function_exists('get_main_post')) {
    function get_main_post()
    {
        $post = new Post();
        // Ambil postingan terbaru berdasarkan created_at DESC
        $post_newest = $post->asObject()->orderBy('created_at', 'DESC')->where('visibility', 1)->first();
        return $post_newest;
    }
}

if (!function_exists('wrapper_limit_text')) {
    function wrapper_limit_text($content = '', $limit = 20)
    {
        $limitText = word_limiter($content, $limit);
        return $limitText;
    }
}

if (!function_exists("convert_time_dmy")) {
    function convert_time_dmy($time)
    {

        // Chuỗi thời gian mẫu

        // 
        $myTime = new Time($time);

        // Định dạng thời gian
        $formattedTime = $myTime->toLocalizedString('dd MMM yyyy');
        return $formattedTime;
    }
}

if (!function_exists('time_to_read')) {
    function time_to_read($content)
    {
        $text_per_minutes = 200;
        $time_read = ceil(strlen($content) / $text_per_minutes);
        return $time_read;
    }
}

if (!function_exists('get_6_posts')) {
    function get_6_posts()
    {
        $post = new Post();
        // Ambil 6 postingan terbaru setelah main post
        $main_post = get_main_post();
        $main_id = $main_post ? $main_post->id : 0;
        $post_data = $post->asObject()->where('id !=', $main_id)->where('visibility', 1)->orderBy('created_at', 'DESC')->limit(6)->findAll();
        return $post_data;
    }
}


if (!function_exists('get_random_posts')) {
    function get_random_posts($max = 4)
    {
        $post = new Post();
        $post_data = $post->asObject()->limit($max)->orderBy("rand()")->findAll();
        return $post_data;
    }
}


if (!function_exists('sidebar_subcategories')) {
    function sidebar_subcategories()
    {
        $subcategories = new SubCategory();
        $subcategories_data = $subcategories->asObject()->findAll();
        return $subcategories_data;
    }
}

if (!function_exists('total_posts_subcategory')) {
    function total_posts_subcategory($subcategory_id)
    {
        $post = new Post();
        $total_post = $post->where('category_id', $subcategory_id)->findAll();
        return count($total_post);
    }
}

if (!function_exists('get_latest_posts')) {
    function get_latest_posts($except = null)
    {
        $post = new Post();
        $query = $post->asObject()->where('visibility', 1);
        if ($except) {
            $query = $query->where('id !=', $except);
        }
        $post_data = $query->orderBy('created_at', 'DESC')->limit(4)->findAll();
        return $post_data;
    }
}


if (!function_exists('get_all_tags')) {
    function get_all_tags()
    {
        $arrTag = [];
        $post = new Post();
        $posts = $post->asObject()->where('visibility', 1)->findAll();
        foreach ($posts as $post) {
            if (!empty($post->tags)) {
                array_push($arrTag, $post->tags);
            }
        }
        $strTags = implode(",", $arrTag);
        $arrTag = explode(",", $strTags);
        $hash = [];
        $newArr = [];
        foreach ($arrTag as $key => $val) {
            $val = trim($val);
            if ($val !== '' && !isset($hash[$val])) {
                $hash[$val] = $key;
                $newArr[] = $val;
            }
        }
        return $newArr;
    }
}

if (!function_exists('get_tags_single_post')) {
    function get_tags_single_post($post_id)
    {
        $post = new Post();
        $post_data = $post->asObject()->find($post_id);
        $list_tags = explode(",", trim($post_data->tags));
        return $list_tags;
    }
}


if (!function_exists('get_related_post_by_post_id')) {
    function get_related_post_by_post_id($post_id, $limited = 3)
    {
        $post = new Post();
        $current_post = $post->asObject()->find($post_id);
        
        if (!$current_post || empty($current_post->tags)) {
            return [];
        }
        
        $tags = explode(",", trim($current_post->tags));
        $post = new Post();
        $post->select("*");
        $post->where('id !=', $post_id);
        $post->where('visibility', 1);
        $post->groupStart();
        foreach ($tags as $tag) {
            $post->orLike('tags', trim($tag), 'both');
        }
        $post->groupEnd();
        $totalPost = $post->asObject()->limit($limited)->findAll();
        return $totalPost;
    }
}

if (!function_exists('get_previous_post')) {
    function get_previous_post($post_id)
    {
        $post = new Post();
        $post_previous = $post->asObject()->where('id < ', $post_id)->where('visibility', 1)->limit(1)->first();
        return $post_previous;
    }
}

if (!function_exists('get_next_post')) {
    function get_next_post($post_id)
    {
        $post = new Post();
        $post_next = $post->asObject()->where('id > ', $post_id)->where('visibility', 1)->limit(1)->first();
        return $post_next;
    }
}

if (!function_exists('get_footer_pages')) {
    function get_footer_pages()
    {
        $page = new Page();
        return $page->asObject()->where('show_in_footer', 1)->where('status', 1)->orderBy('ordering', 'asc')->findAll();
    }
}

if (!function_exists('get_admin_avatar_url')) {
    /**
     * Return URL foto author/admin yang diupload.
     * Fallback ke Gravatar jika belum ada foto.
     */
    function get_admin_avatar_url(int $size = 44): string
    {
        $author = (new \App\Models\Author())->asObject()->first();
        if ($author && !empty($author->photo)) {
            $path = 'uploads/author/' . $author->photo;
            if (file_exists(FCPATH . $path)) {
                return base_url($path);
            }
        }
        // Fallback gravatar dari email settings
        $email = get_settings()->blog_email ?? 'admin@blog.com';
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '?s=' . $size . '&d=mp';
    }
}