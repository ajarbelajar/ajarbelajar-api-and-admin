<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Category;
use App\Model\RequestPost;

class VideoController extends Controller
{
    const driver = 'public';

    private function getVideo($user, $id){
        return $user->requestPosts()
                    ->whereNull('requested_at')
                    ->where('type', 'video')
                    ->findOrFail($id);
    }

    public function index(Request $request)
    {
        $videos = $request->user()->requestPosts()->whereNull('requested_at')->where('type', 'video')->orderBy('updated_at', 'desc')->paginate(12);
        return view('web.dashboard.video.index', ['videos' => $videos]);
    }
    
    public function store(Request $request)
    {
        
        $data = $request->validate([ 'title' => 'required|string|min:10|max:160', 'type' => 'video' ]);
        $video = new RequestPost($data);
        $request->user()->requestPosts()->save($video);
        return redirect()->route('dashboard.video.edit', $video->id);
    }

    public function edit(Request $request, $id)
    {
        $video = $this->getVideo($request->user(), $id);
        $categories = Category::all();
        return view('web.dashboard.video.edit', ['video' => $video, 'categories' => $categories]);
    }

    public function update(Request $request, $id)
    {
        $video = $this->getVideo($request->user(), $id);
        $data = $request->validate([
            'title' => 'required|string|min:10|max:160',
            'description' => 'nullable|min:30|max:300',
            'category_id' => 'nullable|numeric|exists:categories,id',
            'videos' => 'nullable|url|max:250',
            'hero' => 'nullable|image|max:4000'
        ]);

        if(isset($data['hero'])) {
            if($video->hero) {
                if(Storage::disk(self::driver)->exists('post/hero/request/' . $video->hero)) {
                    Storage::disk(self::driver)->delete('post/hero/request/' . $video->hero);
                }
                if(Storage::disk(self::driver)->exists('post/hero/request/thumb/' . $video->hero)) {
                    Storage::disk(self::driver)->delete('post/hero/request/thumb/' . $video->hero);
                }
            }
            $lg = Image::make($data['hero'])->fit(720*1.5, 480*1.5, function ($constraint) {
                $constraint->aspectRatio();
            });
            $sm = Image::make($data['hero'])->fit(720/1.5, 480/1.5, function ($constraint) {
                $constraint->aspectRatio();
            });

            $name = Str::random(60) . '.jpg';

            Storage::disk(self::driver)->put('post/hero/request/' . $name, (string) $lg->encode('jpg', 90));
            Storage::disk(self::driver)->put('post/hero/request/thumb/' . $name, (string) $sm->encode('jpg', 90));

            $data['hero'] = $name;
        } else {
            unset($data['hero']);
        }

        $video->update($data);
        return redirect()->back()->withSuccess('Video berhasil di update.');
    }

    public function destroy(Request $request, $id)
    {
        $video = $this->getVideo($request->user(), $id);
        if($video->hero) {
            if(Storage::disk(self::driver)->exists('post/hero/request/' . $video->hero)) {
                Storage::disk(self::driver)->delete('post/hero/request/' . $video->hero);
            }
            if(Storage::disk(self::driver)->exists('post/hero/request/thumb/' . $video->hero)) {
                Storage::disk(self::driver)->delete('post/hero/request/thumb/' . $video->hero);
            }
        }
        $video->delete();
        return redirect()->back()->withSuccess('Video berhasil di hapus.');
    }
}
