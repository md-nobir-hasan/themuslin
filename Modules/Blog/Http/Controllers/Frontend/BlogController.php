<?php

namespace Modules\Blog\Http\Controllers\Frontend;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use App\Mail\BasicMail;
use App\Traits\SeoDataConfig;
use Artesaos\SEOTools\SEOMeta;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Modules\Blog\Entities\BlogComment;

class BlogController extends Controller
{
    use SEOToolsTrait, SeoDataConfig;

    private const BASE_PATH = 'blog::landlord.frontend.blog.';

    public function blog_single($slug)
    {
        $blog_post = Blog::with(['user', 'category', 'comments'])->where(['slug' => $slug, 'status' => 1])->firstOrFail();
        $blog_comments = BlogComment::where(['blog_id' => $blog_post->id, 'parent_id' => null])->orderByDesc('created_at')->take(3)->get();

        $this->setMetaDataInfo($blog_post);

        return view(self::BASE_PATH . 'blog-single', compact('blog_post', 'blog_comments'));
    }

    public function category_wise_blog_page($id)
    {
        if (empty($id)) {
            abort(404);
        }

        $all_blogs = Blog::usingLocale(GlobalLanguage::default_slug())->where(['category_id' => $id, 'status' => 1])->orderBy('id', 'desc')->paginate(get_static_option('category_page_item_show'));
        $category = BlogCategory::where(['id' => $id, 'status' => 1])->first();
        $category_name = $category->getTranslation('title', get_user_lang());

        return view(self::BASE_PATH . 'blog-category')->with([
            'all_blogs' => $all_blogs,
            'category_name' => $category_name,
        ]);
    }


    public function blog_search_page(Request $request)
    {
        $request->validate([
            'search' => 'required'
        ],
            ['search.required' => 'Enter anything to search']);

        $all_blogs = Blog::Where('title', 'LIKE', '%' . $request->search . '%')
            ->orderBy('id', 'desc')->paginate(get_static_option('blog_search_item_show'));

        return view(self::BASE_PATH . 'blog-search')->with([
            'all_blogs' => $all_blogs,
            'search_term' => $request->search,
        ]);
    }

    public function tags_wise_blog_page($tag)
    {
        $all_blogs = Blog::Where('tags', 'LIKE', '%' . $tag . '%')
            ->orderBy('id', 'desc')->paginate(4);

        return view(self::BASE_PATH . 'blog-tags')->with([
            'all_blogs' => $all_blogs,
            'tag_name' => $tag,

        ]);
    }

    public function blog_comment_store(Request $request)
    {
        $request->validate([
            'comment_content' => 'required|string'
        ]);

        $content = BlogComment::create([
            'blog_id' => $request->blog_id,
            'user_id' => $request->user_id,
            'parent_id' => $request->comment_id ?? null,
            'commented_by' => $request->commented_by,
            'comment_content' => SanitizeInput::esc_html($request->comment_content),
        ]);

        $sub = __('You have a comment from') . ' ' . get_static_option('site_title');
        $message = __('you have a new comment submitted by') . ' ' . Auth::user()->name . ' ' . __('Email') . ' ' . Auth::user()->email . ' .' . __('check admin panel for more info');

        try {
            \Mail::to(get_static_option('site_global_email'))->send(new BasicMail($sub, $message));
        } catch (\Exception $e) {

        }

        return response()->json([
            'msg' => __('Your comment sent succefully'),
            'type' => 'success',
            'status' => 'ok',
            'content' => $content,
        ]);
    }

    public function load_more_blogs_ajax(Request $request)
    {
        $user_lang = GlobalLanguage::user_lang_slug();

        $posts = $request->posts;

        $results = Blog::query();
        if (!empty($request->category)) {
            $results = $results->where('category_id', $request->category)->orderBy($request->order_by, $request->order)->skip($posts - 6)->take($posts)->get();
        } else {
            $results = $results->orderBy($request->order_by, $request->order)->skip($posts - 6)->take($posts)->get();
        }

        $artilces = '';
        if ($request->ajax()) {
            foreach ($results as $result) {
                $blog_slug = route('landlord.frontend.blog.single', $result->slug);
                $image = render_image_markup_by_attachment_id($result->image ?? '', '', 'full', false);
                $title = Str::words($result->getTranslation('title', $user_lang), 8) ?? '';
                $date = $result->created_at->format('d M Y');
                $keep_reading_text = __('Keep Reading');

                $markup = <<<HTML
<div class="col-lg-4 col-md-6 mt-4">
                        <div class="single-blog">
                            <div class="single-blog-thumb">
                                <a href="{$blog_slug}">
                                    {$image}
                                </a>
                            </div>
                            <div class="single-blog-contents mt-4">
                                <h2 class="single-blog-contents-title">
                                    <a href="{$blog_slug}"> {$title} </a>
                                </h2>
                                <div class="single-blog-contents-bottom mt-4">
                                    <a href="{$blog_slug}" class="reading-btn"> {$keep_reading_text} <i class="las la-arrow-right"></i> </a>
                                    <span class="min-reading"> {$date} </span>
                                </div>
                            </div>
                        </div>
                    </div>
HTML;
                $artilces .= $markup;
            }
            return $artilces;
        }
    }


    public function load_more_comments(Request $request)
    {
        if ($request->type == 'load-one') {
            $items = $request->items - 4;
            $take = 1;
        } else {
            $items = $request->items;
            $take = 5;
        }

        $all_comment = BlogComment::with(['blog', 'user', 'reply'])
            ->where('parent_id', null)
            ->orderBy('id', 'desc')
            ->skip($items)
            ->take($take)
            ->get();

        $markup = '';
        foreach ($all_comment as $item) {
            $commented_user_image = render_image_markup_by_attachment_id(get_static_option('blog_avater_image'), '', 'thumb');

            $var_data_parent_name = optional($item->user)->name;
            $title = optional($item->user)->name ?? '';
            $created_at = date('d F Y', strtotime($item->created_at ?? ''));
            $comment_content = $item->comment_content;
            $data_id = $item->id;
            $replay_mark = '';
            $replay_text = __('Reply');


            $replay_mark .= <<<REPLA
            <a href="#1" data-comment_id="{$data_id}" class="reply-btn btn-replay"><i class="las la-reply icon"></i>{$replay_text}</a>
REPLA;

            $repl = auth('web')->check() ? $replay_mark : '';

            $child_data = '';
            foreach ($item->reply as $repData) {
                $child_image = render_image_markup_by_attachment_id(optional($repData->user)->image ?? get_static_option('blog_avater_image'), '', '', false);
                $child_user_name = optional($repData->user)->name ?? '';
                $child_commented_date = date('d F Y', strtotime($repData->created_at ?? ''));
                $child_comment = $repData->comment_content ?? '';


                $child_data .= <<<CHILDDATA
    <ul class="comment-list wow fadeInLeft" data-wow-delay=".2s">
                                <li>
                                    <div class="blog-details-flex-content">
                                        <div class="blog-details-thumb radius-10">
                                            {$child_image}
                                        </div>
                                        <div class="blog-details-content">
                                            <div class="blog-details-content-flex">
                                                <div class="blog-details-content-item">
                                                    <h5 class="blog-details-content-title"><a
                                                            href="#1"> {$child_user_name} </a></h5>
                                                    <span class="blog-details-content-date"> {$child_commented_date} </span>
                                                </div>

                                            </div>
                                            <p class="blog-details-content-para"> {$child_comment} </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
CHILDDATA;
            }


            $markup .= <<<MARKUP
<li>
    <div class="blog-details-flex-content">
                                <div class="blog-details-thumb radius-10">
                                    {$commented_user_image}
                                </div>
                                <div class="blog-details-content">
                                    <div class="blog-details-content-flex">
                                        <div class="blog-details-content-item">
                                            <h5 class="blog-details-content-title"><a
                                                    href="#1" data-parent_name="{$var_data_parent_name}"> {$title} </a>
                                            </h5>
                                            <span
                                                class="blog-details-content-date"> {$created_at} </span>
                                        </div>

                                        {$repl}
                                    </div>
                                    <p class="blog-details-content-para"> {$comment_content} </p>
                                </div>
                            </div>

                            {$child_data}
                            </li>
MARKUP;
        }

        return response()->json(['blogComments' => $all_comment, 'markup' => $markup]);
    }
}
