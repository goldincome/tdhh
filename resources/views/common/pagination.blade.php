<div class="py-4 text-sm flex">

    <div class="w-full hidden my-auto sm:w-1/2 sm:block">
        @if($properties->total() > 0)
            Showing {{ $properties->firstItem() }} - {{ $properties->lastItem() }} items out of {{ $properties->total() }}
        @else
            No items to display
        @endif
    </div>

    <div class="w-full sm:w-1/2 my-auto">
        <div class="flex justify-end">
            <div class="mr-4 my-auto">
                {{$properties->currentPage()}} of {{$properties->lastPage()}}
            </div>

            <div class="pagination">
                @if(!$properties->onFirstPage())
                    <a href="{{ $properties->previousPageUrl() }}" class="pagination-items group">
                        <div class="fa fa-angle-left pagination-items-icon"></div>
                        <div>
                            Prev
                        </div>
                    </a>
                @else
                    <div class="pagination-items group disabled">
                        <div class="fa fa-angle-left pagination-items-icon disabled"></div>
                        <div>
                            Prev
                        </div>
                    </div>
                @endif
                

                @if($properties->hasMorePages())
                    <a href="{{ $properties->nextPageUrl() }}" class="pagination-items group">
                        <div>
                            Next
                        </div>
                        <div class="fa fa-angle-right pagination-items-icon text-right"></div>
                    </a>
                @else
                
                    <div class="pagination-items group disabled">
                        <div>
                            Next
                        </div>
                        <div class="fa fa-angle-right pagination-items-icon text-right disabled"></div>
                    </div>
                @endif
            </div>

        </div>
    </div>