<?php

namespace Encore\Admin\Media;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        return Admin::content(function (Content $content) use ($request) {
            $content->header('Media manager');

            $path = $request->get('path', '/');
            $view = $request->get('view', 'table');

            $manager = new MediaManager($path);

            $content->body(view("laravel-admin-media::$view", [
                'list'   => $manager->ls(),
                'nav'    => $manager->navigation(),
                'url'    => $manager->urls(),
            ]));
        });
    }

    public function download(Request $request)
    {
        $file = $request->get('file');

        $manager = new MediaManager($file);

        try {
            return $manager->download();
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function upload(Request $request)
    {
        $files = $request->file('files');
        $dir = $request->get('dir', '/');

        $manager = new MediaManager($dir);

        try {
            if ($manager->upload($files)) {
                admin_toastr(trans('admin.upload_succeeded'));
            }
        } catch (\Exception $e) {
            admin_toastr($e->getMessage(), 'error');
        }

        return back();
    }

    public function delete(Request $request)
    {
        $files = $request->get('files');

        $manager = new MediaManager();

        try {
            if ($manager->delete($files)) {
                return response()->json([
                    'status'  => true,
                    'message' => trans('admin.delete_succeeded'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function move(Request $request)
    {
        $path = $request->get('path');
        $new = $request->get('new');

        $manager = new MediaManager($path);

        try {
            if ($manager->move($new)) {
                return response()->json([
                    'status'  => true,
                    'message' => trans('admin.move_succeeded'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function newFolder(Request $request)
    {
        $dir = $request->get('dir');
        $name = $request->get('name');

        $manager = new MediaManager($dir);

        try {
            if ($manager->newFolder($name)) {
                return response()->json([
                    'status'  => true,
                    'message' => trans('admin.move_succeeded'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function uploadFiles(Request $request){
        $file = $request->file("file");
        $path = Str::of($request->get("path", "storage"))->replace("storage/", "")->toString();
        if($path == "storage"){
            $path = '/';
        }
        $file->storePubliclyAs($path, $file->getClientOriginalName());

        return $this->viewFiles($request);
    }

    public function viewFiles(Request $request){
        $path = $request->get("path", "storage");

        $returnpath = Str::of($path)->rtrim('/')->ltrim('/')->beforeLast("/")->toString();

        $root_path = public_path();
        $list_path = public_path($path);

        $rii = new \RecursiveDirectoryIterator($list_path);
        $rii->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);
        $files = array();
        $folders = array();

        /** @var \SplFileInfo $file */
        foreach ($rii as $file) {
            $file_path = str_replace($root_path, "", $file->getPathname());
            if ($file->isDir()){
                $folders[] = $file_path;
                continue;
            }
            if(Str::of($file_path)->contains([".DS_Store", ".gitignore"])){
                continue;
            }
            $files[] = $file_path;
        }

        usort($folders, 'strnatcasecmp');
        usort($files, 'strnatcasecmp');

        // Direct rendering view, Since v1.6.12
        return view('admin.files', ['files' => $files, 'folders' => $folders, 'path' => $path, 'returnpath' => $returnpath]);
    }
}
