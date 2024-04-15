<?php 

namespace App\Services\DataTable;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Helper class to apply user filters passed as query params 
 * Meant to be used with DataTables but can be applied when filtering, sorting or searching is needed
 */
class DataTable 
{
    private Builder|Relation $query;

    private int $page = 1;
    private int $per_page = 15;

    /**
     * Create DataTable instance
     *
     * @param array $sortable Limits the columns user can order
     */
    public function __construct(
        private array $sortable = [],
        private array $searchable = [],
        private int $max_page_size = 50,
    )
    {
        
    }

    /**
     * Apply user filters to the provided query
     *
     * @param Builder $query
     * @param Request $request
     * @return DataTable
     */
    public function applyUserFilters( Builder|Relation $query, Request $request ) : DataTable
    {
        $this->query = $query;

        $this->search( $request );

        $this->sort( $request );

        $this->storePaginatorParams( $request );

        return $this;
    }

    public function getQuery() : Builder|Relation 
    {
        return $this->query;
    }

    public function getPaginated() : Paginator
    {
        return $this->query->paginate( $this->per_page, page:$this->page )
            ->withQueryString();
    }

    private function search( Request $request )
    {
        $search = $request->query( 'search', '' );
        $searchable = $this->searchable;

        if ( count( $searchable ) == 0 ) return;

        // Filter by search fields
        $this->query->where( function ( $query ) use ( $search, $searchable ) {
            foreach ( $searchable as $col ) 
            {
                $query->orWhere( $col, 'like', "$search%" );
            }
        });
    }

    private function sort( Request $request )
    {
        $sort = $request->query( 'sort', null );

        if ( $sort == null ) return;

        // Determine the sort order
        $sort_order = 'ASC';
        if ( $sort[0] == '-' ) 
        {
            $sort = substr( $sort, 1 );
            $sort_order = 'DESC';
        }

        // Check if this column can be sorted
        if ( !in_array( $sort, $this->sortable ) ) return;

        // Apply order
        $this->query->orderBy( $sort, $sort_order );
    }

    private function storePaginatorParams( Request $request )
    {
        $per_page = $request->query( 'per_page', null );
        $page = $request->query( 'page', null );

        if ( is_numeric( $per_page ) )
        {
            $this->per_page = min( $this->max_page_size, max( 5, (int)$per_page ) );
        }
        if ( is_numeric( $page ) )
        {
            $this->page = max( 1, (int)$page );
        }
    }
}