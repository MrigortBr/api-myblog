<?php
namespace App\Service;

use App\Models\Posts;
use App\Utils\RequestUtility;
use Illuminate\Http\Request;
use App\Exceptions\CustomResponses;
use App\Models\Categories;
use App\Utils\MyPostDTO;
use App\Utils\PostDTO;
use App\Utils\PostTitleDTO;

class PostsService{

    protected $requestUtility;

    public function __construct( RequestUtility $requestUtility){
        $this->requestUtility =  $requestUtility;
    }

    public function create(Request $request){
        $data = $this->requestUtility->validatePostCreate($request);

        if (!$data) return;

        $idCategory = CategoryService::findOrCreateCategory($data['category']);
        $user = $this->requestUtility->getUserFromToken($request);

        $published_at = null;

        if ($data['status'] == 'published') $published_at = now();

        $newPost = Posts::Create([
            'title' => $data['title'],
            'content' => $data['content'],
            'status' => $data['status'],
            'category_id' => $idCategory,
            'user_id' => $user['id'],
            'published_at' => $published_at,
            'updated_at' => now()
        ]);

        CustomResponses::PostCreated($newPost->id);
    }

    public function show(Request $request){
        $countForPage = intval(env('pageSize'));
        $category = $request->get('category');

        $posts = [];


        $query = Posts::where('status', 'published');

        if ($category){
            $category = CategoryService::findCategory($category);
            if ($category){
                $query->where('category_id', $category['id']);
            }else{
                CustomResponses::NoCategory();
                return;
            }
        }

        $posts = $query->paginate($countForPage);

        if (!$posts->isEmpty()){
            $postDTOs = $posts->map(function ($post) use ($category) {
                $category = Categories::find($post['category_id']);
                return new PostTitleDTO($post, $category);
            });

            $data =[
                'data' => $postDTOs,
                'current_page' => $posts->currentPage(),
                'total_pages' => $posts->lastPage(),
                'total_items' => $posts->total(),
            ];
            CustomResponses::ShowPosts($data);
        }else{
            CustomResponses::NoPosts();
        }
    }

    public function listById(Request $request, $id){
        $post = Posts::find($id);

        $user = $this->requestUtility->getUserFromToken($request);

        if (json_encode($post) === 'null'){
            return CustomResponses::UnablePost();
        }

        if ($post['status'] == 'draft'){
            if($post['user_id'] != $user['id']){
                return CustomResponses::UnablePost();
            }
        }

        if (!$post) {
            return CustomResponses::UnablePost();
        }

        if (!$post->category_id) {
            return CustomResponses::NoCategory();
        }

        $category = CategoryService::findCategoryById($post->category_id);
        $comments = CommentsService::getCommentsByPost($request ,$post->id);
        $postDTO = new PostDTO($post, $category, $comments);

        return CustomResponses::ShowPosts($postDTO);
    }

    public function listMyPosts(Request $request){
        $myID =  AuthService::getUserIdFromToken($request);

        $myPosts = Posts::where('user_id', $myID)->leftjoin('categories', 'categories.id', '=', 'posts.category_id')->select('posts.*', 'categories.name', 'categories.id as categoryid')->get();


        if ($myPosts->isEmpty()) {
            return CustomResponses::YouNoHavePost();
        }

        $postsDTO = [];

        foreach ($myPosts as $post) {
            $comments = CommentsService::getCommentsByPost($request ,$post->id);
            $postsDTO[] = new MyPostDTO($post, $comments);
        }

        return response()->json($postsDTO)->send();


    }

    public function update(Request $request, $id){
        // Validação dos dados recebidos na requisição
        $data = $this->requestUtility->validatePostUpdate($request);
        if (!$data) return;

        $post = Posts::find($id);

        if (!$post) {
            return CustomResponses::UnablePost();
        }

        if (!$this->iCanChangeThisPost($request, $post)) return CustomResponses::UnablePost();

        $updated = false;

        if (isset($data['title']) && $post->title != $data['title']) {
            $post->title = $data['title'];
            $updated = true;
        }

        if (isset($data['content']) && $post->content != $data['content']) {
            $post->content = $data['content'];
            $updated = true;
        }

        if (isset($data['status']) && $post->status != $data['status']) {
            $post->status = $data['status'];
            if ($data['status'] === 'published') {
                $post->published_at = now();
            } elseif ($data['status'] === 'draft') {
                $post->published_at = null;
            }
            $updated = true;
        }


        if (isset($data['category'])){
            $category = CategoryService::findOrCreateCategory($data['category']);

            if ($post->category_id != $category) {
                $post->category_id = $category;
                $updated = true;
            }
        }


        if ($updated) {
            $post->updated_at = now();
            $post->save();
            return CustomResponses::PostUpdated();
        }

        return CustomResponses::PostNoUpdated();
    }

    public function delete(Request $request,$idPost){
        $post = Posts::find($idPost);

        if (!$post) {
            return CustomResponses::UnablePost();
        }

        if (!$this->iCanChangeThisPost($request, $post)) return CustomResponses::UnablePost();

        $post->delete();
        return CustomResponses::PostDeleted();
    }

    private function iCanChangeThisPost($request ,$post){
        $user = $this->requestUtility->getUserFromToken($request);
        if ($post->user_id !== $user->id) {
            return false;
        }else{
            return true;
        }

    }

}
