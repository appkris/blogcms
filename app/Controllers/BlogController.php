<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Post;
use App\Models\SubCategory;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use App\Models\Author;
use App\Models\Comment;

class BlogController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions', 'text'];
    public function index()
    {
        $authorModel = new Author();
        $postModel   = new Post();
        $perPage     = 5;
        $currentPage = (int) ($this->request->getGet('page') ?? 1);

        $author = $authorModel->asObject()->first();
        $posts  = $postModel->asObject()
                            ->where('visibility', 1)
                            ->orderBy('created_at', 'DESC')
                            ->paginate($perPage);
        $pager  = $postModel->pager;

        // Halaman 1: post pertama jadi featured, sisanya grid
        // Halaman 2+: semua post tampil sebagai grid
        $mainPost = null;
        if ($currentPage === 1 && !empty($posts)) {
            $mainPost = array_shift($posts);
        }

        return view('frontend/pages/home', [
            'pageTitle'   => get_settings()->blog_title,
            'author'      => $author,
            'mainPost'    => $mainPost,
            'posts'       => $posts,
            'pager'       => $pager,
            'currentPage' => $currentPage,
        ]);
    }


    public function readPost($slug)
{
    $post = new Post();
    $authorModel = new Author();
    $subCategoryModel = new SubCategory();
    $commentModel = new Comment();

    $post_data = $post->asObject()->where('slug', $slug)->first();

    if (!$post_data) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    $category = $subCategoryModel->asObject()->find($post_data->category_id);
    $post_data->category_slug = $category ? $category->slug : '';
    $post_data->category_name = $category ? $category->name : '';

    $author = $authorModel->asObject()->first();

    $data = [
        'pageTitle'     => $post_data->title,
        'post'          => $post_data,
        'author'        => $author,
        'comments'      => $commentModel->getNestedByPost((int)$post_data->id),
        'totalComments' => $commentModel->countApproved((int)$post_data->id),
    ];

    return view('frontend/pages/single_post', $data);
}

    public function categoryPost($slug)
    {
        $subCat = new SubCategory();
        $subCat_data = $subCat->asObject()->where('slug', $slug)->first();
        if (!$subCat_data) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $post = new Post();
        $data = [];
        $data['pageTitle'] = 'Category: ' . $subCat_data->name;
        $data['page'] = (int) ($this->request->getGet('page') ?? 1);
        $data['perPage'] = 4;
        $postQuery = $post->asObject()->where('visibility', 1)->where('category_id', $subCat_data->id);
        $data['total'] = $postQuery->countAllResults(false); // false agar query tidak dieksekusi ulang
        $data['post_data'] = $postQuery->paginate($data['perPage']);
        $data['pager'] = $post->pager;
        return view('frontend/pages/category', $data);
    }

    public function tagPost($tag)
    {
        $post = new Post();
        $data['pageTitle'] = 'Tag: ' . $tag;
        $data['page'] = (int) ($this->request->getGet('page') ?? 1);
        $data['perPage'] = 4;
        $postQuery = $post->asObject()->where('visibility', 1)->like('tags', $tag, 'both');
        $data['total'] = $postQuery->countAllResults(false);
        $data['post_data'] = $postQuery->paginate($data['perPage']);
        $data['pager'] = $post->pager;
        return view('frontend/pages/category', $data);
    }

    public function searchPost()
    {
        $q       = trim((string) ($this->request->getVar('q') ?? ''));
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 6;

        $postModel = new Post();
        $query = $postModel->asObject()->where('visibility', 1);

        if (!empty($q)) {
            $words = array_filter(explode(' ', $q));
            if (!empty($words)) {
                $query->groupStart();
                foreach ($words as $word) {
                    $query->orLike('title', $word, 'both')->orLike('tags', $word, 'both');
                }
                $query->groupEnd();
            }
        }

        $total = $query->countAllResults(false);
        $posts = $query->orderBy('created_at', 'DESC')->paginate($perPage);

        $data = [
            'pageTitle' => !empty($q) ? 'Search for: ' . esc($q) : 'Search',
            'page'      => $page,
            'perPage'   => $perPage,
            'total'     => $total,
            'post_data' => $posts,
            'pager'     => $postModel->pager,
            'q'         => $q,
        ];

        return view('frontend/pages/search_posts', $data);
    }

    // ...fungsi handleSearch dihapus karena tidak digunakan...

    public function contactUs()
    {
        $data = [
            'pageTitle' => 'Contact Us',
            'validation' => null
        ];
        return view('frontend/pages/contact', $data);
    }

    public function contactUsForm()
    {
        $request = Services::request();
        $validation = Services::validation();
        $this->validate([
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'name is required',
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'email is required',
                    'valid_email' => 'Invalid email address'
                ]
            ],
            'subject' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'subject is required'
                ]
            ],
            'message' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'message is required'
                ]
            ]
        ]);
        if ($validation->run() === false) {
            $data = [
                'pageTitle' => 'Contact Us',
                'validation' => $this->validator
            ];
            return view('frontend/pages/contact', $data);
        } else {

            // send email notification
            $mail_body = "message from:<b>" . $request->getVar('email') . "</b> </br>";
            $mail_body .= "------------------------------------</br>";
            $mail_body .= $request->getVar('message');


            $mailConfig = array(
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => get_settings()->blog_email,
                'mail_recipient_name' => get_settings()->blog_title,
                'mail_subject' => $request->getVar('subject'),
                'mail_body' => $mail_body
            );

            sendEmail($mailConfig);
            return redirect()->back()->with('success', 'Your message has been sent');
        }
    }
    public function about()
    {
        return view('frontend/pages/about');
    }

    /**
     * Generate dynamic sitemap.xml for search engines
     */
    public function sitemap(): ResponseInterface
    {
        $postModel        = new Post();
        $subCategoryModel = new SubCategory();
        $pageModel        = new \App\Models\Page();

        $posts        = $postModel->asObject()->where('visibility', 1)->orderBy('updated_at', 'DESC')->findAll();
        $subCategories = $subCategoryModel->asObject()->findAll();
        $pages        = $pageModel->asObject()->where('status', 1)->findAll();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        // Homepage
        $latestPostTime = !empty($posts[0]->updated_at) ? date('c', strtotime($posts[0]->updated_at)) : date('c');
        $xml .= "  <url>\n";
        $xml .= '    <loc>' . htmlspecialchars(base_url(), ENT_XML1, 'UTF-8') . "</loc>\n";
        $xml .= '    <lastmod>' . $latestPostTime . "</lastmod>\n";
        $xml .= "    <changefreq>daily</changefreq>\n";
        $xml .= "    <priority>1.0</priority>\n";
        $xml .= "  </url>\n";

        // Static / Public pages
        $xml .= "  <url>\n";
        $xml .= '    <loc>' . htmlspecialchars(route_to('contact-us'), ENT_XML1, 'UTF-8') . "</loc>\n";
        $xml .= "    <changefreq>monthly</changefreq>\n";
        $xml .= "    <priority>0.5</priority>\n";
        $xml .= "  </url>\n";

        // CMS Dynamic Pages
        foreach ($pages as $p) {
            $pageMod = !empty($p->updated_at) ? date('c', strtotime($p->updated_at)) : date('c');
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars(route_to('page-show', $p->slug), ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= '    <lastmod>' . $pageMod . "</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.6</priority>\n";
            $xml .= "  </url>\n";
        }

        // Categories
        foreach ($subCategories as $cat) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars(route_to('category-post', $cat->slug), ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }

        // Posts
        foreach ($posts as $item) {
            $postMod = !empty($item->updated_at) ? date('c', strtotime($item->updated_at)) : (!empty($item->created_at) ? date('c', strtotime($item->created_at)) : date('c'));
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars(route_to('read-post', $item->slug), ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= '    <lastmod>' . $postMod . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";

            if (!empty($item->featured_image)) {
                $xml .= "    <image:image>\n";
                $xml .= '      <image:loc>' . htmlspecialchars(base_url('images/posts/' . $item->featured_image), ENT_XML1, 'UTF-8') . "</image:loc>\n";
                $xml .= '      <image:title>' . htmlspecialchars($item->title, ENT_XML1, 'UTF-8') . "</image:title>\n";
                $xml .= "    </image:image>\n";
            }

            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $this->response
                    ->setHeader('Content-Type', 'text/xml; charset=UTF-8')
                    ->setBody($xml);
    }

    /**
     * Generate dynamic RSS 2.0 Feed for readers and syndication
     */
    public function feed(): ResponseInterface
    {
        $postModel = new Post();
        $posts     = $postModel->asObject()
                               ->where('visibility', 1)
                               ->orderBy('created_at', 'DESC')
                               ->findAll(20);

        $settings = get_settings();
        $title    = !empty($settings->blog_title) ? $settings->blog_title : 'Blog';
        $desc     = !empty($settings->blog_meta_description) ? $settings->blog_meta_description : $title;

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
        $xml .= "  <channel>\n";
        $xml .= '    <title>' . htmlspecialchars($title, ENT_XML1, 'UTF-8') . "</title>\n";
        $xml .= '    <link>' . htmlspecialchars(base_url(), ENT_XML1, 'UTF-8') . "</link>\n";
        $xml .= '    <description>' . htmlspecialchars($desc, ENT_XML1, 'UTF-8') . "</description>\n";
        $xml .= '    <language>id-ID</language>\n';
        $xml .= '    <atom:link href="' . htmlspecialchars(site_url('feed'), ENT_XML1, 'UTF-8') . '" rel="self" type="application/rss+xml" />' . "\n";

        if (!empty($posts[0]->created_at)) {
            $xml .= '    <lastBuildDate>' . date(DATE_RSS, strtotime($posts[0]->created_at)) . "</lastBuildDate>\n";
        }

        foreach ($posts as $item) {
            $postUrl = route_to('read-post', $item->slug);
            $excerpt = !empty($item->meta_description) ? $item->meta_description : word_limiter(strip_tags($item->content ?? ''), 40);
            $pubDate = !empty($item->created_at) ? date(DATE_RSS, strtotime($item->created_at)) : date(DATE_RSS);

            $xml .= "    <item>\n";
            $xml .= '      <title>' . htmlspecialchars($item->title, ENT_XML1, 'UTF-8') . "</title>\n";
            $xml .= '      <link>' . htmlspecialchars($postUrl, ENT_XML1, 'UTF-8') . "</link>\n";
            $xml .= '      <guid isPermaLink="true">' . htmlspecialchars($postUrl, ENT_XML1, 'UTF-8') . "</guid>\n";
            $xml .= '      <description>' . htmlspecialchars($excerpt, ENT_XML1, 'UTF-8') . "</description>\n";
            $xml .= '      <pubDate>' . $pubDate . "</pubDate>\n";
            $xml .= "    </item>\n";
        }

        $xml .= "  </channel>\n";
        $xml .= '</rss>';

        return $this->response
                    ->setHeader('Content-Type', 'application/rss+xml; charset=UTF-8')
                    ->setBody($xml);
    }
}
