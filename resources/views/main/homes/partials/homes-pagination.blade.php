{{ ($paginator ?? $homes)->appends(request()->query())->onEachSide(1)->links('vendor.pagination.homes') }}
