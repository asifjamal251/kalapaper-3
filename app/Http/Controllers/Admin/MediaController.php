<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Media\MediaCollection;
use App\Models\Media;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
   
    public function index(Request $request)
    {
       
        if ($request->ajax()) {
            $datas = Media::orderBy('created_at', 'desc')->select('id','file','type','name','original_name','size');
            $totaldata = $datas->count();

            $search = $request->search['value'];

            if ($search) {
                $datas->where('name', 'like', '%'.$search.'%');
            }
            
            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();
            return response()->json(new MediaCollection($datas));
           
        }
        return view('admin.media.list');
    }

    public function create(Request $request )
    {
        return view('admin.media.create');
    }

    public function show(Request $request )
    {   
       
    }

    public function store(Request $request)
{
    $publicKey = env('AR_PUBLIC_KEY');
    $secretKey = env('AR_SECRET_KEY');
    $apiUrl = env('AR_URL');
    $file = $request->file('file');

    if (!$file) {
        return response()->json(['error' => 'No file uploaded.'], 400);
    }

    if (empty($publicKey) || empty($secretKey)) {
        return $this->handleLocalUpload($file);
    }

    return $this->handleApiUpload($file, $publicKey, $secretKey, $apiUrl);
}

private function handleLocalUpload($file)
{
    $media = new Media;
    $mediaName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $mediaExt = $file->getClientOriginalExtension();
    $mediaSize = $this->formatBytes($file->getSize());

    $media->name = $mediaName;
    $media->original_name = $file->getClientOriginalName();
    $media->type = $mediaExt;
    $media->handle = Str::slug($mediaName, '-');
    $media->size = $mediaSize;
    $media->save();

    $storageType = env('FILESYSTEM_DISK');
    $mediaRename = $media->slug . '.' . $mediaExt;
    $path = $file->storeAs('media', $mediaRename);

    $fullPath = Storage::path($path);

    $iconName = $media->slug . '_icon.' . $mediaExt;
    $iconPath = Storage::path('media/' . $iconName);

    $this->resizeImage($fullPath, $iconPath, 60, 60);

    $media->file = $storageType === 's3' ? config('printing.media_url') . $path : 'storage/' . $path;
    $media->icon = $storageType === 's3' ? config('printing.media_url') . 'media/' . $iconName : 'storage/media/' . $iconName;
    $media->save();

    return response()->json(['success' => true, 'message' => 'File Uploaded Successfully', 'class' => 'success']);
}

private function handleApiUpload($file, $publicKey, $secretKey, $apiUrl)
{
    try {
        $response = Http::withHeaders([
            'public_key' => $publicKey,
            'secret_key' => $secretKey,
        ])->attach(
            'file',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post("{$apiUrl}/upload");

        if (!$response->successful()) {
            return response()->json(['error' => 'File upload failed, please try again.'], $response->status());
        }

        $data = $response->json()['datas'];

        Media::create([
            'name' => $data['name'],
            'file' => $data['url'],
            'icon' => $data['url'],  
            'type' => $data['type'],
            'handle' => $data['handle'],
            'size' => $data['size'],
        ]);

        return response()->json(['success' => true, 'message' => 'File Uploaded Successfully', 'class' => 'success']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Something went wrong, please try again later.'], 500);
    }
}

private function resizeImage($sourcePath, $targetPath, $width, $height)
{
    $info = getimagesize($sourcePath);
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $image = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($sourcePath);
            break;
        default:
            return;
    }

    $newImage = imagecreatetruecolor($width, $height);
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));

    switch ($mime) {
        case 'image/jpeg':
            imagejpeg($newImage, $targetPath, 90);
            break;
        case 'image/png':
            imagepng($newImage, $targetPath, 9);
            break;
        case 'image/gif':
            imagegif($newImage, $targetPath);
            break;
    }

    imagedestroy($image);
    imagedestroy($newImage);
}

private function formatBytes($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}

    public function edit(Request $request, Slider $slider)
    {
        return view('admin.slider.edit', compact('slider')); 
    }

    public function update(Request $request, Slider $slider)
    {
        $this->validate($request,[
                // 'title'=>'required',
                // 'sub_title'=>'required',
                // 'button_text'=>'required',
                // 'button_link'=>'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:4000',    
            ]);
          
            $slider->title = $request->title;
            $slider->body = $request->description;
            $slider->button_text = $request->button_text;
            $slider->button_link = $request->button_link;
            $slider->status = $request->status;

            if($request->hasFile('image')){
                $image_name = time().'.'.$request->file('image')->getClientOriginalExtension();
                $image = $request->file('image')->storeAs('slider', $image_name);
                $slider->image = 'storage/'.$image;
            }  

            if($slider->save()){ 
                return redirect()->route('admin.slider.index')->with(['class' => 'success','message' => 'Slider Updated successfully.']);
            }

            return redirect()->back()->with(['class' => 'error','message' => 'Whoops, looks like something went wrong ! Try again ...']);
    }

    public function destroy(Request $request, $id)
    {
        $media = Media::find($id);

        if($media->delete()){
            
            return response()->json(['message' => 'Media deleted successfully ...', 'class' => 'bg-success', 'error' => false, 'title' => 'Item Deleted!', 'timer' => 2000]);

        }
        return response()->json(['message' => 'Whoops, looks like something went wrong ! Try again ...', 'class' => 'error']);
    }

    public function getAllMediaSingle(Request $request){
        
        // if ($request->search != '') {
        //     $medias = Media::orderBy('created_at', 'desc')->where('name', 'like', '%'.$request->search.'%')->select('id', 'file', 'name')->paginate(10);
        // }
        // else{
        //     $medias = Media::orderBy('created_at', 'desc')->select('id', 'file', 'name')->paginate(10);
        // }

        $medias = Media::orderBy('created_at', 'desc')->when($request->has('search'),function($q)use($request){
            return $q->where('name','like','%'.$request->get('search').'%');
        })->paginate(10);

        if ($request->ajax()) {

            $type = $request->opentype;

            return view('admin.media.ajax-list', compact('medias','type'));

            $html = '';

            if ($request->opentype == 'single') {
                foreach ($medias as $media) {
                    $current_page = $medias->currentPage();
                    $last_page = $medias->lastPage();
                    if($current_page == $last_page){
                        $html .= '<li current="'.$current_page.'" last="'.$last_page.'" class="d-inline-block get-all-media">
                        <input type="radio" name="media[]" id="mediaid'.$media->id.'" value="'.$media->id.'"/><label for="mediaid'.$media->id.'" id="getmedia-'.$media->id.'"><img class="d-block" src="'.asset($media->file).'" alt="'.$media->name.'"></label></li><script>$(".no-more").show();$("#load-more-mediafiles").hide();</script>';
                    }
                    else{
                        $html .= '<li current="'.$current_page.'" last="'.$last_page.'" class="d-inline-block get-all-media">
                        <input type="radio" name="media[]" id="mediaid'.$media->id.'" value="'.$media->id.'"/><label for="mediaid'.$media->id.'" id="getmedia-'.$media->id.'"><img class="d-block" src="'.asset($media->file).'" alt="'.$media->name.'"></label></li>';
                    }
                    
                }
            }
            else{
                foreach ($medias as $media) {
                    $current_page = $medias->currentPage();
                    $last_page = $medias->lastPage();
                    if($current_page == $last_page){
                        $html .= '<li current="'.$current_page.'" last="'.$last_page.'" class="d-inline-block get-all-media">
                        <input type="checkbox" name="media[]" id="mediaid'.$media->id.'" value="'.$media->id.'"/><label for="mediaid'.$media->id.'" id="getmedia-'.$media->id.'"><img class="d-block" src="'.asset($media->file).'" alt="'.$media->name.'"></label></li><script>$(".no-more").show();$("#load-more-mediafiles").hide();</script>';
                    }
                    else{
                        $html .= '<li current="'.$current_page.'" last="'.$last_page.'" class="d-inline-block get-all-media">
                        <input type="checkbox" name="media[]" id="mediaid'.$media->id.'" value="'.$media->id.'"/><label for="mediaid'.$media->id.'" id="getmedia-'.$media->id.'"><img class="d-block" src="'.asset($media->file).'" alt="'.$media->name.'"></label></li>';
                    }
                    
                }
            }

            return $html;
        }
        return view('admin.media.select-media-single');
    }

    public function getAllMediaMultiple(Request $request){
        $medias = Media::orderBy('created_at', 'desc')->select('id', 'file', 'name')->paginate(10);
        if ($request->ajax()) {
            $html = '';
            foreach ($medias as $media) {
                $current_page = $medias->currentPage();
                $last_page = $medias->lastPage();
                if($current_page == $last_page){
                    $html .= '<li current="'.$current_page.'" last="'.$last_page.'" class="d-inline-block get-all-media">
                    <input type="checkbox" name="media[]" id="mediaid'.$media->id.'" value="'.$media->id.'"/><label for="mediaid'.$media->id.'" id="getmedia-'.$media->id.'"><img class="d-block" src="'.asset($media->file).'" alt="'.$media->name.'"></label></li><script>$(".no-more").show();$("#load-more-multiple").hide();</script>';
                }
                else{
                    $html .= '<li current="'.$current_page.'" last="'.$last_page.'" class="d-inline-block get-all-media">
                    <input type="checkbox" name="media[]" id="mediaid'.$media->id.'" value="'.$media->id.'"/><label for="mediaid'.$media->id.'" id="getmedia-'.$media->id.'"><img class="d-block" src="'.asset($media->file).'" alt="'.$media->name.'"></label></li>';
                }
                
            }
            return $html;
        }
        return view('admin.media.select-media-multiple');
    }
}
