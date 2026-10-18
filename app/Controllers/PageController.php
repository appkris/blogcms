<?php

namespace App\Controllers;

use App\Models\Page;

class PageController extends BaseController
{
    protected $helpers = ['url', 'CIFunctions'];

    public function show($slug)
    {
        $page = new Page();
        $data = $page->asObject()->where('slug', $slug)->where('status', 1)->first();

        if (!$data) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('frontend/pages/page', [
            'pageTitle' => $data->title,
            'page'      => $data,
        ]);
    }
}
