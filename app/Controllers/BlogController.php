<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Post;
use App\Models\SubCategory;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use App\Models\Author;

class BlogController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions', 'text'];
    public function index()
    {
        $authorModel = new \App\Models\Author();
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
    $post = new \App\Models\Post();
    $authorModel = new \App\Models\Author();
    $subCategoryModel = new \App\Models\SubCategory();
    $commentModel = new \App\Models\Comment();

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

    
}
