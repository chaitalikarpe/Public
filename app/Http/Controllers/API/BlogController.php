<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Blog;
use App\Models\Like;
use App\Traits\Likable;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Resources\BlogResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

Class BlogController extends BaseController 
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request): JsonResponse
    {
        // Check if the user is authenticated
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Access properties or methods on the authenticated user
        $user = optional(Auth::guard('sanctum')->user());
        
        $query = Blog::query();

        // Apply sorting based on the 'filter' parameter
        if ($request->has('filter') && $request->filter === 'most_liked') {
            $query = Blog::select('blogs.*')
            ->addSelect(DB::raw('COALESCE(COUNT(likes.id), 0) as most_liked'))  
            ->leftJoin('likes', function ($join) {
                $join->on('blogs.id', '=', 'likes.likeable_id')
                     ->where('likes.reaction', '=', 1);
            })
            ->groupBy('blogs.id')  // Group by the primary key of the blogs table
            ->orderByDesc('most_liked')
            ->orderByDesc('blogs.created_at');

            $blogs = $query->paginate(10);
        } else {
            $query->latest();
        }

        if ($request->has('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($subquery) use ($searchTerm) {
                $subquery->where('title', 'like', $searchTerm)
                         ->orWhere('description', 'like', $searchTerm);
            });
        }

        // Get paginated blog list
        $blogs = $query->withCount('likes')->paginate(10);

        // Add a custom attribute to each blog indicating whether it is liked by the logged-in user
        $blogs->each(function ($blog) use ($user) {
            if($blog->isLikedByUser($user->id) == true) {
                $blog->is_liked = true;
            }
            else {
                $blog->is_liked = false;
            }
        });
        $blogs->makeHidden('likes_count');
        return $this->sendResponse($blogs, 'Blogs retrieved successfully.');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        // Check if the user is authenticated
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Access properties or methods on the authenticated user
        $user = optional(Auth::guard('sanctum')->user());

        $input = $request->all();
        
        $validator = Validator::make($input, [
            'title' => 'required' ,
            'description' => 'required' ,
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $image_path = $request->file('image')->store('image', 'public');

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->image = $image_path;
        $blog->save();
   
        return $this->sendResponse(new BlogResource($blog), 'Blog created successfully.');
    } 

    /**
     * update a created blog.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function togglelike(Request $request): JsonResponse
    {
        // Check if the user is authenticated
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Access properties or methods on the authenticated user
        $user = optional(Auth::guard('sanctum')->user());

        $blog_id = $request->id;
        $blog = Blog::find($blog_id);

        // Check if the user has already liked the blog
        $like = $blog->likes()->where('user_id', $user->id)->first();

        //create the blog like entry
        if($like == Null) {
            $blog->likes()->create([
                'user_id' => $user->id 
            ]);
            $blog->likes()->update([
                'reaction' => 1, 
            ]);
            return $this->sendResponse($blog->likes,'You have liked this blog');
        }
        else if($like->user_id == $user->id && $like->likeable_id == $blog_id && $like->reaction == 0) {
            $blog->likes()->update([
                'reaction' => 1, 
            ]);
            return $this->sendResponse($blog->likes,'You have liked this blog');
        }
        else if($like->user_id == $user->id && $like->likeable_id == $blog_id && $like->reaction == 1) {
            $blog->likes()->update([
                'reaction' => 0, 
            ]);
            return $this->sendResponse($blog->likes,'You have disliked this blog');
        }
        else {
            return response()->json(['message' => 'Invalid request']);
        }
    }

}