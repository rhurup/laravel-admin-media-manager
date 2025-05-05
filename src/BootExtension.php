<?php

namespace Encore\Admin\Media;

use Encore\Admin\Admin;

trait BootExtension
{
    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        static::registerRoutes();

        Admin::extend('media-manager', __CLASS__);
    }

    /**
     * Register routes for laravel-admin.
     *
     * @return void
     */
    protected static function registerRoutes()
    {
        parent::routes(function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            $router->get('media', [\Encore\Admin\Media\MediaController::class, 'index'])->name('media-index');
            $router->get('media/download', [\Encore\Admin\Media\MediaController::class, 'download'])->name('media-download');
            $router->delete('media/delete', [\Encore\Admin\Media\MediaController::class, 'delete'])->name('media-delete');
            $router->put('media/move', [\Encore\Admin\Media\MediaController::class, 'move'])->name('media-move');
            $router->post('media/upload', [\Encore\Admin\Media\MediaController::class, 'upload'])->name('media-upload');
            $router->post('media/folder', [\Encore\Admin\Media\MediaController::class, 'newFolder'])->name('media-new-folder');
            $router->get('files', [\Encore\Admin\Media\MediaController::class, 'viewFiles'])->name("media-editor-list");
            $router->post('files', [\Encore\Admin\Media\MediaController::class, 'uploadFiles'])->name("media-editor-upload");
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('Media manager', 'media', 'fa-file');

        parent::createPermission('Media manager', 'ext.media-manager', 'media*');
    }
}
