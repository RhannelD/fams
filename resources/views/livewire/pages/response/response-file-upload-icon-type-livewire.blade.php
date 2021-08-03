<div>
    @isset( $file_mine_type )
        @switch( $file_mine_type )
            @case( 'application/pdf' )
                <i class="fas fa-file-pdf"></i>
                @break
            @case( 'application/msword' )
                <i class="fas fa-file-word"></i>
                @break
            @case( 'application/vnd.ms-excel' )
            @case( 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' )
                <i class="fas fa-file-excel"></i>
                @break
            @case( 'text/csv' )
                <i class="fas fa-file-csv"></i>
            @case( 'text/plain' )
                <i class="fas fa-file-alt"></i>
                @break
            @case( 'image/png' )
            @case( 'image/gif' )
            @case( 'image/jpeg' )
            @case( 'image/svg+xml' )
            @case( 'image/webp' )
                <i class="fas fa-file-image"></i>
                @break
            @case( 'text/html' )
                <i class="fas fa-file-code"></i>
                @break
            @default
                <i class="fas fa-file"></i>
        @endswitch
    @endisset
</div>
