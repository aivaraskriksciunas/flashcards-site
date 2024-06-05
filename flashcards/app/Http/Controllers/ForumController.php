<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ForumPost;
use App\Services\DataTable\DataTable;

class ForumController extends Controller
{
    /**
     * Show forum posts
     */
    public function index( Request $request )
    {
        $datatable = new DataTable([ 'title', 'created_at' ]);
        $datatable->applyUserFilters( ForumPost::query(), $request );

        return view(
            'forum.index',
            [ 'posts' => $datatable->getPaginated() ]
        );
    }
}
