<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Category;
use App\Models\Setting;
use App\Models\SocialMedia;
use App\Models\SubCategory;
use App\Models\BlogAuthorModel;
use App\Models\ContentTypeModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\Post;
use App\Models\User;
use Config\Services;
use Mberecall\CI_Slugify\SlugService;
use SSP;

class AdminController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions', 'CISiteSource'];
    protected $db;

    public function __construct()
    {
        require_once APPPATH . "ThirdParty/ssp.php";
        $this->db = db_connect();
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Dashboard',
        ];
        return view('backend/pages/home', $data);
    }

    private function LoadJS($pageTitle)
    {
        return RunQueryJsCSSPage(array('/assets/js/' . strtolower($pageTitle) . '.js'), 'js');   /*Position Add Js*/
    }

    public function logoutHandler()
    {
        CIAuth::forget();
        return redirect('admin.login.form')->with('fail', 'Anda sudah log out!');
    }

    public function profile()
    {
        $data = array(
            'pageTitle' => 'Profile',
            'loadJS' => $this->LoadJS('Profile')
        );
        return view('backend/pages/profile', $data);
    }


    public function updatePersonalDetails()
{

    $validation = \Config\Services::validation();
    $rules = [
        'name' => 'required',
        'username' => 'required',
        'bio' => 'required'
    ];

    if (!$this->validate($rules)) {
        return $this->response->setJSON([
            'status' => 0,
            'errors' => $validation->getErrors()
        ]);
    }

    $userModel = new \App\Models\User();
    $userId = function_exists('get_user') ? get_user()->id : (CIAuth::id() ?? null);
    $user_info = null;
    if ($userId) {
        $userModel->update($userId, [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'bio'  => $this->request->getPost('bio')
        ]);
        $user_info = $userModel->find($userId);
    }

    return $this->response->setJSON([
        'status' => 1,
        'msg' => 'Data berhasil diperbarui',
        'user_info' => $user_info
    ]);
}


    public function updateProfilePicture()
    {
        $request = Services::request();
        $user_id = CIAuth::id();
        $user = new User();
        $user_info = $user->asObject()->where('id', $user_id)->first();
        $path = 'images/users/';
        $file = $request->getFile('user_profile_file');
        $old_picture = $user_info->picture;
        $new_filename = 'UIMG_' . $user_id . $file->getRandomName();

        //normal ways
        // if ($file->move($path, $new_filename)) {
        //     if ($old_picture != null && file_exists($path . $old_picture)) {
        //         unlink($path . $old_picture);
        //     }
        //     $user->where('id', $user_info->id)
        //         ->set(['picture' => $new_filename])
        //         ->update();
        //     return json_encode(['status' => 1, 'msg' => 'Done!, Your Profile picture has been successfully updated']);
        // } else {
        //     return json_encode(['status' => 0, 'msg' => 'Something went wrong']);
        // }

        //image manipulation 
        $upload_image = Services::image()->withFile($file)->resize(450, 450, true, 'height')->save($path . $new_filename);
        if ($upload_image) {
            if ($old_picture !== null && file_exists($path . $new_filename)) {
                unlink($path . $old_picture);
            }
            $user->where('id', $user_info->id)->set(['picture' => $new_filename])->update();
            echo json_encode(['status' => 1, 'msg' => 'Gambar profile berhasil di ubah.']);
        } else {
            echo json_encode(['status' => 0, 'msg' => 'Terjadi kesalahan']);
        }
    }

    public function changePassword()
    {
        $request = Services::request();
        if ($request->isAJAX()) {
            $validation = Services::validation();
            $user_id = CIAuth::id();
            $user = new User();
            $user_info = $user->asObject()->where('id', $user_id)->first();

            //validate the form
            $this->validate([
                'current_password' => [
                    'rules' => 'required|min_length[5]|check_current_password[current_password]',
                    'errors' => [
                        'required' => 'Silahkan masukan password anda',
                        'min_length' => 'password minimal 5 karakter',
                        'check_current_password' => 'Password anda salah'
                    ]
                ],
                'new_password' => [
                    'rules' => 'required|min_length[5]|max_length[20]|is_password_strong[new_password]',
                    'errors' => [
                        'required' => 'Masukan password baru',
                        'min_length' => 'Minimal password 5 karakter',
                        'max_length' => 'Password tidak boleh lebih dari 20 karakter',
                        'is_password_strong' => 'Password harus berisi angka, huruf besar, huruf kecil dan simbol'
                    ]
                ],
                'confirm_new_password' => [
                    'rules' => 'required|matches[new_password]',
                    'errors' => [
                        'required' => 'Konfirmasi password baru',
                        'matches' => 'password anda tidak sesuai'
                    ]
                ]
            ]);

            if ($validation->run() === false) {
                $errors = $validation->getErrors();
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'error' => $errors
                ]);
            } else {

                //update user(admin) password in DB
                $user->where('id', $user_info->id)->set(['password' => Hash::make($request->getVar('new_password'))])->update();

                // send email notification to user(admin) email address 
                $mail_data = array(
                    'user' => $user_info,
                );

                $view = Services::renderer();
                $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/password-changed-email-template');

                $mailConfig = array(
                    'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                    'mail_from_name' => env('EMAIL_FROM_NAME'),
                    'mail_recipient_email' => $user_info->email,
                    'mail_recipient_name' => $user_info->name,
                    'mail_subject' => 'Password Changed',
                    'mail_body' => $mail_body
                );

                sendEmail($mailConfig);
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => "Password berhasil diubah!"
                ]);
            }
        }
    }

    public function settings()
    {
        $data = [
            'pageTitle' => 'Settings',
            'loadJS' => $this->LoadJS('Settings')
        ];
        return view('backend/pages/settings', $data);

    }

    public function updateGeneralSettings()
    {
        $request = Services::request();
        if ($request->isAJAX()) {
            $validation = Services::validation();
            $this->validate([
                'blog_title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => "Masukan judul blog"
                    ]
                ],
                'blog_email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Masukan email blog',
                        'valid_email' => 'Alamat email tidak valid'
                    ]
                ]
            ]);

            if ($validation->run() === false) {
                $errors = $validation->getErrors();
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'error' => $errors
                ]);
            } else {
                $settings = new Setting();
                $setting_id = $settings->asObject()->first()->id;
                $update = $settings->where('id', $setting_id)->set([
                    'blog_title' => $request->getVar('blog_title'),
                    'blog_email' => $request->getVar('blog_email'),
                    'blog_phone' => $request->getVar('blog_phone'),
                    'blog_meta_keywords' => $request->getVar('blog_meta_keywords'),
                    'blog_meta_description' => $request->getVar('blog_meta_description'),
                ])->update();
                if ($update) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Pengaturan general berhasil diubah.'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'token' => csrf_hash(),
                        'msg' => 'Terjadi kesalahan'
                    ]);
                }

            }
        }
    }

    public function updateBlogLogo()
    {
        $request = Services::request();

        if ($request->isAJAX()) {
            $path = 'images/blogs/';
            $file = $request->getFile('blog_logo');
            $settings = new Setting();
            $setting_data = $settings->asObject()->first();
            $old_blog_logo = $setting_data->blog_logo;
            $new_filename = 'CI4blog_logo' . $file->getRandomName();
            if ($file->move($path, $new_filename)) {
                if ($old_blog_logo != null && file_exists($path . $old_blog_logo)) {
                    unlink($path . $old_blog_logo);
                }
            }

            $update = $settings->where('id', $setting_data->id)->set([
                'blog_logo' => $new_filename
            ])->update();

            if ($update) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Logo blog berhasil diubah.'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'msg' => 'Terjadi kesalahan'
                ]);
            }

        } else {
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'msg' => 'Terjadi kesalahan mengunggah gambar'
            ]);
        }
    }

    public function updateBlogFavicon()
    {

        $request = Services::request();
        if ($request->isAJAX()) {
            $path = 'images/blogs/';
            $file = $request->getFile('blog_favicon');
            $settings = new Setting();
            $setting_data = $settings->asObject()->first();
            $old_blog_favicon = $setting_data->blog_favicon;
            $new_filename = 'CI4Blog_favicon' . $file->getRandomName();
            if ($file->move($path, $new_filename)) {
                if ($old_blog_favicon !== null && file_exists($path . $old_blog_favicon)) {
                    unlink($path . $old_blog_favicon);
                }
                $update = $settings->where('id', $setting_data->id)->set([
                    'blog_favicon' => $new_filename
                ])->update();
                if ($update) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => "Favicon blog berhasil diubah"
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'token' => csrf_hash(),
                        'msg' => 'Terjadi kesalahan'
                    ]);
                }
            }
        } else {
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'msg' => 'Terjadi kesalahan mengunggah favicon'
            ]);
        }
    }

    public function updateSocialMedia()
    {
        $request = Services::request();
        if ($request->isAJAX()) {
            $validation = Services::validation();
            $this->validate([
                'facebook_url' => [
                    'rules' => 'permit_empty|valid_url_strict',
                    'errors' => [
                        'valid_url_strict' => 'url facebook tidak valid'
                    ]
                ],
                'twitter_url' => [
                    'rules' => 'permit_empty|valid_url_strict',
                    'errors' => [
                        'valid_url_strict' => 'url twitter tidak valid'
                    ]
                ],
                'instagram_url' => [
                    'rules' => 'permit_empty|valid_url_strict',
                    'errors' => [
                        'valid_url_strict' => 'url instagram tidak valid'
                    ]
                ],
                'youtube_url' => [
                    'rules' => 'permit_empty|valid_url_strict',
                    'errors' => [
                        'valid_url_strict' => 'url youtube tidak valid'
                    ]
                ],
                'tiktok_url' => [
                    'rules' => 'permit_empty|valid_url_strict',
                    'errors' => [
                        'valid_url_strict' => 'url tiktok tidak valid'
                    ]
                ],
                'linkedin_url' => [
                    'rules' => 'permit_empty|valid_url_strict',
                    'errors' => [
                        'valid_url_strict' => 'url linkedin tidak valid'
                    ]
                ],
            ]);

            if ($validation->run() === false) {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'error' => $validation->getErrors()
                ]);
            } else {

                $social_media = new SocialMedia();
                $social_media_id = $social_media->asObject()->first()->id;

                $update = $social_media->where('id', $social_media_id)->set([
                    'facebook_url' => $request->getVar('facebook_url'),
                    'twitter_url' => $request->getVar('twitter_url'),
                    'instagram_url' => $request->getVar('instagram_url'),
                    'youtube_url' => $request->getVar('youtube_url'),
                    'tiktok_url' => $request->getVar('tiktok_url'),
                    'linkedin_url' => $request->getVar('linkedin_url'),
                ])->update();

                if ($update) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Social media have berhasil diubah.'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'token' => csrf_hash(),
                        'msg' => 'Terjadi kesalahan mengubah social media'
                    ]);
                }


            }
        } else {
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'msg' => "Terjadi kesalahan."
            ]);
        }
    }

    public function categories()
    {
        $data = [
            'pageTitle' => 'Categories',
            'loadJS' => $this->LoadJS('Categories')
        ];
        return view('backend/pages/categories', $data);
    }

    public function addCategories()
    {
        $request = Services::request();
        if ($request->isAJAX()) {
            $validation = Services::validation();
            $this->validate([
                'category_name' => [
                    'rules' => 'required|is_unique[categories.name]',
                    'errors' => [
                        'required' => 'category name is required',
                        'is_unique' => 'Category name is already exists'
                    ]
                ]
            ]);
            if ($validation->run() === false) {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'error' => $validation->getErrors()
                ]);
            } else {
                $category = new Category();
                $save = $category->save(['name' => $request->getVar('category_name')]);
                if ($save) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'New category has been successfully added'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'token' => csrf_hash(),
                        'msg' => 'New category has been successfully added'
                    ]);
                }

            }
        } else {
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'msg' => 'something went wrong'
            ]);
        }
    }

    public function getCategories()
    {
        // db details
        $dbDetails = array(
            'host' => $this->db->hostname,
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db'   => $this->db->database
        );
        $table = 'categories';
        $primaryKey = 'id';

        // Preload subcategory count grouped by parent_cat to eliminate N+1 queries
        $subCatBuilder = $this->db->table('sub_categories')
                                  ->select('parent_cat, COUNT(*) as total')
                                  ->groupBy('parent_cat')
                                  ->get();
        $subCatCounts = [];
        foreach ($subCatBuilder->getResultArray() as $row) {
            $subCatCounts[$row['parent_cat']] = (int) $row['total'];
        }

        $columns = array(
            array(
                'db' => 'id',
                'dt' => 0
            ),
            array(
                'db' => 'name',
                'dt' => 1
            ),
            array(
                'db' => 'id',
                'dt' => 2,
                'formatter' => function ($d, $row) use ($subCatCounts) {
                    return $subCatCounts[$row['id']] ?? 0;
                }
            ),
            array(
                'db' => 'id',
                'dt' => 3,
                'formatter' => function ($d, $row) {
                    return "
                    <div class='btn-group'>
                        <button class='btn btn-sm btn-link p-0 mx-1 editCategoryBtn' data-id='" . $row['id'] . "' data-route='" . route_to('get-category') . "'>Edit</button>
                        <button class='btn btn-sm btn-link p-0 mx-1 deleteCategoryBtn' data-id='" . $row['id'] . "' data-route='" . route_to('delete-category') . "'>Delete</button>
                    </div>
                    ";
                }
            ),
            array(
                'db' => 'ordering',
                'dt' => 4
            )
        );
        return json_encode(
            SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
        );
    }

    public function getCategory()
    {
        $request = Services::request();
        if ($request->isAJAX()) {
            $id = $request->getVar('category_id');
            $category = new Category();
            $category_data = $category->find($id);
            return $this->response->setJSON(['data' => $category_data]);

        }
    }

    public function updateCategories()
    {
        $request = Services::request();

        if ($request->isAJAX()) {
            $id = $request->getVar('category_id');
            $validation = Services::validation();
            $this->validate([
                'category_name' => [
                    'rules' => 'required|is_unique[categories.name,id,' . $id . ']',
                    'errors' => [
                        'required' => 'Category name is required',
                        'is_unique' => 'Category name is already exists'
                    ]
                ]
            ]);
            if ($validation->run() === false) {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'error' => $validation->getErrors()
                ]);
            } else {
                $category = new Category();
                $update = $category->where('id', $id)->set([
                    'name' => $request->getVar('category_name')
                ])->update();
                if ($update) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => "Done!, update Category success"
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'token' => csrf_hash(),
                        'msg' => "something went wrong with update category"
                    ]);
                }
            }
        } else {
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'msg' => "something went wrong"
            ]);
        }
    }

    public function deleteCategory()
    {
        $request = Services::request();
        if ($request->isAJAX()) {
            $id = $request->getVar('category_id');

            //check it's related sub category in fure video

            //check it's related posts through it's subcategories in future video

            $subCategory = new SubCategory();
            $subCategories = $subCategory->where('parent_cat', $id)->findAll();
            if (count($subCategories) > 0) {
                $msg = count($subCategories) === 1 ? 'There is (' . count($subCategories) . ' sub category related to this parent category, so that it can not be deleted.' : '
                There are (' . count($subCategories) . ' sub category related to this parent category, so that it can not be deleted.
                ';
                return $this->response->setJSON([
                    'status' => 0,
                    'msg' => $msg
                ]);
            } else {
                $category = new Category();
                //delete category 
                $delete = $category->where('id', $id)->delete();
                $delete = $category->delete($id);
                if ($delete) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'msg' => 'category has been successfully deleted.'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'msg' => 'Something went wrong'
                    ]);
                }
            }

        }
    }

    public function reorderCategories()
    {
        $request = Services::request();
        if ($request->isAJAX()) {
            $positions = $request->getVar('positions');
            $category = new Category();
            foreach ($positions as $position) {
                $index = $position[0];
                $newPosition = $position[1];
                $category->where('id', $index)->set(['ordering' => $newPosition])->update();
            }
            return $this->response->setJSON(['status' => 1, 'msg' => 'Categories ordering has been successfully updated']);
        }
    }


    public function getParentCategories()
    {
        $request = Services::request();
        if ($request->isAJAX()) {
            $id = $request->getVar('parent_category_id');
            $option = '<option value="0">Uncategorized</option>';
            $category = new Category();
            $parent_categories = $category->findAll();
            if (count($parent_categories)) {
                foreach ($parent_categories as $parent_category) {
                    $isSelected = $parent_category['id'] == $id ? 'selected' : "";
                    $option .= '<option value="' . $parent_category['id'] . '" ' . $isSelected . '>' . $parent_category['name'] . '</option>';
                }
                return $this->response->setJSON([
                    'status' => 1,
                    'data' => $option
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 1,
                    'data' => $option
                ]);
            }
        } else {
            $this->response->setJSON([
                'status' => 0,
                'msg' => 'something went wrong'
            ]);
        }
    }

    public function addSubCategory()
    {
        $request = Services::request();

        if ($request->isAJAX()) {
            $validation = Services::validation();
            $this->validate([
                'subcategory_name' => [
                    'rules' => 'required|is_unique[sub_categories.name]',
                    'errors' => [
                        'required' => 'Sub Category is required',
                        'is_unique' => 'sub category name is already exists'
                    ]
                ]
            ]);
            if ($validation->run() === false) {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'error' => $validation->getErrors()
                ]);
            } else {
                $sub_category = new SubCategory();
                $save = $sub_category->save([
                    'name' => $request->getVar('subcategory_name'),
                    'slug' => SlugService::model(SubCategory::class)->make($request->getVar('subcategory_name')),
                    'parent_cat' => $request->getVar('parent_cat'),
                    'description' => $request->getVar('description')
                ]);
                if ($save) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Done! create sub categories successfully'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'token' => csrf_hash(),
                        'msg' => 'something went wrong'
                    ]);
                }
            }
        } else {
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'msg' => 'something went wrong'
            ]);
        }
    }

    public function getSubCategories()
    {
        $category = new Category();
        $subcategory = new SubCategory();
        // db details
        $dbDetails = array(
            'host' => $this->db->hostname,
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db' => $this->db->database
        );
        $table = 'sub_categories';
        $primaryKey = 'id';
        $columns = array(
            array(
                'db' => 'id',
                'dt' => 0
            ),
            array(
                'db' => 'name',
                'dt' => 1
            ),
            array(
                'db' => 'id',
                'dt' => 2,
                'formatter' => function ($d, $row) use ($category, $subcategory) {
                    $parent_cat_id = $subcategory->asObject()->where('id', $row['id'])->first()->parent_cat;
                    $parent_cat_name = " - ";
                    if ($parent_cat_id != 0) {
                        $parent_cat_name = $category->asObject()->where('id', $parent_cat_id)->first()->name;
                    }
                    return $parent_cat_name;
                }
            ),
            array(
                'db' => 'id',
                'dt' => 3,
                'formatter' => function ($d, $row) {
                    $post = new Post();
                    $post_data = $post->where('category_id', $row['id'])->findAll();
                    return count($post_data);


                }
            ),
            array(
                'db' => 'id',
                'dt' => 4,
                'formatter' => function ($d, $row) {
                    return "
                    <div class='btn-group'>
                        <button class='btn btn-sm btn-link p-0 mx-1 editSubCategoryBtn' data-id='" . $row['id'] . "' data-route-get-subcategory='" . route_to('get-subcategory') . "'>Edit</button>
                        <button class='btn btn-sm btn-link p-0 mx-1 deleteSubCategoryBtn' data-id='" . $row['id'] . "' data-route='" . route_to('delete-subcategory') . "'>Delete</button>
                    </div>
                    ";
                }
            ),
            array(
                'db' => 'ordering',
                'dt' => 5
            )
        );
        return json_encode(
            SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
        );
    }


    public function getSubCategory()
    {
        $request = Services::request();
        if ($request->isAJAX()) {
            $id = $request->getVar('sub_category_id');
            $sub_category = new SubCategory();
            $sub_category_data = $sub_category->find($id);
            $option = '<option value="0">Uncategorized</option>';
            $category = new Category();
            $parent_categories = $category->findAll();
            if (count($parent_categories)) {
                foreach ($parent_categories as $parent_category) {
                    $isSelected = $parent_category['id'] == $sub_category_data->parent_cat ? 'selected' : "";
                    $option .= '<option value="' . $parent_category['id'] . '" ' . $isSelected . '>' . $parent_category['name'] . '</option>';
                }
                return $this->response->setJSON([
                    'status' => 1,
                    'data_category' => $option,
                    'data_subcategory' => $sub_category_data
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 1,
                    'data_category' => $option,
                    'data_subcategory' => $sub_category_data
                ]);
            }
        }
    }

    public function updateSubCategory()
    {
        $request = Services::request();

        if ($request->isAJAX()) {
            $id = $request->getVar('subcategory_id');
            $validation = Services::validation();
            $this->validate([
                'subcategory_name' => [
                    'rules' => 'required|is_unique[sub_categories.name,id,' . $id . ']',
                    'errors' => [
                        'required' => 'Sub Category name is required',
                        'is_unique' => 'Sub Category name is already exists'
                    ]
                ]
            ]);
            if ($validation->run() === false) {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'error' => $validation->getErrors()
                ]);
            } else {
                $sub_category = new SubCategory();
                $update = $sub_category->where('id', $id)->set([
                    'name' => $request->getVar('subcategory_name'),
                    'parent_cat' => $request->getVar('parent_cat'),
                    'description' => $request->getVar('description'),
                    'slug' => SlugService::model(SubCategory::class)->sid($id)->make($request->getVar('subcategory_name'))
                ])->update();
                if ($update) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => "Done!, update Sub Category success"
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'token' => csrf_hash(),
                        'msg' => "something went wrong with update category"
                    ]);
                }
            }
        } else {
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'msg' => "something went wrong"
            ]);
        }
    }

    public function reorderSubCategories()
    {
        $request = Services::request();
        if ($request->isAJAX()) {
            $positions = $request->getVar('positions');
            $sub_category = new SubCategory();
            foreach ($positions as $position) {
                $index = $position[0];
                $newPosition = $position[1];
                $sub_category->where('id', $index)->set(['ordering' => $newPosition])->update();
            }
            return $this->response->setJSON(['status' => 1, 'msg' => 'Sub Categories ordering has been successfully updated']);
        }
    }

    public function deleteSubCategory()
    {
        $request = Services::request();
        if ($request->isAJAX()) {
            $id = $request->getVar('subcategory_id');

            $post = new Post();
            $post_data = $post->where('category_id', $id)->findAll();
            if (count($post_data) > 0) {
                $msg = count($post_data) === 1 ? 'There is (' . count($post_data) . ' Posts related to this parent category, so that it can not be deleted.' : '
                There are (' . count($post_data) . ' Posts related to this parent category, so that it can not be deleted.
                ';
                return $this->response->setJSON([
                    'status' => 0,
                    'msg' => $msg
                ]);
            } else {
                $sub_category = new SubCategory();

                //delete  subcategory 
                $delete = $sub_category->where('id', $id)->delete();

                if ($delete) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'msg' => 'Subcategory has been successfully deleted.'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'msg' => 'Something went wrong'
                    ]);
                }
            }

        }
    }

    public function addPost()
    {
        $category = new SubCategory();
        $data = [
            'pageTitle' => 'Add New Post',
            'loadJS' => $this->LoadJS('AddNewPost'),
            'categories' => $category->asObject()->findAll()
        ];
        return view('backend/pages/new-post', $data);
    }

    public function createPost()
    {
        $request = Services::request();
        $validation = Services::validation();
        $this->validate([
            'title' => [
                'rules' => 'required|is_unique[posts.title]',
                'errors' => [
                    'required' => 'Post title is required',
                    'is_unique' => 'this post title is already exists'
                ]
            ],
            'content' => [
                'rules' => 'required|min_length[20]',
                'errors' => [
                    'required' => 'Post content is required',
                    'min_length' => 'Post content must have atleast 20 characters'
                ]
            ],
            'category' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'category is required'
                ]
            ],
            'featured_image' => [
                'rules' => 'uploaded[featured_image]|is_image[featured_image]|max_size[featured_image,2048]',
                'errors' => [
                    'uploaded' => 'Featured image is required',
                    'is_image' => 'select an image file type',
                    'max_size' => 'select image that not excess 2MB'
                ]
            ]
        ]);

        if ($validation->run() === false) {
            $errors = $validation->getErrors();
            if ($request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 0,
                    'token'  => csrf_hash(),
                    'error'  => $errors
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $user_id  = CIAuth::id();
        $path     = FCPATH . 'images/posts/';
        $file     = $request->getFile('featured_image');
        $filename = 'pimg_' . time() . $file->getClientName();

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        if (!$file->move($path, $filename)) {
            $msg = ['status' => 0, 'token' => csrf_hash(), 'msg' => 'Error uploading featured image.'];
            return $request->isAJAX()
                ? $this->response->setJSON($msg)
                : redirect()->back()->withInput()->with('error', $msg['msg']);
        }

        Services::image()->withFile($path . $filename)->fit(150, 150, 'center')->save($path . 'thumb_' . $filename);
        Services::image()->withFile($path . $filename)->resize(450, 300, true, 'width')->save($path . 'resized_' . $filename);

        $post = new Post();
        $post->insert([
            'author_id'        => $user_id,
            'category_id'      => $request->getVar('category'),
            'title'            => $request->getVar('title'),
            'slug'             => SlugService::model(Post::class)->make($request->getVar('title')),
            'content'          => $request->getVar('content'),
            'featured_image'   => $filename,
            'tags'             => $request->getVar('tags'),
            'meta_keywords'    => $request->getVar('meta_keywords'),
            'meta_description' => $request->getVar('meta_description'),
            'visibility'       => $request->getVar('visibility'),
        ]);
        $last_id = $post->getInsertID();

        if ($last_id) {
            if ($request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token'  => csrf_hash(),
                    'msg'    => 'Done!, create post successfully'
                ]);
            }
            return redirect()->route('all-posts')->with('success', 'Artikel berhasil dibuat.');
        }

        $errMsg = ['status' => 0, 'token' => csrf_hash(), 'msg' => 'Gagal menyimpan post ke database.'];
        return $request->isAJAX()
            ? $this->response->setJSON($errMsg)
            : redirect()->back()->withInput()->with('error', $errMsg['msg']);
    }

    public function allPosts()
    {
        $data = [
            'pageTitle' => 'All Post',
            'loadJS' => $this->LoadJS('AllPost'),
        ];
        return view('backend/pages/post', $data);
    }


    public function getPosts()
    {
        // db details
        $dbDetails = array(
            'host' => $this->db->hostname,
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db'   => $this->db->database
        );
        $table = 'posts';
        $primaryKey = 'id';

        // Preload all subcategories mapped by ID to eliminate per-row query
        $subCatList = (new SubCategory())->findAll();
        $subCatMap  = [];
        foreach ($subCatList as $sc) {
            $subCatMap[$sc['id']] = $sc['name'];
        }

        $columns = array(
            array(
                'db' => 'id',
                'dt' => 0
            ),
            array(
                'db' => 'featured_image',
                'dt' => 1,
                'formatter' => function ($d, $row) {
                    $img = $row['featured_image'] ?? '';
                    return '<img src="/images/posts/thumb_' . esc($img) . '" style="max-width:70px;"/>';
                }
            ),
            array(
                'db' => 'title',
                'dt' => 2,
            ),
            array(
                'db' => 'category_id',
                'dt' => 3,
                'formatter' => function ($d, $row) use ($subCatMap) {
                    $catId = $row['category_id'] ?? 0;
                    return esc($subCatMap[$catId] ?? '-');
                }
            ),
            array(
                'db' => 'visibility',
                'dt' => 4,
                'formatter' => function ($d, $row) {
                    return ($row['visibility'] == 1) ? 'Public' : 'Private';
                }
            ),
            array(
                'db' => 'slug',
                'dt' => 5,
                'formatter' => function ($d, $row) {
                    $slug = $row['slug'] ?? '';
                    $id   = $row['id'] ?? 0;
                    $viewUrl = base_url('post/' . $slug);
                    return "
                    <div class='btn-group'>
                        <a href='" . $viewUrl . "' target='_blank' class='btn btn-sm btn-link p-0 mx-1 viewPostBtn' data-id='" . $id . "'>View</a>
                        <a href='" . route_to('edit-post', $id) . "' class='btn btn-sm btn-link p-0 mx-1 editCategoryBtn' data-id='" . $id . "'>Edit</a>
                        <button class='btn btn-sm btn-link p-0 mx-1 deletePostBtn' data-id='" . $id . "' data-route='" . route_to('delete-post') . "'>Delete</button>
                    </div>
                    ";
                }
            ),
            array(
                'db' => 'created_at',
                'dt' => 6
            )
        );
        // Tambahkan default order by created_at DESC jika belum ada
        if (!isset($_GET['order'])) {
            $_GET['order'] = [['column' => 6, 'dir' => 'desc']]; // Kolom ke-6 adalah created_at
        }
        return json_encode(
            SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
        );
    }

    public function editPost($id)
    {
        $subCategory = new SubCategory();
        $post = new Post();
        $post_data = $post->asObject()->find($id);
        $data = [
            'pageTitle' => 'Edit Post',
            'loadJS' => $this->LoadJS('EditPost'),
            'categories' => $subCategory->asObject()->findAll(),
            'post_data' => $post_data
        ];
        return view('backend/pages/edit-post', $data);
    }

    public function updatePost()
    {
        $request   = Services::request();
        $author_id = CIAuth::id();
        $post_id   = $request->getVar('post_id');
        $post      = new Post();
        $post_data = $post->asObject()->find($post_id);
        $hasNewImage = isset($_FILES['featured_image']['name']) && !empty($_FILES['featured_image']['name']);

        // Validasi
        $rules = [
            'title'   => ['rules' => 'required|is_unique[posts.title,id,' . $post_id . ']',
                          'errors' => ['required' => 'Post title is required', 'is_unique' => 'Post title already exists']],
            'content' => ['rules' => 'required|min_length[20]',
                          'errors' => ['required' => 'Post content is required', 'min_length' => 'Min 20 characters']],
        ];
        if ($hasNewImage) {
            $rules['featured_image'] = [
                'rules'  => 'uploaded[featured_image]|is_image[featured_image]|max_size[featured_image,2048]',
                'errors' => ['uploaded' => 'Image required', 'is_image' => 'Select image file', 'max_size' => 'Max 2MB'],
            ];
        }

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            if ($request->isAJAX()) {
                return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'error' => $errors]);
            }
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $path = FCPATH . 'images/posts/';
        $updateData = [
            'author_id'        => $author_id,
            'category_id'      => $request->getVar('category'),
            'title'            => $request->getVar('title'),
            'slug'             => SlugService::model(Post::class)->sid($post_id)->make($request->getVar('title')),
            'content'          => $request->getVar('content'),
            'tags'             => $request->getVar('tags'),
            'meta_keywords'    => $request->getVar('meta_keywords'),
            'meta_description' => $request->getVar('meta_description'),
            'visibility'       => $request->getVar('visibility'),
        ];

        if ($hasNewImage) {
            $file     = $request->getFile('featured_image');
            $filename = 'pimg_' . time() . $file->getClientName();

            if (!is_dir($path)) mkdir($path, 0755, true);

            if (!$file->move($path, $filename)) {
                $err = ['status' => 0, 'token' => csrf_hash(), 'msg' => 'Error uploading image.'];
                return $request->isAJAX() ? $this->response->setJSON($err) : redirect()->back()->withInput();
            }

            Services::image()->withFile($path . $filename)->fit(150, 150, 'center')->save($path . 'thumb_' . $filename);
            Services::image()->withFile($path . $filename)->resize(450, 300, true, 'width')->save($path . 'resized_' . $filename);

            // Hapus gambar lama
            $old = $post_data->featured_image;
            foreach (['', 'thumb_', 'resized_'] as $prefix) {
                if ($old && file_exists($path . $prefix . $old)) unlink($path . $prefix . $old);
            }

            $updateData['featured_image'] = $filename;
        }

        $updated = $post->update($post_id, $updateData);

        if ($request->isAJAX()) {
            return $this->response->setJSON([
                'status' => $updated ? 1 : 0,
                'token'  => csrf_hash(),
                'msg'    => $updated ? 'Post berhasil diperbarui.' : 'Gagal memperbarui post.',
            ]);
        }
        return redirect()->route('all-posts')->with('success', 'Post berhasil diperbarui.');
    }

    public function deletePost()
    {
        $request = Services::request();
        if ($request->isAJAX()) {
            $post_id = $request->getVar('post_id');
            $post = new Post();
            $post_data = $post->asObject()->find($post_id);
            $path = 'images/posts/';
            $filename = $post_data->featured_image;
            if (file_exists($path . $filename)) {
                unlink($path . $filename);
            }
            if (file_exists($path . 'thumb_' . $filename)) {
                unlink($path . 'thumb_' . $filename);
            }
            if (file_exists($path . 'resized_' . $filename)) {
                unlink($path . 'resized_' . $filename);
            }

            $delete = $post->delete($post_id);
            if ($delete) {
                return $this->response->setJSON([
                    'status' => 1,
                    'msg' => 'Delete Post successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'msg' => 'Delete Post failed'
                ]);
            }
        } else {
            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Something went wrong!'
            ]);
        }
    }

public function authorInfo()
{
    $authorModel = new \App\Models\Author();
    $author = $authorModel->asObject()->first(); // Selalu ambil sebagai object
    $types = [
                ['id'=>1,'name'=>'Gaya Hidup'],
                ['id'=>2,'name'=>'Makanan dan Kuliner'],
                ['id'=>3,'name'=>'Tekologi dan Informasi'],
                ['id'=>4,'name'=>'Personal'],
                ['id'=>5,'name'=>'Kesehatan dan Kebugaran'],
                ['id'=>6,'name'=>'Berita dan Opini'],
                ['id'=>7,'name'=>'Pendidikan dan Edukasi'],
                ['id'=>8,'name'=>'Kesehatan dan Kebugaran'],
                ['id'=>9,'name'=>'Lainnya']
            ]; // contoh, sesuaikan dengan tabel content_type
    $selectedTypeId = ($author && isset($author->content_type_id)) ? $author->content_type_id : '';
    $data = [
        'pageTitle' => 'Informasi Author',
        'author' => $author,
        'types' => $types,
        'selectedTypeId' => $selectedTypeId
    ];
    return view('backend/pages/blog-author', $data);
}

public function updateAuthor()
{
    $authorModel = new \App\Models\Author();
    // Ambil data lama agar field photo tidak hilang saat update
    $old = $authorModel->asObject()->first();
    $data = [
        'name' => $this->request->getPost('name'),
        'bio'  => $this->request->getPost('bio'),
        'content_type_id' => $this->request->getPost('content_type_id'),
        'photo' => $old && isset($old->photo) ? $old->photo : null
    ];
    $author = $authorModel->asObject()->first();
    if ($author) {
        // update existing author (replace)
        $update = $authorModel->update($author->id, $data);
        if ($update) {
            return $this->response->setJSON(['status'=>1, 'msg'=>'Berhasil update author']);
        } else {
            return $this->response->setJSON(['status'=>0, 'msg'=>'Gagal update author']);
        }
    } else {
        // if not exist, insert once only
        // Hapus semua data lain sebelum insert baru (jika ada duplikat)
        $authorModel->truncate();
        $insertId = $authorModel->insert($data);
        if ($insertId) {
            return $this->response->setJSON(['status'=>1, 'msg'=>'Berhasil menambah author', 'id'=>$insertId]);
        } else {
            return $this->response->setJSON(['status'=>0, 'msg'=>'Gagal menambah author']);
        }
    }
}

public function updateAuthorPhoto()
{
    $authorModel = new \App\Models\Author();
    $id = $this->request->getPost('id');
    $file = $this->request->getFile('user_profile_file');
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $newName = $file->getRandomName();
        $uploadPath = 'uploads/author/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Hapus foto lama jika ada
        $author = $authorModel->asObject()->first();
        if ($author && property_exists($author, 'photo') && $author->photo && file_exists($uploadPath . $author->photo)) {
            @unlink($uploadPath . $author->photo);
        }

        // Manipulasi gambar: resize ke max width 450px, crop rasio 16:9
        $imageService = \Config\Services::image();
        $imageService->withFile($file)
            ->resize(450, 253, true, 'width') // 16:9 rasio, 450x253
            ->save($uploadPath . $newName);

        // Update/replace baris author pertama (single row)
        if ($author) {
            $authorModel->update($author->id, ['photo' => $newName]);
            return $this->response->setJSON(['status'=>1, 'msg'=>'Foto berhasil diupdate', 'photo'=>$newName, 'id'=>$author->id]);
        } else {
            $insertId = $authorModel->insert(['photo' => $newName]);
            if ($insertId) {
                return $this->response->setJSON(['status'=>1, 'msg'=>'Foto berhasil diupload', 'photo'=>$newName, 'id'=>$insertId]);
            } else {
                return $this->response->setJSON(['status'=>0, 'msg'=>'Gagal upload foto']);
            }
        }
    }
    return $this->response->setJSON(['status'=>0, 'msg'=>'Gagal upload foto']);
}

public function deleteAuthorPhoto()
{
    $authorModel = new \App\Models\Author();
    $id = $this->request->getPost('id');
    $author = $authorModel->asObject()->find($id);
    if ($author && isset($author->photo) && $author->photo) {
        @unlink('uploads/author/'.$author->photo);
        $authorModel->update($id, ['photo'=>null]);
        return $this->response->setJSON(['status'=>1, 'msg'=>'Foto dihapus']);
    }
    return $this->response->setJSON(['status'=>0, 'msg'=>'Tidak ada foto']);
}

// ── ADSENSE ───────────────────────────────────────────────────────────────

public function adsense()
{
    $model = new \App\Models\AdsenseSetting();
    return view('backend/pages/adsense', [
        'pageTitle' => 'Pengaturan AdSense',
        'ads'       => $model->getSettings(),
    ]);
}

public function updateAdsense()
{
    $model = new \App\Models\AdsenseSetting();
    $ads   = $model->getSettings();

    $model->update($ads->id, [
        'publisher_id' => trim($this->request->getPost('publisher_id') ?? ''),
        'is_active'    => $this->request->getPost('is_active')    ? 1 : 0,
        'slot_header'  => trim($this->request->getPost('slot_header')  ?? ''),
        'slot_sidebar' => trim($this->request->getPost('slot_sidebar') ?? ''),
        'slot_content' => trim($this->request->getPost('slot_content') ?? ''),
        'show_header'  => $this->request->getPost('show_header')  ? 1 : 0,
        'show_sidebar' => $this->request->getPost('show_sidebar') ? 1 : 0,
        'show_content' => $this->request->getPost('show_content') ? 1 : 0,
    ]);

    return redirect()->route('admin.adsense')->with('success', 'Pengaturan AdSense berhasil disimpan.');
}

// ── PAGES ─────────────────────────────────────────────────────────────────

public function pages()
{
    $pageModel = new \App\Models\Page();
    return view('backend/pages/pages', [
        'pageTitle' => 'Halaman Statis',
        'pages'     => $pageModel->asObject()->orderBy('ordering', 'asc')->findAll(),
    ]);
}

public function addPage()
{
    return view('backend/pages/page-form', [
        'pageTitle' => 'Tambah Halaman',
        'page'      => null,
    ]);
}

public function createPage()
{
    $rules = [
        'title'   => 'required|max_length[255]',
        'content' => 'required',
    ];
    if (!$this->validate($rules)) {
        return view('backend/pages/page-form', [
            'pageTitle'  => 'Tambah Halaman',
            'page'       => null,
            'validation' => $this->validator,
        ]);
    }

    $slug = $this->_makePageSlug($this->request->getPost('title'));
    $pageModel = new \App\Models\Page();
    $pageModel->insert([
        'title'          => $this->request->getPost('title'),
        'slug'           => $slug,
        'content'        => $this->request->getPost('content'),
        'show_in_footer' => $this->request->getPost('show_in_footer') ? 1 : 0,
        'status'         => $this->request->getPost('status') ?? 1,
        'ordering'       => $this->request->getPost('ordering') ?? 100,
    ]);

    return redirect()->route('admin.pages')->with('success', 'Halaman berhasil ditambahkan.');
}

public function editPage($id)
{
    $pageModel = new \App\Models\Page();
    $page = $pageModel->asObject()->find($id);
    if (!$page) {
        return redirect()->route('admin.pages')->with('fail', 'Halaman tidak ditemukan.');
    }
    return view('backend/pages/page-form', [
        'pageTitle' => 'Edit Halaman',
        'page'      => $page,
    ]);
}

public function updatePage($id)
{
    $rules = [
        'title'   => 'required|max_length[255]',
        'content' => 'required',
    ];
    $pageModel = new \App\Models\Page();
    $page = $pageModel->asObject()->find($id);

    if (!$this->validate($rules)) {
        return view('backend/pages/page-form', [
            'pageTitle'  => 'Edit Halaman',
            'page'       => $page,
            'validation' => $this->validator,
        ]);
    }

    $pageModel->update($id, [
        'title'          => $this->request->getPost('title'),
        'slug'           => $this->_makePageSlug($this->request->getPost('title'), $id),
        'content'        => $this->request->getPost('content'),
        'show_in_footer' => $this->request->getPost('show_in_footer') ? 1 : 0,
        'status'         => $this->request->getPost('status') ?? 1,
        'ordering'       => $this->request->getPost('ordering') ?? 100,
    ]);

    return redirect()->route('admin.pages')->with('success', 'Halaman berhasil diperbarui.');
}

public function deletePage($id)
{
    $pageModel = new \App\Models\Page();
    $pageModel->delete($id);
    return redirect()->route('admin.pages')->with('success', 'Halaman berhasil dihapus.');
}

public function togglePageFooter($id)
{
    $pageModel = new \App\Models\Page();
    $page = $pageModel->find($id);
    if ($page) {
        $pageModel->update($id, ['show_in_footer' => $page['show_in_footer'] ? 0 : 1]);
        return $this->response->setJSON(['status' => 1, 'show_in_footer' => !$page['show_in_footer']]);
    }
    return $this->response->setJSON(['status' => 0]);
}

private function _makePageSlug(string $title, int $excludeId = 0): string
{
    $slug = url_title(strtolower($title), '-', true);
    $pageModel = new \App\Models\Page();
    $original  = $slug;
    $i = 1;
    while (true) {
        $q = $pageModel->where('slug', $slug);
        if ($excludeId) $q = $q->where('id !=', $excludeId);
        if (!$q->first()) break;
        $slug = $original . '-' . $i++;
    }
    return $slug;
}

}
