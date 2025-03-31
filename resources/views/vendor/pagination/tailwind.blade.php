@if ($paginator->hasPages())
<div class="flex items-center justify-end w-full space-x-4 mt-8">
    <!-- Previous Button -->
    @if ($paginator->onFirstPage())
        <button class="flex items-center px-2 py-1 text-gray-400 cursor-not-allowed">
            <i class="ri-arrow-left-s-line"></i>
            <span class="ml-1">Prev</span>
        </button>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="flex items-center px-2 py-1 text-gray-500 hover:text-black focus:outline-none">
            <i class="ri-arrow-left-s-line"></i>
            <span class="ml-1">Prev</span>
        </a>
    @endif

    <!-- Page Numbers -->
    <div class="flex items-center space-x-2">
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="flex items-center justify-center w-8 h-8 text-gray-500">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="flex items-center justify-center w-8 h-8 font-bold text-black bg-[#52B69A80] rounded-full focus:outline-none">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="flex items-center justify-center w-8 h-8 text-black rounded-full hover:bg-gray-200 focus:outline-none">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>

    <!-- Next Button -->
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="flex items-center px-2 py-1 text-gray-500 hover:text-black focus:outline-none">
            <span class="mr-1">Next</span>
            <i class="ri-arrow-right-s-line"></i>
        </a>
    @else
        <button class="flex items-center px-2 py-1 text-gray-400 cursor-not-allowed ">
            <span class="mr-1">Next</span>
            <i class="ri-arrow-right-s-line"></i>
        </button>
    @endif
</div>
@endif
